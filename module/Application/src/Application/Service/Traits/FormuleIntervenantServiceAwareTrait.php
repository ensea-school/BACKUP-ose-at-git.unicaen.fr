<?php

namespace Application\Service\Traits;

use Application\Service\FormuleIntervenantService;

/**
 * Description of FormuleIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleIntervenantServiceAwareTrait
{
    /**
     * @var FormuleIntervenantService
     */
    private $serviceFormuleIntervenant;



    /**
     * @param FormuleIntervenantService $serviceFormuleIntervenant
     *
     * @return self
     */
    public function setServiceFormuleIntervenant(FormuleIntervenantService $serviceFormuleIntervenant)
    {
        $this->serviceFormuleIntervenant = $serviceFormuleIntervenant;

        return $this;
    }



    /**
     * @return FormuleIntervenantService
     */
    public function getServiceFormuleIntervenant()
    {
        if (empty($this->serviceFormuleIntervenant)) {
            $this->serviceFormuleIntervenant = \Application::$container->get(FormuleIntervenantService::class);
        }

        return $this->serviceFormuleIntervenant;
    }
}