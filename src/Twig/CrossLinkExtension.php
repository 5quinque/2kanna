<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use App\Service\CrossLink;

class CrossLinkExtension extends AbstractExtension
{
    private $boardRepository;
    private $postRepository;
    private $crossLink;

    public function __construct(BoardRepository $boardRepository, PostRepository $postRepository, CrossLink $crossLink)
    {
        $this->boardRepository = $boardRepository;
        $this->postRepository = $postRepository;
        $this->crossLink = $crossLink;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('crosslink', [$this, 'createCrossLinks']),
        ];
    }

    // [TODO] tidy this
    public function createCrossLinks(string $string)
    {
        $regex = '/&gt;&gt;&gt;\/(\w+)\/?(\d+)?/';

        preg_match_all($regex, $string, $matches);

        if ($matches[0]) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $text = $matches[0][$i];
                $board = $matches[1][$i];
                $post = $matches[2][$i];

                // Check if post is there, and if it's valid
                if ($post) {
                    $postEntity = $this->postRepository->findOneBy(["id" => $post]);
                }

                // Check if the board is valid
                $boardEntity = $this->boardRepository->findOneBy(["name" => $board]);
                if ($boardEntity) {
                    if (isset($postEntity)) {
                        $url = $this->crossLink->generatePostUrl($boardEntity->getName(), $postEntity->getId());
                    } else {
                        $url = $this->crossLink->generateBoardUrl($boardEntity->getName());
                    }

                    $string = preg_replace("!$text!", "<a href='$url'>$text</a>", $string);
                }
            }
        }

        return $string;
    }
}
