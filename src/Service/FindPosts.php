<?php
namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;
use App\Repository\PostRepository;

class FindPosts
{
    private $om;
    private $postRepository;

    public function __construct(ObjectManager $objectManager, PostRepository $postRepository)
    {
        $this->om = $objectManager;
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
        $posts = $this->postRepository->findByChildNewerThan('-60 seconds', $userIP);

        return $posts;
    }

    public function findOldPosts()
    {
        $debug = [];

        $oldPosts = $this->postRepository->findByParentOlderThan('-7 days');
        foreach ($oldPosts as $p) {
            $debug[] = "Deleting Post ID: {$p->getId()}\n";

            $this->om->remove($p);
            $this->om->flush();
        }

        $this->printDebug($debug);
    }
}
