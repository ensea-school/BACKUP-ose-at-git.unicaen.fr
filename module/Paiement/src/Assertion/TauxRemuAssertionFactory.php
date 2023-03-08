<?php

namespace Paiement\Assertion;

use Psr\Container\ContainerInterface;


/**
 * Description of TauxRemuAssertionFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TauxRemuAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TauxRemuAssertion
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TauxRemuAssertion
    {
        return new TauxRemuAssertion();
    }
}

