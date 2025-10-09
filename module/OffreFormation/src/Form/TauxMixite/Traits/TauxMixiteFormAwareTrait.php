<?php

namespace OffreFormation\Form\TauxMixite\Traits;

use OffreFormation\Form\TauxMixite\TauxMixiteForm;

/**
 * Description of TauxMixiteFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxMixiteFormAwareTrait
{
    protected ?TauxMixiteForm $formOffreFormationTauxMixiteTauxMixite = null;



    /**
     * @param TauxMixiteForm $formOffreFormationTauxMixiteTauxMixite
     *
     * @return self
     */
    public function setFormOffreFormationTauxMixiteTauxMixite(?TauxMixiteForm $formOffreFormationTauxMixiteTauxMixite)
    {
        $this->formOffreFormationTauxMixiteTauxMixite = $formOffreFormationTauxMixiteTauxMixite;

        return $this;
    }



    public function getFormOffreFormationTauxMixiteTauxMixite(): ?TauxMixiteForm
    {
        if (!empty($this->formOffreFormationTauxMixiteTauxMixite)) {
            return $this->formOffreFormationTauxMixiteTauxMixite;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TauxMixiteForm::class);
    }
}