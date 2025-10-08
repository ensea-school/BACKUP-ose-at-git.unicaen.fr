<?php

namespace Framework\Navigation;

use Framework\Authorize\Authorize;
use Framework\Router\Router;
use Psr\Container\ContainerInterface;

class Page
{

    /** @var array|Page[] */
    private array $pages = [];

    private Router    $router;
    private Authorize $authorize;



    public function __construct(
        private readonly Navigation         $navigation,
        private readonly ContainerInterface $container,
        private readonly string             $pageName,
        private readonly array              $data,
        private ?Page                       $parent = null,
    )
    {
        $this->authorize = $this->container->get(Authorize::class);
        $this->router    = $this->container->get(Router::class);

        if (array_key_exists('pages', $data)) {
            // tri direct
            uasort($data['pages'], function (array $p1, array $p2) {
                return ($p1['order'] ?? 0) <=> ($p2['order'] ?? 0);
            });
            foreach ($data['pages'] as $pageName => $pageData) {
                $this->pages[$pageName] = new Page($this->navigation, $this->container, $pageName, $pageData, $this);
            }
        }
    }



    public function isFooter(): bool
    {
        return $this->data['footer'] ?? false;
    }



    public function isVisible(): bool
    {
        $visible = $this->data['visible'] ?? true;

        if (false === $visible) {
            return false;
        }

        $ressource = $this->data['resource'] ?? null;


        if (is_string($ressource)) {
            $visible = $this->authorize->isAllowed($ressource);
        }

        if (is_string($visible) && $this->container->has($visible)) {
            $assertion = $this->container->get($visible);
            $visible   = $assertion->__invoke($this->data);
        }

        return $visible;
    }



    public function isActive(): bool
    {
        $currentPage = $this->navigation->getCurrentPage();
        if ($currentPage) {
            return $this->isParentOf($this->navigation->getCurrentPage());
        } else {
            return false;
        }
    }



    public function isParentOf(Page $page): bool
    {
        do {
            if ($this === $page) {
                return true;
            }
        } while ($page = $page->getParent());

        return false;
    }



    public function getName(): string
    {
        return $this->pageName;
    }



    public function getLabel(): string
    {
        return $this->data['label'] ?? '';
    }



    public function getTitle(): string
    {
        return $this->data['title'] ?? '';
    }



    public function getRoute(): ?string
    {
        return $this->data['route'] ?? null;
    }



    public function getClass(): ?string
    {
        return $this->data['class'] ?? null;
    }



    public function getColor(): ?string
    {
        return $this->data['color'] ?? null;
    }



    public function getIcon(): ?string
    {
        return $this->data['icon'] ?? null;
    }



    public function getData(?string $key = null): mixed
    {
        if (null !== $key) {
            return $this->data[$key] ?? null;
        }else{
            return $this->data;
        }
    }



    public function getParent(): ?Page
    {
        return $this->parent;
    }



    public function getPage(string $name): ?Page
    {
        return $this->pages[$name] ?? null;
    }



    /**
     * @return array|Page[]
     */
    public function getPages(): array
    {
        return $this->pages;
    }



    /**
     * @return array|Page[]
     */
    public function getVisiblePages(): array
    {
        $pages = $this->pages;
        foreach ($pages as $pname => $page) {
            if (!$page->isVisible()) {
                unset($pages[$pname]);
            }
        }
        return $pages;
    }



    public function getUri(array $params = []): string
    {
        if (array_key_exists('uri', $this->data)) {
            return $this->data['uri'];
        }

        if (array_key_exists('route', $this->data)) {
            return $this->router->url($this->data['route'], $params);
        }

        throw new \Exception('La route ou l\'uri n\'ont pas été définies');
    }



    public function htmlify(): string
    {
        $label = $this->getLabel();
        $title = $this->getTitle();

        // get attribs for anchor element
        $attribs = [
            'id'     => $this->getData('id'),
            'title'  => $title,
            'class'  => $this->getClass(),
            'href'   => $this->getUri([]),
            'target' => $this->getData('target'),
        ];

        if ($this->isActive()) {
            $attribs['aria-current'] = 'page';
        }

        foreach($attribs as $attrib => $value) {
            if ($value) {
                $attribs[$attrib] = ' '.$attrib.'="'.htmlspecialchars($value, ENT_QUOTES).'"';
            }else{
                unset($attribs[$attrib]);
            }
        }

        return '<a' .implode('', $attribs) . '>' . $label . '</a>';
    }
}