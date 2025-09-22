<?php

namespace Dossier\View\Helper;


use Psr\Container\ContainerInterface;

/**
 * Description of LigneEnseignementFactory
 *
 */
class ValidationViewHelperFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new ValidationViewHelper();

        return $helper;
    }
}