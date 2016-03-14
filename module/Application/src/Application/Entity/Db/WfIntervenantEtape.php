<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;

/**
 * WfIntervenantEtape
 */
class WfIntervenantEtape
{
    use IntervenantAwareTrait;
    use StructureAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $atteignable = false;

    /**
     * @var float
     */
    private $objectif;

    /**
     * @var float
     */
    private $realisation;

    /**
     * @var WfEtape
     */
    private $etape;



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
     * @return WfEtape
     */
    public function getEtape()
    {
        return $this->etape;
    }



    /**
     * Get atteignable
     *
     * @return boolean
     */
    public function getAtteignable()
    {
        return $this->atteignable;
    }



    /**
     * @return float
     */
    public function getObjectif()
    {
        return $this->objectif;
    }



    /**
     * @return float
     */
    public function getRealisation()
    {
        return $this->realisation;
    }



    /**
     * Get franchie
     *
     * @return float
     */
    public function getFranchie()
    {
        $res = 0;
        if ($this->objectif > 0){
            $res = $this->realisation / $this->objectif;
        }
        if ($res > 1) $res = 1; // pour éviter tout malentendu au cas où...
        return $res;
    }

}
