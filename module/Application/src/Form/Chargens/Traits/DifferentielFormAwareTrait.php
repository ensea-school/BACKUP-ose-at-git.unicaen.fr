<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\DifferentielForm;

/**
 * Description of ScenarioFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DifferentielFormAwareTrait
{
    /**
     * @var DifferentielForm
     */
    private $formChargensDifferenitel;



    /**
     * @param DifferentielForm $formChargensDifferentiel
     *
     * @return self
     */
    public function setFormChargensDifferentiel(DifferentielForm $formChargensDifferentiel)
    {
        $this->formChargensDifferenitel = $formChargensDifferentiel;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DifferentielForm
     */
    public function getFormChargensDifferentiel()
    {
        if (!empty($this->formChargensDifferenitel)) {
            return $this->formChargensDifferenitel;
        }

        return \Application::$container->get('FormElementManager')->get(DifferentielForm::class);
    }
}