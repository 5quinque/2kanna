<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Repository\BoardRepository;
use App\Service\CrossLink;

class CrossLinkExtension extends AbstractExtension
{
    private $boardRepository;
    private $crossLink;

    public function __construct(BoardRepository $boardRepository, CrossLink $crossLink)
    {
        $this->boardRepository = $boardRepository;
        $this->crossLink = $crossLink;
    }
    public function getFilters()
    {
        return [
            new TwigFilter('crosslink', [$this, 'createCrossLinks']),
        ];
    }

    public function createCrossLinks(string $string)
    {
        $regex = '/&gt;&gt;&gt;\/(\w+)\/?/';

        preg_match_all($regex, $string, $matches);

        if ($matches[0]) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $text = $matches[0][$i];
                $board = $matches[1][$i];

                // Check if the board is valid
                $en = $this->boardRepository->findOneBy(["name" => $board]);
                if ($en) {
                    $url = $this->crossLink->generateBoardUrl($en->getName());
                    $string = preg_replace("!$text!", "<a href='$url'>$text</a>", $string);
                }
            }

            return $string;
        } else {
            return $string;
        }
    }
}
