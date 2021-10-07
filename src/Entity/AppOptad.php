<?php

namespace App\Entity;

use App\Repository\AppOptadRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\AppSetting as Setting;

/**
 * @ORM\Entity(repositoryClass=AppOptadRepository::class)
 */
class AppOptad
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $urls;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $tags;

    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $date;

    /**
     * @ORM\Column(type="integer")
     */
    private int $estimatedRevenue;

    /**
     * @ORM\Column(type="integer")
     */
    private int $adImpression;

    /**
     * @ORM\Column(type="integer")
     */
    private int $adEcpm;

    /**
     * @ORM\Column(type="integer")
     */
    private int $clicks;

    /**
     * @ORM\Column(type="float")
     */
    private float $adCtr;

    /**
     * @ORM\ManyToOne(targetEntity=AppSetting::class, inversedBy="optads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $setting;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrls(): string
    {
        return $this->urls;
    }

    public function setUrls(string $urls): self
    {
        $this->urls = $urls;

        return $this;
    }

    public function getTags(): string
    {
        return $this->tags;
    }

    public function setTags(string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEstimatedRevenue(): int
    {
        return $this->estimatedRevenue;
    }

    public function setEstimatedRevenue(int $estimatedRevenue): self
    {
        $this->estimatedRevenue = $estimatedRevenue;

        return $this;
    }

    public function getAdImpression(): int
    {
        return $this->adImpression;
    }

    public function setAdImpression(int $adImpression): self
    {
        $this->adImpression = $adImpression;

        return $this;
    }

    public function getAdEcpm(): int
    {
        return $this->adEcpm;
    }

    public function setAdEcpm(int $adEcpm): self
    {
        $this->adEcpm = $adEcpm;

        return $this;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function setClicks(int $clicks): self
    {
        $this->clicks = $clicks;

        return $this;
    }

    public function getAdCtr(): float
    {
        return $this->adCtr;
    }

    public function setAdCtr(float $adCtr): self
    {
        $this->adCtr = $adCtr;

        return $this;
    }

    public function getSetting(): Setting
    {
        return $this->setting;
    }

    public function setSetting(Setting $setting): self
    {
        $this->setting = $setting;

        return $this;
    }
}
