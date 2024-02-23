<?php

namespace Formule\Tbl\Process;

use Formule\Tbl\Process\Sub\ServiceDataManager;
use Psr\Container\ContainerInterface;


/**
 * Description of FormuleProcessFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FormuleProcessFactory
{

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return FormuleProcess
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): FormuleProcess
    {
        $serviceDataManager = $container->get(ServiceDataManager::class);

        $service = new FormuleProcess($serviceDataManager);

        return $service;
    }
}