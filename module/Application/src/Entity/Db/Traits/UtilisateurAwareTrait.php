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
    protected ?Utilisateur $utilisateur = null;



    /**
     * @param Utilisateur $utilisateur
     *
     * @return self
     */
    public function setUtilisateur( ?Utilisateur $utilisateur )
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }



    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }
}