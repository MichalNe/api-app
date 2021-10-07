<?php


namespace App\Service;


use App\Interfaces\PrepareInterface;
use App\Repository\AppSettingRepository as SettingRepository;
use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Exception;
use App\Entity\AppSetting as Setting;
use App\Entity\AppOptad as Optad;
use DateTime;

class PrepareOptadService implements PrepareInterface
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

    public function setData(array $data): PrepareInterface
    {
        $this->data = $data;

        return $this;
    }

    public function saveDataIntoDatabase(): ?bool
    {
        if (!$this->data) {
            $this->logger->error('something wrong with input data');

            return null;
        }

        $this->em->getConnection()->beginTransaction();

        try {
            $setting = new Setting();
            $setting
                ->setCurrency($this->data['settings']['currency'])
                ->setPeriodLength($this->data['settings']['PeriodLength'])
                ->setGroupBy($this->data['settings']['groupby']);

            $this->em->persist($setting);

            foreach ($this->data['data'] as $key => $value) {
                $optad = new Optad();
                $optad
                    ->setSetting($setting)
                    ->setUrls($value[0])
                    ->setTags($value[1])
                    ->setDate(new DateTime($value[2]))
                    ->setEstimatedRevenue($value[3])
                    ->setAdImpression($value[4])
                    ->setAdEcpm($value[5])
                    ->setClicks($value[6])
                    ->setAdCtr($value[7]);

                $this->em->persist($optad);
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

        $this->logger->info('saved all optad data with setting');

        return true;
    }
}