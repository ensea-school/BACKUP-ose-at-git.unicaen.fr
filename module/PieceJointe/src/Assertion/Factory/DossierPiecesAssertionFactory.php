<?php

namespace PieceJointe\Assertion\Factory;

use Dossier\Assertion\IntervenantDossierAssertion;
use PieceJointe\Assertion\DossierPiecesAssertion;
use Psr\Container\ContainerInterface;


class DossierPiecesAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DossierPiecesAssertion
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DossierPiecesAssertion
    {
        $assertion = new DossierPiecesAssertion();

        /* Injectez vos dépendances ICI */

        return $assertion;
    }
}