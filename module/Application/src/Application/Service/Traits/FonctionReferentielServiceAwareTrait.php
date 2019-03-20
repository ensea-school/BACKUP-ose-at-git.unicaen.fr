<?php

namespace Application\Service\Traits;

use Application\Service\FonctionReferentielService;

/**
 * Description of FonctionReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielServiceAwareTrait
{
    /**
     * @var FonctionReferentielService
     */
    private $serviceFonctionReferentiel;



    /**
     * @param FonctionReferentielService $serviceFonctionReferentiel
     *
     * @return self
     */
    public function setServiceFonctionReferentiel(FonctionReferentielService $serviceFonctionReferentiel)
    {
        $this->serviceFonctionReferentiel = $serviceFonctionReferentiel;

        return $this;
    }



    /**
     * @return FonctionReferentielService
     */
    public function getServiceFonctionReferentiel()
    {
        if (empty($this->serviceFonctionReferentiel)) {
            $this->serviceFonctionReferentiel = \Application::$container->get(FonctionReferentielService::class);
        }

        return $this->serviceFonctionReferentiel;
    }
}