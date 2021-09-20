<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\SerializeInterface;
use App\Entity\AppUserAddress as UserAddress;

class UserAddressSerializeService implements SerializeInterface
{
    /**
     * @param UserAddress $userAddress
     */
    public function __construct(
      public UserAddress $userAddress
    ) {
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        return [
            'id' => $this->userAddress->getId(),
            'street' => $this->userAddress->getStreet(),
            'city' => $this->userAddress->getCity(),
            'state' => $this->userAddress->getState(),
            'country' => $this->userAddress->getCountry(),
            'zipcode' => $this->userAddress->getZipcode()
        ];
    }

}