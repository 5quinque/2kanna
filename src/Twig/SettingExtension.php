<?php

namespace App\Twig;

use App\Util\SettingUtil;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingExtension extends AbstractExtension
{
    private $util;

    public function __construct(SettingUtil $util)
    {
        $this->util = $util;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('setting', [$this, 'setting']),
        ];
    }

    public function setting(string $name)
    {
        return $this->util->setting($name);
    }
}
