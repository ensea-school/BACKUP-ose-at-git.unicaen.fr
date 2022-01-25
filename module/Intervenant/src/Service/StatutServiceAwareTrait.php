<?php

namespace Intervenant\Service;


/**
 * Description of StatutIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutServiceAwareTrait
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
    public function setServiceStatut(StatutService $serviceStatutIntervenant)
    {
        $this->serviceStatutIntervenant = $serviceStatutIntervenant;

        return $this;
    }



    /**
     * @return StatutService
     */
    public function getServiceStatut()
    {
        if (empty($this->serviceStatutIntervenant)) {
            $this->serviceStatutIntervenant = \Application::$container->get(StatutService::class);
        }

        return $this->serviceStatutIntervenant;
    }
}