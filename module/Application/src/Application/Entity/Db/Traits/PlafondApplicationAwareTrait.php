<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\PlafondApplication;

/**
 * Description of PlafondApplicationAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondApplicationAwareTrait
{
    /**
     * @var PlafondApplication
     */
    protected $plafondApplication;





    /**
     * @param PlafondApplication $plafondApplication
     * @return self
     */
    public function setPlafondApplication( PlafondApplication $plafondApplication = null )
    {
        $this->plafondApplication = $plafondApplication;
        return $this;
    }



    /**
     * @return PlafondApplication
     */
    public function getPlafondApplication()
    {
        return $this->plafondApplication;
    }
}