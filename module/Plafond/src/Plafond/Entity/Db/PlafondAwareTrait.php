<?php

namespace Plafond\Entity\Db;

/**
 * Description of PlafondAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondAwareTrait
{
    /**
     * @var Plafond
     */
    protected $plafond;



    /**
     * @param Plafond $plafond
     *
     * @return self
     */
    public function setPlafond(Plafond $plafond = null)
    {
        $this->plafond = $plafond;

        return $this;
    }



    /**
     * @return Plafond
     */
    public function getPlafond()
    {
        return $this->plafond;
    }
}