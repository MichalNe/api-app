<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AppSettingRepository;
use Doctrine\ORM\Mapping as ORM;

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
}
