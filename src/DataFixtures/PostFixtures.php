<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $cowboyBoard = $this->getReference(BoardFixtures::COWBOY_BOARD_REFERENCE);

        $date = new DateTime();

        for ($i = 0; $i < 20; ++$i) {
            $uuid = Uuid::v4();

            $post = new Post();
            $post->setTitle("Title-{$i}");
            $post->setMessage('Some message 🤠');
            $post->setBoard($cowboyBoard);
            $post->setCreated($date);
            $post->setLatestpost($date);
            $post->setIpAddress('127.0.0.1');
            $post->setSlug($uuid->toBase58());
            $manager->persist($post);
        }

        $manager->flush();
    }
}
