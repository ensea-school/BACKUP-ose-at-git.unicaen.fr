<?php

namespace Application\Traits;

/**
 * Trait fournissant le nécessaire pour spécifier un mode de fonctionnement en lecture seule.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait ReadOnlyAwareTrait
{
    /**
     * @var boolean
     */
    protected $readOnly = false;

    /**
     * Indique si l'on est en mode lecture seule.
     *
     * @return boolean
     */
    public function getReadOnly()
    {
        return $this->readOnly;
    }

    /**
     * Spécifie si l'on est en mode lecture seule.
     *
     * @param boolean $readOnly
     * @return self
     */
    public function setReadOnly($readOnly = true)
    {
        $this->readOnly = $readOnly;
        
        return $this;
    }
}