<?php

namespace Enseignement\Processus;

/**
 * Description of ValidationEnseignementProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationEnseignementProcessusAwareTrait
{
    protected ?ValidationEnseignementProcessus $processusValidationEnseignement = null;



    /**
     * @param ValidationEnseignementProcessus $processusValidationEnseignement
     *
     * @return self
     */
    public function setProcessusValidationEnseignement(?ValidationEnseignementProcessus $processusValidationEnseignement)
    {
        $this->processusValidationEnseignement = $processusValidationEnseignement;

        return $this;
    }



    public function getProcessusValidationEnseignement(): ?ValidationEnseignementProcessus
    {
        if (empty($this->processusValidationEnseignement)) {
            $this->processusValidationEnseignement = \Framework\Application\Application::getInstance()->container()->get(ValidationEnseignementProcessus::class);
        }

        return $this->processusValidationEnseignement;
    }
}