<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\ElementPedagogiqueSaisie;

/**
 * Description of ElementPedagogiqueSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementPedagogiqueSaisieAwareTrait
{
    protected ?ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie = null;



    /**
     * @param ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSaisie( ?ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie )
    {
        $this->formOffreFormationElementPedagogiqueSaisie = $formOffreFormationElementPedagogiqueSaisie;

        return $this;
    }



    public function getFormOffreFormationElementPedagogiqueSaisie(): ?ElementPedagogiqueSaisie
    {
        if (empty($this->formOffreFormationElementPedagogiqueSaisie)){
            $this->formOffreFormationElementPedagogiqueSaisie = \Application::$container->get('FormElementManager')->get(ElementPedagogiqueSaisie::class);
        }

        return $this->formOffreFormationElementPedagogiqueSaisie;
    }
}