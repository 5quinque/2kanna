<?php

namespace App\DataFixtures;

use App\Entity\Post;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $board = $this->getReference(BoardFixtures::MISC_BOARD_REFERENCE);

        $date = new DateTime();

        $uuid = Uuid::v4();
        [$slug] = explode('-', $uuid->toRfc4122());
        $slug = strtoupper($slug);

        $post = new Post();
        $post->setMessage("Code test
```c
#include <stdio.h>

int main() {
    printf('Hello World\\n');

    return 0;
}
```");
        $post->setBoard($board);
        $post->setCreated(new DateTime('+1 min'));
        $post->setLatestpost(new DateTime('+1 min'));
        $post->setIpAddress('127.0.0.1');
        $post->setSlug($slug);
        $manager->persist($post);

        for ($i = 0; $i < 20; ++$i) {
            $uuid = Uuid::v4();
            [$slug] = explode('-', $uuid->toRfc4122());
            $slug = strtoupper($slug);

            $post = new Post();
            $post->setMessage("Post {$i}");
            $post->setBoard($board);
            $post->setCreated($date);
            $post->setLatestpost($date);
            $post->setIpAddress('127.0.0.1');
            $post->setSlug($slug);
            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            BoardFixtures::class,
        );
    }
}
