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
    /**
     * @var ElementPedagogiqueSaisie
     */
    private $formOffreFormationElementPedagogiqueSaisie;



    /**
     * @param ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSaisie(ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie)
    {
        $this->formOffreFormationElementPedagogiqueSaisie = $formOffreFormationElementPedagogiqueSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementPedagogiqueSaisie
     */
    public function getFormOffreFormationElementPedagogiqueSaisie()
    {
        if (!empty($this->formOffreFormationElementPedagogiqueSaisie)) {
            return $this->formOffreFormationElementPedagogiqueSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(ElementPedagogiqueSaisie::class);
    }
}