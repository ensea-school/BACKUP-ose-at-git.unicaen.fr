<?php

namespace OffreFormation\Entity\Db;

use Administration\Interfaces\ChampsAutresInterface;
use Administration\Traits\ChampsAutresTrait;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Etape
 */
class Etape implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, ChampsAutresInterface
{
    use HistoriqueAwareTrait;
    use AnneeAwareTrait;
    use ImportAwareTrait;
    use ChampsAutresTrait;


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
     * @var \Lieu\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \OffreFormation\Entity\Db\TypeFormation
     */
    protected $typeFormation;

    /**
     * @var \Paiement\Entity\Db\DomaineFonctionnel
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
     * @param \OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique
     *
     * @return Etape
     */
    public function addElementPedagogique(\OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique)
    {
        $this->elementPedagogique[] = $elementPedagogique;

        return $this;
    }



    /**
     * Remove elementPedagogique
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique
     */
    public function removeElementPedagogique(\OffreFormation\Entity\Db\ElementPedagogique $elementPedagogique)
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
     * @param \OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique
     *
     * @return Etape
     */
    public function addCheminPedagogique(\OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique)
    {
        $this->cheminPedagogique[] = $cheminPedagogique;

        return $this;
    }



    /**
     * Remove cheminPedagogique
     *
     * @param \OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique
     */
    public function removeCheminPedagogique(\OffreFormation\Entity\Db\CheminPedagogique $cheminPedagogique)
    {
        $this->cheminPedagogique->removeElement($cheminPedagogique);
    }



    /**
     * Get cheminPedagogique
     *
     * @return \Doctrine\Common\Collections\Collection|CheminPedagogique[]
     *
     */
    public function getCheminPedagogique()
    {
        return $this->cheminPedagogique;
    }



    /**
     * Set structure
     *
     * @param \Lieu\Entity\Db\Structure $structure
     *
     * @return Etape
     */
    public function setStructure(?\Lieu\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }



    /**
     * Get structure
     *
     * @return \Lieu\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Set typeFormation
     *
     * @param \OffreFormation\Entity\Db\TypeFormation $typeFormation
     *
     * @return Etape
     */
    public function setTypeFormation(?\OffreFormation\Entity\Db\TypeFormation $typeFormation = null)
    {
        $this->typeFormation = $typeFormation;

        return $this;
    }



    /**
     * Get typeFormation
     *
     * @return \OffreFormation\Entity\Db\TypeFormation
     */
    public function getTypeFormation()
    {
        return $this->typeFormation;
    }



    /**
     * Set domaineFonctionnel
     *
     * @param \Paiement\Entity\Db\DomaineFonctionnel $domaineFonctionnel
     *
     * @return Etape
     */
    public function setDomaineFonctionnel(?\Paiement\Entity\Db\DomaineFonctionnel $domaineFonctionnel = null)
    {
        $this->domaineFonctionnel = $domaineFonctionnel;

        return $this;
    }



    /**
     * Get domaineFonctionnel
     *
     * @return \Paiement\Entity\Db\DomaineFonctionnel
     */
    public function getDomaineFonctionnel()
    {
        return $this->domaineFonctionnel;
    }



    public function getResourceId(): string
    {
        return self::class;
    }
}
