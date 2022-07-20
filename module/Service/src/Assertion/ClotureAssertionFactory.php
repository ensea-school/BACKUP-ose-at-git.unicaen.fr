<?php

namespace Service\Assertion;

use Psr\Container\ContainerInterface;



/**
 * Description of ClotureAssertionFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ClotureAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ClotureAssertion
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ClotureAssertion
    {
        $assertion = new ClotureAssertion;

        /* Injectez vos dépendances ICI */

        return $assertion;
    }
}