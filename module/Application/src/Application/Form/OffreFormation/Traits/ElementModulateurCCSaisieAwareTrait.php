<?php

namespace Application\Form\OffreFormation\Traits;


use Application\Form\OffreFormation\ElementModulateurCCSaisie;

/**
 * Description of ElementModulateurCCSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurCCSaisieAwareTrait
{
    /**
     * @var ElementModulateurCCSaisie
     */
    private $formOffreFormationElementModulateurCCSaisie;



    /**
     * @param ElementModulateurSaisie $formOffreFormationElementModulateurCCSaisie
     *
     * @return self
     */
    public function setFormOffreFormationElementModulateurCCSaisie(ElementModulateurSaisie $formOffreFormationElementModulateurCCSaisie)
    {
        $this->formOffreFormationElementModulateurCCSaisie = $formOffreFormationElementModulateurCCSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementModulateurCCSaisie
     */
    public function getFormOffreFormationElementModulateurCCSaisie()
    {
        if (!empty($this->formOffreFormationElementModulateurCCSaisie)) {
            return $this->formOffreFormationElementModulateurCCSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(ElementModulateurCCSaisie::class);
    }
}