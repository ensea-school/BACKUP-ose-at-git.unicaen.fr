<?php

namespace Enseignement\View\Helper;


use Psr\Container\ContainerInterface;

/**
 * Description of LigneEnseignementFactory
 *
 */
class LigneEnseignementFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new LigneEnseignementViewHelper();

        return $helper;
    }
}