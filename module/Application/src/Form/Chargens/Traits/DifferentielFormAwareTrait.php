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
    protected ?DifferentielForm $formChargensDifferentiel;



    /**
     * @param DifferentielForm|null $formChargensDifferentiel
     *
     * @return self
     */
    public function setFormChargensDifferentiel( ?DifferentielForm $formChargensDifferentiel )
    {
        $this->formChargensDifferentiel = $formChargensDifferentiel;

        return $this;
    }



    public function getFormChargensDifferentiel(): ?DifferentielForm
    {
        if (!$this->formChargensDifferentiel){
            $this->formChargensDifferentiel = \Application::$container->get('FormElementManager')->get(DifferentielForm::class);
        }

        return $this->formChargensDifferentiel;
    }
}