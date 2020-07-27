<?php

namespace App\Util;

use App\Repository\SettingRepository;

class SettingUtil
{
    private $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function setting(string $name)
    {
        $result = $this->settingRepository->findOneBy(['name' => $name]);
        if ($result) {
            if ('checkbox' === $result->getType()) {
                return $result->getValueBool();
            }

            return $result->getValue();
        }

        return null;
    }
}
