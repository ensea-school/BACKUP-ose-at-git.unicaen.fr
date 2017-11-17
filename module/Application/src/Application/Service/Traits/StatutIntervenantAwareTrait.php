<?php

namespace Application\Service\Traits;

use Application\Service\StatutIntervenant;

/**
 * Description of StatutIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutIntervenantAwareTrait
{
    /**
     * @var StatutIntervenant
     */
    private $serviceStatutIntervenant;



    /**
     * @param StatutIntervenant $serviceStatutIntervenant
     *
     * @return self
     */
    public function setServiceStatutIntervenant(StatutIntervenant $serviceStatutIntervenant)
    {
        $this->serviceStatutIntervenant = $serviceStatutIntervenant;

        return $this;
    }



    /**
     * @return StatutIntervenant
     */
    public function getServiceStatutIntervenant()
    {
        if (empty($this->serviceStatutIntervenant)) {
            $this->serviceStatutIntervenant = \Application::$container->get('ApplicationStatutIntervenant');
        }

        return $this->serviceStatutIntervenant;
    }
}