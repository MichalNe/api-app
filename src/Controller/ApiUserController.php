<?php

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\ApiInterface;
use App\Repository\AppUserRepository as UserRepository;
use App\Service\UserSerializeService as UserSerialize;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/user')]
class ApiUserController extends AbstractController implements ApiInterface
{

    /**
     * @param UserRepository  $userRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private UserRepository $userRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get', name: 'user_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        if (!$users) {
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

        foreach ($users as $user) {
            $response[] = (new UserSerialize($user))->serialize();
        }

        sort($response);

        $this->logger->info('return all users');

        return $this->json([
            'status' => 200,
            'message' => 'return all users',
            'data' => [
                'users' => $response,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get/{id}', name: 'user_get_one', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $user = $this->userRepository->findOneById($id);

        if (!$user) {
            $this->logger->alert('user does not exist');

            return $this->json([
                'status' => 404,
                'message' => 'user does not exist',
                'data' => [],
            ],
                404
            );
        }

        $this->logger->info('return one user');

        return $this->json([
            'status' => 200,
            'message' => 'return one user',
            'data' => [
                'user' => (new UserSerialize($user))->serialize(),
            ],
        ]);
    }

}