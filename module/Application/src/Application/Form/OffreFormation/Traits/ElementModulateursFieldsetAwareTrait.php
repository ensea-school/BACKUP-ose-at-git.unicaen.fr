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
    /**
     * @var ElementModulateursFieldset
     */
    private $fieldsetOffreFormationElementModulateurs;



    /**
     * @param ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs
     *
     * @return self
     */
    public function setFieldsetOffreFormationElementModulateurs(ElementModulateursFieldset $fieldsetOffreFormationElementModulateurs)
    {
        $this->fieldsetOffreFormationElementModulateurs = $fieldsetOffreFormationElementModulateurs;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementModulateursFieldset
     */
    public function getFieldsetOffreFormationElementModulateurs()
    {
        if (!empty($this->fieldsetOffreFormationElementModulateurs)) {
            return $this->fieldsetOffreFormationElementModulateurs;
        }

        return \Application::$container->get('FormElementManager')->get('ElementModulateursFieldset');
    }
}