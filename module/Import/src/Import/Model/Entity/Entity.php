<?php

namespace Import\Model\Entity;

use DateTime;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
abstract class Entity {

    /**
     * Source de données
     *
     * @var string
     */
    protected $sourceId;

    /**
     * Identifiant au niveau de la source
     *
     * @var string
     */
    protected $sourceCode;

    /**
     * Date de début d'historique
     *
     * @var DateTime
     */
    protected $histoDebut;

    /**
     * Date de fin d'historique
     *
     * @var DateTime
     */
    protected $histoFin;





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