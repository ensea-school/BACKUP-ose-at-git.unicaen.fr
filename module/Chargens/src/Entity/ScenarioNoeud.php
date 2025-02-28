<?php

namespace Chargens\Entity;

use Chargens\Entity\Db\Scenario;
use Chargens\Entity\Db\ScenarioAwareTrait;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\TypeHeures;
use OffreFormation\Entity\Db\TypeIntervention;

class ScenarioNoeud
{
    use ScenarioAwareTrait;
    use NoeudAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $assiduite = 1.0;

    /**
     * @var ScenarioNoeudEffectif[][]
     */
    private $effectif = [];

    /**
     * @var ScenarioNoeudSeuil[]
     */
    private $seuil = [];



    /**
     * ScenarioNoeud constructor.
     *
     * @param Noeud    $noeud
     * @param Scenario $scenario
     */
    public function __construct(Noeud $noeud, Scenario $scenario)
    {
        $this->setNoeud($noeud);
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
     * @return ScenarioNoeud
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * @return ScenarioNoeud
     */
    public function setAssiduite($assiduite)
    {
        $this->assiduite = $assiduite;

        return $this;
    }



    /**
     * @param TypeHeures $typeHeures
     * @param Etape      $etape
     *
     * @return bool
     */
    public function hasEffectif(TypeHeures $typeHeures, Etape $etape)
    {
        return isset($this->effectif[$typeHeures->getId()][$etape->getId()]);
    }



    /**
     * @param TypeHeures|null $typeHeures
     * @param Etape|null      $etape
     *
     * @return ScenarioNoeudEffectif|ScenarioNoeudEffectif[]
     */
    public function getEffectif(TypeHeures $typeHeures = null, Etape $etape = null)
    {
        if (!$typeHeures) {
            return $this->effectif;
        }

        if ($etape) {
            if (!$this->hasEffectif($typeHeures, $etape)) {
                $this->effectif[$typeHeures->getId()][$etape->getId()] = new ScenarioNoeudEffectif($this, $typeHeures, $etape);
            }

            return $this->effectif[$typeHeures->getId()][$etape->getId()];
        } else {
            return isset($this->effectif[$typeHeures->getId()]) ? $this->effectif[$typeHeures->getId()] : [];
        }
    }



    /**
     * @param ScenarioNoeudEffectif $effectif
     *
     * @return $this
     * @throws \Exception
     */
    public function addEffectif(ScenarioNoeudEffectif $effectif)
    {
        $effectif->setScenarioNoeud($this);
        $this->effectif[$effectif->getTypeHeures()->getId()][$effectif->getEtape()->getId()] = $effectif;

        return $this;
    }



    /**
     * @param TypeHeures|null $typeHeures
     * @param Etape|null      $etape
     *
     * @return $this
     */
    public function removeEffectif(TypeHeures $typeHeures = null, Etape $etape = null)
    {
        if ($typeHeures && $etape) {
            unset($this->effectif[$typeHeures->getId()][$etape->getId()]);
        } elseif ($typeHeures) {
            unset($this->effectif[$typeHeures->getId()]);
        } else {
            $this->effectif = [];
        }

        return $this;
    }



    /**
     * @param TypeIntervention $typeIntervention
     *
     * @return bool
     */
    public function hasSeuil(TypeIntervention $typeIntervention)
    {
        return array_key_exists($typeIntervention->getId(), $this->seuil);
    }



    /**
     * @param TypeIntervention|null $typeIntervention
     *
     * @return ScenarioNoeudSeuil|ScenarioNoeudSeuil[]
     */
    public function getSeuil(TypeIntervention $typeIntervention = null)
    {
        if (!$typeIntervention) {
            return $this->seuil;
        }

        if (!$this->hasSeuil($typeIntervention)) {
            $this->seuil[$typeIntervention->getId()] = new ScenarioNoeudSeuil($this, $typeIntervention);
        }

        return $this->seuil[$typeIntervention->getId()];
    }



    /**
     * @param ScenarioNoeudSeuil $seuil
     *
     * @return $this
     * @throws \Exception
     */
    public function addSeuil(ScenarioNoeudSeuil $seuil)
    {
        if (!$seuil->getTypeIntervention()) {
            throw new \Exception('Le type d\'heures de l\'effectif n\'a pas été défini');
        }

        $seuil->setScenarioNoeud($this);
        $this->seuil[$seuil->getTypeIntervention()->getId()] = $seuil;

        return $this;
    }



    /**
     * @return $this
     */
    public function removeSeuil(TypeIntervention $typeIntervention = null)
    {
        if ($typeIntervention) {
            unset($this->seuil[$typeIntervention->getId()]);
        } else {
            $this->seuil = [];
        }

        return $this;
    }



    /**
     * @param TypeIntervention $typeIntervention
     *
     * @return bool
     */
    public function hasSeuilParDefaut(TypeIntervention $typeIntervention)
    {
        return $this->getNoeud()->hasSeuilParDefaut($this->getScenario(), $typeIntervention);
    }



    /**
     * @param TypeIntervention|null $typeIntervention
     *
     * @return array|integer|null
     */
    public function getSeuilParDefaut(TypeIntervention $typeIntervention = null)
    {
        return $this->getNoeud()->getSeuilParDefaut($this->getScenario(), $typeIntervention);
    }



    /**
     *
     * @return float|null
     */
    public function getHeures()
    {
        return $this->getNoeud()->getHeures($this->getScenario());
    }



    /**
     *
     * @return float|null
     */
    public function getHetd()
    {
        return $this->getNoeud()->getHetd($this->getScenario());
    }
}