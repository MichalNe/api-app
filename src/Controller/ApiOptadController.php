<?php


namespace App\Controller;


use App\Interfaces\ApiInterface;
use App\Repository\AppOptadRepository as OptadRepository;
use App\Service\OptadSerializeService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/optad')]
class ApiOptadController extends AbstractController implements ApiInterface
{

    /**
     * @param OptadRepository $optadRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private OptadRepository $optadRepository,
        private LoggerInterface $logger
    ) {
    }


    /**
     * @inheritDoc
     */
    #[Route(path: '/get', name: 'optad_get_all', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $optads = $this->optadRepository->findAll();

        if (!$optads) {
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

        foreach ($optads as $optad) {
            $response[] = (new OptadSerializeService($optad))->serialize();
        }

        sort($response);

        $this->logger->info('return all optads');

        return $this->json([
            'status' => 200,
            'message' => 'return all optads',
            'data' => [
                'optads' => $response,
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    #[Route(path: '/get/{id}', name: 'optad_get_one', methods: ['GET'])]
    public function getOne(int $id): JsonResponse
    {
        $optad = $this->optadRepository->findOneById($id);

        if (!$optad) {
            $this->logger->alert('optad does not exist');

            return $this->json([
                'status' => 404,
                'message' => 'optad does not exist',
                'data' => [],
            ],
                404
            );
        }

        $this->logger->info('return one optad');

        return $this->json([
            'status' => 200,
            'message' => 'return one optad',
            'data' => [
                'setting' => (new OptadSerializeService($optad))->serialize(),
            ],
        ]);
    }
}