<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Console\Console;
use Zend\Mvc\Router\RouteMatch;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\AnneeServiceAwareTrait;

/**
 * Description of AppLinkFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AppLinkFactory implements FactoryInterface
{
    use ContextServiceAwareTrait;
    use AnneeServiceAwareTrait;



    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $router = Console::isConsole() ? 'HttpRouter' : 'Router';
        $match  = $container->get('application')->getMvcEvent()->getRouteMatch();
        $helper = new AppLink();

        $helper->setRouter($container->get($router));

        $helper->setAnnees($this->getServiceAnnee()->getChoixAnnees());
        $helper->setAnnee($this->getServiceContext()->getAnnee());

        if ($match instanceof RouteMatch) {
            $helper->setRouteMatch($match);
        }

        return $helper;
    }
}