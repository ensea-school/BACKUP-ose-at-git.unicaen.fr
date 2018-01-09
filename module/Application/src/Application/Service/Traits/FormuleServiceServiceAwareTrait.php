<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceService;

/**
 * Description of FormuleServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceServiceAwareTrait
{
    /**
     * @var FormuleServiceService
     */
    private $serviceFormuleService;



    /**
     * @param FormuleServiceService $serviceFormuleService
     *
     * @return self
     */
    public function setServiceFormuleService(FormuleServiceService $serviceFormuleService)
    {
        $this->serviceFormuleService = $serviceFormuleService;

        return $this;
    }



    /**
     * @return FormuleServiceService
     */
    public function getServiceFormuleService()
    {
        if (empty($this->serviceFormuleService)) {
            $this->serviceFormuleService = \Application::$container->get(FormuleServiceService::class);
        }

        return $this->serviceFormuleService;
    }
}