<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CodeBlockExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('codeblock', [$this, 'createCodeBlock']),
        ];
    }
    public function createCodeBlock(string $string)
    {
        $regex = '/```(\w+)?(.+)```/sm';

        $language = $this->getLanguage("JavaScript");

        return preg_replace($regex, "<pre class='line-numbers'><code class='language-{$language}'>$2</code></pre>", $string);
    }

    private static function getLanguage(string $language)
    {
        $languages = ["C", "JavaScript", "PHP"];

        return $languages[
            array_search($language, $languages, true)
        ];
    }
} 
