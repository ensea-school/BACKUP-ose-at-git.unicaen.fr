<?php

namespace Plafond\Service;


/**
 * Description of PlafondStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondStructureServiceAwareTrait
{
    protected ?PlafondStructureService $servicePlafondStructure;



    /**
     * @param PlafondStructureService|null $servicePlafondStructure
     *
     * @return self
     */
    public function setServicePlafondStructure( ?PlafondStructureService $servicePlafondStructure )
    {
        $this->servicePlafondStructure = $servicePlafondStructure;

        return $this;
    }



    public function getServicePlafondStructure(): ?PlafondStructureService
    {
        if (!$this->servicePlafondStructure){
            $this->servicePlafondStructure = \Application::$container->get(PlafondStructureService::class);
        }

        return $this->servicePlafondStructure;
    }
}