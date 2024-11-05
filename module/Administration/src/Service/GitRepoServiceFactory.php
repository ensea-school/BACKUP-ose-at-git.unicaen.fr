<?php

namespace Administration\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of GitRepoServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class GitRepoServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return GitRepoService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): GitRepoService
    {
        $service = new GitRepoService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}