<?php

namespace Import\Model\Entity\Intervenant;

use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Permanent extends Entity {

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
    protected $corpsId;

    /**
     * Section CNU
     *
     * @var integer
     */
    protected $sectionCnuId;





    public function getId()
    {
        return $this->id;
    }

    public function getSectionCnuId()
    {
        return $this->sectionCnuId;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setSectionCnuId($sectionCnuId)
    {
        $this->sectionCnuId = $sectionCnuId;
        return $this;
    }

    public function getCorpsId()
    {
        return $this->corpsId;
    }

    public function setCorpsId($corpsId)
    {
        $this->corpsId = $corpsId;
        return $this;
    }

}
