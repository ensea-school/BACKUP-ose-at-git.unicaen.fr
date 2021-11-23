<?php

namespace Application\Entity\Chargens\Traits;

use Application\Entity\Chargens\ScenarioLien;

/**
 * Description of ScenarioLienAwareTrait
 *
 */
trait ScenarioLienAwareTrait
{
    /**
     * @var ScenarioLien
     */
    private $scenarioLien;



    /**
     * @param ScenarioLien $scenarioLien
     *
     * @return self
     */
    public function setScenarioLien(ScenarioLien $scenarioLien = null)
    {
        $this->scenarioLien = $scenarioLien;

        return $this;
    }



    /**
     * @return ScenarioLien
     */
    public function getScenarioLien()
    {
        return $this->scenarioLien;
    }
}