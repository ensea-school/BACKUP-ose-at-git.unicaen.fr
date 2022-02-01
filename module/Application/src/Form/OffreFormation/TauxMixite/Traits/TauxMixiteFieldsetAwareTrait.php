<?php

namespace Application\Form\OffreFormation\TauxMixite\Traits;

use Application\Form\OffreFormation\TauxMixite\TauxMixiteFieldset;

/**
 * Description of TauxMixiteFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxMixiteFieldsetAwareTrait
{
    protected ?TauxMixiteFieldset $formOffreFormationTauxMixiteTauxMixiteFieldset = null;



    /**
     * @param TauxMixiteFieldset $formOffreFormationTauxMixiteTauxMixiteFieldset
     *
     * @return self
     */
    public function setFormOffreFormationTauxMixiteTauxMixiteFieldset( TauxMixiteFieldset $formOffreFormationTauxMixiteTauxMixiteFieldset )
    {
        $this->formOffreFormationTauxMixiteTauxMixiteFieldset = $formOffreFormationTauxMixiteTauxMixiteFieldset;

        return $this;
    }



    public function getFormOffreFormationTauxMixiteTauxMixiteFieldset(): ?TauxMixiteFieldset
    {
        if (empty($this->formOffreFormationTauxMixiteTauxMixiteFieldset)){
            $this->formOffreFormationTauxMixiteTauxMixiteFieldset = \Application::$container->get('FormElementManager')->get(TauxMixiteFieldset::class);
        }

        return $this->formOffreFormationTauxMixiteTauxMixiteFieldset;
    }
}