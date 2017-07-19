<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Corps;

/**
 * Description of CorpsAwareTrait
 *
 * @author UnicaenCode
 */
trait CorpsAwareTrait
{
    /**
     * @var Corps
     */
    private $corps;





    /**
     * @param Corps $corps
     * @return self
     */
    public function setCorps( Corps $corps = null )
    {
        $this->corps = $corps;
        return $this;
    }



    /**
     * @return Corps
     */
    public function getCorps()
    {
        return $this->corps;
    }
}