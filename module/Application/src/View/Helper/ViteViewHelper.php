<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;


/**
 * Description of ViteViewHelper
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ViteViewHelper extends AbstractHtmlElement
{
    private $config = [
        'host'   => 'http://localhost:5133',
        'vue-url' => '/vendor/vue.js',
        'hot-loading' => true,
    ];



    /**
     * @param string[] $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }



    /**
     *
     * @return self|string
     */
    public function __invoke(?string $entry = null)
    {
        if (!empty($entry)) {
            return $this->vite($entry);
        }

        return $this;
    }



    /**
     * Retourne le code HTML.
     *
     * @return string Code HTML
     */
    public function __toString(): string
    {
        return $this->render();
    }



    /**
     *
     *
     * @return string Code HTML
     */
    public function render(): string
    {
        $r = '';

        /* Complétez */

        return $r;
    }



    public function useNode(): bool
    {
        return $this->getConfig('hot-loading');
    }



    public function getConfig(string $param)
    {
        return $this->config[$param] ?: null;
    }



    public function setConfig(string $param, $value): self
    {
        $this->config[$param] = $value;

        return $this;
    }



    public function head(): string
    {
        $h = '';
        if ($this->useNode()) {
            $url = $this->getView()->basePath($this->getConfig('vue-url'));
            $h   = '<script type="text/javascript" src="' . $url . '"></script>';
        }
        $h .= $this->vite('main.js');

        return $h;
    }



    public function vite(string $entry): string
    {
        return "\n" . $this->jsTag($entry)
            . "\n" . $this->jsPreloadImports($entry)
            . "\n" . $this->cssTag($entry);
    }



    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }



    protected function jsTag(string $entry): string
    {
        $url = $this->useNode()
            ? $this->getConfig('host') . '/' . $entry
            : $this->assetUrl($entry);

        if (!$url) {
            return '';
        }

        return '<script type="module" crossorigin src="' . $url . '"></script>';
    }



    protected function jsPreloadImports(string $entry): string
    {
        if ($this->useNode()) {
            return '';
        }

        $res = '';
        foreach ($this->importsUrls($entry) as $url) {
            $res .= '<link rel="modulepreload" href="' . $url . '">';
        }

        return $res;
    }



    protected function cssTag(string $entry): string
    {
        // not needed on dev, it's inject by Vite
        if ($this->useNode()) {
            return '';
        }

        $tags = '';
        foreach ($this->cssUrls($entry) as $url) {
            $tags .= '<link rel="stylesheet" href="' . $url . '">';
        }

        return $tags;
    }



    protected function getManifest(): array
    {
        $content = file_get_contents(getcwd() . '/public/dist/manifest.json');

        return json_decode($content, true);
    }



    protected function assetUrl(string $entry): string
    {
        $manifest = $this->getManifest();

        return isset($manifest[$entry])
            ? $this->getView()->basePath('/dist/' . $manifest[$entry]['file'])
            : '';
    }



    protected function importsUrls(string $entry): array
    {
        $urls     = [];
        $manifest = $this->getManifest();

        if (!empty($manifest[$entry]['imports'])) {
            foreach ($manifest[$entry]['imports'] as $imports) {
                $urls[] = $this->getView()->basePath('/dist/' . $manifest[$imports]['file']);
            }
        }

        return $urls;
    }



    protected function cssUrls(string $entry): array
    {
        $urls     = [];
        $manifest = $this->getManifest();

        if (!empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $file) {
                $urls[] = $this->getView()->basePath('/dist/' . $file);
            }
        }

        return $urls;
    }

}