<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use App\Entity\SettingChoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Intl\Timezones;

class SettingFixtures extends Fixture
{
    private $manager;

    private function setManager(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function load(ObjectManager $manager)
    {
        $this->setManager($manager);

        $this->sitename();
        $this->leadtext();
        $this->timezone();
        $this->waitImageFilter();
        $this->anonCanCreateBoard();

        $manager->flush();
    }

    private function sitename()
    {
        $sitename = new Setting();
        $sitename->setLabel('Site Name');
        $sitename->setName('sitename');
        $sitename->setValue('2Kanna');
        $sitename->setType('text');
        $sitename->setSection('Site');
        $sitename->setPlacement(0);

        $this->manager->persist($sitename);
    }

    private function leadtext()
    {
        $leadtext = new Setting();
        $leadtext->setLabel('Lead Text');
        $leadtext->setName('leadtext');
        $leadtext->setValue('~');
        $leadtext->setType('text');
        $leadtext->setSection('Site');
        $leadtext->setPlacement(1);

        $this->manager->persist($leadtext);
    }
    
    private function timezone()
    {
        $timezone = new Setting();
        $timezone->setLabel('Time Zone');
        $timezone->setName('timezone');
        $timezone->setValue('Europe/London');
        $timezone->setType('choice');
        $timezone->setSection('Site');
        $timezone->setPlacement(2);

        $this->manager->persist($timezone);

        $timezones = array_keys(Timezones::getNames());
        sort($timezones);

        foreach ($timezones as $tz) {
            $tzChoice = new SettingChoice();
            $tzChoice->setSetting($timezone);
            $tzChoice->setKey($tz);
            $tzChoice->setValue($tz);
            $this->manager->persist($tzChoice);
        }
    }

    private function waitImageFilter()
    {
        $waitImageFilter = new Setting();
        $waitImageFilter->setLabel('Wait for thumbnails to resolve');
        $waitImageFilter->setName('wait_image_filter');
        $waitImageFilter->setValueBool(true);
        $waitImageFilter->setType('checkbox');
        $waitImageFilter->setSection('Uploads');
        $waitImageFilter->setPlacement(10);

        $this->manager->persist($waitImageFilter);
    }

    private function anonCanCreateBoard()
    {
        $anonCanCreateBoard = new Setting();
        $anonCanCreateBoard->setLabel('Anyone can create boards');
        $anonCanCreateBoard->setName('anon_can_create_board');
        $anonCanCreateBoard->setValueBool(false);
        $anonCanCreateBoard->setType('checkbox');
        $anonCanCreateBoard->setSection('Site');
        $anonCanCreateBoard->setPlacement(3);

        $this->manager->persist($anonCanCreateBoard);
    }
}
