<?php

namespace Referentiel\View\Helper;


use Psr\Container\ContainerInterface;

/**
 * Description of LigneFactory
 *
 */
class LigneFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new LigneViewHelper();

        return $helper;
    }
}