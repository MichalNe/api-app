<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\PrepareInterface;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\AppCurrency as Currency;
use App\Repository\AppCurrencyRepository as CurrencyRepository;
use DateTime;
use Exception;
use DateTimeZone;

class PrepareCurrencyService implements PrepareInterface
{
    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface        $logger
     * @param CurrencyRepository     $cr
     * @param array                  $data
     */
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private CurrencyRepository $cr,
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
            foreach ($this->data['rates'] as $datum) {
                $currency = $this->cr->findOneByCode($datum['code']);

                if (!$currency) {
                    $currency = new Currency();
                    $currency->setCode($datum['code']);
                }

                $currency->setName($datum['currency']);
                $currency->setValue($datum['mid']);
                $currency->setDate(new DateTime($this->data['effectiveDate'], new DateTimeZone('Europe/Warsaw')));

                $this->em->persist($currency);
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

        $this->logger->info('saved all currencies');

        return true;
    }
}