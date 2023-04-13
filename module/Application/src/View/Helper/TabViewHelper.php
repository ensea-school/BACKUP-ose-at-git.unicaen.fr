<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHtmlElement;


/**
 * Description of TabViewHelper
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TabViewHelper extends AbstractHtmlElement
{

    protected array  $pages = [];

    protected string $current;



    /**
     *
     * @return self
     */
    public function __invoke()
    {
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
        $r = $this->getView()->tag('ul', ['class' => 'nav nav-tabs', 'role' => 'tablist'])->open();
        foreach ($this->pages as $id => $label) {
            $liAttrs     = [
                'class' => 'nav-item',
                'role'  => 'presentation',
            ];
            $buttonAttrs = [
                'class'          => ['nav-link'],
                'data-bs-toggle' => 'tab',
                'data-bs-target' => '#' . $id,
                'type'           => 'button',
                'role'           => 'tab',
                'aria-controls'  => $id,
                'aria-selected'  => $id === $this->current ? 'true' : 'false',
            ];
            if ($id === $this->current) {
                $buttonAttrs['class'][] = 'active';
            }
            $r .= $this->getView()->tag('li', $liAttrs)->html(
                $this->getView()->tag('button', $buttonAttrs)->text($label)
            );
        }
        $r .= $this->getView()->tag('ul')->close();

        return $r;
    }



    public function contentBegin(): string
    {
        return '<div class="tab-content">';
    }



    public function contentEnd(): string
    {
        return '</div>';
    }


    public function pageBegin(string $pageId): string
    {
        $attrs = [
            'class'           => ['tab-pane', 'fade', 'show'],
            'id'              => $pageId,
            'role'            => 'tabpanel',
            'aria-labelledby' => $pageId . '-tab',
        ];
        if ($pageId === $this->current) {
            $attrs['class'][] = 'active';
        }

        return $this->getView()->tag('div', $attrs)->open();
    }



    public function pageEnd(): string
    {
        return '</div>';
    }



    public function addPages(array $pages): self
    {
        foreach ($pages as $id => $label) {
            if (is_string($label) && is_string($id)) {
                $this->pages[$id] = $label;
            }
        }

        return $this;
    }



    public function addPage(string $id, string $label): self
    {
        $this->pages[$id] = $label;

        return $this;
    }



    public function getPages(): array
    {
        return $this->pages;
    }



    public function setPages(array $pages): TabViewHelper
    {
        $this->pages = $pages;

        return $this;
    }



    public function getCurrent(): string
    {
        return $this->current;
    }



    public function setCurrent(string $current): TabViewHelper
    {
        $this->current = $current;

        return $this;
    }

}