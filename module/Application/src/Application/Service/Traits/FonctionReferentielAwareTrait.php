<?php

namespace Application\Service\Traits;

use Application\Service\FonctionReferentiel;

/**
 * Description of FonctionReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielAwareTrait
{
    /**
     * @var FonctionReferentiel
     */
    private $serviceFonctionReferentiel;



    /**
     * @param FonctionReferentiel $serviceFonctionReferentiel
     *
     * @return self
     */
    public function setServiceFonctionReferentiel(FonctionReferentiel $serviceFonctionReferentiel)
    {
        $this->serviceFonctionReferentiel = $serviceFonctionReferentiel;

        return $this;
    }



    /**
     * @return FonctionReferentiel
     */
    public function getServiceFonctionReferentiel()
    {
        if (empty($this->serviceFonctionReferentiel)) {
            $this->serviceFonctionReferentiel = \Application::$container->get('ApplicationFonctionReferentiel');
        }

        return $this->serviceFonctionReferentiel;
    }
}