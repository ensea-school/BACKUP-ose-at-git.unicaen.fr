<?php

namespace Application\Service;

use Zend\Http\Request;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Mvc\Router\RouteMatch;
use Zend\Navigation\Service\DefaultNavigationFactory;

class NavigationFactory extends DefaultNavigationFactory
{
    /**
     * @param array $pages
     * @param RouteMatch $routeMatch
     * @param Router $router
     * @param null|Request $request
     * @return mixed
     */
    protected function injectComponents(array $pages, RouteMatch $routeMatch = null, Router $router = null, $request = null)
    {
        $pages = parent::injectComponents($pages, $routeMatch, $router, $request);
        
        foreach ($pages as &$page) {
            $this->injectEntity($page, $routeMatch, $router, $request);
        }
        
        return $pages;
    }
    
    /**
     * Injection de l'id d'une entité dans les paramètres d'une page.
     * 
     * @param array $page
     * @param \Zend\Mvc\Router\RouteMatch $routeMatch
     * @param \Zend\Mvc\Router\RouteStackInterface $router
     * @param type $request
     */
    protected function injectEntity(array &$page, RouteMatch $routeMatch = null, Router $router = null, $request = null)
    {
        if (isset($page['withtarget'])) {
            if (($id = $routeMatch->getParam('id'))) {
                $page['params']['id'] = $id;
            }
            else {
                $page['visible'] = false;
            }
        }
    }
}