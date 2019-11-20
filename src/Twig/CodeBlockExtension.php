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
        $regex = '/```(\w+)?[^\w].+\n(.+)```/smU';

        preg_match_all($regex, $string, $matches);

        for ($i = 0; $i < count($matches[0]); $i++) {
            $text = $matches[0][$i];
            $language = strtolower($matches[1][$i]);
            $language = $this->getLanguage($language);

            $code = $matches[2][$i];

            $string = str_replace($text, "<pre class='line-numbers'><code class='language-{$language}'>{$code}</code></pre>", $string);
        }

        // $backtickPos = strpos($string, '`');
        // $preCount = substr_count($string, '</pre>', 0, $backtickPos);
        //dump($preCount);

        // $string = preg_replace('/`(.+)`/', "<code class='language-clike'>$1</code>", $string);

        return $string;
    }



    private static function getLanguage(string $language)
    {
        $languages = ["clike", "c", "javascript", "php"];

        return $languages[
            array_search($language, $languages, true)
        ];
    }
}
