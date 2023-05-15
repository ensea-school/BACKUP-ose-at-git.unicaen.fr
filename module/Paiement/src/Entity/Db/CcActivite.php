<?php

namespace Paiement\Entity\Db;

use OffreFormation\Entity\Db\TypeHeures;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * CcActivite
 */
class CcActivite implements HistoriqueAwareInterface
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
    private $fcMajorees;

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
     * @var integer
     */
    private $id;


    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }


    /**
     * Set code
     *
     * @param string $code
     *
     * @return CcActivite
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
     * @return CcActivite
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
     * @return CcActivite
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
     * Set fcMajorees
     *
     * @param boolean $fcMajorees
     *
     * @return CcActivite
     */
    public function setFcMajorees($fcMajorees)
    {
        $this->fcMajorees = $fcMajorees;

        return $this;
    }



    /**
     * Get fcMajorees
     *
     * @return boolean
     */
    public function getFcMajorees()
    {
        return $this->fcMajorees;
    }



    /**
     * Set fi
     *
     * @param boolean $fi
     *
     * @return CcActivite
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
     * @return CcActivite
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
     * @return CcActivite
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * détermine si un type d'heures peut être appliqué à ce type d'activité de centre de coûts ou non
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
            case TypeHeures::FC_MAJOREES:
                return $this->getFcMajorees();
            case TypeHeures::REFERENTIEL:
                return $this->getReferentiel();
        }

        return false;
    }
}
