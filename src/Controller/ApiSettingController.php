<?php

namespace App\Controller;

use App\Interfaces\ApiInterface;
use App\Service\SettingSerializeService as SettingSerialize;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\AppSettingRepository as SettingRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/setting')]
class ApiSettingController extends AbstractController implements ApiInterface
{
    /**
     * @param SettingRepository $settingRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private SettingRepository $settingRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get', name: 'setting_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $settings = $this->settingRepository->findAll();

        if (!$settings) {
            $this->logger->alert('Database is empty');

            return $this->json([
                'status' => 404,
                'message' => 'Database is empty',
                'data' => [],
            ],
                404
            );
        }

        $response = [];

        foreach ($settings as $setting) {
            $response[] = (new SettingSerialize($setting))->serialize();
        }

        sort($response);

        $this->logger->info('return all settings');

        return $this->json([
            'status' => 200,
            'message' => 'return all setting',
            'data' => [
                'settings' => $response,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get/{id}', name: 'setting_get_one', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $setting = $this->settingRepository->findOneById($id);

        if (!$setting) {
            $this->logger->alert('setting does not exist');

            return $this->json([
                'status' => 404,
                'message' => 'setting does not exist',
                'data' => [],
            ],
                404
            );
        }

        $this->logger->info('return one setting');

        return $this->json([
            'status' => 200,
            'message' => 'return one setting',
            'data' => [
                'setting' => (new SettingSerialize($setting))->serialize(),
            ],
        ]);
    }
}