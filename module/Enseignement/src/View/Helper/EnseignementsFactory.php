<?php

namespace Enseignement\View\Helper;

use Psr\Container\ContainerInterface;

/**
 * Description of EnseignementsFactory
 *
 */
class EnseignementsFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new EnseignementsViewHelper();

        return $helper;
    }
}