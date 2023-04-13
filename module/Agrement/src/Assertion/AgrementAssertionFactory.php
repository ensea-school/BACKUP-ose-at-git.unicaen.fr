<?php

namespace Agrement\Assertion;

use Psr\Container\ContainerInterface;


class AgrementAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AgrementAssertion
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AgrementAssertion
    {
        $assertion = new AgrementAssertion();

        /* Injectez vos dépendances ICI */

        return $assertion;
    }
}