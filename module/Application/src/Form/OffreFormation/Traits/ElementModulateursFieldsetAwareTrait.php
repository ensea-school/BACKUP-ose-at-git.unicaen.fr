<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementModulateursFieldset;

/**
 * Description of ElementModulateursFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateursFieldsetAwareTrait
{
    protected ?ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs = null;



    /**
     * @param ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs
     *
     * @return self
     */
    public function setFieldsetOffreFormationElementModulateurs(?ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs)
    {
        $this->fieldsetOffreFormationElementModulateurs = $fieldsetOffreFormationElementModulateurs;

        return $this;
    }



    public function getFieldsetOffreFormationElementModulateurs(): ?ElementModulateursFieldset
    {
        if (!empty($this->fieldsetOffreFormationElementModulateurs)) {
            return $this->fieldsetOffreFormationElementModulateurs;
        }

        return \Application::$container->get('FormElementManager')->get(ElementModulateursFieldset::class);
    }
}