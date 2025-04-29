<?php

namespace Chargens\Entity;

use Chargens\Entity\Db\Scenario;
use Chargens\Entity\Db\ScenarioAwareTrait;


class ScenarioLien
{
    use ScenarioAwareTrait;
    use LienAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $actif = true;

    /**
     * @var float
     */
    private $poids = 1.0;

    /**
     * @var integer
     */
    private $choixMinimum = null;

    /**
     * @var integer
     */
    private $choixMaximum = null;



    /**
     * ScenarioLien constructor.
     *
     * @param Lien     $lien
     * @param Scenario $scenario
     */
    public function __construct(Lien $lien, Scenario $scenario)
    {
        $this->setLien($lien);
        $this->setScenario($scenario);
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
     * @return ScenarioLien
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return bool
     */
    public function isActif()
    {
        return $this->actif;
    }



    /**
     * @param bool $actif
     *
     * @return ScenarioLien
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }



    /**
     * @return float
     */
    public function getPoids()
    {
        return $this->poids;
    }



    /**
     * @param float $poids
     *
     * @return ScenarioLien
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }



    /**
     * @return int
     */
    public function getChoixMinimum()
    {
        return $this->choixMinimum;
    }



    /**
     * @param int $choixMinimum
     *
     * @return ScenarioLien
     */
    public function setChoixMinimum($choixMinimum)
    {
        $this->choixMinimum = $choixMinimum;

        return $this;
    }



    /**
     * @return int
     */
    public function getChoixMaximum()
    {
        return $this->choixMaximum;
    }



    /**
     * @param int $choixMaximum
     *
     * @return ScenarioLien
     */
    public function setChoixMaximum($choixMaximum)
    {
        $this->choixMaximum = $choixMaximum;

        return $this;
    }
}