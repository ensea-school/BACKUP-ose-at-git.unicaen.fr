<?php

namespace Plafond\Entity\Db;


/**
 * Description of PlafondEtatAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondEtatAwareTrait
{
    protected ?PlafondEtat $plafondEtat = null;



    /**
     * @param PlafondEtat $plafondEtat
     *
     * @return self
     */
    public function setPlafondEtat( ?PlafondEtat $plafondEtat )
    {
        $this->plafondEtat = $plafondEtat;

        return $this;
    }



    public function getPlafondEtat(): ?PlafondEtat
    {
        return $this->plafondEtat;
    }
}