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
    protected ?MiseEnPaiementForm $formPaiementMiseEnPaiement = null;



    /**
     * @param MiseEnPaiementForm $formPaiementMiseEnPaiement
     *
     * @return self
     */
    public function setFormPaiementMiseEnPaiement( MiseEnPaiementForm $formPaiementMiseEnPaiement )
    {
        $this->formPaiementMiseEnPaiement = $formPaiementMiseEnPaiement;

        return $this;
    }



    public function getFormPaiementMiseEnPaiement(): ?MiseEnPaiementForm
    {
        if (empty($this->formPaiementMiseEnPaiement)){
            $this->formPaiementMiseEnPaiement = \Application::$container->get('FormElementManager')->get(MiseEnPaiementForm::class);
        }

        return $this->formPaiementMiseEnPaiement;
    }
}