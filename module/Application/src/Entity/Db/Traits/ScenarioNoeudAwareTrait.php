<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ScenarioNoeud;

/**
 * Description of ScenarioNoeudAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioNoeudAwareTrait
{
    /**
     * @var ScenarioNoeud
     */
    private $scenarioNoeud;





    /**
     * @param ScenarioNoeud $scenarioNoeud
     * @return self
     */
    public function setScenarioNoeud( ScenarioNoeud $scenarioNoeud = null )
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