<?php

namespace Plafond\Entity\Db;

/**
 * Description of PlafondEtatAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondEtatAwareTrait
{
    /**
     * @var PlafondEtat
     */
    protected $plafondEtat;



    /**
     * @param PlafondEtat $plafondEtat
     *
     * @return self
     */
    public function setPlafondEtat(PlafondEtat $plafondEtat = null)
    {
        $this->plafondEtat = $plafondEtat;

        return $this;
    }



    public function getPlafondEtat(): ?PlafondEtat
    {
        return $this->plafondEtat;
    }
}