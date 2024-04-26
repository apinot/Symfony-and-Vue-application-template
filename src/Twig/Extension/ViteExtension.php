<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ViteExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite', [ViteExtensionRuntime::class, 'includeViteAssets'], ['is_safe' => ['html']]),
        ];
    }
}
