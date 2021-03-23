<?php

namespace App\DataFixtures;

use App\Entity\Board;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BoardFixtures extends Fixture implements DependentFixtureInterface
{
    public const MISC_BOARD_REFERENCE = 'misc-board';

    public function load(ObjectManager $manager)
    {
        $admin = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE);
        $user = $this->getReference(UserFixtures::STANDARD_USER_REFERENCE);

        $board = new Board();
        $board->setName('miscellaneous');
        $board->setOwner($user);
        $manager->persist($board);
        $this->addReference(self::MISC_BOARD_REFERENCE, $board);

        $boardNames = ['technology', 'λ', 'Ω', 'music', 'literature'];
        foreach ($boardNames as $name) {
            $board = new Board();
            $board->setName($name);
            $board->setOwner($admin);
            $manager->persist($board);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
