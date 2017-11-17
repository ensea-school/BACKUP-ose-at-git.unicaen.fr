<?php

namespace Application\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;
use Zend\Console\Console;
use Zend\Mvc\Router\RouteMatch;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\AnneeAwareTrait;

/**
 * Description of AppLinkFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AppLinkFactory implements FactoryInterface
{
    use ContextServiceAwareTrait;
    use AnneeAwareTrait;


    /**
     * Create service
     *
     * @param HelperPluginManager $helperPluginManager
     * @return AppInfos
     */
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        $container = $helperPluginManager->getServiceLocator();

        $router = Console::isConsole() ? 'HttpRouter' : 'Router';
        $match  = $container->get('application')->getMvcEvent()->getRouteMatch();
        $helper = new AppLink();

        $helper->setRouter($container->get($router));

        $helper->setAnnees( $this->getServiceAnnee()->getChoixAnnees() );
        $helper->setAnnee( $this->getServiceContext()->getAnnee() );

        if ($match instanceof RouteMatch) {
            $helper->setRouteMatch($match);
        }

        return $helper;
    }
}