<?php

namespace Application\Service;

use Application\Service\ContextProvider;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface ContextProviderAwareInterface
{
    /**
     * Spécifie le service fournissant le context global de l'application.
     *
     * @param ContextProvider $contextProvider
     * @return self
     */
    public function setContextProvider(ContextProvider $contextProvider);
    
    /**
     * Retourne le service fournissant le context global de l'application.
     *
     * @return ContextProvider
     */
    public function getContextProvider();
}