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
    /**
     * @var ModeleContratService
     */
    protected $serviceModeleContrat;



    /**
     * @param ModeleContratService $serviceModeleContrat
     * @return self
     */
    public function setServiceModeleContrat( ModeleContratService $serviceModeleContrat )
    {
        $this->serviceModeleContrat = $serviceModeleContrat;

        return $this;
    }



    /**
     * @return ModeleContratService
     */
    public function getServiceModeleContrat() : ModeleContratService
    {
        if (!$this->serviceModeleContrat){
            $this->serviceModeleContrat = \Application::$container->get(ModeleContratService::class);
        }

        return $this->serviceModeleContrat;
    }
}