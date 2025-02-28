<?php

namespace Formule\Service;

use Administration\Service\ParametresService;
use Psr\Container\ContainerInterface;


/**
 * Description of AfficheurServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AfficheurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AfficheurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AfficheurService
    {
        $service = new AfficheurService;

        $distinctionFiFaFc = $container->get(ParametresService::class)->get('distinction_fi_fa_fc') == '1';
        $service->setDistinctionFiFaFc($distinctionFiFaFc);

        return $service;
    }
}