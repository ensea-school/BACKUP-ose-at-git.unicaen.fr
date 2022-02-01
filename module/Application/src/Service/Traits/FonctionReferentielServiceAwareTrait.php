<?php

namespace Application\Service\Traits;

use Application\Service\FonctionReferentielService;

/**
 * Description of FonctionReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielServiceAwareTrait
{
    protected ?FonctionReferentielService $serviceFonctionReferentiel;



    /**
     * @param FonctionReferentielService|null $serviceFonctionReferentiel
     *
     * @return self
     */
    public function setServiceFonctionReferentiel( ?FonctionReferentielService $serviceFonctionReferentiel )
    {
        $this->serviceFonctionReferentiel = $serviceFonctionReferentiel;

        return $this;
    }



    public function getServiceFonctionReferentiel(): ?FonctionReferentielService
    {
        if (!$this->serviceFonctionReferentiel){
            $this->serviceFonctionReferentiel = \Application::$container->get(FonctionReferentielService::class);
        }

        return $this->serviceFonctionReferentiel;
    }
}