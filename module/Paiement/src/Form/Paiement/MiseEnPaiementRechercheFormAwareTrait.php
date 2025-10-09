<?php

namespace Paiement\Form\Paiement;


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
    public function setFormPaiementMiseEnPaiementRecherche(?MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche)
    {
        $this->formPaiementMiseEnPaiementRecherche = $formPaiementMiseEnPaiementRecherche;

        return $this;
    }



    public function getFormPaiementMiseEnPaiementRecherche(): ?MiseEnPaiementRechercheForm
    {
        if (!empty($this->formPaiementMiseEnPaiementRecherche)) {
            return $this->formPaiementMiseEnPaiementRecherche;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MiseEnPaiementRechercheForm::class);
    }
}