<?php

namespace Application\Form\OffreFormation\Interfaces;

use Application\Form\OffreFormation\ElementPedagogiqueSaisie;
use RuntimeException;

/**
 * Description of ElementPedagogiqueSaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface ElementPedagogiqueSaisieAwareInterface
{
    /**
     * @param ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie
     * @return self
     */
    public function setFormOffreFormationElementPedagogiqueSaisie( ElementPedagogiqueSaisie $formOffreFormationElementPedagogiqueSaisie );



    /**
     * @return ElementPedagogiqueSaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormOffreFormationElementPedagogiqueSaisie();
}