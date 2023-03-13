<?php

namespace OffreFormation\Form\Interfaces;

use OffreFormation\Form\ElementPedagogiqueSaisie;

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
    public function setFormOffreFormationElementPedagogiqueSaisie( ?ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie );



    public function getFormOffreFormationElementPedagogiqueSaisie(): ?ElementPedagogiqueSaisie;
}