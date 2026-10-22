<?php

namespace Referentiel\View\Helper;


use Psr\Container\ContainerInterface;

/**
 * Description of FormSaisieFactory
 *
 */
class FormSaisieFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new FormSaisieViewHelper();

        return $helper;
    }
}