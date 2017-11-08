<?php

namespace Application\Service\Traits;

use Application\Service\PlafondApplicationService;


/**
 * Description of PlafondApplicationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondApplicationServiceAwareTrait
{
    /**
     * @var PlafondApplicationService
     */
    protected $servicePlafondApplication;



    /**
     * @param PlafondApplicationService $servicePlafondApplication
     * @return self
     */
    public function setServicePlafondApplication( PlafondApplicationService $servicePlafondApplication )
    {
        $this->servicePlafondApplication = $servicePlafondApplication;

        return $this;
    }



    /**
     * @return PlafondApplicationService
     */
    public function getServicePlafondApplication()
    {
        return $this->servicePlafondApplication;
    }
}