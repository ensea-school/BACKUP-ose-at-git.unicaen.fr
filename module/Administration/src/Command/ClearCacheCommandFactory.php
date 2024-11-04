<?php

namespace Administration\Command;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;



/**
 * Description of ClearCacheCommandFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ClearCacheCommandFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ClearCacheCommand
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): ClearCacheCommand
    {
        $command = new ClearCacheCommand;

        $command->setEntityManager($container->get(EntityManager::class));

        return $command;
    }
}