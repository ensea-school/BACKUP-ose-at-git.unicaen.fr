<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VIndicAttenteValidationService;

/**
 * Description of VIndicAttenteValidationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait VIndicAttenteValidationServiceAwareTrait
{
    /**
     * @var VIndicAttenteValidationService
     */
    private $vIndicAttenteValidationService;





    /**
     * @param VIndicAttenteValidationService $vIndicAttenteValidationService
     * @return self
     */
    public function setVIndicAttenteValidationService( VIndicAttenteValidationService $vIndicAttenteValidationService = null )
    {
        $this->vIndicAttenteValidationService = $vIndicAttenteValidationService;
        return $this;
    }



    /**
     * @return VIndicAttenteValidationService
     */
    public function getVIndicAttenteValidationService()
    {
        return $this->vIndicAttenteValidationService;
    }
}