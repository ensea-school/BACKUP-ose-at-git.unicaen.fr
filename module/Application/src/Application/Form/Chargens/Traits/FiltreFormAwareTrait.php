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
    /**
     * @var FiltreForm
     */
    private $formChargensFiltre;



    /**
     * @param FiltreForm $formChargensFiltre
     *
     * @return self
     */
    public function setFormChargensFiltre(FiltreForm $formChargensFiltre)
    {
        $this->formChargensFiltre = $formChargensFiltre;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return FiltreForm
     */
    public function getFormChargensFiltre()
    {
        if (!empty($this->formChargensFiltre)) {
            return $this->formChargensFiltre;
        }

        return \Application::$container->get('FormElementManager')->get(FiltreForm::class);
    }
}