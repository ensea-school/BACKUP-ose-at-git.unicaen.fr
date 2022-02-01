<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\ElementPedagogiqueSaisie;

/**
 * Description of ElementPedagogiqueSaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementPedagogiqueSaisieAwareInterface
{
    /**
     * @param ElementPedagogiqueSaisie|null $formOffreFormationElementPedagogiqueSaisie
     *
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSaisie( ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie );



    public function getFormOffreFormationElementPedagogiqueSaisie(): ?ElementPedagogiqueSaisie;
}