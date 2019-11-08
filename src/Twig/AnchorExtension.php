<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AnchorExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('urltoanchor', [$this, 'createAnchor']),
        ];
    }
    public function createAnchor(string $string)
    {
        $regex = '/(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,10}(:[0-9]{1,5})?(\/.*)?/';

        return preg_replace($regex, "<a target='_blank' href='$0'>$0</a>", $string);
    }
} 
