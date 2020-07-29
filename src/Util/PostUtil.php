<?php

namespace App\Util;

use App\Entity\Post;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\Request;

class PostUtil
{
    private $em;
    private $liipCacheManager;
    private $imageCache;

    public function __construct(EntityManagerInterface $em, CacheManager $liipCacheManager, ImageCache $imageCache)
    {
        $this->em = $em;
        $this->liipCacheManager = $liipCacheManager;
        $this->imageCache = $imageCache;
    }

    public function deletePost(Post $post)
    {
        foreach ($post->getChildPost() as $childPost) {
            $this->deletePost($childPost);
        }

        // Remove LiipImagine image cache
        $this->liipCacheManager->remove($post->getImageName());

        $this->em->remove($post);
        $this->em->flush();
    }

    public function createPost(Post $post)
    {
        $request = Request::createFromGlobals();

        $post->setCreated(new DateTime());

        $post->setIpAddress($request->getClientIp());

        // We will lose access to Post::imageFile so need to save the mimetype
        if ($post->getImageFile()) {
            $post->setImageMimeType($post->getImageFile()->getMimeType());
        }

        $rootPost = $post->getRootParentPost();

        // Update parent post timestamp
        $rootPost->setLatestpost(new DateTime());

        $this->em->persist($rootPost);
        $this->em->persist($post);
        $this->em->flush();

        $this->imageCache->queueImageFilter($post);
    }
}
