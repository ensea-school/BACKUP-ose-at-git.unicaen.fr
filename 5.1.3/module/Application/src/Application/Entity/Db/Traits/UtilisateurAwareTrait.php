<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Utilisateur;

/**
 * Description of UtilisateurAwareTrait
 *
 * @author UnicaenCode
 */
trait UtilisateurAwareTrait
{
    /**
     * @var Utilisateur
     */
    private $utilisateur;





    /**
     * @param Utilisateur $utilisateur
     * @return self
     */
    public function setUtilisateur( Utilisateur $utilisateur = null )
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }



    /**
     * @return Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
}