<?php

namespace Application\Service\Traits;

use Application\Service\VoirieService;

/**
 * Description of VoirieServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait VoirieServiceAwareTrait
{
    protected ?VoirieService $serviceVoirie;



    /**
     * @param VoirieService|null $serviceVoirie
     *
     * @return self
     */
    public function setServiceVoirie( ?VoirieService $serviceVoirie )
    {
        $this->serviceVoirie = $serviceVoirie;

        return $this;
    }



    public function getServiceVoirie(): ?VoirieService
    {
        if (!$this->serviceVoirie){
            $this->serviceVoirie = \Application::$container->get(VoirieService::class);
        }

        return $this->serviceVoirie;
    }
}