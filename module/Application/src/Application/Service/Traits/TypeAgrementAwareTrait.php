<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrement;

/**
 * Description of TypeAgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementAwareTrait
{
    /**
     * @var TypeAgrement
     */
    private $serviceTypeAgrement;



    /**
     * @param TypeAgrement $serviceTypeAgrement
     *
     * @return self
     */
    public function setServiceTypeAgrement(TypeAgrement $serviceTypeAgrement)
    {
        $this->serviceTypeAgrement = $serviceTypeAgrement;

        return $this;
    }



    /**
     * @return TypeAgrement
     */
    public function getServiceTypeAgrement()
    {
        if (empty($this->serviceTypeAgrement)) {
            $this->serviceTypeAgrement = \Application::$container->get('ApplicationTypeAgrement');
        }

        return $this->serviceTypeAgrement;
    }
}