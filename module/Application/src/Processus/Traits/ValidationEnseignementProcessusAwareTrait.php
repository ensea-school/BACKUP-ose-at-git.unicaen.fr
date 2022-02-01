<?php

namespace Application\Processus\Traits;

use Application\Processus\ValidationEnseignementProcessus;

/**
 * Description of ValidationEnseignementProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationEnseignementProcessusAwareTrait
{
    protected ?ValidationEnseignementProcessus $processusValidationEnseignement;



    /**
     * @param ValidationEnseignementProcessus|null $processusValidationEnseignement
     *
     * @return self
     */
    public function setProcessusValidationEnseignement( ?ValidationEnseignementProcessus $processusValidationEnseignement )
    {
        $this->processusValidationEnseignement = $processusValidationEnseignement;

        return $this;
    }



    public function getProcessusValidationEnseignement(): ?ValidationEnseignementProcessus
    {
        if (!$this->processusValidationEnseignement){
            $this->processusValidationEnseignement = \Application::$container->get(ValidationEnseignementProcessus::class);
        }

        return $this->processusValidationEnseignement;
    }
}