<?php

namespace Application\Form\Service\Interfaces;

use Application\Form\Service\Saisie;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie|null $formServiceSaisie
     *
     * @return self
     */
    public function setFormServiceSaisie( ?Saisie $formServiceSaisie );



    public function getFormServiceSaisie(): ?Saisie;
}