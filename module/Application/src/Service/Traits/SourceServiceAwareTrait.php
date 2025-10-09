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
    protected ?SourceService $serviceSource = null;



    /**
     * @param SourceService $serviceSource
     *
     * @return self
     */
    public function setServiceSource(?SourceService $serviceSource)
    {
        $this->serviceSource = $serviceSource;

        return $this;
    }



    public function getServiceSource(): ?SourceService
    {
        if (empty($this->serviceSource)) {
            $this->serviceSource = \Unicaen\Framework\Application\Application::getInstance()->container()->get(SourceService::class);
        }

        return $this->serviceSource;
    }
}