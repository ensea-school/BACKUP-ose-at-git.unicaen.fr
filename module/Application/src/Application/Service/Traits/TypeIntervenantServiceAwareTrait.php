<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervenantService;

/**
 * Description of TypeIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeIntervenantServiceAwareTrait
{
    /**
     * @var TypeIntervenantService
     */
    private $serviceTypeIntervenant;



    /**
     * @param TypeIntervenantService $serviceTypeIntervenant
     *
     * @return self
     */
    public function setServiceTypeIntervenant(TypeIntervenantService $serviceTypeIntervenant)
    {
        $this->serviceTypeIntervenant = $serviceTypeIntervenant;

        return $this;
    }



    /**
     * @return TypeIntervenantService
     */
    public function getServiceTypeIntervenant()
    {
        if (empty($this->serviceTypeIntervenant)) {
            $this->serviceTypeIntervenant = \Application::$container->get('ApplicationTypeIntervenant');
        }

        return $this->serviceTypeIntervenant;
    }
}