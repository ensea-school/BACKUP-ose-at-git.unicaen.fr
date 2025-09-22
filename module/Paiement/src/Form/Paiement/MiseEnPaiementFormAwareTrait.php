<?php

namespace Paiement\Form\Paiement;


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

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MiseEnPaiementForm::class);
    }
}