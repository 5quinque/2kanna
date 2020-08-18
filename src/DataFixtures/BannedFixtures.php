<?php

namespace App\DataFixtures;

use App\Entity\Banned;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BannedFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $banned = new Banned();

        $banned->setIpAddress('192.168.0.1');
        $banned->setBanTime(new \DateTime());
        $banned->setUnbanTime(new \DateTime('+3 day'));

        $manager->persist($banned);
        $manager->flush();
    }
}
