<?php

namespace Application\Entity\Chargens\Traits;

use Application\Entity\Chargens\ScenarioNoeud;

/**
 * Description of ScenarioNoeudAwareTrait
 *
 */
trait ScenarioNoeudAwareTrait
{
    /**
     * @var ScenarioNoeud
     */
    private $scenarioNoeud;



    /**
     * @param ScenarioNoeud $scenarioNoeud
     *
     * @return self
     */
    public function setScenarioNoeud(ScenarioNoeud $scenarioNoeud = null)
    {
        $this->scenarioNoeud = $scenarioNoeud;

        return $this;
    }



    /**
     * @return ScenarioNoeud
     */
    public function getScenarioNoeud()
    {
        return $this->scenarioNoeud;
    }
}