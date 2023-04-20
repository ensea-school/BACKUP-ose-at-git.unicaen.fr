<?php

namespace Mission\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;


/**
 * Description of CandidatureServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class CandidatureServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CandidatureService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CandidatureService
    {
        $service = new CandidatureService();
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}