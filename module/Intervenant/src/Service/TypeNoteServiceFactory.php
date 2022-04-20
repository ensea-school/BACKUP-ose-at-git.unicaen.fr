<?php

namespace Intervenant\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of StatutServiceFactory
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class TypeNoteServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return StatutService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $service = new TypeNoteService();

        return $service;
    }
}