<?php

namespace App\Util;

use App\Entity\Post;
use Enqueue\Client\ProducerInterface;
use Liip\ImagineBundle\Async\Commands;
use Liip\ImagineBundle\Async\ResolveCache;

class ImageCache
{
    private $producer;

    public function __construct(ProducerInterface $producer)
    {
        $this->producer = $producer;
    }

    public function queueImageFilter(Post $post)
    {
        // Resolve cached images in the background (thumbnails, stripping exif)
        if (preg_match('/^image\//', $post->getImageMimeType())) {
            $reply = $this->producer->sendCommand(
                Commands::RESOLVE_CACHE,
                new ResolveCache($post->getImageName(), ['thumb', 'jpeg']),
                true
            );

            $replyMessage = $reply->receive(20000); // wait for 20 sec
        }
    }
}
