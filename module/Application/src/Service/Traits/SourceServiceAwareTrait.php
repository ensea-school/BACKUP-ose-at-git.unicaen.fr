<?php

namespace Application\Service\Traits;

use Application\Service\SourceService;

/**
 * Description of SourceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait SourceServiceAwareTrait
{
    protected ?SourceService $serviceSource;



    /**
     * @param SourceService|null $serviceSource
     *
     * @return self
     */
    public function setServiceSource( ?SourceService $serviceSource )
    {
        $this->serviceSource = $serviceSource;

        return $this;
    }



    public function getServiceSource(): ?SourceService
    {
        if (!$this->serviceSource){
            $this->serviceSource = \Application::$container->get(SourceService::class);
        }

        return $this->serviceSource;
    }
}