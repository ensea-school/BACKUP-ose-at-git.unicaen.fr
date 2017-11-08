<?php

namespace Application\Service\Traits;

use Application\Service\PlafondApplicationService;
use Application\Module;

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
    public function getServicePlafondApplication() : PlafondApplicationService
    {
        if (!$this->servicePlafondApplication){
            $this->servicePlafondApplication = Module::$serviceLocator->get(PlafondApplicationService::class);
        }

        return $this->servicePlafondApplication;
    }
}