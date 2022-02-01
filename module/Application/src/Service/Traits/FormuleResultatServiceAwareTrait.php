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
    protected ?FormuleResultatService $serviceFormuleResultat;



    /**
     * @param FormuleResultatService|null $serviceFormuleResultat
     *
     * @return self
     */
    public function setServiceFormuleResultat( ?FormuleResultatService $serviceFormuleResultat )
    {
        $this->serviceFormuleResultat = $serviceFormuleResultat;

        return $this;
    }



    public function getServiceFormuleResultat(): ?FormuleResultatService
    {
        if (!$this->serviceFormuleResultat){
            $this->serviceFormuleResultat = \Application::$container->get('FormElementManager')->get(FormuleResultatService::class);
        }

        return $this->serviceFormuleResultat;
    }
}