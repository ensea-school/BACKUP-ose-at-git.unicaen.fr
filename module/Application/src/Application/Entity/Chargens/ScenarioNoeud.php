<?php

namespace Application\Entity\Chargens;

use Application\Entity\Chargens\Traits\NoeudAwareTrait;
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
    private $hetd;

    /**
     * @var ScenarioNoeudEffectif[]
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
     * @return float
     */
    public function getHetd()
    {
        return $this->hetd;
    }



    /**
     * @param float $hetd
     *
     * @return ScenarioNoeud
     */
    public function setHetd($hetd)
    {
        $this->hetd = $hetd;

        return $this;
    }



    /**
     * @param TypeHeures $typeHeures
     *
     * @return bool
     */
    public function hasEffectif(TypeHeures $typeHeures)
    {
        return array_key_exists($typeHeures->getId(), $this->effectif);
    }



    /**
     * @param TypeHeures|null $typeHeures
     *
     * @return ScenarioNoeudEffectif|ScenarioNoeudEffectif[]
     */
    public function getEffectif(TypeHeures $typeHeures = null)
    {
        if (!$typeHeures) {
            return $this->effectif;
        }

        if (!$this->hasEffectif($typeHeures)) {
            $this->effectif[$typeHeures->getId()] = new ScenarioNoeudEffectif($this, $typeHeures);
        }

        return $this->effectif[$typeHeures->getId()];
    }



    /**
     * @param ScenarioNoeudEffectif $effectif
     *
     * @return $this
     * @throws \Exception
     */
    public function addEffectif(ScenarioNoeudEffectif $effectif)
    {
        if (!$effectif->getTypeHeures()) {
            throw new \Exception('Le type d\'heures de l\'effectif n\'a pas été défini');
        }

        $effectif->setScenarioNoeud($this);
        $this->effectif[$effectif->getTypeHeures()->getId()] = $effectif;

        return $this;
    }



    /**
     * @return $this
     */
    public function removeEffectif(TypeHeures $typeHeures = null)
    {
        if ($typeHeures) {
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

}