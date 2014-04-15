<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Personnel;
use Application\Entity\Db\Utilisateur;
use Common\Exception\LogicException;

/**
 * Service fournissant le contexte global de fonctionnement de l'application.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Context extends AbstractService
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
     * @var Intervenant
     */
    protected $intervenant;
    
    /**
     * @var Structure
     */
    protected $structure;
    
    /**
     * @var Personnel
     */
    protected $personnel;
    
    /**
     * @var Annee
     */
    protected $annee;
    
    /**
     * @var Etablissement
     */
    protected $etablissement;
    
    
    public function toArray()
    {
        return array(
            'utilisateur'   => $this->getUtilisateur(),
            'intervenant'   => $this->getIntervenant(),
            'structure'     => $this->getStructure(),
            'personnel'     => $this->getPersonnel(),
            'annee'         => $this->getAnnee(),
            'etablissement' => $this->getEtablissement(),
        );
    }
    
    public function set($name, $value)
    {
        if (!method_exists($this, $method = 'set' . ucfirst($name))) {
            throw new LogicException("Méthode $method inexistante.");
        }
        
        $this->$method($value);
    }
    
    public function get($name)
    {
        if (method_exists($this, $method = 'get' . ucfirst($name))) {
            return $this->$method();
        }
        
        return null;
    }
    
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
    
    public function __get($name)
    {
        return $this->get($name);
    }
    
    protected function getParametres()
    {
        if (null === $this->parametres) {
            $this->parametres = $this->getServiceLocator()->get('ApplicationParametres');
        }
        return $this->parametres;
    }
    
    public function getUtilisateur()
    {
        if (null === $this->utilisateur) {
            $this->utilisateur = $this->getServiceLocator()->get('authUserContext')->getDbUser();
        }
        return $this->utilisateur;
    }

    public function getIntervenant()
    {
        if (null === $this->intervenant) {
            $this->intervenant = $this->getUtilisateur()->getIntervenant();
        }
        return $this->intervenant;
    }

    public function getStructure()
    {
        if (null === $this->structure) {
//            $this->structure = $this->getIntervenant()->getStructure();
        }
        return $this->structure;
    }

    public function getPersonnel()
    {
        if (null === $this->personnel) {
            $this->personnel = $this->getUtilisateur()->getPersonnel();
        }
        return $this->personnel;
    }

    public function getAnnee()
    {
        if (null === $this->annee) {
            $this->annee = $this->getEntityManager()->find(
                    'Application\Entity\Db\Annee', 
                    $this->getParametres()->annee);
        }
        return $this->annee;
    }

    public function getEtablissement()
    {
        if (null === $this->etablissement) {
            $this->etablissement = $this->getEntityManager()->find(
                    'Application\Entity\Db\Etablissement', 
                    $this->getParametres()->etablissement);
        }
        return $this->etablissement;
    }

    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function setIntervenant(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        return $this;
    }

    public function setStructure(Structure $structure)
    {
        $this->structure = $structure;
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

    public function setEtablissement(Etablissement $etablissement)
    {
        $this->etablissement = $etablissement;
        return $this;
    }
}