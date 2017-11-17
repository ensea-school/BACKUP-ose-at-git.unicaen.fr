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
    /**
     * @var ScenarioService
     */
    private $serviceScenario;



    /**
     * @param ScenarioService $serviceScenario
     *
     * @return self
     */
    public function setServiceScenario(ScenarioService $serviceScenario)
    {
        $this->serviceScenario = $serviceScenario;

        return $this;
    }



    /**
     * @return ScenarioService
     */
    public function getServiceScenario()
    {
        if (empty($this->serviceScenario)) {
            $this->serviceScenario = \Application::$container->get('applicationScenario');
        }

        return $this->serviceScenario;
    }
}