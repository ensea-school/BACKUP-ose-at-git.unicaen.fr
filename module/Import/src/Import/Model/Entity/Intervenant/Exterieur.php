<?php

namespace Import\Model\Entity\Intervenant;

use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Exterieur extends Entity {

     /**
     * Identifiant
     *
     * @var integer
     */
    protected $id;

    /**
     * Corps
     *
     * @var integer
     */
    protected $typeIntervenantExterieurId;

    /**
     * Situation familiale
     *
     * @var integer
     */
    protected $situationFamilialeId;

    /**
     * Régime Sécu
     *
     * @var integer
     */
    protected $regimeSecuId;

    /**
     * Type de poste
     *
     * @var integer
     */
    protected $typePosteId;


    


    public function getId()
    {
        return $this->id;
    }

    public function getTypeIntervenantExterieurId()
    {
        return $this->typeIntervenantExterieurId;
    }

    public function getSituationFamilialeId()
    {
        return $this->situationFamilialeId;
    }

    public function getRegimeSecuId()
    {
        return $this->regimeSecuId;
    }

    public function getTypePosteId()
    {
        return $this->typePosteId;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTypeIntervenantExterieurId($typeIntervenantExterieurId)
    {
        $this->typeIntervenantExterieurId = $typeIntervenantExterieurId;
        return $this;
    }

    public function setSituationFamilialeId($situationFamilialeId)
    {
        $this->situationFamilialeId = $situationFamilialeId;
        return $this;
    }

    public function setRegimeSecuId($regimeSecuId)
    {
        $this->regimeSecuId = $regimeSecuId;
        return $this;
    }

    public function setTypePosteId($typePosteId)
    {
        $this->typePosteId = $typePosteId;
        return $this;
    }

}
