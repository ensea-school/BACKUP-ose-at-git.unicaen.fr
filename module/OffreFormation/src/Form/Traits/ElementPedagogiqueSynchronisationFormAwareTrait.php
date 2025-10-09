<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\ElementPedagogiqueSynchronisationForm;

/**
 * Description of ElementPedagogiqueSynchronisationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueSynchronisationFormAwareTrait
{
    protected ?ElementPedagogiqueSynchronisationForm $formOffreFormationElementPedagogiqueSynchronisation = null;



    /**
     * @param ElementPedagogiqueSynchronisationForm $formOffreFormationElementPedagogiqueSynchronisation
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSynchronisation(?ElementPedagogiqueSynchronisationForm $formOffreFormationElementPedagogiqueSynchronisation)
    {
        $this->formOffreFormationElementPedagogiqueSynchronisation = $formOffreFormationElementPedagogiqueSynchronisation;

        return $this;
    }



    public function getFormOffreFormationElementPedagogiqueSynchronisation(): ?ElementPedagogiqueSynchronisationForm
    {
        if (!empty($this->formOffreFormationElementPedagogiqueSynchronisation)) {
            return $this->formOffreFormationElementPedagogiqueSynchronisation;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ElementPedagogiqueSynchronisationForm::class);
    }
}