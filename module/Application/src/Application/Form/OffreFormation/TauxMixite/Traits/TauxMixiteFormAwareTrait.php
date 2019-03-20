<?php

namespace Application\Form\OffreFormation\TauxMixite\Traits;

use Application\Form\OffreFormation\TauxMixite\TauxMixiteForm;

/**
 * Description of TauxMixiteFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxMixiteFormAwareTrait
{
    /**
     * @var TauxMixiteForm
     */
    private $formOffreFormationTauxMixite;



    /**
     * @param TauxMixiteForm $formOffreFormationTauxMixite
     *
     * @return self
     */
    public function setFormOffreFormationTauxMixite(TauxMixiteForm $formOffreFormationTauxMixite)
    {
        $this->formOffreFormationTauxMixite = $formOffreFormationTauxMixite;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TauxMixiteForm
     */
    public function getFormOffreFormationTauxMixite()
    {
        if (!empty($this->formOffreFormationTauxMixite)) {
            return $this->formOffreFormationTauxMixite;
        }

        return \Application::$container->get('FormElementManager')->get(TauxMixiteForm::class);
    }
}