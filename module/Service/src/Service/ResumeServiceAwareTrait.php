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
            $this->serviceResume = \Application::$container->get(ResumeService::class);
        }

        return $this->serviceResume;
    }
}