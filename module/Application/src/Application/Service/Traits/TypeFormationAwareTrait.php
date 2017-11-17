<?php

namespace Application\Service\Traits;

use Application\Service\TypeFormation;

/**
 * Description of TypeFormationAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeFormationAwareTrait
{
    /**
     * @var TypeFormation
     */
    private $serviceTypeFormation;



    /**
     * @param TypeFormation $serviceTypeFormation
     *
     * @return self
     */
    public function setServiceTypeFormation(TypeFormation $serviceTypeFormation)
    {
        $this->serviceTypeFormation = $serviceTypeFormation;

        return $this;
    }



    /**
     * @return TypeFormation
     */
    public function getServiceTypeFormation()
    {
        if (empty($this->serviceTypeFormation)) {
            $this->serviceTypeFormation = \Application::$container->get('ApplicationTypeFormation');
        }

        return $this->serviceTypeFormation;
    }
}