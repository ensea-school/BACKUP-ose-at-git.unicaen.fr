<?php

namespace Enseignement\View\Helper;

use Psr\Container\ContainerInterface;

/**
 * Description of EnseignementSaisieFormFactory
 *
 */
class EnseignementSaisieFormFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new EnseignementSaisieFormViewHelper();

        return $helper;
    }
}