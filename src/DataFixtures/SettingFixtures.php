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
        $sitename->setPlacement(0);

        $leadtext = new Setting();
        $leadtext->setName('leadtext');
        $leadtext->setValue('~');
        $leadtext->setType('text');
        $leadtext->setPlacement(1);

        $waitImageFilter = new Setting();
        $waitImageFilter->setName('wait_image_filter');
        $waitImageFilter->setValue('true');
        $waitImageFilter->setType('checkbox');
        $waitImageFilter->setPlacement(2);

        $manager->persist($sitename);
        $manager->persist($leadtext);
        $manager->persist($waitImageFilter);

        $manager->flush();
    }
}
