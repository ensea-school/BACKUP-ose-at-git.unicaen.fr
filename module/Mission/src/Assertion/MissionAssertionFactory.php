<?php

namespace Mission\Assertion;

use Psr\Container\ContainerInterface;



/**
 * Description of MissionAssertionFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionAssertionFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionAssertion
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionAssertion
    {
        $assertion = new MissionAssertion;

        /* Injectez vos dépendances ICI */

        return $assertion;
    }
}