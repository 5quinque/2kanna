<?php

namespace App\DataFixtures;

use App\Entity\Board;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BoardFixtures extends Fixture
{
    public const COWBOY_BOARD_REFERENCE = 'cowboy_board';

    public function load(ObjectManager $manager)
    {
        $board = new Board();
        $board->setName('🤠miscellaneous');
        $manager->persist($board);
        $this->addReference(self::COWBOY_BOARD_REFERENCE, $board);

        $boardNames = ['technology', 'programming', 'music',
            'television', 'cooking', 'literature'];
        foreach ($boardNames as $name) {
            $board = new Board();
            $board->setName($name);
            $manager->persist($board);
        }

        $manager->flush();
    }
}