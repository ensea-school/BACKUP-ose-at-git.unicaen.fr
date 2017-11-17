<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervenant;

/**
 * Description of TypeIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeIntervenantAwareTrait
{
    /**
     * @var TypeIntervenant
     */
    private $serviceTypeIntervenant;



    /**
     * @param TypeIntervenant $serviceTypeIntervenant
     *
     * @return self
     */
    public function setServiceTypeIntervenant(TypeIntervenant $serviceTypeIntervenant)
    {
        $this->serviceTypeIntervenant = $serviceTypeIntervenant;

        return $this;
    }



    /**
     * @return TypeIntervenant
     */
    public function getServiceTypeIntervenant()
    {
        if (empty($this->serviceTypeIntervenant)) {
            $this->serviceTypeIntervenant = \Application::$container->get('ApplicationTypeIntervenant');
        }

        return $this->serviceTypeIntervenant;
    }
}