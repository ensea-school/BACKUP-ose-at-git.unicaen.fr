<?php

namespace Application\Entity\Db;

/**
 * TypePieceJointe
 */
class TypePieceJointe implements HistoriqueAwareInterface
{
    const CV               = "CV";
    const RIB              = "RIB";
    const CARTE_VITALE     = "CARTE_VITALE";
    const DERN_BUL_SALAIR  = "DERN_BUL_SALAIR";
    const ATT_ACT_SAL_900  = "ATT_ACT_SAL_900";
    const AUTORIS_CUMUL    = "AUTORIS_CUMUL";
    const CONT_TER_ATT_HON = "CONT_TER_ATT_HON";
    const TITRE_PENSION    = "TITRE_PENSION";
    const CARTE_ETUD       = "CARTE_ETUD";

    public function __toString()
    {
        return $this->getLibelle();
    }
    
    /**
     * @var string
     */
    private $code;

    /**
     * @var \DateTime
     */
    private $histoCreation;

    /**
     * @var \DateTime
     */
    private $histoDestruction;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $urlModeleDoc;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typePieceJointeStatut;


    /**
     * Set code
     *
     * @param string $code
     * @return TypePieceJointe
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
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return TypePieceJointe
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return TypePieceJointe
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return TypePieceJointe
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return TypePieceJointe
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
     * Set urlModeleDoc
     *
     * @param string $urlModeleDoc
     * @return TypePieceJointe
     */
    public function setUrlModeleDoc($urlModeleDoc)
    {
        $this->urlModeleDoc = $urlModeleDoc;

        return $this;
    }

    /**
     * Get urlModeleDoc
     *
     * @return string 
     */
    public function getUrlModeleDoc()
    {
        return $this->urlModeleDoc;
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
     * Get ordre
     *
     * @return integer
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return self
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return TypePieceJointe
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return TypePieceJointe
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return TypePieceJointe
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Get typePieceJointeStatut
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypePieceJointeStatut()
    {
        return $this->typePieceJointeStatut;
    }
}
