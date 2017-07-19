<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicAttenteValidationServiceRef;

/**
 * Description of VIndicAttenteValidationServiceRefAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicAttenteValidationServiceRefAwareTrait
{
    /**
     * @var VIndicAttenteValidationServiceRef
     */
    private $vIndicAttenteValidationServiceRef;





    /**
     * @param VIndicAttenteValidationServiceRef $vIndicAttenteValidationServiceRef
     * @return self
     */
    public function setVIndicAttenteValidationServiceRef( VIndicAttenteValidationServiceRef $vIndicAttenteValidationServiceRef = null )
    {
        $this->vIndicAttenteValidationServiceRef = $vIndicAttenteValidationServiceRef;
        return $this;
    }



    /**
     * @return VIndicAttenteValidationServiceRef
     */
    public function getVIndicAttenteValidationServiceRef()
    {
        return $this->vIndicAttenteValidationServiceRef;
    }
}