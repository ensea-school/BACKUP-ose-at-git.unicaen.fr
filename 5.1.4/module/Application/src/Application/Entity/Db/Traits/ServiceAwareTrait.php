<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Service;

/**
 * Description of ServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAwareTrait
{
    /**
     * @var Service
     */
    private $service;





    /**
     * @param Service $service
     * @return self
     */
    public function setService( Service $service = null )
    {
        $this->service = $service;
        return $this;
    }



    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }
}