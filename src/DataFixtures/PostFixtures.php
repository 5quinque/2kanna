<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cowboyBoard = $this->getReference(BoardFixtures::COWBOY_BOARD_REFERENCE);
        
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle("Title-{$i}");
            $post->setMessage("Some message 🤠");
            $post->setBoard($cowboyBoard);
            $post->setCreated(new DateTime());
            $post->setIpAddress("127.0.0.1");
            $manager->persist($post);
        }

        $manager->flush();
    }
}
