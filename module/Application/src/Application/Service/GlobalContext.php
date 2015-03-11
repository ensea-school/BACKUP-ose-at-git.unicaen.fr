<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Annee as AnneeEntity;
use Application\Entity\Db\Etablissement as EntityEtablissement;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Personnel as PersonnelEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Utilisateur as UtilisateurEntity;
use DateTime;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

/**
 * Classe regroupant les données globales de fonctionnement de l'application.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class GlobalContext
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $sl;
    
    /**
     * @var UtilisateurEntity
     */
    protected $utilisateur;
    
    /**
     * @var EntityIntervenant
     */
    protected $intervenant;
    
    /**
     * @var PersonnelEntity
     */
    protected $personnel;
    
    /**
     * @var Annee
     */
    protected $annee;
    
    /**
     * @var Annee
     */
    protected $anneePrecedente;
    
    /**
     * @var Annee
     */
    protected $anneeSuivante;
    
    /**
     *
     * @var DateTime
     */
    protected $dateFinSaisiePermanents;
    
    /**
     * Constructeur.
     * 
     * @param ServiceLocatorInterface $sl
     */
    public function __construct(ServiceLocatorInterface $sl)
    {
        $this->sl = $sl;
    }
    
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

    public function getAnneePrecedente()
    {
        return $this->anneePrecedente;
    }

    public function getAnneeSuivante()
    {
        return $this->anneeSuivante;
    }

    public function getEtablissement()
    {
        return $this->etablissement;
    }

    function getDateFinSaisiePermanents()
    {
        return $this->dateFinSaisiePermanents;
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

    public function setPersonnel(PersonnelEntity $personnel = null)
    {
        $this->personnel = $personnel;
        return $this;
    }

    public function setAnnee(AnneeEntity $annee)
    {
        $this->annee = $annee;
        return $this;
    }

    public function setAnneePrecedente(AnneeEntity $annee)
    {
        $this->anneePrecedente = $annee;
        return $this;
    }

    public function setAnneeSuivante(AnneeEntity $annee)
    {
        $this->anneeSuivante = $annee;
        return $this;
    }

    public function setEtablissement(EntityEtablissement $etablissement)
    {
        $this->etablissement = $etablissement;
        return $this;
    }

    function setDateFinSaisiePermanents(DateTime $dateFinSaisiePermanents)
    {
        $this->dateFinSaisiePermanents = $dateFinSaisiePermanents;
        return $this;
    }
    
    /**
     * Retourne l'éventuelle structure sélectionnée mémorisée en session.
     * 
     * @return StructureEntity|null
     */
    function getStructure()
    {
        $structure = $this->getSessionContainer()->structure;
        
        if ($structure instanceof StructureEntity) {
            return $structure;
        }
        if ($structure) {
            $structure = $this->sl->get('ApplicationStructure')->get($structure);
        }
        
        return $structure;
    }

    /**
     * Spécifie l'éventuelle structure sélectionnée à mémoriser en session.
     * 
     * @param StructureEntity|int|null $structure
     */
    function setStructure($structure)
    {
        if ($structure instanceof StructureEntity) {
            $structure = $structure->getId();
        }
        
        $this->getSessionContainer()->structure = $structure;
    }
    
    /**
     * @var Container
     */
    protected $sessionContainer;
    
    /**
     * @return Container
     */
    function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new Container(__CLASS__);
        }
        
        return $this->sessionContainer;
    }
}