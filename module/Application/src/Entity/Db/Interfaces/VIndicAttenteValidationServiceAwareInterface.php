<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VIndicAttenteValidationService;

/**
 * Description of VIndicAttenteValidationServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface VIndicAttenteValidationServiceAwareInterface
{
    /**
     * @param VIndicAttenteValidationService $vIndicAttenteValidationService
     * @return self
     */
    public function setVIndicAttenteValidationService( VIndicAttenteValidationService $vIndicAttenteValidationService = null );



    /**
     * @return VIndicAttenteValidationService
     */
    public function getVIndicAttenteValidationService();
}