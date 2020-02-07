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
    /**
     * @var VoirieService
     */
    private $serviceVoirie;



    /**
     * @param VoirieService $serviceVoirie
     *
     * @return self
     */
    public function setServiceVoirie(VoirieService $serviceVoirie)
    {
        $this->serviceVoirie = $serviceVoirie;

        return $this;
    }



    /**
     * @return VoirieService
     */
    public function getServiceVoirie()
    {
        if (empty($this->serviceVoirie)) {
            $this->serviceVoirie = \Application::$container->get(VoirieService::class);
        }

        return $this->serviceVoirie;
    }
}