<?php

namespace Application\Service\Interfaces;

use Application\Service\Utilisateur;
use RuntimeException;

/**
 * Description of UtilisateurAwareInterface
 *
 * @author UnicaenCode
 */
interface UtilisateurAwareInterface
{
    /**
     * @param Utilisateur $serviceUtilisateur
     * @return self
     */
    public function setServiceUtilisateur( Utilisateur $serviceUtilisateur );



    /**
     * @return UtilisateurAwareInterface
     * @throws RuntimeException
     */
    public function getServiceUtilisateur();
}