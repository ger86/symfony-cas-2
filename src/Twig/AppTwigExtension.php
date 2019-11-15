<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getCharsCount', [$this, 'countChars'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter(
                'withHeaderImage', 
                [$this, 'addHeaderImageToText'], 
                ['is_safe' => ['html']]
            )
        ];
    }

    public function countChars(string $text): int
    {
        return strlen($text);
    }

    public function addHeaderImageToText(string $text, $width, $height): string {
        $img = sprintf('<img src="https://placekitten.com/g/%s/%s"><br>', $width, $height);
        return $img.$text;
    }
}