<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\ElementModulateurCentreCoutForm;

/**
 * Description of ElementModulateurCentreCoutFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurCentreCoutFormAwareTrait
{
    protected ?ElementModulateurCentreCoutForm $formOffreFormationElementModulateurCentreCout = null;



    /**
     * @param ElementModulateurCentreCoutForm $formOffreFormationElementModulateurCentreCout
     *
     * @return self
     */
    public function setFormOffreFormationElementModulateurCentreCout(?ElementModulateurCentreCoutForm $formOffreFormationElementModulateurCentreCout)
    {
        $this->formOffreFormationElementModulateurCentreCout = $formOffreFormationElementModulateurCentreCout;

        return $this;
    }



    public function getFormOffreFormationElementModulateurCentreCout(): ?ElementModulateurCentreCoutForm
    {
        if (!empty($this->formOffreFormationElementModulateurCentreCout)) {
            return $this->formOffreFormationElementModulateurCentreCout;
        }

        return \Application::$container->get('FormElementManager')->get(ElementModulateurCentreCoutForm::class);
    }
}