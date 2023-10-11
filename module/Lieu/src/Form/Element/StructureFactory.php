<?php

namespace Lieu\Form\Element;

use Psr\Container\ContainerInterface;

class StructureFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $element = new Structure();

        return $element;
    }
}