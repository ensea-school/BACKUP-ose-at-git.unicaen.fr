<?php

namespace Referentiel\View\Helper;


use Psr\Container\ContainerInterface;

/**
 * Description of ListeFactory
 *
 */
class ReferentielsFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new ReferentielsViewHelper();

        return $helper;
    }
}