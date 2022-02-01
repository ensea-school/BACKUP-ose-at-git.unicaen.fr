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
    protected ?FormuleResultatServiceService $serviceFormuleResultatService = null;



    /**
     * @param FormuleResultatServiceService $serviceFormuleResultatService
     *
     * @return self
     */
    public function setServiceFormuleResultatService( FormuleResultatServiceService $serviceFormuleResultatService )
    {
        $this->serviceFormuleResultatService = $serviceFormuleResultatService;

        return $this;
    }



    public function getServiceFormuleResultatService(): ?FormuleResultatServiceService
    {
        if (empty($this->serviceFormuleResultatService)){
            $this->serviceFormuleResultatService = \Application::$container->get(FormuleResultatServiceService::class);
        }

        return $this->serviceFormuleResultatService;
    }
}