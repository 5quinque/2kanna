<?php

namespace App\Tests\Util;

use App\Entity\Setting;
use App\Repository\SettingRepository;
use App\Util\SettingUtil;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * @internal
 * @covers \App\Util\SettingUtil
 */
class SettingUtilTest extends TestCase
{
    public function testSetting()
    {
        $setting = new Setting();
        $cache = new FilesystemAdapter();

        $setting->setName('sitename');
        $setting->setValue('TextBoard');

        $settingRepository = $this->createMock(SettingRepository::class);

        $settingRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($setting)
        ;

        $settingUtil = new SettingUtil($settingRepository);

        // Ensure we have the cache clear
        $settingUtil->clearSetting('sitename');

        $this->assertEquals('TextBoard', $settingUtil->setting('sitename'));

        $this->assertTrue($cache->hasItem('sitename'));
    }

    public function testClearSetting()
    {
        $setting = new Setting();
        $cache = new FilesystemAdapter();

        $setting->setName('sitename');
        $setting->setValue('TextBoard');

        $settingRepository = $this->createMock(SettingRepository::class);

        $settingRepository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($setting)
        ;

        $settingUtil = new SettingUtil($settingRepository);

        // Ensure we have the setting cached
        $settingUtil->setting('sitename');

        $settingUtil->clearSetting('sitename');

        $this->assertFalse($cache->hasItem('sitename'));
    }
}
