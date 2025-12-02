<?php

namespace PieceJointe\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypePieceJointe
 */
class TypePieceJointe implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

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
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $urlModeleDoc;

    private ?string $description = null;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $typePieceJointeStatut;



    /**
     * Set code
     *
     * @param string $code
     *
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
     * Set libelle
     *
     * @param string $libelle
     *
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
     *
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



    public function getDescription(): ?string
    {
        return $this->description;
    }



    public function setDescription(?string $description): TypePieceJointe
    {
        $this->description = $description;
        return $this;
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
     *
     * @return self
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
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
