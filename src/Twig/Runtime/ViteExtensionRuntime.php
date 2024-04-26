<?php

namespace App\Twig\Runtime;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Extension\RuntimeExtensionInterface;

readonly class ViteExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        #[Autowire(env: 'bool:TWIG_VITE_DEV')]
        private bool $isDev,
        #[Autowire('%kernel.project_dir%/public/assets/.vite/manifest.json')]
        private string $manifest,
    ) {
    }

    public function includeViteAssets(string $entrypoint): string
    {
        return $this->isDev ? $this->generateViteHtmlForDev($entrypoint) : $this->generateViteHtmlForProd($entrypoint);
    }

    private function generateViteHtmlForDev(string $entrypoint): string
    {
        return <<<HTML
<script type='module' src='http://localhost:5173/assets/@vite/client'></script>
<script type="module" src="http://localhost:5173/assets/$entrypoint"></script>
HTML;
    }

    private function generateViteHtmlForProd(string $entrypoint): string
    {
        $manifestContent = file_get_contents($this->manifest) ?: '';
        $manifestParsed = json_decode($manifestContent, true);
        $entrypointEntries = is_array($manifestParsed) ? ($manifestParsed[$entrypoint] ?? []) : [];

        $file = $entrypointEntries['file'] ?? '';
        $css = $entrypointEntries['css'] ?? [];
        $imports = $entrypointEntries['imports'] ?? [];

        $html = <<<HTML
<script type="module" src="/assets/{$file}"></script>
HTML;
        foreach ($css as $cssFile) {
            $html .= <<<HTML
<link rel="stylesheet" media="screen" href="/assets/{$cssFile}"/>
HTML;
        }

        foreach ($imports as $import) {
            $html .= <<<HTML
<link rel="modulepreload" href="/assets/{$import}"/>
HTML;
        }

        return $html;
    }
}
