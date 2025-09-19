<?php

namespace OffreFormation\Form\TauxMixite\Traits;

use OffreFormation\Form\TauxMixite\TauxMixiteFieldset;

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
    public function setFieldsetOffreFormationTauxMixiteTauxMixite(?TauxMixiteFieldset $fieldsetOffreFormationTauxMixiteTauxMixite)
    {
        $this->fieldsetOffreFormationTauxMixiteTauxMixite = $fieldsetOffreFormationTauxMixiteTauxMixite;

        return $this;
    }



    public function getFieldsetOffreFormationTauxMixiteTauxMixite(): ?TauxMixiteFieldset
    {
        if (!empty($this->fieldsetOffreFormationTauxMixiteTauxMixite)) {
            return $this->fieldsetOffreFormationTauxMixiteTauxMixite;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TauxMixiteFieldset::class);
    }
}