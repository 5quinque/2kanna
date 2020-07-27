<?php

namespace App\Util;

use App\Entity\Post;
use Enqueue\Client\ProducerInterface;
use Liip\ImagineBundle\Async\Commands;
use Liip\ImagineBundle\Async\ResolveCache;

class ImageCache
{
    private $producer;
    private $settingUtil;

    public function __construct(ProducerInterface $producer, SettingUtil $settingUtil)
    {
        $this->producer = $producer;
        $this->settingUtil = $settingUtil;
    }

    public function queueImageFilter(Post $post)
    {
        // Resolve cached images in the background (thumbnails, stripping exif)
        if (preg_match('/^image\//', $post->getImageMimeType())) {
            $reply = $this->producer->sendCommand(
                Commands::RESOLVE_CACHE,
                new ResolveCache($post->getImageName(), ['thumb', 'img']),
                $this->needReply()
            );

            if ($reply) {
                $reply->receive(10000); // wait for 10 sec
            }
        }
    }

    private function needReply()
    {
        return $this->settingUtil->setting('wait_image_filter');
    }
}
