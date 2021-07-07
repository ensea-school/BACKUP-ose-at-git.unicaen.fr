<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\PlafondEtat;

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
     * @return self
     */
    public function setPlafondEtat( PlafondEtat $plafondEtat = null )
    {
        $this->plafondEtat = $plafondEtat;
        return $this;
    }



    /**
     * @return PlafondEtat
     */
    public function getPlafondEtat()
    {
        return $this->plafondEtat;
    }
}