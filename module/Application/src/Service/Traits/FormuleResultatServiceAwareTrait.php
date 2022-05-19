<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatService;

/**
 * Description of FormuleResultatServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceAwareTrait
{
    protected ?FormuleResultatService $serviceFormuleResultat = null;



    /**
     * @param FormuleResultatService $serviceFormuleResultat
     *
     * @return self
     */
    public function setServiceFormuleResultat(?FormuleResultatService $serviceFormuleResultat)
    {
        $this->serviceFormuleResultat = $serviceFormuleResultat;

        return $this;
    }



    public function getServiceFormuleResultat(): ?FormuleResultatService
    {
        if (empty($this->serviceFormuleResultat)) {
            $this->serviceFormuleResultat = \Application::$container->get(FormuleResultatService::class);
        }

        return $this->serviceFormuleResultat;
    }
}