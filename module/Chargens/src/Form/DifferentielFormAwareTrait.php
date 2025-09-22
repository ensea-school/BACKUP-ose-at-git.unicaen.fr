<?php

namespace Chargens\Form;

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

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(DifferentielForm::class);
    }
}