<?php

namespace Application\Service\Traits;

use Application\Service\ScenarioService;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceScenario( ScenarioService $serviceScenario )
    {
        $this->serviceScenario = $serviceScenario;
        return $this;
    }



    /**
     * @return ScenarioService
     * @throws RuntimeException
     */
    public function getServiceScenario()
    {
        if (empty($this->serviceScenario)){
            $serviceLocator = Module::$serviceLocator;
            if (! $serviceLocator) {
                if (!method_exists($this, 'getServiceLocator')) {
                    throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
                }

                $serviceLocator = $this->getServiceLocator();
                if (method_exists($serviceLocator, 'getServiceLocator')) {
                    $serviceLocator = $serviceLocator->getServiceLocator();
                }
            }
            $this->serviceScenario = $serviceLocator->get('applicationScenario');
        }
        return $this->serviceScenario;
    }
}