<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityNotFoundException;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * FonctionReferentiel
 */
class FonctionReferentiel implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var self
     */
    protected $parent;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelleCourt;

    /**
     * @var string
     */
    protected $libelleLong;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var DomaineFonctionnel
     */
    protected $domaineFonctionnel;

    /**
     * @var bool
     */
    protected $etapeRequise;

    /**
     * @var bool
     */
    protected $serviceStatutaire = true;

    /**
     * @var FonctionReferentiel[]
     */
    protected $fille;



    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct()
    {
        $this->fille = new ArrayCollection();
    }



    /**
     * @return FonctionReferentiel
     */
    public function getParent()
    {
        return $this->parent;
    }



    /**
     * @param FonctionReferentiel|null $parent
     *
     * @return FonctionReferentiel
     */
    public function setParent($parent = null): FonctionReferentiel
    {
        if ($parent instanceof FonctionReferentiel && $parent->getParent()) {
            throw new \Exception('Il est impossible de définir cette fonction référentielle comme parente : elle a déjà un parent');
        }

        $this->parent = $parent;

        return $this;
    }



    /**
     * @param FonctionReferentiel $fille
     *
     * @return $this
     */
    public function addFille(FonctionReferentiel $fille)
    {
        $this->fille[] = $fille;

        return $this;
    }



    /**
     * @param FonctionReferentiel $fille
     */
    public function removeFille(FonctionReferentiel $fille)
    {
        $this->fille->removeElement($fille);
    }



    /**
     * @return ArrayCollection|FonctionReferentiel[]
     */
    public function getFille()
    {
        return $this->fille;
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return FonctionReferentiel
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
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return FonctionReferentiel
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    /**
     * Get libelleCourt
     *
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }



    /**
     * Set libelleLong
     *
     * @param string $libelleLong
     *
     * @return FonctionReferentiel
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    /**
     * Get libelleLong
     *
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return self
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     *
     * @return DomaineFonctionnel
     */
    function getDomaineFonctionnel()
    {
        return $this->domaineFonctionnel;
    }



    /**
     *
     * @param DomaineFonctionnel $domaineFonctionnel
     *
     * @return self
     */
    function setDomaineFonctionnel(DomaineFonctionnel $domaineFonctionnel)
    {
        $this->domaineFonctionnel = $domaineFonctionnel;

        return $this;
    }



    /**
     * @return bool
     */
    public function isEtapeRequise()
    {
        return $this->etapeRequise;
    }



    /**
     * @param bool $etapeRequise
     *
     * @return FonctionReferentiel
     */
    public function setEtapeRequise($etapeRequise): FonctionReferentiel
    {
        $this->etapeRequise = $etapeRequise;

        return $this;
    }



    /**
     * @return bool
     */
    public function isServiceStatutaire(): bool
    {
        return $this->serviceStatutaire;
    }



    /**
     * @param bool $serviceStatutaire
     *
     * @return FonctionReferentiel
     */
    public function setServiceStatutaire(bool $serviceStatutaire): FonctionReferentiel
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        $str = $this->getLibelleCourt();
        //Try catch préventif dans le cas d'une fonction référentiel attachée à une structure historisée.
        try {
            if ($this->getStructure()) {
                $str .= " (" . $this->getStructure() . ")";
            }
        } catch (EntityNotFoundException $e) {
            return $str;
        }

        return $str;
    }
}
