<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ApiInterface;
use App\Service\HeaderSerializeService as HeaderSerialize;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\AppHeaderRepository as HeaderRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/header')]
class ApiHeaderController extends AbstractController implements ApiInterface
{
    /**
     * @param HeaderRepository $headerRepository
     * @param LoggerInterface  $logger
     */
    public function __construct(
        private HeaderRepository $headerRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get', name: 'header_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $headers = $this->headerRepository->findAll();

        if (!$headers) {
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

        foreach ($headers as $header) {
            $response[] = (new HeaderSerialize($header))->serialize();
        }

        sort($response);

        $this->logger->info('return all headers');

        return $this->json([
            'status' => 200,
            'message' => 'return all headers',
            'data' => [
                'headers' => $response,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get/{id}', name: 'header_get_one', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $header = $this->headerRepository->findOneById($id);

        if (!$header) {
            $this->logger->alert('header does not exist');

            return $this->json([
                'status' => 404,
                'message' => 'header does not exist',
                'data' => [],
            ],
                404
            );
        }

        $this->logger->info('return one header');

        return $this->json([
            'status' => 200,
            'message' => 'return one header',
            'data' => [
                'header' => (new HeaderSerialize($header))->serialize(),
            ],
        ]);
    }
}