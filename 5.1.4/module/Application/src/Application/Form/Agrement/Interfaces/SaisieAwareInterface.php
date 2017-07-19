<?php

namespace Application\Form\Agrement\Interfaces;

use Application\Form\Agrement\Saisie;
use RuntimeException;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie $formAgrementSaisie
     * @return self
     */
    public function setFormAgrementSaisie( Saisie $formAgrementSaisie );



    /**
     * @return SaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormAgrementSaisie();
}