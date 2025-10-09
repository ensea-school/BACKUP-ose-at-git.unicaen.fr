<?php

namespace Lieu\Service;

/**
 * Description of VoirieServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait VoirieServiceAwareTrait
{
    protected ?VoirieService $serviceVoirie = null;



    /**
     * @param VoirieService $serviceVoirie
     *
     * @return self
     */
    public function setServiceVoirie(?VoirieService $serviceVoirie)
    {
        $this->serviceVoirie = $serviceVoirie;

        return $this;
    }



    public function getServiceVoirie(): ?VoirieService
    {
        if (empty($this->serviceVoirie)) {
            $this->serviceVoirie =\Unicaen\Framework\Application\Application::getInstance()->container()->get(VoirieService::class);
        }

        return $this->serviceVoirie;
    }
}