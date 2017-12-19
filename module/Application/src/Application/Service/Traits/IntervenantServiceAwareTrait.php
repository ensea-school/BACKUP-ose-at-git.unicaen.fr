<?php

namespace Application\Service\Traits;

use Application\Service\IntervenantService;

/**
 * Description of IntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantServiceAwareTrait
{
    /**
     * @var IntervenantService
     */
    private $serviceIntervenant;



    /**
     * @param IntervenantService $serviceIntervenant
     *
     * @return self
     */
    public function setServiceIntervenant(IntervenantService $serviceIntervenant)
    {
        $this->serviceIntervenant = $serviceIntervenant;

        return $this;
    }



    /**
     * @return IntervenantService
     */
    public function getServiceIntervenant()
    {
        if (empty($this->serviceIntervenant)) {
            $this->serviceIntervenant = \Application::$container->get('ApplicationIntervenant');
        }

        return $this->serviceIntervenant;
    }
}