<?php
namespace App\Service;
use Doctrine\Common\Persistence\ObjectManager;
// use App\Entity\Post;
use App\Repository\PostRepository;

class DeleteOldPosts
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

    public function findOldPosts()
    {
        $oldPosts = $this->postRepository->findByOlderThan('-2 days');
        foreach ($oldPosts as $p) {
            echo $p->getMessage() . "\n";
        }

        $debug = ["test1\n"];
        $debug[] = "test2\n";

        $this->printDebug($debug);
    }
}