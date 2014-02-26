<?php

namespace Import\Model\Entity\Structure;

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
     * @var integer
     */
    protected $typeId;

    /**
     * ID de l'établissement correspondant
     *
     * @var integer
     */
    protected $etablissementId;





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

}