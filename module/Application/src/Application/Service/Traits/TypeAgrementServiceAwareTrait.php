<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrementService;

/**
 * Description of TypeAgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementServiceAwareTrait
{
    /**
     * @var TypeAgrementService
     */
    private $serviceTypeAgrement;



    /**
     * @param TypeAgrementService $serviceTypeAgrement
     *
     * @return self
     */
    public function setServiceTypeAgrement(TypeAgrementService $serviceTypeAgrement)
    {
        $this->serviceTypeAgrement = $serviceTypeAgrement;

        return $this;
    }



    /**
     * @return TypeAgrementService
     */
    public function getServiceTypeAgrement()
    {
        if (empty($this->serviceTypeAgrement)) {
            $this->serviceTypeAgrement = \Application::$container->get('ApplicationTypeAgrement');
        }

        return $this->serviceTypeAgrement;
    }
}