<?php

namespace Application\Entity\Chargens;

use Application\Entity\Chargens\Traits\ScenarioNoeudAwareTrait;
use Application\Entity\Db\Traits\TypeHeuresAwareTrait;
use Application\Entity\Db\TypeHeures;


class ScenarioNoeudEffectif
{
    use ScenarioNoeudAwareTrait;
    use TypeHeuresAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $effectif;



    /**
     * ScenarioNoeudEffectif constructor.
     */
    public function __construct( ScenarioNoeud $scenarioNoeud, TypeHeures $typeHeures )
    {
        $this->setScenarioNoeud($scenarioNoeud);
        $this->setTypeHeures($typeHeures);
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
    public function getEffectif()
    {
        return $this->effectif;
    }



    /**
     * @param int $effectif
     *
     * @return ScenarioNoeudEffectif
     */
    public function setEffectif($effectif)
    {
        $this->effectif = $effectif;

        return $this;
    }


}
