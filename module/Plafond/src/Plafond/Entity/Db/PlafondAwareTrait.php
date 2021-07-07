<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Plafond;

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
     * @return self
     */
    public function setPlafond( Plafond $plafond = null )
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