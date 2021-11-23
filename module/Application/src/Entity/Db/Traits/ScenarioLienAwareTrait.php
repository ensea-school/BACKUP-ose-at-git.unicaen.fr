<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ScenarioLien;

/**
 * Description of ScenarioLienAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioLienAwareTrait
{
    /**
     * @var ScenarioLien
     */
    private $scenarioLien;





    /**
     * @param ScenarioLien $scenarioLien
     * @return self
     */
    public function setScenarioLien( ScenarioLien $scenarioLien = null )
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