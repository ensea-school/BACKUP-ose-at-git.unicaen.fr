<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Service;

/**
 * Description of ServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface ServiceAwareInterface
{
    /**
     * @param Service $service
     * @return self
     */
    public function setService( Service $service = null );



    /**
     * @return Service
     */
    public function getService();
}