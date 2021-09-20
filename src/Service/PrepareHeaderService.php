<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\PrepareInterface;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\AppHeader as Header;
use App\Repository\AppHeaderRepository as HeaderRepository;

class PrepareHeaderService implements PrepareInterface
{
    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface        $logger
     * @param HeaderRepository       $hr
     * @param array                  $data
     */
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private HeaderRepository $hr,
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
            foreach ($this->data as $datum) {
                $header = $this->hr->findOneByName($datum);

                if (!$header) {
                    $header = new Header();
                    $header->setName($datum);

                    $this->em->persist($header);
                }
            }

            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (ConnectionException $e) {
            $this->logger->error($e->getMessage());

            $this->em->getConnection()->rollBack();
            $this->em->getConnection()->close();

            return null;
        }

        $this->em->getConnection()->close();

        $this->logger->info('saved all headers');

        return true;
    }
}