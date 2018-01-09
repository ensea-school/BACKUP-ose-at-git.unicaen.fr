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
    /**
     * @var ValidationEnseignementProcessus
     */
    private $processusValidationEnseignement;



    /**
     * @param ValidationEnseignementProcessus $processusValidationEnseignement
     *
     * @return self
     */
    public function setProcessusValidationEnseignement(ValidationEnseignementProcessus $processusValidationEnseignement)
    {
        $this->processusValidationEnseignement = $processusValidationEnseignement;

        return $this;
    }



    /**
     * @return ValidationEnseignementProcessus
     */
    public function getProcessusValidationEnseignement()
    {
        if (empty($this->processusValidationEnseignement)) {
            $this->processusValidationEnseignement = \Application::$container->get(ValidationEnseignementProcessus::class);
        }

        return $this->processusValidationEnseignement;
    }
}