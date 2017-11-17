<?php

namespace Application\Service\Traits;

use Application\Service\Structure;

/**
 * Description of StructureAwareTrait
 *
 * @author UnicaenCode
 */
trait StructureAwareTrait
{
    /**
     * @var Structure
     */
    private $serviceStructure;



    /**
     * @param Structure $serviceStructure
     *
     * @return self
     */
    public function setServiceStructure(Structure $serviceStructure)
    {
        $this->serviceStructure = $serviceStructure;

        return $this;
    }



    /**
     * @return Structure
     */
    public function getServiceStructure()
    {
        if (empty($this->serviceStructure)) {
            $this->serviceStructure = \Application::$container->get('ApplicationStructure');
        }

        return $this->serviceStructure;
    }
}