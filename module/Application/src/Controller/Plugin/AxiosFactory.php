<?php

namespace Application\Controller\Plugin;

use Psr\Container\ContainerInterface;


/**
 * Description of AxiosFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AxiosFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return Axios
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): Axios
    {
        $plugin = new Axios;

        /* Injectez vos dépendances ICI */

        return $plugin;
    }
}