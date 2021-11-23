<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\ContratRetour;
use RuntimeException;

/**
 * Description of ContratRetourAwareInterface
 *
 * @author UnicaenCode
 */
interface ContratRetourAwareInterface
{
    /**
     * @param ContratRetour $formIntervenantContratRetour
     * @return self
     */
    public function setFormIntervenantContratRetour( ContratRetour $formIntervenantContratRetour );



    /**
     * @return ContratRetourAwareInterface
     * @throws RuntimeException
     */
    public function getFormIntervenantContratRetour();
}