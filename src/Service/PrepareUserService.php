<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\PrepareInterface;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\AppUser as User;
use App\Repository\AppUserRepository as UserRepository;
use App\Entity\AppUserAddress as UserAddress;
use App\Repository\AppUserAddressRepository as UserAddressRepository;
use Exception;

class PrepareUserService implements PrepareInterface
{
    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface        $logger
     * @param UserRepository         $ur
     * @param UserAddressRepository  $uar
     * @param array                  $data
     */
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private UserRepository $ur,
        private UserAddressRepository $uar,
        private array $data = []
    ) {
    }

    /**
     * @inheritDoc
     */
    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function saveDataIntoDatabase(): ?bool
    {
        if (!$this->data) {
            $this->logger->error('something wrong with input data');

            return null;
        }

        $this->em->getConnection()->beginTransaction();

        try {
            foreach ($this->data['results'] as $datum) {
                $user = new User();
                $user
                    ->setFirstname($datum['name']['first'])
                    ->setLastname($datum['name']['last'])
                    ->setEmail($datum['email'])
                    ->setGender($datum['gender'] ?? null)
                    ->setUsername($datum['login']['username'])
                    ->setPassword(password_hash($datum['login']['password'], PASSWORD_BCRYPT));

                $location = $datum['location'];
                $address = new UserAddress();
                $address
                    ->setStreet($location['street']['name'] . ' / ' . $location['street']['number'])
                    ->setCity($location['city'])
                    ->setCountry($location['country'])
                    ->setState($location['state'])
                    ->setZipcode((string) $location['postcode'])
                    ->setUser($user);

                $this->em->persist($user);
                $this->em->persist($address);
            }

            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (Exception | ConnectionException $e) {
            $this->logger->error($e->getMessage());

            $this->em->getConnection()->rollBack();
            $this->em->getConnection()->close();

            return null;
        }

        $this->em->getConnection()->close();

        $this->logger->info('saved all users with address');

        return true;
    }
}