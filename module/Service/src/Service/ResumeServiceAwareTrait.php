<?php

namespace Service\Service;

/**
 * Description of ResumeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ResumeServiceAwareTrait
{
    protected ?ResumeService $serviceResume = null;



    /**
     * @param ResumeService $serviceResume
     *
     * @return self
     */
    public function setServiceResume(?ResumeService $serviceResume)
    {
        $this->serviceResume = $serviceResume;

        return $this;
    }



    public function getServiceResume(): ?ResumeService
    {
        if (empty($this->serviceResume)) {
            $this->serviceResume = \Framework\Application\Application::getInstance()->container()->get(ResumeService::class);
        }

        return $this->serviceResume;
    }
}