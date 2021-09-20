<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ApiInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AppCurrencyRepository as CurrencyRepository;
use App\Service\CurrencySerializeService as CurrencySerialize;

#[Route(path: '/api/currency')]
class ApiCurrencyController extends AbstractController implements ApiInterface
{
    /**
     * @param CurrencyRepository $currencyRepository
     * @param LoggerInterface    $logger
     */
    public function __construct(
        private CurrencyRepository $currencyRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get', name: 'currency_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $currencies = $this->currencyRepository->findAll();

        if (!$currencies) {
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

        foreach ($currencies as $currency) {
            $response[] = (new CurrencySerialize($currency))->serialize();
        }

        sort($response);

        $this->logger->info('return all currencies');

        return $this->json([
            'status' => 200,
            'message' => 'return all currencies',
            'data' => [
                'currencies' => $response,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get/{id}', name: 'currency_get_one', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $currency = $this->currencyRepository->findOneById($id);

        if (!$currency) {
            $this->logger->alert('currency does not exist');

            return $this->json([
                'status' => 404,
                'message' => 'currency does not exist',
                'data' => [],
            ],
                404
            );
        }

        $this->logger->info('return one currency');

        return $this->json([
            'status' => 200,
            'message' => 'return one currency',
            'data' => [
                'currency' => (new CurrencySerialize($currency))->serialize(),
            ],
        ]);
    }
}