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
    protected ?ElementModulateursFieldset $formOffreFormationElementModulateursFieldset;



    /**
     * @param ElementModulateursFieldset|null $formOffreFormationElementModulateursFieldset
     *
     * @return self
     */
    public function setFormOffreFormationElementModulateursFieldset( ?ElementModulateursFieldset $formOffreFormationElementModulateursFieldset )
    {
        $this->formOffreFormationElementModulateursFieldset = $formOffreFormationElementModulateursFieldset;

        return $this;
    }



    public function getFormOffreFormationElementModulateursFieldset(): ?ElementModulateursFieldset
    {
        if (!$this->formOffreFormationElementModulateursFieldset){
            $this->formOffreFormationElementModulateursFieldset = \Application::$container->get('FormElementManager')->get(ElementModulateursFieldset::class);
        }

        return $this->formOffreFormationElementModulateursFieldset;
    }
}