<?php

namespace Dossier\Assertion;

use Psr\Container\ContainerInterface;


class IntervenantDossierAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return IntervenantDossierAssertion
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): IntervenantDossierAssertion
    {
        $assertion = new IntervenantDossierAssertion;

        /* Injectez vos dépendances ICI */

        return $assertion;
    }
}