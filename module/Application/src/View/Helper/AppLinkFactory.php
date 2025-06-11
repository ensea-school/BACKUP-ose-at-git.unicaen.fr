<?php

namespace Application\View\Helper;

use Psr\Container\ContainerInterface;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\AnneeServiceAwareTrait;

/**
 * Description of AppLinkFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AppLinkFactory
{
    use ContextServiceAwareTrait;
    use AnneeServiceAwareTrait;


    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $router = \AppAdmin::inCli() ? 'HttpRouter' : 'Router';
        $match  = $container->get('application')->getMvcEvent()->getRouteMatch();
        $helper = new AppLink();

        $helper->setRouter($container->get($router));

        $helper->setAnnees($this->getServiceAnnee()->getChoixAnnees());
        $helper->setAnnee($this->getServiceContext()->getAnnee());

        if ($match instanceof \Laminas\Router\RouteMatch) {
            $helper->setRouteMatch($match);
        }

        return $helper;
    }
}