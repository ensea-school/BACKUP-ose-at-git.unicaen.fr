<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatService;

/**
 * Description of FormuleResultatAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceAwareTrait
{
    /**
     * @var FormuleResultatService
     */
    private $serviceFormuleResultat;



    /**
     * @param FormuleResultatService $serviceFormuleResultat
     *
     * @return self
     */
    public function setServiceFormuleResultat(FormuleResultatService $serviceFormuleResultat)
    {
        $this->serviceFormuleResultat = $serviceFormuleResultat;

        return $this;
    }



    /**
     * @return FormuleResultatService
     */
    public function getServiceFormuleResultat()
    {
        if (empty($this->serviceFormuleResultat)) {
            $this->serviceFormuleResultat = \Application::$container->get(FormuleResultatService::class);
        }

        return $this->serviceFormuleResultat;
    }
}