<?php

namespace Application\Service\Traits;

use Application\Service\StatutIntervenantService;

/**
 * Description of StatutIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutIntervenantServiceAwareTrait
{
    /**
     * @var StatutIntervenantService
     */
    private $serviceStatutIntervenant;



    /**
     * @param StatutIntervenantService $serviceStatutIntervenant
     *
     * @return self
     */
    public function setServiceStatutIntervenant(StatutIntervenantService $serviceStatutIntervenant)
    {
        $this->serviceStatutIntervenant = $serviceStatutIntervenant;

        return $this;
    }



    /**
     * @return StatutIntervenantService
     */
    public function getServiceStatutIntervenant()
    {
        if (empty($this->serviceStatutIntervenant)) {
            $this->serviceStatutIntervenant = \Application::$container->get(StatutIntervenantService::class);
        }

        return $this->serviceStatutIntervenant;
    }
}