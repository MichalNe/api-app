<?php

namespace App\Interfaces;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface FetchInterface
{
    /**
     * @param string $method
     * @param string $pathToApi
     * @param array  $options
     *
     * @return array|null
     */
    public function fetchData(string $method, string $pathToApi, array $options = []): ?array;

    /**
     * @param string $method
     * @param string $pathToApi
     * @param array  $options
     *
     * @return ResponseInterface|null
     */
    public function prepareRequest(string $method, string $pathToApi, array $options): ?ResponseInterface;
}