<?php

namespace Application\Service\Traits;

use Application\Service\Intervenant;

/**
 * Description of IntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantAwareTrait
{
    /**
     * @var Intervenant
     */
    private $serviceIntervenant;



    /**
     * @param Intervenant $serviceIntervenant
     *
     * @return self
     */
    public function setServiceIntervenant(Intervenant $serviceIntervenant)
    {
        $this->serviceIntervenant = $serviceIntervenant;

        return $this;
    }



    /**
     * @return Intervenant
     */
    public function getServiceIntervenant()
    {
        if (empty($this->serviceIntervenant)) {
            $this->serviceIntervenant = \Application::$container->get('ApplicationIntervenant');
        }

        return $this->serviceIntervenant;
    }
}