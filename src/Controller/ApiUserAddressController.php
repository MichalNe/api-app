<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ApiInterface;
use App\Repository\AppUserAddressRepository as UserAddressRepository;
use App\Service\UserAddressSerializeService as UserAddressSerialize;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/address')]
class ApiUserAddressController extends AbstractController implements ApiInterface
{

    /**
     * @param UserAddressRepository $userAddressRepository
     * @param LoggerInterface       $logger
     */
    public function __construct(
        private UserAddressRepository $userAddressRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get', name: 'address_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $addresses = $this->userAddressRepository->findAll();

        if (!$addresses) {
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

        foreach ($addresses as $address) {
            $response[] = (new UserAddressSerialize($address))->serialize();
        }

        sort($response);

        $this->logger->info('return all addresses');

        return $this->json([
            'status' => 200,
            'message' => 'return all addresses',
            'data' => [
                'addresses' => $response,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get/{id}', name: 'address_get_one', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $address = $this->userAddressRepository->findOneById($id);

        if (!$address) {
            $this->logger->alert('address does not exist');

            return $this->json([
                'status' => 404,
                'message' => 'address does not exist',
                'data' => [],
            ],
                404
            );
        }

        $this->logger->info('return one address');

        return $this->json([
            'status' => 200,
            'message' => 'return one address',
            'data' => [
                'address' => (new UserAddressSerialize($address))->serialize(),
            ],
        ]);
    }

}