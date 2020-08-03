<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

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

        $waitImageFilter = new Setting();
        $waitImageFilter->setName('wait_image_filter');
        $waitImageFilter->setValueBool(true);
        $waitImageFilter->setType('checkbox');
        $waitImageFilter->setSection('Uploads');
        $waitImageFilter->setPlacement(10);

        $manager->persist($sitename);
        $manager->persist($leadtext);
        $manager->persist($waitImageFilter);

        $manager->flush();
    }
}
