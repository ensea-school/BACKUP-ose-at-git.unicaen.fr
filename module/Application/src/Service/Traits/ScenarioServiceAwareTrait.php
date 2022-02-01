<?php

namespace Application\Service\Traits;

use Application\Service\ScenarioService;

/**
 * Description of ScenarioServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioServiceAwareTrait
{
    protected ?ScenarioService $serviceScenario;



    /**
     * @param ScenarioService|null $serviceScenario
     *
     * @return self
     */
    public function setServiceScenario( ?ScenarioService $serviceScenario )
    {
        $this->serviceScenario = $serviceScenario;

        return $this;
    }



    public function getServiceScenario(): ?ScenarioService
    {
        if (!$this->serviceScenario){
            $this->serviceScenario = \Application::$container->get(ScenarioService::class);
        }

        return $this->serviceScenario;
    }
}