<?php

declare(strict_types=1);

namespace App\Service;

use App\Interfaces\SerializeInterface;
use App\Entity\AppSetting as Setting;

class SettingSerializeService implements SerializeInterface
{
    /**
     * @param Setting $setting
     */
    public function __construct(
        public Setting $setting
    ) {
    }

    /**
     * @inheritDoc
     */
    public function serialize(): array
    {
        return [
            'id' => $this->setting->getId(),
            'currency' => $this->setting->getCurrency(),
            'periodLength' => $this->setting->getPeriodLength() ?? '',
            'groupBy' => $this->setting->getGroupBy() ?? ''
        ];
    }
}