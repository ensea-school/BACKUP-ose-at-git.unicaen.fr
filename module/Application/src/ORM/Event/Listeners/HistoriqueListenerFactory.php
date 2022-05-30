<?php

namespace Application\ORM\Event\Listeners;

use Psr\Container\ContainerInterface;


/**
 * Description of HistoriqueListenerFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class HistoriqueListenerFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return HistoriqueListener
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): HistoriqueListener
    {
        $listener = new HistoriqueListener;

        /* Injectez vos dépendances ICI */

        return $listener;
    }
}