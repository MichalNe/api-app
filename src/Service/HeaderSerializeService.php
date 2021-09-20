<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AppHeader as Header;
use App\Interfaces\SerializeInterface;

class HeaderSerializeService implements SerializeInterface
{
    /**
     * @param Header $header
     */
    public function __construct(
        public Header $header
    ) {
    }

    /**
     * @inheritDoc
     */
    public function serialize(): array
    {
        return [
            'id' => $this->header->getId(),
            'name' => $this->header->getName()
        ];
    }
}