<?php

namespace Application\Entity\Charge;

use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\TypeIntervention;
use Application\Provider\Charge\ChargeProvider;

class ScenarioNoeud
{
    /**
     * @var ChargeProvider
     */
    private $provider;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $changes = [];



    public function __construct(ChargeProvider $provider, array $data)
    {
        $this->provider = $provider;
        $this->data     = $data;
    }



    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->data['ID'];
    }



    /**
     * @param bool $object
     *
     * @return Noeud|int
     */
    public function getNoeud($object = true)
    {
        $id = (int)$this->data['NOEUD_ID'];

        return $object ? $this->provider->getNoeud($id) : $id;
    }



    /**
     * @param bool $object
     *
     * @return \Application\Entity\Db\Scenario|int
     */
    public function getScenario($object = true)
    {
        $id = (int)$this->data['SCENARIO_ID'];

        return $object ? $this->provider->getScenario($id) : $id;
    }



    /**
     * @return integer
     */
    public function getGroupes()
    {
        return (int)$this->data['GROUPES'];
    }



    /**
     * @param integer $groupes
     *
     * @return $this
     */
    public function setGroupes($groupes)
    {
        $this->data['GROUPES']    = (string)(int)$groupes;
        $this->changes['GROUPES'] = $this->data['GROUPES'];

        return $this;
    }



    /**
     * @return integer
     */
    public function getChoixMinimum()
    {
        return (int)$this->data['CHOIX_MINIMUM'];
    }



    /**
     * @param integer $choixMinimum
     *
     * @return $this
     */
    public function setChoixMinimum($choixMinimum)
    {
        $this->data['CHOIX_MINIMUM']    = (string)(int)$choixMinimum;
        $this->changes['CHOIX_MINIMUM'] = $this->data['CHOIX_MINIMUM'];

        return $this;
    }



    /**
     * @return integer
     */
    public function getChoixMaximum()
    {
        return (int)$this->data['CHOIX_MAXIMUM'];
    }



    /**
     * @param integer $choixMaximum
     *
     * @return $this
     */
    public function setChoixMaximum($choixMaximum)
    {
        $this->data['CHOIX_MAXIMUM']    = (string)(int)$choixMaximum;
        $this->changes['CHOIX_MAXIMUM'] = $this->data['CHOIX_MAXIMUM'];

        return $this;
    }



    /**
     * @return float
     */
    public function getAssiduite()
    {
        return (float)$this->data['ASSIDUITE'];
    }



    /**
     * @param $assiduite
     *
     * @return $this
     */
    public function setAssiduite($assiduite)
    {
        $this->data['ASSIDUITE']    = (string)(float)$assiduite;
        $this->changes['ASSIDUITE'] = $this->data['ASSIDUITE'];

        return $this;
    }



    /**
     * @param TypeHeures|integer|null $typeHeures
     *
     * @return float
     */
    public function getEffectif($typeHeures = null)
    {
        if ($typeHeures instanceof TypeHeures) {
            $thid = $typeHeures->getId();
        } else {
            $thid = (int)$typeHeures;
        }
        if (0 == $thid) return isset($this->data['EFFECTIFS']) ? $this->data['EFFECTIFS'] : [];

        return isset($this->data['EFFECTIFS'][$thid]) ? (float)$this->data['EFFECTIFS'][$thid] : null;
    }



    /**
     * @param TypeHeures|integer $typeHeures
     * @param float              $effectif
     *
     * @return $this
     */
    public function setEffectif($typeHeures, $effectif)
    {
        if ($typeHeures instanceof TypeHeures) {
            $thid = $typeHeures->getId();
        } else {
            $thid = (int)$typeHeures;
        }

        $this->data['EFFECTIFS'][$thid]    = (string)(float)$effectif;
        $this->changes['EFFECTIFS'][$thid] = $this->data['EFFECTIFS'][$thid];

        return $this;
    }



    /**
     * @param TypeIntervention|integer|null $typeIntervention
     *
     * @return integer
     */
    public function getSeuilOuverture($typeIntervention = null)
    {
        if ($typeIntervention instanceof TypeIntervention) {
            $tiid = $typeIntervention->getId();
        } else {
            $tiid = (int)$typeIntervention;
        }
        if (0 == $tiid) return isset($this->data['SEUILS_OUVERTURE']) ? $this->data['SEUILS_OUVERTURE'] : [];

        return isset($this->data['SEUILS_OUVERTURE'][$tiid]) ? (int)$this->data['SEUILS_OUVERTURE'][$tiid] : null;
    }



    /**
     * @param TypeIntervention|integer $typeIntervention
     * @param integer                  $seuilOuverture
     *
     * @return $this
     */
    public function setSeuilOuverture($typeIntervention, $seuilOuverture)
    {
        if ($typeIntervention instanceof TypeIntervention) {
            $tiid = $typeIntervention->getId();
        } else {
            $tiid = (int)$typeIntervention;
        }

        $this->data['SEUILS_OUVERTURE'][$tiid]    = (string)(int)$seuilOuverture;
        $this->changes['SEUILS_OUVERTURE'][$tiid] = $this->data['SEUILS_OUVERTURE'][$tiid];

        return $this;
    }



    /**
     * @param TypeIntervention|integer|null $typeIntervention
     *
     * @return integer
     */
    public function getSeuilDedoublement($typeIntervention = null)
    {
        if ($typeIntervention instanceof TypeIntervention) {
            $tiid = $typeIntervention->getId();
        } else {
            $tiid = (int)$typeIntervention;
        }
        if (0 == $tiid) return isset($this->data['SEUILS_DEDOUBLEMENT']) ? $this->data['SEUILS_DEDOUBLEMENT'] : [];

        return isset($this->data['SEUILS_DEDOUBLEMENT'][$tiid]) ? (int)$this->data['SEUILS_DEDOUBLEMENT'][$tiid] : null;
    }



    /**
     * @param TypeIntervention|integer $typeIntervention
     * @param integer                  $seuilDedoublement
     *
     * @return $this
     */
    public function setSeuilDedoublement($typeIntervention, $seuilDedoublement)
    {
        if ($typeIntervention instanceof TypeIntervention) {
            $tiid = $typeIntervention->getId();
        } else {
            $tiid = (int)$typeIntervention;
        }

        $this->data['SEUILS_DEDOUBLEMENT'][$tiid]    = (string)(int)$seuilDedoublement;
        $this->changes['SEUILS_DEDOUBLEMENT'][$tiid] = $this->data['SEUILS_DEDOUBLEMENT'][$tiid];

        return $this;
    }
}