<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\Dossier;
use RuntimeException;

/**
 * Description of DossierAwareInterface
 *
 * @author UnicaenCode
 */
interface DossierAwareInterface
{
    /**
     * @param Dossier $formIntervenantDossier
     * @return self
     */
    public function setFormIntervenantDossier( Dossier $formIntervenantDossier );



    /**
     * @return DossierAwareInterface
     * @throws RuntimeException
     */
    public function getFormIntervenantDossier();
}