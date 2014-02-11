<?php

namespace Import\Model\Entity\Structure;

use DateTime;
use Exception;
use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Structure extends Entity {

    /**
     * id
     *
     * @var integer
     */
    protected $id;

    /**
     * Libellé long
     *
     * @var string
     */
    protected $libelleLong;

    /**
     * Libellé court
     *
     * @var string
     */
    protected $libelleCourt;

    /**
     * Structure parente (identifiant)
     *
     * @var integer
     */
    protected $parenteId;

    /**
     * Identifiant du type de structure
     *
     * @var string
     */
    protected $typeId;

    /**
     * ID de l'établissement correspondant
     *
     * @var integer
     */
    protected $etablissementId;

    /**
     * Nom de la source de données
     *
     * @var string
     */
    protected $sourceId;

    /**
     * Identifiant de la donnée source
     *
     * @var string
     */
    protected $sourceCode;

    /**
     * Date de début pour l'historique
     *
     * @var DateTime
     */
    protected $histoDebut;

    /**
     * Date de fin pour l'historique
     *
     * @var DateTime
     */
    protected $histoFin;





    public function getId()
    {
        return $this->id;
    }

    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    public function getParenteId()
    {
        return $this->parenteId;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }

    public function getEtablissementId()
    {
        return $this->etablissementId;
    }

    public function getSourceId()
    {
        return $this->sourceId;
    }

    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    public function getHistoDebut()
    {
        return $this->histoDebut;
    }

    public function getHistoFin()
    {
        return $this->histoFin;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
        return $this;
    }

    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;
        return $this;
    }

    public function setParenteId($parenteId)
    {
        $this->parenteId = $parenteId;
        return $this;
    }

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function setEtablissementId($etablissementId)
    {
        $this->etablissementId = $etablissementId;
        return $this;
    }

    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;
        return $this;
    }

    public function setHistoDebut($histoDebut)
    {
        if (!($histoDebut === null || $histoDebut instanceof DateTime))
            throw new Exception('DateTime ou null doit être transmis');
        $this->histoDebut = $histoDebut;
        return $this;
    }

    public function setHistoFin($histoFin)
    {
        if (!($histoFin === null || $histoFin instanceof DateTime))
            throw new Exception('DateTime ou null doit être transmis');
        $this->histoFin = $histoFin;
        return $this;
    }

}
