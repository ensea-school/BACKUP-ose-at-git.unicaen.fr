<?php

namespace Application\Form\Paiement\Traits;

use Application\Form\Paiement\MiseEnPaiementRechercheForm;

/**
 * Description of MiseEnPaiementRechercheFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementRechercheFormAwareTrait
{
    protected ?MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche = null;



    /**
     * @param MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche
     *
     * @return self
     */
    public function setFormPaiementMiseEnPaiementRecherche( ?MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche )
    {
        $this->formPaiementMiseEnPaiementRecherche = $formPaiementMiseEnPaiementRecherche;

        return $this;
    }



    public function getFormPaiementMiseEnPaiementRecherche(): ?MiseEnPaiementRechercheForm
    {
        if (empty($this->formPaiementMiseEnPaiementRecherche)){
            $this->formPaiementMiseEnPaiementRecherche = \Application::$container->get('FormElementManager')->get(MiseEnPaiementRechercheForm::class);
        }

        return $this->formPaiementMiseEnPaiementRecherche;
    }
}