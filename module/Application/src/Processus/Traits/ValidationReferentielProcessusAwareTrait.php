<?php

namespace Application\Processus\Traits;

use Application\Processus\ValidationReferentielProcessus;

/**
 * Description of ValidationReferentielProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationReferentielProcessusAwareTrait
{
    protected ?ValidationReferentielProcessus $processusValidationReferentiel = null;



    /**
     * @param ValidationReferentielProcessus $processusValidationReferentiel
     *
     * @return self
     */
    public function setProcessusValidationReferentiel(?ValidationReferentielProcessus $processusValidationReferentiel)
    {
        $this->processusValidationReferentiel = $processusValidationReferentiel;

        return $this;
    }



    public function getProcessusValidationReferentiel(): ?ValidationReferentielProcessus
    {
        if (empty($this->processusValidationReferentiel)) {
            $this->processusValidationReferentiel = \Application::$container->get(ValidationReferentielProcessus::class);
        }

        return $this->processusValidationReferentiel;
    }
}