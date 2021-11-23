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
    /**
     * @var ValidationReferentielProcessus
     */
    private $processusValidationReferentiel;



    /**
     * @param ValidationReferentielProcessus $processusValidationReferentiel
     *
     * @return self
     */
    public function setProcessusValidationReferentiel(ValidationReferentielProcessus $processusValidationReferentiel)
    {
        $this->processusValidationReferentiel = $processusValidationReferentiel;

        return $this;
    }



    /**
     * @return ValidationReferentielProcessus
     */
    public function getProcessusValidationReferentiel()
    {
        if (empty($this->processusValidationReferentiel)) {
            $this->processusValidationReferentiel = \Application::$container->get(ValidationReferentielProcessus::class);
        }

        return $this->processusValidationReferentiel;
    }
}