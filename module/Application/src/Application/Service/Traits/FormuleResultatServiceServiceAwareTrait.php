<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceService;

/**
 * Description of FormuleResultatServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceServiceAwareTrait
{
    /**
     * @var FormuleResultatServiceService
     */
    private $serviceFormuleResultatService;



    /**
     * @param FormuleResultatServiceService $serviceFormuleResultatService
     *
     * @return self
     */
    public function setServiceFormuleResultatService(FormuleResultatServiceService $serviceFormuleResultatService)
    {
        $this->serviceFormuleResultatService = $serviceFormuleResultatService;

        return $this;
    }



    /**
     * @return FormuleResultatServiceService
     */
    public function getServiceFormuleResultatService()
    {
        if (empty($this->serviceFormuleResultatService)) {
            $this->serviceFormuleResultatService = \Application::$container->get('ApplicationFormuleResultatService');
        }

        return $this->serviceFormuleResultatService;
    }
}