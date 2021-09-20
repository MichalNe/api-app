<?php

namespace App\Service;

use App\Interfaces\SerializeInterface;
use App\Entity\AppCurrency as Currency;

class CurrencySerializeService implements SerializeInterface
{
    public function __construct(
       public Currency $currency
    ) {
    }

    /**
     * @inheritDoc
     */
    public function serialize(): array
    {
        return [
            'id' => $this->currency->getId(),
            'name' => $this->currency->getName(),
            'code' => $this->currency->getCode(),
            'value' => $this->currency->getValue(),
            'date' => $this->currency->getDate()->format('d-m-Y'),
        ];
    }
}