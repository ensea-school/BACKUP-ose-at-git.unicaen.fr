<?php

namespace Application\Entity\Chargens\Traits;

use Application\Entity\Chargens\ScenarioLien;

/**
 * Description of ScenarioLienAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioLienAwareTrait
{
    protected ?ScenarioLien $scenarioLien;



    /**
     * @param ScenarioLien|null $scenarioLien
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