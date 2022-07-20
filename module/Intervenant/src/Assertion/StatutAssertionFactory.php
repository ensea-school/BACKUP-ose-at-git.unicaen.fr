<?php

namespace Intervenant\Assertion;

use Psr\Container\ContainerInterface;



/**
 * Description of StatutAssertionFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class StatutAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return StatutAssertion
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): StatutAssertion
    {
        $assertion = new StatutAssertion;

        /* Injectez vos dépendances ICI */

        return $assertion;
    }
}