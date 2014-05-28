<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Intervenant as EntityIntervenant;
use Application\Entity\Db\Utilisateur;

/**
 * Classe mère des classes de contexte.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractContext
{
    /**
     * @var Utilisateur
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
    
    /**
     * Retourne la valeur d'un attribut.
     * 
     * @param string $name
     * @return null
     */
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

    public function getAnnee()
    {
        return $this->annee;
    }

    public function setUtilisateur(Utilisateur $utilisateur = null)
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

    public function fromArray(array $context = array())
    {
        $h = new \Zend\Stdlib\Hydrator\ClassMethods(false);
        $h->hydrate($context, $this);
        return $this;
    }

    public function __set($name, $value)
    {
        if (method_exists($this, $method = 'set' . ucfirst($name))) {
            return $this->$method($value);
        }
    }
}