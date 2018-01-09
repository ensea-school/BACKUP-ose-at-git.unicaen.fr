<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceModifieService;

/**
 * Description of FormuleServiceModifieAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceModifieServiceAwareTrait
{
    /**
     * @var FormuleServiceModifieService
     */
    private $serviceFormuleServiceModifie;



    /**
     * @param FormuleServiceModifieService $serviceFormuleServiceModifie
     *
     * @return self
     */
    public function setServiceFormuleServiceModifie(FormuleServiceModifieService $serviceFormuleServiceModifie)
    {
        $this->serviceFormuleServiceModifie = $serviceFormuleServiceModifie;

        return $this;
    }



    /**
     * @return FormuleServiceModifieService
     */
    public function getServiceFormuleServiceModifie()
    {
        if (empty($this->serviceFormuleServiceModifie)) {
            $this->serviceFormuleServiceModifie = \Application::$container->get(FormuleServiceModifieService::class);
        }

        return $this->serviceFormuleServiceModifie;
    }
}