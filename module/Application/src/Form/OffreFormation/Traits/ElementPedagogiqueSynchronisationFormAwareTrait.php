<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementPedagogiqueSynchronisationForm;

/**
 * Description of ElementPedagogiqueSynchronisationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueSynchronisationFormAwareTrait
{
    /**
     * @var ElementPedagogiqueSynchronisationForm
     */
    private $formOffreFormationElementPedagogiqueSynchronisation;



    /**
     * @param ElementPedagogiqueSynchronisationForm $formOffreFormationElementPedagogiqueSynchronisation
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSynchronisation(ElementPedagogiqueSynchronisationForm $formOffreFormationElementPedagogiqueSynchronisation)
    {
        $this->formOffreFormationElementPedagogiqueSynchronisation = $formOffreFormationElementPedagogiqueSynchronisation;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementPedagogiqueSynchronisationForm
     */
    public function getFormOffreFormationElementPedagogiqueSynchronisation()
    {
        if (!empty($this->formOffreFormationElementPedagogiqueSynchronisation)) {
            return $this->formOffreFormationElementPedagogiqueSynchronisation;
        }

        return \Application::$container->get('FormElementManager')->get(ElementPedagogiqueSynchronisationForm::class);
    }
}