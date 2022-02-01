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
    protected ?Scenario $scenario = null;



    /**
     * @param Scenario $scenario
     *
     * @return self
     */
    public function setScenario( Scenario $scenario )
    {
        $this->scenario = $scenario;

        return $this;
    }



    public function getScenario(): ?Scenario
    {
        return $this->scenario;
    }
}