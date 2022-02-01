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
    protected ?MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche;



    /**
     * @param MiseEnPaiementRechercheForm|null $formPaiementMiseEnPaiementRecherche
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
        if (!$this->formPaiementMiseEnPaiementRecherche){
            $this->formPaiementMiseEnPaiementRecherche = \Application::$container->get('FormElementManager')->get(MiseEnPaiementRechercheForm::class);
        }

        return $this->formPaiementMiseEnPaiementRecherche;
    }
}