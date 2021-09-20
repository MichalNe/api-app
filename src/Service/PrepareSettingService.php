<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\PrepareInterface;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\AppSetting as Setting;
use App\Repository\AppSettingRepository as SettingRepository;

class PrepareSettingService implements PrepareInterface
{
    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface        $logger
     * @param SettingRepository      $sr
     * @param array                  $data
     */
    public function __construct(
        private EntityManagerInterface $em,
        private LoggerInterface $logger,
        private SettingRepository $sr,
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
        if (!$this->data || !$this->data['currency']) {
            $this->logger->error('something wrong with input data');

            return null;
        }

        $this->em->getConnection()->beginTransaction();

        try {
            /** @var Setting $setting */
            $setting = $this->sr->findOneByCurrency($this->data['currency']);

            if (!$setting) {
                $setting = new Setting();
                $setting->setCurrency($this->data['currency']);
                $setting->setGroupBy($this->data['groupby'] ?? null);
                $setting->setPeriodLength($this->data['PeriodLength'] ?? null);
            } else {
                $setting->setPeriodLength($this->data['PeriodLength'] ?? $setting->getPeriodLength());
                $setting->setGroupBy($this->data['groupby'] ?? $setting->getGroupBy());
            }

            $this->em->persist($setting);
            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (ConnectionException $e) {
            $this->logger->error($e->getMessage());

            $this->em->getConnection()->rollBack();
            $this->em->getConnection()->close();

            return null;
        }

        $this->em->getConnection()->close();

        $this->logger->info('saved settings');

        return true;
    }
}