<?php

namespace App\Tests\Util;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use App\Util\SettingUtil;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class SettingUtilTest extends TestCase
{
    public function testSetting()
    {
        $setting = new Setting();

        $setting->setName('sitename');
        $setting->setValue('TextBoard');

        $settingRepository = $this->createMock(SettingRepository::class);
        $settingRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($setting)
        ;

        $settingCache = $this->createMock(TagAwareCacheInterface::class);
        $settingCache->expects($this->any())
        ->method('get')
        ->willReturn($setting->getValue())
        ;

        $settingUtil = new SettingUtil($settingRepository, $settingCache);

        $this->assertEquals('TextBoard', $settingUtil->setting('sitename'));
    }
}
