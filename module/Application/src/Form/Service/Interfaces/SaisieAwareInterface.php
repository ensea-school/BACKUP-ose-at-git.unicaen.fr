<?php

namespace Application\Form\Service\Interfaces;

use Application\Form\Service\Saisie;
use RuntimeException;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie $formServiceSaisie
     * @return self
     */
    public function setFormServiceSaisie( Saisie $formServiceSaisie );



    /**
     * @return SaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormServiceSaisie();
}