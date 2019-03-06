<?php

namespace Application\Service\Traits;

use Application\Service\MotifModificationServiceService;

/**
 * Description of MotifModificationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceServiceAwareTrait
{
    /**
     * @var MotifModificationServiceService
     */
    private $serviceMotifModificationService;



    /**
     * @param MotifModificationServiceService $serviceMotifModificationService
     *
     * @return self
     */
    public function setServiceMotifModificationService(MotifModificationServiceService $serviceMotifModificationService)
    {
        $this->serviceMotifModificationService = $serviceMotifModificationService;

        return $this;
    }



    /**
     * @return MotifModificationServiceService
     */
    public function getServiceMotifModificationService()
    {
        if (empty($this->serviceMotifModificationService)) {
            $this->serviceMotifModificationService = \Application::$container->get(MotifModificationServiceService::class);
        }

        return $this->serviceMotifModificationService;
    }
}
