<?php

namespace Application\Service\Traits;

use Application\Service\ModeleContratService;

/**
 * Description of ModeleContratServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ModeleContratServiceAwareTrait
{
    protected ?ModeleContratService $serviceModeleContrat;



    /**
     * @param ModeleContratService|null $serviceModeleContrat
     *
     * @return self
     */
    public function setServiceModeleContrat( ?ModeleContratService $serviceModeleContrat )
    {
        $this->serviceModeleContrat = $serviceModeleContrat;

        return $this;
    }



    public function getServiceModeleContrat(): ?ModeleContratService
    {
        if (!$this->serviceModeleContrat){
            $this->serviceModeleContrat = \Application::$container->get(ModeleContratService::class);
        }

        return $this->serviceModeleContrat;
    }
}