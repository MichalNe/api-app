<?php

namespace App\Interfaces;

interface PrepareInterface
{
    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data): self;

    /**
     * @return bool|null
     */
    public function saveDataIntoDatabase(): ?bool;
}