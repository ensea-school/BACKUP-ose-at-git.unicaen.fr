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
    protected ?TauxMixiteForm $formOffreFormationTauxMixiteTauxMixite;



    /**
     * @param TauxMixiteForm|null $formOffreFormationTauxMixiteTauxMixite
     *
     * @return self
     */
    public function setFormOffreFormationTauxMixiteTauxMixite( ?TauxMixiteForm $formOffreFormationTauxMixiteTauxMixite )
    {
        $this->formOffreFormationTauxMixiteTauxMixite = $formOffreFormationTauxMixiteTauxMixite;

        return $this;
    }



    public function getFormOffreFormationTauxMixiteTauxMixite(): ?TauxMixiteForm
    {
        if (!$this->formOffreFormationTauxMixiteTauxMixite){
            $this->formOffreFormationTauxMixiteTauxMixite = \Application::$container->get('FormElementManager')->get(TauxMixiteForm::class);
        }

        return $this->formOffreFormationTauxMixiteTauxMixite;
    }
}