<?php

namespace Application\Service;

use Application\Service\ContextProvider;

/**
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait ContextProviderAwareTrait
{
    /**
     * @var ContextProvider
     */
    protected $contextProvider;
    
    /**
     * Spécifie le service fournissant le context global de l'application.
     *
     * @param ContextProvider $contextProvider
     * @return self
     */
    public function setContextProvider(ContextProvider $contextProvider = null)
    {
        $this->contextProvider = $contextProvider;
        
        return $this;
    }
    
    /**
     * Retourne le service fournissant le context global de l'application.
     *
     * @return ContextProvider
     */
    public function getContextProvider()
    {
        return $this->contextProvider;
    }
}