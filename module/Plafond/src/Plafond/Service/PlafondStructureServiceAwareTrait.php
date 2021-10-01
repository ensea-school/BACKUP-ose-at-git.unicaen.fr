<?php

namespace Plafond\Service;


/**
 * Description of PlafondStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondStructureServiceAwareTrait
{
    /**
     * @var PlafondStructureService
     */
    protected $servicePlafondStructure;



    /**
     * @param PlafondStructureService $servicePlafondStructure
     *
     * @return self
     */
    public function setServicePlafondStructure( PlafondStructureService $servicePlafondStructure )
    {
        $this->servicePlafondStructure = $servicePlafondStructure;

        return $this;
    }



    /**
     * @return PlafondStructureService
     */
    public function getServicePlafondStructure(): ?PlafondStructureService
    {
        if (!$this->servicePlafondStructure){
            $this->servicePlafondStructure = \Application::$container->get(PlafondStructureService::class);
        }

        return $this->servicePlafondStructure;
    }
}