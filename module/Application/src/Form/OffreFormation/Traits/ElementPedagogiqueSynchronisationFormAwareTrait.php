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
    protected ?ElementPedagogiqueSynchronisationForm $formOffreFormationElementPedagogiqueSynchronisation;



    /**
     * @param ElementPedagogiqueSynchronisationForm|null $formOffreFormationElementPedagogiqueSynchronisation
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSynchronisation( ?ElementPedagogiqueSynchronisationForm $formOffreFormationElementPedagogiqueSynchronisation )
    {
        $this->formOffreFormationElementPedagogiqueSynchronisation = $formOffreFormationElementPedagogiqueSynchronisation;

        return $this;
    }



    public function getFormOffreFormationElementPedagogiqueSynchronisation(): ?ElementPedagogiqueSynchronisationForm
    {
        if (!$this->formOffreFormationElementPedagogiqueSynchronisation){
            $this->formOffreFormationElementPedagogiqueSynchronisation = \Application::$container->get('FormElementManager')->get(ElementPedagogiqueSynchronisationForm::class);
        }

        return $this->formOffreFormationElementPedagogiqueSynchronisation;
    }
}