<?php

namespace Chargens\Entity;

use OffreFormation\Entity\Db\Traits\TypeInterventionAwareTrait;
use OffreFormation\Entity\Db\TypeIntervention;


class ScenarioNoeudSeuil
{
    use ScenarioNoeudAwareTrait;
    use TypeInterventionAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $ouverture;

    /**
     * @var integer
     */
    private $dedoublement;

    /**
     * @var float
     */
    private $assiduite;



    /**
     * ScenarioNoeudEffectif constructor.
     */
    public function __construct(ScenarioNoeud $scenarioNoeud, TypeIntervention $typeIntervention)
    {
        $this->setScenarioNoeud($scenarioNoeud);
        $this->setTypeIntervention($typeIntervention);
    }



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return ScenarioNoeudEffectif
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return int
     */
    public function getOuverture()
    {
        return $this->ouverture;
    }



    /**
     * @param int $ouverture
     *
     * @return ScenarioNoeudSeuil
     */
    public function setOuverture($ouverture)
    {
        $this->ouverture = $ouverture;

        return $this;
    }



    /**
     * @return int
     */
    public function getDedoublement()
    {
        return $this->dedoublement;
    }



    /**
     * @param int $dedoublement
     *
     * @return ScenarioNoeudSeuil
     */
    public function setDedoublement($dedoublement)
    {
        $this->dedoublement = $dedoublement;

        return $this;
    }



    /**
     * @return float
     */
    public function getAssiduite()
    {
        return $this->assiduite;
    }



    /**
     * @param float $assiduite
     *
     * @return ScenarioNoeudSeuil
     */
    public function setAssiduite($assiduite)
    {
        $this->assiduite = $assiduite;

        return $this;
    }

}
