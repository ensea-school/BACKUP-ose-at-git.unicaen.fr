<?php

namespace Paiement\Entity\Db;

use Application\Provider\Privileges;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\TypeHeures;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeRessource
 */
class TypeRessource implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    private $code;

    /**
     * @var boolean
     */
    private $fa;

    /**
     * @var boolean
     */
    private $fc;

    /**
     * @var boolean
     */
    private $primes;

    /**
     * @var boolean
     */
    private $fi;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var boolean
     */
    private $referentiel;

    /**
     * @var boolean
     */
    private $mission;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $etablissement;



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeRessource
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * Set fa
     *
     * @param boolean $fa
     *
     * @return TypeRessource
     */
    public function setFa($fa)
    {
        $this->fa = $fa;

        return $this;
    }



    /**
     * Get fa
     *
     * @return boolean
     */
    public function getFa()
    {
        return $this->fa;
    }



    /**
     * Set fc
     *
     * @param boolean $fc
     *
     * @return TypeRessource
     */
    public function setFc($fc)
    {
        $this->fc = $fc;

        return $this;
    }



    /**
     * Get fc
     *
     * @return boolean
     */
    public function getFc()
    {
        return $this->fc;
    }



    /**
     * Set primes
     *
     * @param boolean $primes
     *
     * @return TypeRessource
     */
    public function setPrimes($primes)
    {
        $this->primes = $primes;

        return $this;
    }



    /**
     * Get primes
     *
     * @return boolean
     */
    public function getPrimes()
    {
        return $this->primes;
    }



    /**
     * Set fi
     *
     * @param boolean $fi
     *
     * @return TypeRessource
     */
    public function setFi($fi)
    {
        $this->fi = $fi;

        return $this;
    }



    /**
     * Get fi
     *
     * @return boolean
     */
    public function getFi()
    {
        return $this->fi;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeRessource
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * Set referentiel
     *
     * @param boolean $referentiel
     *
     * @return TypeRessource
     */
    public function setReferentiel($referentiel)
    {
        $this->referentiel = $referentiel;

        return $this;
    }



    /**
     * Get referentiel
     *
     * @return boolean
     */
    public function getReferentiel()
    {
        return $this->referentiel;
    }



    /**
     * Set mission
     *
     * @param boolean $mission
     *
     * @return TypeRessource
     */
    public function setMission($mission)
    {
        $this->mission = $mission;

        return $this;
    }



    /**
     * Get mission
     *
     * @return boolean
     */
    public function getMission()
    {
        return $this->mission;
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return boolean
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }



    /**
     * @param boolean $etablissement
     *
     * @return TypeRessource
     */
    public function setEtablissement($etablissement)
    {
        $this->etablissement = $etablissement;

        return $this;
    }



    /**
     * détermine si un type d'heures peut être appliqué à ce type de ressource ou non
     *
     * @param \OffreFormation\Entity\Db\TypeHeures $typeHeures
     *
     * @return boolean
     */
    public function typeHeuresMatches(TypeHeures $typeHeures)
    {
        $code = $typeHeures->getCode();
        switch ($code) {
            case TypeHeures::FI         :
                return $this->getFi();
            case TypeHeures::FA         :
                return $this->getFa();
            case TypeHeures::FC         :
                return $this->getFc();
            case TypeHeures::PRIMES:
                return $this->getPrimes();
            case TypeHeures::ENSEIGNEMENT:
                return $this->getFi() + $this->getFa() + $this->getFc() + $this->getPrimes();
            case TypeHeures::REFERENTIEL:
                return $this->getReferentiel();
            case TypeHeures::MISSION:
                return $this->getMission();
        }

        return false;
    }



    /**
     * Retourne le privilège d'édition de budget correspondant au type de ressource...
     *
     * @return string
     */
    public function getPrivilegeBudgetEdition()
    {
        if ($this->getEtablissement()) {
            return Privileges::BUDGET_EDITION_ENGAGEMENT_ETABLISSEMENT;
        } else {
            return Privileges::BUDGET_EDITION_ENGAGEMENT_COMPOSANTE;
        }
    }



    /**
     * @return array
     * @since PHP 5.6.0
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [
            'code' => $this->code,
        ];
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'TypeRessource';
    }

}
