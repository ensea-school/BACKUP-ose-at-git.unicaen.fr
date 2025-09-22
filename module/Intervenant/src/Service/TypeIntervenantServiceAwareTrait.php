<?php

namespace Intervenant\Service;


/**
 * Description of TypeIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeIntervenantServiceAwareTrait
{
    protected ?TypeIntervenantService $serviceTypeIntervenant = null;



    /**
     * @param TypeIntervenantService $serviceTypeIntervenant
     *
     * @return self
     */
    public function setServiceTypeIntervenant(?TypeIntervenantService $serviceTypeIntervenant)
    {
        $this->serviceTypeIntervenant = $serviceTypeIntervenant;

        return $this;
    }



    public function getServiceTypeIntervenant(): ?TypeIntervenantService
    {
        if (empty($this->serviceTypeIntervenant)) {
            $this->serviceTypeIntervenant = \Framework\Application\Application::getInstance()->container()->get(TypeIntervenantService::class);
        }

        return $this->serviceTypeIntervenant;
    }
}