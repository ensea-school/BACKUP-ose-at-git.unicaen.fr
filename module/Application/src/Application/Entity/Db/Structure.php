<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Structure
 */
class Structure implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

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
    protected $niveau;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var string
     */
    protected $contactPj;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var \Application\Entity\Db\TypeStructure
     */
    protected $type;

    /**
     * @var \Application\Entity\Db\Etablissement
     */
    protected $etablissement;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $parente;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structureNiv2;

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



    function __construct()
    {
        $this->structureNiv2                      = new ArrayCollection;
        $this->elementPedagogique                 = new ArrayCollection;
        $this->centreCout                         = new ArrayCollection;
        $this->miseEnPaiementIntervenantStructure = new ArrayCollection;
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
     * Set niveau
     *
     * @param integer $niveau
     *
     * @return Structure
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }



    /**
     * Get niveau
     *
     * @return integer
     */
    public function getNiveau()
    {
        return $this->niveau;
    }



    /**
     * Set sourceCode
     *
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
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }



    /**
     * Set contactPj
     *
     * @param string $contactPj
     *
     * @return Structure
     */
    public function setContactPj($contactPj)
    {
        $this->contactPj = $contactPj;

        return $this;
    }



    /**
     * Get contactPj
     *
     * @return string
     */
    public function getContactPj()
    {
        return $this->contactPj;
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     *
     * @return Structure
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }



    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source
     */
    public function getSource()
    {
        return $this->source;
    }



    /**
     * Set type
     *
     * @param \Application\Entity\Db\TypeStructure $type
     *
     * @return Structure
     */
    public function setType(\Application\Entity\Db\TypeStructure $type = null)
    {
        $this->type = $type;

        return $this;
    }



    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeStructure
     */
    public function getType()
    {
        return $this->type;
    }



    /**
     * Set etablissement
     *
     * @param \Application\Entity\Db\Etablissement $etablissement
     *
     * @return Structure
     */
    public function setEtablissement(\Application\Entity\Db\Etablissement $etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }



    /**
     * Get etablissement
     *
     * @return \Application\Entity\Db\Etablissement
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }



    /**
     * Set parente
     *
     * @param \Application\Entity\Db\Structure $parente
     *
     * @return Structure
     */
    public function setParente(\Application\Entity\Db\Structure $parente = null)
    {
        $this->parente = $parente;

        return $this;
    }



    /**
     * Get parente
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getParente()
    {
        return $this->parente;
    }



    /**
     * Set structureNiv2
     *
     * @param \Application\Entity\Db\Structure $structureNiv2
     *
     * @return Structure
     */
    public function setParenteNiv2(\Application\Entity\Db\Structure $structureNiv2 = null)
    {
        $this->structureNiv2 = $structureNiv2;

        return $this;
    }



    /**
     * Get structureNiv2
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getParenteNiv2()
    {
        return $this->structureNiv2;
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
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelleCourt();
    }



    /**
     * Get source id
     *
     * @return integer
     * @see \Application\Entity\Db\Source
     */
    public function getSourceToString()
    {
        return $this->getSource()->getLibelle();
    }



    /**
     * Teste si cette structure est une structure fille de la structure de niveau 2 spécifiée.
     *
     * @param \Application\Entity\Db\Structure $structureDeNiv2
     *
     * @return bool
     */
    public function estFilleDeLaStructureDeNiv2(\Application\Entity\Db\Structure $structureDeNiv2)
    {
        return $this->getParenteNiv2()->getId() === $structureDeNiv2->getId();
    }



    /**
     * @since PHP 5.6.0
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [
            'id'           => $this->id,
            'libelleCourt' => $this->libelleCourt,
            'historisee'   => !$this->estNonHistorise(),
        ];
    }

}
