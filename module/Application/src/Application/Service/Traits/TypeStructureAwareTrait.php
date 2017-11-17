<?php

namespace Application\Service\Traits;

use Application\Service\TypeStructure;

/**
 * Description of TypeStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeStructureAwareTrait
{
    /**
     * @var TypeStructure
     */
    private $serviceTypeStructure;



    /**
     * @param TypeStructure $serviceTypeStructure
     *
     * @return self
     */
    public function setServiceTypeStructure(TypeStructure $serviceTypeStructure)
    {
        $this->serviceTypeStructure = $serviceTypeStructure;

        return $this;
    }



    /**
     * @return TypeStructure
     */
    public function getServiceTypeStructure()
    {
        if (empty($this->serviceTypeStructure)) {
            $this->serviceTypeStructure = \Application::$container->get('ApplicationTypeStructure');
        }

        return $this->serviceTypeStructure;
    }
}