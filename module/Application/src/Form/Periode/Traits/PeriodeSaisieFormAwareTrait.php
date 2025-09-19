<?php

namespace Application\Form\Periode\Traits;

use Application\Form\Periode\PeriodeSaisieForm;

/**
 * Description of PeriodeSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PeriodeSaisieFormAwareTrait
{
    protected ?PeriodeSaisieForm $formPeriodePeriodeSaisie = null;



    /**
     * @param PeriodeSaisieForm $formPeriodePeriodeSaisie
     *
     * @return self
     */
    public function setFormPeriodePeriodeSaisie(?PeriodeSaisieForm $formPeriodePeriodeSaisie)
    {
        $this->formPeriodePeriodeSaisie = $formPeriodePeriodeSaisie;

        return $this;
    }



    public function getFormPeriodePeriodeSaisie(): ?PeriodeSaisieForm
    {
        if (!empty($this->formPeriodePeriodeSaisie)) {
            return $this->formPeriodePeriodeSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(PeriodeSaisieForm::class);
    }
}