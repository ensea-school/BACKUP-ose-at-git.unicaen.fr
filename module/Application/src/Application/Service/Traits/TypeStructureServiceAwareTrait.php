<?php

namespace Application\Service\Traits;

use Application\Service\TypeStructureService;

/**
 * Description of TypeStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeStructureServiceAwareTrait
{
    /**
     * @var TypeStructureService
     */
    private $serviceTypeStructure;



    /**
     * @param TypeStructureService $serviceTypeStructure
     *
     * @return self
     */
    public function setServiceTypeStructure(TypeStructureService $serviceTypeStructure)
    {
        $this->serviceTypeStructure = $serviceTypeStructure;

        return $this;
    }



    /**
     * @return TypeStructureService
     */
    public function getServiceTypeStructure()
    {
        if (empty($this->serviceTypeStructure)) {
            $this->serviceTypeStructure = \Application::$container->get(TypeStructureService::class);
        }

        return $this->serviceTypeStructure;
    }
}