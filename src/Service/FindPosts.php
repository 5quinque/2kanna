<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostRepository;

class FindPosts
{
    private $em;
    private $postRepository;

    public function __construct(EntityManagerInterface $em, PostRepository $postRepository)
    {
        $this->em = $em;
        $this->postRepository = $postRepository;
    }

    public function printDebug(array $debug)
    {
        foreach ($debug as $d) {
            print "$d";
        }
    }

    public function isPosterHot($userIP)
    {
        $posts = $this->postRepository->findByChildNewerThan('-10 seconds', $userIP);

        return $posts;
    }

    public function findOldPosts()
    {
        $debug = [];

        $oldPosts = $this->postRepository->findByParentOlderThan('-7 days');
        foreach ($oldPosts as $p) {
            $debug[] = "Deleting Post ID: {$p->getId()}\n";

            $this->em->remove($p);
            $this->em->flush();
        }

        $this->printDebug($debug);
    }
}
