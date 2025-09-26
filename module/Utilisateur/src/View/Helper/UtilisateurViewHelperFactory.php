<?php

namespace Utilisateur\View\Helper;

use Psr\Container\ContainerInterface;


/**
 * Description of UtilisateurViewHelperFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class UtilisateurViewHelperFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UtilisateurViewHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new UtilisateurViewHelper();

        return $service;
    }
}