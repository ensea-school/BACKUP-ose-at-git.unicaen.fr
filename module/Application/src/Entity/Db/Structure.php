<?php

namespace Application\Entity\Db;

use Application\Entity\Traits\AdresseTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Structure
 */
class Structure implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface
{
    use AdresseTrait;
    use ImportAwareTrait;
    use HistoriqueAwareTrait;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $elementPedagogique;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $centreCout;

    /**
     * miseEnPaiementIntervenantStructure
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;

    /**
     * @var boolean
     */
    protected $enseignement;

    /**
     * @var boolean
     */
    protected $affAdresseContrat;

    /**
     * @var string
     */
    protected $sourceCode;



    function __construct()
    {
        $this->elementPedagogique                 = new ArrayCollection;
        $this->centreCout                         = new ArrayCollection;
        $this->miseEnPaiementIntervenantStructure = new ArrayCollection;
    }



    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return Structure
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Set libelleCourt
     *
     * @param string $libelleCourt
     *
     * @return Structure
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
     * @return Structure
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
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }



    /**
     * @param string $sourceCode
     *
     * @return Structure
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }



    /**
     * Add elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return Intervenant
     */
    public function addElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique[] = $elementPedagogique;

        return $this;
    }



    /**
     * Remove elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     */
    public function removeElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique->removeElement($elementPedagogique);
    }



    /**
     * Get elementPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getElementPedagogique()
    {
        return $this->elementPedagogique;
    }



    /**
     * Add centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     *
     * @return Intervenant
     */
    public function addCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {
        $this->centreCout[] = $centreCout;

        return $this;
    }



    /**
     * Remove centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     */
    public function removeCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {
        $this->service->removeElement($centreCout);
    }



    /**
     * Get centreCout
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }



    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }



    /**
     * @return bool
     */
    public function isEnseignement()
    {
        return $this->enseignement;
    }



    /**
     * @param bool $enseignement
     *
     * @return Structure
     */
    public function setEnseignement($enseignement)
    {
        $this->enseignement = $enseignement;

        return $this;
    }



    /**
     * @return bool
     */
    public function isAffAdresseContrat()
    {
        return $this->affAdresseContrat;
    }



    /**
     * @param bool $affAdresseContrat
     *
     * @return Structure
     */
    public function setAffAdresseContrat($affAdresseContrat)
    {
        $this->affAdresseContrat = $affAdresseContrat;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleCourt();
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Structure';
    }



    function __sleep()
    {
        return [];
    }

}
