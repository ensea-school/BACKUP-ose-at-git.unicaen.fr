<?php

namespace Application\Provider\Chargens;

use Application\Entity\Db\GroupeTypeFormation;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervention;

class SeuilProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;

    /**
     * @var array
     */
    private $seuils = null;



    /**
     * LienProvider constructor.
     *
     * @param ChargensProvider $chargens
     */
    public function __construct(ChargensProvider $chargens)
    {
        $this->chargens = $chargens;
    }



    /**
     * @return array
     */
    public function getSeuils()
    {
        if (null === $this->seuils) {
            $this->loadSeuils();
        }

        return $this->seuils;
    }



    /**
     * @param $scenario
     * @param $typeIntervention
     * @param $structure
     * @param $groupeTypeFormation
     *
     * @return null|integer
     */
    public function getSeuil($scenario, $typeIntervention, $structure, $groupeTypeFormation)
    {
        if ($scenario instanceof Scenario){
            $scenario = $scenario->getId();
        }else{
            $scenario = (int)$scenario;
        }

        if ($typeIntervention instanceof TypeIntervention){
            $typeIntervention = $typeIntervention->getId();
        }else{
            $typeIntervention = (int)$typeIntervention;
        }

        if ($structure instanceof Structure){
            $structure = $structure->getId();
        }else{
            $structure = (int)$structure;
        }

        if ($groupeTypeFormation instanceof GroupeTypeFormation){
            $groupeTypeFormation = $groupeTypeFormation->getId();
        }else{
            $groupeTypeFormation = (int)$groupeTypeFormation;
        }

        $seuils = $this->getSeuils();
        if (!isset($seuils[$scenario][$typeIntervention])){
            return null;
        }
        $seuils = $seuils[$scenario][$typeIntervention];

        $seuil = null;
        if (isset($seuils[0][0])){
            $seuil = $seuils[0][0];
        }
        if (isset($seuils[0][$groupeTypeFormation])){
            $seuil = $seuils[0][$groupeTypeFormation];
        }
        if (isset($seuils[$structure][0])){
            $seuil = $seuils[$structure][0];
        }
        if (isset($seuils[$structure][$groupeTypeFormation])){
            $seuil = $seuils[$structure][$groupeTypeFormation];
        }

        return $seuil;
    }



    /**
     * @return $this
     */
    private function loadSeuils()
    {
        $this->seuils = [];

        $sql  = "
          SELECT
            sc.scenario_id,
            sc.type_intervention_id,
            sc.structure_id,
            sc.groupe_type_formation_id,
            sc.dedoublement
          FROM
            seuil_charge sc
          WHERE
            sc.dedoublement IS NOT NULL
            AND 1 = OSE_DIVERS.COMPRISE_ENTRE( sc.histo_creation, sc.histo_destruction)
        ";
        $data = $this->chargens->getBdd()->fetch($sql);

        foreach ($data as $d) {
            $scenario            = (int)$d['SCENARIO_ID'];
            $typeIntervention    = (int)$d['TYPE_INTERVENTION_ID'];
            $structure           = (int)$d['STRUCTURE_ID'];
            $groupeTypeFormation = (int)$d['GROUPE_TYPE_FORMATION_ID'];
            $dedoublement        = (int)$d['DEDOUBLEMENT'];

            $this->seuils[$scenario][$typeIntervention][$structure][$groupeTypeFormation] = $dedoublement;
        }

        return $this;
    }



    /**
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @since PHP 5.6.0
     *
     * @return array
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [];
    }

}