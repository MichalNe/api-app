<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AppUser as User;
use App\Interfaces\SerializeInterface;
use App\Service\UserAddressSerializeService as UserAddressSerialize;
use App\Entity\AppUserAddress as UserAddress;

class UserSerializeService implements SerializeInterface
{
    /**
     * @param User $user
     */
    public function __construct(
        public User $user
    ) {
    }

    /**
     * @return array
     */
    public function serialize(): array
    {
        $response = [
            'id' => $this->user->getId(),
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),
            'gender' => $this->user->getGender() ?? '',
            'email' => $this->user->getEmail(),
            'username' => $this->user->getUsername(),
            'addresses' => [],
        ];

        if ($this->user->getAddresses()->count() > 0) {
            /** @var UserAddress $address */
            foreach ($this->user->getAddresses()->getValues() as $address){
                $response['addresses'][] = (new UserAddressSerialize($address))->serialize();
            }
        }

        return $response;
    }

}