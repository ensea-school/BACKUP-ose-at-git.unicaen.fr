<?php

namespace Application\Service;

use Unicaen\Framework\Navigation\Navigation;
use Unicaen\Framework\Router\Router;

class NavbarService
{

    public function __construct(
        private readonly Router         $router,
        private readonly Navigation     $navigation,
        private readonly AnneeService   $anneeService,
        private readonly ContextService $contextService,
    )
    {
    }



    public function appName(): string
    {
        return 'OSE';
    }



    public function appUrl(): string
    {
        return $this->router->url('home');
    }



    public function appTitle(): string
    {
        return 'Page d\'accueil de l\'application';
    }



    public function annee(): int
    {
        return $this->contextService->getAnnee()->getId();
    }



    public function annees(): array
    {
        return $this->anneeService->getChoixAnnees();
    }



    public function menuItems(): array
    {
        $items = [];

        $pages = $this->navigation->home->getVisiblePages();
        foreach ($pages as $pn => $page) {
            $items[$pn] = [
                'label'  => $page->getLabel(),
                'url'    => $page->getUri(),
                'active' => $page->isActive(),
            ];
        }


        return $items;
    }
}