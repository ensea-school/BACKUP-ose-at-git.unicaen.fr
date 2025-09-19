<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\ElementPedagogiqueSaisie;

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
    public function setFormOffreFormationElementPedagogiqueSaisie(?ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie)
    {
        $this->formOffreFormationElementPedagogiqueSaisie = $formOffreFormationElementPedagogiqueSaisie;

        return $this;
    }



    public function getFormOffreFormationElementPedagogiqueSaisie(): ?ElementPedagogiqueSaisie
    {
        if (!empty($this->formOffreFormationElementPedagogiqueSaisie)) {
            return $this->formOffreFormationElementPedagogiqueSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ElementPedagogiqueSaisie::class);
    }
}