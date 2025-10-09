<?php

namespace Referentiel\Service;

/**
 * Description of FonctionReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielServiceAwareTrait
{
    protected ?FonctionReferentielService $serviceFonctionReferentiel = null;



    /**
     * @param FonctionReferentielService $serviceFonctionReferentiel
     *
     * @return self
     */
    public function setServiceFonctionReferentiel(?FonctionReferentielService $serviceFonctionReferentiel)
    {
        $this->serviceFonctionReferentiel = $serviceFonctionReferentiel;

        return $this;
    }



    public function getServiceFonctionReferentiel(): ?FonctionReferentielService
    {
        if (empty($this->serviceFonctionReferentiel)) {
            $this->serviceFonctionReferentiel = \Unicaen\Framework\Application\Application::getInstance()->container()->get(FonctionReferentielService::class);
        }

        return $this->serviceFonctionReferentiel;
    }
}