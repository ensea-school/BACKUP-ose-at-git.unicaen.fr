<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\DifferentielForm;

/**
 * Description of DifferentielFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DifferentielFormAwareTrait
{
    protected ?DifferentielForm $formChargensDifferentiel = null;



    /**
     * @param DifferentielForm $formChargensDifferentiel
     *
     * @return self
     */
    public function setFormChargensDifferentiel(?DifferentielForm $formChargensDifferentiel)
    {
        $this->formChargensDifferentiel = $formChargensDifferentiel;

        return $this;
    }



    public function getFormChargensDifferentiel(): ?DifferentielForm
    {
        if (!empty($this->formChargensDifferentiel)) {
            return $this->formChargensDifferentiel;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(DifferentielForm::class);
    }
}