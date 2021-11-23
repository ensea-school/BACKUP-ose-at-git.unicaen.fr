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
    /**
     * @var MiseEnPaiementRechercheForm
     */
    private $formPaiementMiseEnPaiementRecherche;



    /**
     * @param MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche
     *
     * @return self
     */
    public function setFormPaiementMiseEnPaiementRecherche(MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche)
    {
        $this->formPaiementMiseEnPaiementRecherche = $formPaiementMiseEnPaiementRecherche;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return MiseEnPaiementRechercheForm
     */
    public function getFormPaiementMiseEnPaiementRecherche()
    {
        if (!empty($this->formPaiementMiseEnPaiementRecherche)) {
            return $this->formPaiementMiseEnPaiementRecherche;
        }

        return \Application::$container->get('FormElementManager')->get(MiseEnPaiementRechercheForm::class);
    }
}