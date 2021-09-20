<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\FetchInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;

class FetchService implements FetchInterface
{
    /**
     * @param HttpClientInterface $client
     * @param LoggerInterface     $logger
     */
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @inheritDoc
     */
    public function fetchData(string $method, string $pathToApi, array $options = []): ?array
    {
        $response  = $this->prepareRequest($method, $pathToApi, $options);

        try {
            if (!$response) {
                throw new Exception('something goes wrong with getting from api');
            }

            $content = $response->toArray();

        } catch (
            Exception |
            DecodingExceptionInterface |
            TransportExceptionInterface |
            ClientExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface $e
        ) {
            $this->logger->error($e->getMessage());

            return null;
        }

        $this->logger->info('convert data to array');

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function prepareRequest(string $method, string $pathToApi, array $options): ?ResponseInterface
    {
        try {
            $response = $this->client->request($method, $pathToApi, $options);
        } catch (
            Exception |
            TransportExceptionInterface |
            ClientExceptionInterface |
            RedirectionExceptionInterface |
            ServerExceptionInterface $e
        ) {
            $this->logger->error($e->getMessage());

            return null;
        }

        $this->logger->info('fetch data from api');

        return $response;
    }
}