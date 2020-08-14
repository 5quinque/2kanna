<?php

namespace App\DataFixtures;

use App\Entity\Board;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BoardFixtures extends Fixture
{
    public const COWBOY_BOARD_REFERENCE = 'cowboy_board';

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $board = new Board();
        $board->setName('miscellaneous');
        $board->setPassword($this->passwordEncoder->encodePassword(
            $board,
            'password'
        ));
        $manager->persist($board);
        $this->addReference(self::COWBOY_BOARD_REFERENCE, $board);

        $boardNames = ['technology', 'Ω', 'music',
            'literature', 'gaming'];
        foreach ($boardNames as $name) {
            $board = new Board();
            $board->setName($name);
            $board->setPassword($this->passwordEncoder->encodePassword(
                $board,
                'password'
            ));
            $manager->persist($board);
        }

        $manager->flush();
    }
}
