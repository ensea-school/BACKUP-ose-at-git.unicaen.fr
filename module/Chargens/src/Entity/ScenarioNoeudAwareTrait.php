<?php

namespace Chargens\Entity;

/**
 * Description of ScenarioNoeudAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioNoeudAwareTrait
{
    protected ?ScenarioNoeud $scenarioNoeud = null;



    /**
     * @param ScenarioNoeud $scenarioNoeud
     *
     * @return self
     */
    public function setScenarioNoeud( ?ScenarioNoeud $scenarioNoeud )
    {
        $this->scenarioNoeud = $scenarioNoeud;

        return $this;
    }



    public function getScenarioNoeud(): ?ScenarioNoeud
    {
        return $this->scenarioNoeud;
    }
}