<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\ContratRetour;

/**
 * Description of ContratRetourAwareInterface
 *
 * @author UnicaenCode
 */
interface ContratRetourAwareInterface
{
    /**
     * @param ContratRetour|null $formIntervenantContratRetour
     *
     * @return self
     */
    public function setFormIntervenantContratRetour( ?ContratRetour $formIntervenantContratRetour );



    public function getFormIntervenantContratRetour(): ?ContratRetour;
}