<?php

namespace App\Twig;

use App\Repository\BoardRepository;
use App\Repository\PostRepository;
use App\Service\CrossLink;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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

    public function createCrossLinks($string)
    {
        if (is_null($string)) {
            return;
        }

        $regex = '/&gt;&gt;&gt;\/(\w+)\/?([A-F0-9]+)?/';

        preg_match_all($regex, $string, $matches);

        for ($i = 0; $i < count($matches[0]); ++$i) {
            $text = $matches[0][$i];
            $board = $matches[1][$i];
            $post = $matches[2][$i];

            if (!$this->boardEntity($board)) {
                continue;
            }

            if ($this->postEntity($post)) {
                $string = $this->replacePostUrl($text, $string, $board, $post);
            } else {
                $string = $this->replaceBoardUrl($text, $string, $board);
            }
        }

        return $string;
    }

    private function postEntity($post)
    {
        return $this->postRepository->findOneBy(['slug' => $post]);
    }

    private function boardEntity($board)
    {
        return $this->boardRepository->findOneBy(['name' => $board]);
    }

    private function replacePostUrl($text, $string, $board, $post)
    {
        return preg_replace(
            "!{$text}!",
            "<a href='{$this->crossLink->generatePostUrl($board, $post)}'>{$text}</a>",
            $string
        );
    }

    private function replaceBoardUrl($text, $string, $board)
    {
        return preg_replace(
            "!{$text}!",
            "<a href='{$this->crossLink->generateBoardUrl($board)}'>{$text}</a>",
            $string
        );
    }
}
