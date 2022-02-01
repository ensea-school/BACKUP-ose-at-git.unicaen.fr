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
    protected ?FiltreForm $formChargensFiltre;



    /**
     * @param FiltreForm|null $formChargensFiltre
     *
     * @return self
     */
    public function setFormChargensFiltre( ?FiltreForm $formChargensFiltre )
    {
        $this->formChargensFiltre = $formChargensFiltre;

        return $this;
    }



    public function getFormChargensFiltre(): ?FiltreForm
    {
        if (!$this->formChargensFiltre){
            $this->formChargensFiltre = \Application::$container->get('FormElementManager')->get(FiltreForm::class);
        }

        return $this->formChargensFiltre;
    }
}