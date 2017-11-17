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
    /**
     * @var SourceService
     */
    private $serviceSource;



    /**
     * @param SourceService $serviceSource
     *
     * @return self
     */
    public function setServiceSource(SourceService $serviceSource)
    {
        $this->serviceSource = $serviceSource;

        return $this;
    }



    /**
     * @return SourceService
     */
    public function getServiceSource()
    {
        if (empty($this->serviceSource)) {
            $this->serviceSource = \Application::$container->get(SourceService::class);
        }

        return $this->serviceSource;
    }
}