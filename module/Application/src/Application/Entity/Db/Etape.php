<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Etape
 */
class Etape implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use AnneeAwareTrait;
    use ImportAwareTrait;



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
     * Retourne la représentation littérale du niveau corresponadnt à cette étape.
     *
     * @return string
     */
    public function getNiveauToString()
    {
        return $this->getTypeFormation()->getGroupe()->getLibelleCourt() . $this->getNiveau();
    }



    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var integer
     */
    protected $niveau;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $niveauFormation;

    /**
     * @var boolean
     */
    protected $specifiqueEchanges;

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
    protected $cheminPedagogique;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\TypeFormation
     */
    protected $typeFormation;

    /**
     * @var \Application\Entity\Db\DomaineFonctionnel
     */
    private $domaineFonctionnel;



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
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Etape
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
     * Set niveau
     *
     * @param integer $niveau
     *
     * @return Etape
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
     * Get niveauFormation
     *
     * @return NiveauFormation
     */
    public function getNiveauFormation()
    {
        $res = $this->niveauFormation->first();
        if (false === $res) $res = null;

        return $res;
    }



    /**
     * Set specifiqueEchanges
     *
     * @param boolean $specifiqueEchanges
     *
     * @return Etape
     */
    public function setSpecifiqueEchanges($specifiqueEchanges)
    {
        $this->specifiqueEchanges = $specifiqueEchanges;

        return $this;
    }



    /**
     * Get specifiqueEchanges
     *
     * @return boolean
     */
    public function getSpecifiqueEchanges()
    {
        return $this->specifiqueEchanges;
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
     * Add elementPedagogique
     *
     * @param \Application\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return Etape
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
     * Add cheminPedagogique
     *
     * @param \Application\Entity\Db\CheminPedagogique $cheminPedagogique
     *
     * @return Etape
     */
    public function addCheminPedagogique(\Application\Entity\Db\CheminPedagogique $cheminPedagogique)
    {
        $this->cheminPedagogique[] = $cheminPedagogique;

        return $this;
    }



    /**
     * Remove cheminPedagogique
     *
     * @param \Application\Entity\Db\CheminPedagogique $cheminPedagogique
     */
    public function removeCheminPedagogique(\Application\Entity\Db\CheminPedagogique $cheminPedagogique)
    {
        $this->cheminPedagogique->removeElement($cheminPedagogique);
    }



    /**
     * Get cheminPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCheminPedagogique()
    {
        return $this->cheminPedagogique;
    }



    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return Etape
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
     * Set typeFormation
     *
     * @param \Application\Entity\Db\TypeFormation $typeFormation
     *
     * @return Etape
     */
    public function setTypeFormation(\Application\Entity\Db\TypeFormation $typeFormation = null)
    {
        $this->typeFormation = $typeFormation;

        return $this;
    }



    /**
     * Get typeFormation
     *
     * @return \Application\Entity\Db\TypeFormation
     */
    public function getTypeFormation()
    {
        return $this->typeFormation;
    }



    /**
     * Set domaineFonctionnel
     *
     * @param \Application\Entity\Db\DomaineFonctionnel $domaineFonctionnel
     *
     * @return Etape
     */
    public function setDomaineFonctionnel(\Application\Entity\Db\DomaineFonctionnel $domaineFonctionnel = null)
    {
        $this->domaineFonctionnel = $domaineFonctionnel;

        return $this;
    }



    /**
     * Get domaineFonctionnel
     *
     * @return \Application\Entity\Db\DomaineFonctionnel
     */
    public function getDomaineFonctionnel()
    {
        return $this->domaineFonctionnel;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        return 'Etape';
    }
}
