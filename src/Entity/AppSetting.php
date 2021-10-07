<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AppSettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\AppOptad as Optad;

/**
 * @ORM\Entity(repositoryClass=AppSettingRepository::class)
 */
class AppSetting
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
     * @var string $currency
     *
     * @ORM\Column(type="string", length=3, unique=true)
     */
    private string $currency;

    /**
     * @var int|null $periodLength
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private ?int $periodLength;

    /**
     * @var string|null $groupBy
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $groupBy;

    /**
     * @ORM\OneToMany(targetEntity=AppOptad::class, mappedBy="setting", orphanRemoval=true)
     */
    private $optads;

    public function __construct()
    {
        $this->optads = new ArrayCollection();
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
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPeriodLength(): ?int
    {
        return $this->periodLength;
    }

    /**
     * @param int|null $periodLength
     * @return $this
     */
    public function setPeriodLength(?int $periodLength): self
    {
        $this->periodLength = $periodLength;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGroupBy(): ?string
    {
        return $this->groupBy;
    }

    /**
     * @param string|null $groupBy
     *
     * @return $this
     */
    public function setGroupBy(?string $groupBy): self
    {
        $this->groupBy = $groupBy;

        return $this;
    }

    /**
     * @return Collection|AppOptad[]
     */
    public function getOptads(): Collection
    {
        return $this->optads;
    }

    public function addOptad(Optad $optad): self
    {
        if (!$this->optads->contains($optad)) {
            $this->optads[] = $optad;
            $optad->setSetting($this);
        }

        return $this;
    }

    public function removeOptad(Optad $optad): self
    {
        if ($this->optads->removeElement($optad)) {
            // set the owning side to null (unless already changed)
            if ($optad->getSetting() === $this) {
                $optad->setSetting(null);
            }
        }

        return $this;
    }
}
