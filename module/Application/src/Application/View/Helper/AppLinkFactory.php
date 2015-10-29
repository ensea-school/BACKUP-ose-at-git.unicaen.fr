<?php

namespace Application\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Console\Console;
use Zend\Mvc\Router\RouteMatch;

/**
 * Description of AppLinkFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AppLinkFactory implements FactoryInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,
        \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\AnneeAwareTrait
    ;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $helperPluginManager
     * @return AppInfos
     */
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        $this->setServiceLocator( $helperPluginManager->getServiceLocator() );
        $router = Console::isConsole() ? 'HttpRouter' : 'Router';
        $match  = $this->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
        $helper = new AppLink();

        $helper->setRouter($this->getServiceLocator()->get($router));

        $helper->setAnnees( $this->getServiceAnnee()->getList( $this->getServiceAnnee()->finderByActive(true) ) );
        $helper->setAnnee( $this->getServiceContext()->getAnnee() );
        
        if ($match instanceof RouteMatch) {
            $helper->setRouteMatch($match);
        }

        return $helper;
    }
}