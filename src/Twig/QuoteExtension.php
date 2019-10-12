<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class QuoteExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('gt2quote', [$this, 'formatQuote'], ['is_safe' => ['html']]),
        ];
    }

    public function formatQuote($string)
    {
        return preg_replace('/(^&gt;.+$)/m', "<span class='text-dark'>$1</span>", $string);
    }
}