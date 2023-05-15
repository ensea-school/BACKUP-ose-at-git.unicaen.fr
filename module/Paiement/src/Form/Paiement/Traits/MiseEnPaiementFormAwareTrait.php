<?php

namespace Paiement\Form\Paiement\Traits;

use Paiement\Form\Paiement\MiseEnPaiementForm;

/**
 * Description of MiseEnPaiementFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementFormAwareTrait
{
    protected ?MiseEnPaiementForm $formPaiementMiseEnPaiement = null;



    /**
     * @param MiseEnPaiementForm $formPaiementMiseEnPaiement
     *
     * @return self
     */
    public function setFormPaiementMiseEnPaiement(?MiseEnPaiementForm $formPaiementMiseEnPaiement)
    {
        $this->formPaiementMiseEnPaiement = $formPaiementMiseEnPaiement;

        return $this;
    }



    public function getFormPaiementMiseEnPaiement(): ?MiseEnPaiementForm
    {
        if (!empty($this->formPaiementMiseEnPaiement)) {
            return $this->formPaiementMiseEnPaiement;
        }

        return \Application::$container->get('FormElementManager')->get(MiseEnPaiementForm::class);
    }
}