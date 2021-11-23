<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Scenario;

/**
 * Description of ScenarioAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioAwareTrait
{
    /**
     * @var Scenario
     */
    private $scenario;





    /**
     * @param Scenario $scenario
     * @return self
     */
    public function setScenario( Scenario $scenario = null )
    {
        $this->scenario = $scenario;
        return $this;
    }



    /**
     * @return Scenario
     */
    public function getScenario()
    {
        return $this->scenario;
    }
}