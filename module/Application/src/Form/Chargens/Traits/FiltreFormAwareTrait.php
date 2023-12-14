<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\FiltreForm;

/**
 * Description of FiltreFormAwareTrait
 *
 * @author UnicaenCode
 */
trait FiltreFormAwareTrait
{
    protected ?FiltreForm $formChargensFiltre = null;



    /**
     * @param FiltreForm $formChargensFiltre
     *
     * @return self
     */
    public function setFormChargensFiltre(?FiltreForm $formChargensFiltre)
    {
        $this->formChargensFiltre = $formChargensFiltre;

        return $this;
    }



    public function getFormChargensFiltre(): ?FiltreForm
    {
        if (!empty($this->formChargensFiltre)) {
            return $this->formChargensFiltre;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(FiltreForm::class);
    }
}