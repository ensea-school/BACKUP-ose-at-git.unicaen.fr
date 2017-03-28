<?php

namespace Application\Entity\Chargens;

use Application\Entity\Chargens\Traits\NoeudAwareTrait;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\Traits\ScenarioAwareTrait;
use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\TypeIntervention;

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
     * @var float
     */
    private $heures;

    /**
     * @var ScenarioNoeudEffectif[][]
     */
    private $effectif = [];

    /**
     * @var ScenarioNoeudSeuil[]
     */
    private $seuil = [];

    /**
     * @var array
     */
    private $seuilParDefaut = [];



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
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * @param float $heures
     *
     * @return ScenarioNoeud
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

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
        return array_key_exists($typeIntervention->getId(), $this->seuilParDefaut);
    }



    /**
     * @param TypeIntervention|null $typeIntervention
     *
     * @return array|integer|null
     */
    public function getSeuilParDefaut(TypeIntervention $typeIntervention = null)
    {
        if (!$typeIntervention) {
            return $this->seuilParDefaut;
        }

        if (!$this->hasSeuilParDefaut($typeIntervention)) {
            return null;
        } else {
            return $this->seuilParDefaut[$typeIntervention->getId()];
        }
    }



    /**
     * @param TypeIntervention $typeIntervention
     * @param integer|null     $seuilParDefaut
     *
     * @return $this
     */
    public function setSeuilParDefaut(TypeIntervention $typeIntervention, $seuilParDefaut)
    {
        if (null === $seuilParDefaut) {
            unset($this->seuilParDefaut[$typeIntervention->getId()]);
        } else {
            $this->seuilParDefaut[$typeIntervention->getId()] = $seuilParDefaut;
        }

        return $this;
    }
}