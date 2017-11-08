<?php

namespace Application\Service\Traits;

use Application\Service\PlafondService;

/**
 * Description of PlafondServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondServiceAwareTrait
{
    /**
     * @var PlafondService
     */
    protected $servicePlafond;



    /**
     * @param PlafondService $servicePlafond
     * @return self
     */
    public function setServicePlafond( PlafondService $servicePlafond )
    {
        $this->servicePlafond = $servicePlafond;

        return $this;
    }



    /**
     * @return PlafondService
     */
    public function getServicePlafond() : PlafondService
    {
        return $this->servicePlafond;
    }
}