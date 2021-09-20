<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AppUserAddressRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\AppUser as User;

/**
 * @ORM\Entity(repositoryClass=AppUserAddressRepository::class)
 */
class AppUserAddress
{
    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string $street
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $street;

    /**
     * @var string $city
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $city;

    /**
     * @var string $state
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $state;

    /**
     * @var string $country
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $country;

    /**
     * @var string $zipcode
     *
     * @ORM\Column(type="string", length=10)
     */
    private string $zipcode;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class, inversedBy="addresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return $this
     */
    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return $this
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return $this
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    /**
     * @param string $zipcode
     *
     * @return $this
     */
    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
