<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrementStatut;

/**
 * Description of TypeAgrementStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeAgrementStatutAwareTrait
{
    /**
     * @var TypeAgrementStatut
     */
    private $serviceTypeAgrementStatut;



    /**
     * @param TypeAgrementStatut $serviceTypeAgrementStatut
     *
     * @return self
     */
    public function setServiceTypeAgrementStatut(TypeAgrementStatut $serviceTypeAgrementStatut)
    {
        $this->serviceTypeAgrementStatut = $serviceTypeAgrementStatut;

        return $this;
    }



    /**
     * @return TypeAgrementStatut
     */
    public function getServiceTypeAgrementStatut()
    {
        if (empty($this->serviceTypeAgrementStatut)) {
            $this->serviceTypeAgrementStatut = \Application::$container->get('ApplicationTypeAgrementStatut');
        }

        return $this->serviceTypeAgrementStatut;
    }
}