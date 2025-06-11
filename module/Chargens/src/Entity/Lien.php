<?php

namespace Chargens\Entity;

use Chargens\Entity\Db\Scenario;
use Chargens\Provider\ChargensProvider;
use Lieu\Entity\Db\Structure;

class Lien
{
    /**
     * @var ChargensProvider
     */
    private $provider;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $noeudSup;

    /**
     * @var integer
     */
    private $noeudInf;

    /**
     * @var integer
     */
    private $structure = null;

    /**
     * @var boolean
     */
    private $canEditActif = false;

    /**
     * @var boolean
     */
    private $canEditPoids = false;

    /**
     * @var boolean
     */
    private $canEditChoix = false;

    /**
     * @var ScenarioLien[]
     */
    private $scenarioLien = [];



    /**
     * Lien constructor.
     *
     * @param ChargensProvider $provider
     */
    public function __construct(ChargensProvider $provider)
    {
        $this->provider = $provider;
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
     * @return Lien
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @param bool $object
     *
     * @return Noeud|int
     */
    public function getNoeudSup($object = true)
    {
        return $object ? $this->provider->getNoeuds()->getNoeud($this->noeudSup) : $this->noeudSup;
    }



    /**
     * @param Noeud|integer $noeudSup
     *
     * @return $this
     */
    public function setNoeudSup($noeudSup)
    {
        if ($noeudSup instanceof Noeud) {
            $noeudSup = $noeudSup->getId();
        }
        $this->noeudSup = $noeudSup;

        return $this;
    }



    /**
     * @param bool $object
     *
     * @return Noeud|int
     */
    public function getNoeudInf($object = true)
    {
        return $object ? $this->provider->getNoeuds()->getNoeud($this->noeudInf) : $this->noeudInf;
    }



    /**
     * @param Noeud|integer $noeudInf
     *
     * @return $this
     */
    public function setNoeudInf($noeudInf)
    {
        if ($noeudInf instanceof Noeud) {
            $noeudInf = $noeudInf->getId();
        }
        $this->noeudInf = $noeudInf;

        return $this;
    }



    /**
     * @param bool $object
     *
     * @return Structure|int
     */
    public function getStructure($object = true)
    {
        return $object ? $this->provider->getEntities()->get(Structure::class, $this->structure) : $this->structure;
    }



    /**
     * @param Structure|int $structure
     *
     * @return self
     */
    public function setStructure($structure)
    {
        if ($structure instanceof Structure) {
            $structure = $structure->getId();
        }
        $this->structure = $structure;

        return $this;
    }



    /**
     * @return bool
     */
    public function isCanEditActif()
    {
        return $this->canEditActif;
    }



    /**
     * @param bool $canEditActif
     *
     * @return Lien
     */
    public function setCanEditActif($canEditActif)
    {
        $this->canEditActif = $canEditActif;

        return $this;
    }



    /**
     * @return bool
     */
    public function isCanEditPoids()
    {
        return $this->canEditPoids;
    }



    /**
     * @param bool $canEditPoids
     *
     * @return Lien
     */
    public function setCanEditPoids($canEditPoids)
    {
        $this->canEditPoids = $canEditPoids;

        return $this;
    }



    /**
     * @return bool
     */
    public function isCanEditChoix()
    {
        return $this->canEditChoix;
    }



    /**
     * @param bool $canEditChoix
     *
     * @return Lien
     */
    public function setCanEditChoix($canEditChoix)
    {
        $this->canEditChoix = $canEditChoix;

        return $this;
    }



    /**
     * @param Scenario|null $scenario
     *
     * @return bool
     */
    public function hasScenarioLien(?Scenario $scenario = null)
    {
        if (!$scenario) {
            $scenario = $this->provider->getScenario();
        }

        if (!$scenario) {
            throw new \Exception('Le scénario n\'a pas été défini');
        }

        return array_key_exists($scenario->getId(), $this->scenarioLien);
    }



    /**
     * @param Scenario|null $scenario
     *
     * @return ScenarioLien
     */
    public function getScenarioLien(?Scenario $scenario = null)
    {
        if (!$scenario) {
            $scenario = $this->provider->getScenario();
        }

        if (!$scenario) {
            throw new \Exception('Le scénario n\'a pas été défini');
        }

        if (!array_key_exists($scenario->getId(), $this->scenarioLien)) {
            $this->scenarioLien[$scenario->getId()] = new ScenarioLien($this, $scenario);
        }

        return $this->scenarioLien[$scenario->getId()];
    }



    /**
     * @param ScenarioLien $scenarioLien
     *
     * @return $this
     * @throws \Exception
     */
    public function addScenarioLien(ScenarioLien $scenarioLien)
    {
        if (!$scenarioLien->getScenario()) {
            throw new \Exception('Le scénario du lien n\'a pas été défini');
        }

        $scenarioLien->setLien($this);
        $this->scenarioLien[$scenarioLien->getScenario()->getId()] = $scenarioLien;

        return $this;
    }



    /**
     * @return $this
     */
    public function removeScenarioLien(?Scenario $scenario = null)
    {
        if ($scenario) {
            unset($this->scenarioLien[$scenario->getId()]);
        } else {
            $this->scenarioLien = [];
        }

        return $this;
    }
}