<?php

namespace Application\Form\Paiement\Traits;

use Application\Form\Paiement\MiseEnPaiementForm;

/**
 * Description of MiseEnPaiementFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementFormAwareTrait
{
    /**
     * @var MiseEnPaiementForm
     */
    private $formPaiementMiseEnPaiement;



    /**
     * @param MiseEnPaiementForm $formPaiementMiseEnPaiement
     *
     * @return self
     */
    public function setFormPaiementMiseEnPaiement(MiseEnPaiementForm $formPaiementMiseEnPaiement)
    {
        $this->formPaiementMiseEnPaiement = $formPaiementMiseEnPaiement;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return MiseEnPaiementForm
     */
    public function getFormPaiementMiseEnPaiement()
    {
        if (!empty($this->formPaiementMiseEnPaiement)) {
            return $this->formPaiementMiseEnPaiement;
        }

        return \Application::$container->get('FormElementManager')->get(MiseEnPaiementForm::class);
    }
}