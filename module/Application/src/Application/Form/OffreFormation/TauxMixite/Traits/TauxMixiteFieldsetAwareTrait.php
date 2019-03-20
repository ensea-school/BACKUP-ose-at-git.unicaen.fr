<?php

namespace Application\Form\OffreFormation\TauxMixite\Traits;

use Application\Form\OffreFormation\TauxMixite\TauxMixiteFieldset;

/**
 * Description of TauxMixiteFieldsetAwareTrait
 *
 */
trait TauxMixiteFieldsetAwareTrait
{
    /**
     * @var TauxMixiteFieldset
     */
    private $fieldsetOffreFormationTauxMixite;



    /**
     * @param TauxMixiteFieldset $fieldsetOffreFormationTauxMixite
     *
     * @return self
     */
    public function setFieldsetOffreFormationTauxMixite(TauxMixiteFieldset $fieldsetOffreFormationTauxMixite)
    {
        $this->fieldsetOffreFormationTauxMixite = $fieldsetOffreFormationTauxMixite;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TauxMixiteFieldset
     */
    public function getFieldsetOffreFormationTauxMixite()
    {
        if (!empty($this->fieldsetOffreFormationTauxMixite)) {
            return $this->fieldsetOffreFormationTauxMixite;
        }

        return \Application::$container->get('FormElementManager')->get(TauxMixiteFieldset::class);
    }
}