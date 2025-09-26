<?php

namespace Utilisateur\Entity\Db;

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