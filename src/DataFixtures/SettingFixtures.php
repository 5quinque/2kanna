<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use App\Entity\SettingChoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Intl\Timezones;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $sitename = new Setting();
        $sitename->setName('sitename');
        $sitename->setValue('TextBoard');
        $sitename->setType('text');
        $sitename->setSection('Site');
        $sitename->setPlacement(0);

        $leadtext = new Setting();
        $leadtext->setName('leadtext');
        $leadtext->setValue('~');
        $leadtext->setType('text');
        $leadtext->setSection('Site');
        $leadtext->setPlacement(1);

        $timezone = new Setting();
        $timezone->setName('timezone');
        $timezone->setValue('Europe/London');
        $timezone->setType('choice');
        $timezone->setSection('Site');
        $timezone->setPlacement(2);

        $waitImageFilter = new Setting();
        $waitImageFilter->setName('wait_image_filter');
        $waitImageFilter->setValueBool(true);
        $waitImageFilter->setType('checkbox');
        $waitImageFilter->setSection('Uploads');
        $waitImageFilter->setPlacement(10);

        $manager->persist($sitename);
        $manager->persist($leadtext);
        $manager->persist($waitImageFilter);
        $manager->persist($timezone);

        $timezones = array_keys(Timezones::getNames());
        sort($timezones);

        foreach ($timezones as $tz) {
            $tzChoice = new SettingChoice();
            $tzChoice->setSetting($timezone);
            $tzChoice->setKey($tz);
            $tzChoice->setValue($tz);
            $manager->persist($tzChoice);
        }

        $manager->flush();
    }
}
