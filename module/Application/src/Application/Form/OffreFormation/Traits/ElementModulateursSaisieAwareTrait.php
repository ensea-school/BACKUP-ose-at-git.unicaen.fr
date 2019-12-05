<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementModulateursSaisie;

/**
 * Description of ElementModulateurSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateursSaisieAwareTrait
{
    /**
     * @var ElementModulateurSaisie
     */
    private $formOffreFormationElementModulateurSaisie;



    /**
     * @param ElementModulateurSaisie $formOffreFormationElementModulateurSaisie
     *
     * @return self
     */
    public function setFormOffreFormationElementModulateurSaisie(ElementModulateurSaisie $formOffreFormationElementModulateurSaisie)
    {
        $this->formOffreFormationElementModulateurSaisie = $formOffreFormationElementModulateurSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementModulateurSaisie
     */
    public function getFormOffreFormationElementModulateurSaisie()
    {
        if (!empty($this->formOffreFormationElementModulateurSaisie)) {
            return $this->formOffreFormationElementModulateurSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(ElementModulateursSaisie::class);
    }
}