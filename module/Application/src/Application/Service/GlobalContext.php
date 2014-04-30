<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Etablissement as EntityEtablissement;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Personnel;
use Application\Entity\Db\Utilisateur;

/**
 * Classe regroupant les données globales de fonctionnement de l'application.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class GlobalContext
{
    /**
     * @var Parametres
     */
    protected $parametres;
    
    /**
     * @var Utilisateur
     */
    protected $utilisateur;
    
    /**
     * @var EntityIntervenant
     */
    protected $intervenant;
    
    /**
     * @var Personnel
     */
    protected $personnel;
    
    /**
     * @var Annee
     */
    protected $annee;
    
    /**
     * @var EntityEtablissement
     */
    protected $etablissement;
    
    public function get($name)
    {
        if (method_exists($this, $method = 'get' . ucfirst($name))) {
            return $this->$method();
        }
        
        return null;
    }
    
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    public function getIntervenant()
    {
        return $this->intervenant;
    }

    public function getPersonnel()
    {
        return $this->personnel;
    }

    public function getAnnee()
    {
        return $this->annee;
    }

    public function getEtablissement()
    {
        return $this->etablissement;
    }

    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function setIntervenant(EntityIntervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        return $this;
    }

    public function setPersonnel(Personnel $personnel)
    {
        $this->personnel = $personnel;
        return $this;
    }

    public function setAnnee(Annee $annee)
    {
        $this->annee = $annee;
        return $this;
    }

    public function setEtablissement(EntityEtablissement $etablissement)
    {
        $this->etablissement = $etablissement;
        return $this;
    }
}