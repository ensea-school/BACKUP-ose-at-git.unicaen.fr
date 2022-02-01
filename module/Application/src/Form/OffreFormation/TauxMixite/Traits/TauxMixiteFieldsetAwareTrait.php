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
    protected ?TauxMixiteFieldset $fieldsetOffreFormationTauxMixiteTauxMixite = null;



    /**
     * @param TauxMixiteFieldset $fieldsetOffreFormationTauxMixiteTauxMixite
     *
     * @return self
     */
    public function setFieldsetOffreFormationTauxMixiteTauxMixite( ?TauxMixiteFieldset $fieldsetOffreFormationTauxMixiteTauxMixite )
    {
        $this->fieldsetOffreFormationTauxMixiteTauxMixite = $fieldsetOffreFormationTauxMixiteTauxMixite;

        return $this;
    }



    public function getFieldsetOffreFormationTauxMixiteTauxMixite(): ?TauxMixiteFieldset
    {
        if (empty($this->fieldsetOffreFormationTauxMixiteTauxMixite)){
            $this->fieldsetOffreFormationTauxMixiteTauxMixite = \Application::$container->get('FormElementManager')->get(TauxMixiteFieldset::class);
        }

        return $this->fieldsetOffreFormationTauxMixiteTauxMixite;
    }
}