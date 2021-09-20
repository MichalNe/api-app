<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AppUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\AppUserAddress as UserAddress;

/**
 * @ORM\Entity(repositoryClass=AppUserRepository::class)
 */
class AppUser
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
     * @var string $firstname
     *
     * @ORM\Column(type="string", length=50)
     */
    private string $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $lastname;

    /**
     * @var string $gender
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private string $gender;

    /**
     * @var string $email
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private string $email;

    /**
     * @var string $username
     *
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private string $username;

    /**
     * @var string $password
     *
     * @ORM\Column(type="string", length=255)
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=UserAddress::class, mappedBy="user", orphanRemoval=true)
     */
    private $addresses;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
    }

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
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     *
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     *
     * @return $this
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     *
     * @return $this
     */
    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|UserAddress[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    /**
     * @param UserAddress $address
     * 
     * @return $this
     */
    public function addAddress(UserAddress $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    /**
     * @param UserAddress $address
     * 
     * @return $this
     */
    public function removeAddress(UserAddress $address): self
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }
}
