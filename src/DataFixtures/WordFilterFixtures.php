<?php

namespace App\DataFixtures;

use App\Entity\WordFilter;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class WordFilterFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $word = new WordFilter();

        $word->setBadWord('/test_bad_word/');

        $manager->persist($word);
        $manager->flush();
    }
}
