<?php

namespace Chargens\Entity;

use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\Traits\EtapeAwareTrait;
use OffreFormation\Entity\Db\Traits\TypeHeuresAwareTrait;
use OffreFormation\Entity\Db\TypeHeures;


class ScenarioNoeudEffectif
{
    use ScenarioNoeudAwareTrait;
    use TypeHeuresAwareTrait;
    use EtapeAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $effectif;



    /**
     * ScenarioNoeudEffectif constructor.
     */
    public function __construct( ScenarioNoeud $scenarioNoeud, TypeHeures $typeHeures, Etape $etape )
    {
        $this->setScenarioNoeud($scenarioNoeud);
        $this->setTypeHeures($typeHeures);
        $this->setEtape($etape);
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
