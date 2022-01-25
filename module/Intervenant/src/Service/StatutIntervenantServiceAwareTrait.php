<?php

namespace Application\Service\Traits;

use Application\Service\StatutService;

/**
 * Description of StatutIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutIntervenantServiceAwareTrait
{
    /**
     * @var StatutService
     */
    private $serviceStatutIntervenant;



    /**
     * @param StatutService $serviceStatutIntervenant
     *
     * @return self
     */
    public function setServiceStatutIntervenant(StatutService $serviceStatutIntervenant)
    {
        $this->serviceStatutIntervenant = $serviceStatutIntervenant;

        return $this;
    }



    /**
     * @return StatutService
     */
    public function getServiceStatutIntervenant()
    {
        if (empty($this->serviceStatutIntervenant)) {
            $this->serviceStatutIntervenant = \Application::$container->get(StatutService::class);
        }

        return $this->serviceStatutIntervenant;
    }
}