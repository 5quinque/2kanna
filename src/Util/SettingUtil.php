<?php

namespace App\Util;

use App\Repository\SettingRepository;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SettingUtil
{
    private $settingRepository;
    private $cache;
    private $name;

    public function __construct(SettingRepository $settingRepository, TagAwareCacheInterface $settingCache)
    {
        $this->settingRepository = $settingRepository;
        $this->cache = $settingCache;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setting(string $name)
    {
        $this->setName($name);

        return $this->cache->get($name, function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $item->tag('setting');

            $result = $this->settingRepository->findOneBy(['name' => $this->name]);
            if ($result) {
                if ('checkbox' === $result->getType()) {
                    return $result->getValueBool();
                }

                return $result->getValue();
            }

            return null;
        });
    }

    public function clearSetting(string $name)
    {
        $this->cache->delete($name);
    }
}
