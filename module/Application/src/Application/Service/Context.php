<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Etablissement as EntityEtablissement;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Personnel;
use Application\Entity\Db\Utilisateur;
use Common\Exception\LogicException;
use Zend\Stdlib\Hydrator\HydratorAwareInterface;
use Zend\Stdlib\Hydrator\HydratorAwareTrait;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Service fournissant le contexte global de fonctionnement de l'application.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Context extends AbstractService implements HydratorAwareInterface
{
    use HydratorAwareTrait;
    
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
    
    /**
     * Constructeur.
     */
    public function __construct()
    {
        $this->setHydrator(new ClassMethods(false));
    }
    
//    public function toArray()
//    {
//        return array(
//            'utilisateur'   => $this->getUtilisateur(),
//            'intervenant'   => $this->getIntervenant(),
//            'personnel'     => $this->getPersonnel(),
//            'annee'         => $this->getAnnee(),
//            'etablissement' => $this->getEtablissement(),
//        );
//    }
    
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