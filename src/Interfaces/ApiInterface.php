<?php

namespace App\Interfaces;

use Symfony\Component\HttpFoundation\JsonResponse;

interface ApiInterface
{
    /**
     * @return JsonResponse
     */
    public function getAll(): JsonResponse;

    /**
     * @param int $id
     *
     * @return JsonResponse
     */
    public function getOne(int $id): JsonResponse;
}