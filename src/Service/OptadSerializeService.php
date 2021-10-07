<?php


namespace App\Service;


use App\Interfaces\SerializeInterface;
use App\Entity\AppOptad as Optad;

class OptadSerializeService implements SerializeInterface
{
    /**
     * @param Optad $optad
     */
    public function __construct(
        public Optad $optad
    ) {
    }

    public function serialize(): array
    {
        return [
            'id' => $this->optad->getId(),
            'urls' => $this->optad->getUrls(),
            'tags' => $this->optad->getTags(),
            'date' => $this->optad->getDate()->format('Y-m-d'),
            'estimatedRevenue' => $this->optad->getEstimatedRevenue(),
            'adImpressions' => $this->optad->getAdImpression(),
            'adEcpm' => $this->optad->getAdEcpm(),
            'clicks' => $this->optad->getClicks(),
            'adCtr' => $this->optad->getAdCtr(),
            'setting' => (new SettingSerializeService($this->optad->getSetting()))->serialize()
        ];
    }
}