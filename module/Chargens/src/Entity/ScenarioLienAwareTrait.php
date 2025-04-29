<?php

namespace Chargens\Entity;

/**
 * Description of ScenarioLienAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioLienAwareTrait
{
    protected ?ScenarioLien $scenarioLien = null;



    /**
     * @param ScenarioLien $scenarioLien
     *
     * @return self
     */
    public function setScenarioLien( ?ScenarioLien $scenarioLien )
    {
        $this->scenarioLien = $scenarioLien;

        return $this;
    }



    public function getScenarioLien(): ?ScenarioLien
    {
        return $this->scenarioLien;
    }
}