<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Utilisateur as UtilisateurEntity;

/**
 * Classe mère des classes de contexte.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractContext
{
    /**
     * @var UtilisateurEntity
     */
    protected $utilisateur;

    /**
     * @var EntityIntervenant
     */
    protected $intervenant;

    /**
     * @var Annee
     */
    protected $annee;

    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    public function getIntervenant()
    {
        return $this->intervenant;
    }

    public function getAnnee()
    {
        return $this->annee;
    }

    public function setUtilisateur(UtilisateurEntity $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function setIntervenant(EntityIntervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        return $this;
    }

    public function setAnnee(Annee $annee = null)
    {
        $this->annee = $annee;
        return $this;
    }

}