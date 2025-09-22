<?php

namespace Chargens\Form;

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

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(FiltreForm::class);
    }
}