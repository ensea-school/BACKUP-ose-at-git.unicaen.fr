<?php

namespace Application\Form\Agrement\Interfaces;

use Application\Form\Agrement\Saisie;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie|null $formAgrementSaisie
     *
     * @return self
     */
    public function setFormAgrementSaisie( Saisie $formAgrementSaisie );



    public function getFormAgrementSaisie(): ?Saisie;
}