<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicAttenteValidationServiceRef;

/**
 * Description of VIndicAttenteValidationServiceRefAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicAttenteValidationServiceRefAwareInterface
{
    /**
     * @param VIndicAttenteValidationServiceRef $vIndicAttenteValidationServiceRef
     * @return self
     */
    public function setVIndicAttenteValidationServiceRef( VIndicAttenteValidationServiceRef $vIndicAttenteValidationServiceRef = null );



    /**
     * @return VIndicAttenteValidationServiceRef
     */
    public function getVIndicAttenteValidationServiceRef();
}