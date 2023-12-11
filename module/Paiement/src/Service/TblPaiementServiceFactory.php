<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of TblPaiementServiceFactory
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 **/
class TblPaiementServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TblPaiementService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TblPaiementService
    {
        $service = new TblPaiementService();

        /* Injectez vos dépendances ICI */

        return $service;
    }
}