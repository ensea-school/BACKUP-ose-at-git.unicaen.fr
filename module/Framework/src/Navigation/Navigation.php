<?php

namespace Framework\Navigation;

use Framework\Container\Autowire;
use Framework\Router\Router;
use Psr\Container\ContainerInterface;

class Navigation
{
    public Page $home;

    private ?Page $currentPage = null;



    public function __construct(
        private readonly ContainerInterface $container,

        #[Autowire(config: 'navigation')]
        array                               $navigation,
    )
    {
        if (!isset($navigation['default']['home'])) {
            throw new \Exception('La navigation ne comporte pas de page default/home');
        }

        $this->home = new Page($this, $this->container, 'home', $navigation['default']['home']);
    }



    public function getCurrentPage(): ?Page
    {
        if (!$this->currentPage) {
            $router = $this->container->get(Router::class);

            $currentRoute = $router->getCurrentRoute();
            $home         = $this->home;

            if (!$currentRoute) {
                return null;
            }
            $this->searchCurrentPage($home, $currentRoute->getName());
        }
        return $this->currentPage;
    }



    private function searchCurrentPage(Page $page, string $route): ?Page
    {
        $pageRoute = $page->getRoute();
        if (empty($pageRoute)) {
            return null;
        }

        if ($pageRoute === $route) {
            $this->currentPage = $page;
            return null;
        } else {
            foreach ($page->getPages() as $subPage) {
                if ($this->searchCurrentPage($subPage, $route)) {
                    return $subPage;
                }
            }
        }
        return null;
    }



    public function getCurrentSubHomePage(): ?Page
    {
        $currentPage = $this->getCurrentPage();
        if (!$currentPage){
            return null;
        }

        while ($currentPage && $currentPage->getParent() !== $this->home){
            $currentPage = $currentPage->getParent();
        }

        return $currentPage;
    }
}