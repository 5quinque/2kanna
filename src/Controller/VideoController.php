<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\Flysystem\FilesystemInterface;

class VideoController extends AbstractController
{
    /**
     * @Route("/uploads/{post}", name="video")
     * @ParamConverter("post", options={"mapping": {"post": "imageName"}})
     */
    public function index(FilesystemInterface $localFilesystem, Post $post): Response
    {
        $fileStream = $localFilesystem->readStream($post->getImageName());
        $contents = stream_get_contents($fileStream);
        fclose($fileStream);

        $response = new Response($contents);
        $response->headers->set('Content-Type', 'video/webm');

        return $response;
    }
}
