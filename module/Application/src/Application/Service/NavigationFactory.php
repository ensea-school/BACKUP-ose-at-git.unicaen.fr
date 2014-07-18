<?php

namespace Application\Service;

use Zend\Http\Request;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Mvc\Router\RouteMatch;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class NavigationFactory extends DefaultNavigationFactory
{
    use ServiceLocatorAwareTrait;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Navigation\Navigation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        
        return parent::createService($serviceLocator);
    }
    
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
        
        if (!$routeMatch) {
            return $pages;
        }
        
        foreach ($pages as &$page) {
            // l'attribut 'visible' d'une page peut être le nom d'un service
            if (isset($page['visible']) && is_string($page['visible']) && $this->getServiceLocator()->has($page['visible'])) {
                $visible = $this->getServiceLocator()->get($page['visible']);
                if (!is_callable($visible)) {
                    throw new \Common\Exception\LogicException(
                            "Service spécifié pour l'attribut de page 'visible' non valide : " . get_called_class($visible));
                }
                $page['visible'] = $visible($page);
            }
            
            // l'attribut 'pagesProvider' d'une page peut être le nom d'un fournisseur de pages filles
            if (isset($page['pagesProvider']) && is_string($page['pagesProvider']) /*&& $this->getServiceLocator()->has($page['pagesProvider'])*/) {
                $pagesProvider = $this->getServiceLocator()->get($page['pagesProvider']);
                if (!is_callable($pagesProvider)) {
                    throw new \Common\Exception\LogicException(
                            "Service spécifié pour l'attribut de page 'pagesProvider' non valide : " . get_called_class($visible));
                }
                $children = $pagesProvider($page);
                $children = $this->injectComponents($children, $routeMatch, $router, $request);
                if (!isset($page['pages'])) {
                    $page['pages'] = [];
                }
                $page['pages'] = array_merge($children, $page['pages']); // NB: possibilité d'écraser une page fille issue du fournisseur
            }
            
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
        if (!$routeMatch) {
            return;
        }
        
        if (isset($page['withtarget'])) {
            if (($id = $routeMatch->getParam('id'))) {
                $page['params']['id'] = $id;
            }
            elseif (($id = $routeMatch->getParam('intervenant'))) {
                $page['params']['intervenant'] = $id;
            }
            else {
                $page['visible'] = false;
            }
        }
    }
}