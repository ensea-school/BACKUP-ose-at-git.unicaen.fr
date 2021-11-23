<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Utilisateur;

/**
 * Description of UtilisateurAwareInterface
 *
 * @author UnicaenCode
 */
interface UtilisateurAwareInterface
{
    /**
     * @param Utilisateur $utilisateur
     * @return self
     */
    public function setUtilisateur( Utilisateur $utilisateur = null );



    /**
     * @return Utilisateur
     */
    public function getUtilisateur();
}