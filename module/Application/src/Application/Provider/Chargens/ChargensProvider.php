<?php

namespace Application\Provider\Chargens;

use Application\Connecteur\Bdd\BddConnecteurAwareTrait;
use Application\Entity\Chargens\Lien;
use Application\Entity\Chargens\Noeud;
use Application\Entity\Chargens\ScenarioLien;
use Application\Entity\Chargens\ScenarioNoeud;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\TypeIntervention;

class ChargensProvider
{
    use BddConnecteurAwareTrait;

    /**
     * @var Scenario
     */
    private $scenario;

    /**
     * @var Scenario[]
     */
    private $scenarios = [];

    /**
     * @var Noeud[]
     */
    private $noeuds = [];

    /**
     * @var Lien[]
     */
    private $liensById = [];

    /**
     * @var Lien[][]
     */
    private $liensByNoeudInf = [];

    /**
     * @var Lien[][]
     */
    private $liensByNoeudSup = [];

    /**
     * @var $scenarioNoeud [][]
     */
    private $scenarioNoeud = [];

    /**
     * @var $scenarioLien [][]
     */
    private $scenarioLien = [];



    /**
     * @param Etape $etape
     *
     * @return Noeud
     */
    public function loadEtape(Etape $etape)
    {
        $sql = "
        SELECT 
          id 
        FROM 
          noeud n 
        WHERE 
          n.etape_id = :etape
          AND 1 = OSE_DIVERS.COMPRISE_ENTRE(n.histo_creation, n.histo_destruction)
          AND ROWNUM = 1
        ";

        $noeudId = $this->getBdd()->fetchOne($sql, ['etape' => $etape], 'ID', 'int');

        if ($noeudId) {
            $liens = $this->loadLiens($noeudId, 'recursive');
            $nids  = [];
            foreach ($liens as $lien) {
                $nids[$lien->getNoeudInf(false)] = true;
                $nids[$lien->getNoeudSup(false)] = true;
            }

            $noeuds = $this->loadNoeuds(array_keys($nids));
            $this->loadLiens(array_keys($noeuds), 'direct'); // Préchargement des liens au cas où!!

            return $this->getNoeud($noeudId);
        } else {
            return null;
        }
    }



    private function loadNoeud($noeudId)
    {
        $this->loadLiens($noeudId, 'recursive');

        $sql  = "SELECT id, code, libelle, etape_id, element_pedagogique_id FROM noeud WHERE id IN (:noeud)";
        $data = $this->getBdd()->fetchOne($sql, ['noeud' => $noeudId]);

        $id = (int)$data['ID'];

        $noeud             = new Noeud($this, $data);
        $this->noeuds[$id] = $noeud;

        return $noeud;
    }



    private function loadNoeuds(array $noeudIds)
    {
        $ids  = implode(',', $noeudIds);
        $sql  = "SELECT id, code, libelle, etape_id, element_pedagogique_id FROM noeud WHERE id IN (" . $ids . ")";
        $data = $this->getBdd()->fetch($sql, [], 'ID');

        /** @var Noeud[] $noeuds */
        $noeuds = [];

        $sql = "
        SELECT
          n.id noeud_id,
          vhe.type_intervention_id
        FROM
          noeud n 
          JOIN volume_horaire_ens vhe ON 
            vhe.element_pedagogique_id = n.element_pedagogique_id
            AND vhe.heures > 0
            AND 1 = OSE_DIVERS.COMPRISE_ENTRE( vhe.histo_creation, vhe.histo_destruction )
        WHERE
          n.element_pedagogique_id IS NOT NULL
          AND n.id IN (" . implode(',', array_keys($data)) . ")
        ";
        $dti = $this->getBdd()->fetch($sql);
        foreach ($dti as $d) {
            $nid = $d['NOEUD_ID'];

            if (!isset($data[$nid])) {
                $data[$nid]['TYPES_INTERVENTION'] = [];
            }
            $data[$nid]['TYPES_INTERVENTION'][] = (int)$d['TYPE_INTERVENTION_ID'];
        }

        foreach ($data as $d) {
            $noeud                         = new Noeud($this, $d);
            $this->noeuds[$noeud->getId()] = $noeud;
            $noeuds[$noeud->getId()]       = $noeud;
        }

        return $noeuds;
    }



    /**
     * @param $noeudId
     *
     * @return Lien[]
     */
    private function loadLiens($noeudId, $type = 'recursive')
    {
        $created = [];

        switch ($type) {
            case 'recursive':
                $this->getBdd()->execPlsql('ose_charge.set_noeud(:noeud);', ['noeud' => $noeudId]);
                $liens = $this->getBdd()->fetch('SELECT * FROM V_CHARGE_LIEN');
            break;
            case 'direct':
                if (is_array($noeudId)) {
                    $ids = implode(',', $noeudId);
                } else {
                    $ids = (int)$noeudId; // protection!!
                }

                $sql   = "
                SELECT 
                  id, noeud_sup_id, noeud_inf_id 
                FROM 
                  LIEN l 
                WHERE 
                  (noeud_sup_id IN ($ids) OR noeud_sup_id IN ($ids))
                  AND 1 = OSE_DIVERS.COMPRISE_ENTRE( l.histo_creation, l.histo_destruction )
                ";
                $liens = $this->getBdd()->fetch($sql);
            break;
        }

        foreach ($liens as $l) {
            $id = (int)$l['ID'];
            if (!isset($this->liensById[$id])) {
                $lien                                = new Lien($this, $l);
                $nSup                                = $lien->getNoeudSup(false);
                $nInf                                = $lien->getNoeudInf(false);
                $this->liensByNoeudSup[$nSup][$nInf] = $lien;
                $this->liensByNoeudInf[$nInf][$nSup] = $lien;
                $this->liensById[$id]                = $lien;
                $created[$id]                        = $lien;
            }
        }

        return $created;
    }



    /**
     * @param Scenario|null $scenario
     *
     * @return $this
     */
    public function loadScenario(Scenario $scenario = null)
    {
        if ($scenario) $this->setScenario($scenario);

        if ($this->scenario) {
            $this->resetScenario();
            $this->loadScenarioNoeud();
            $this->loadScenarioLien();
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function resetScenario()
    {
        $this->scenarioNoeud = [];
        $this->scenarioLien  = [];

        return $this;
    }



    private function loadScenarioNoeud()
    {
        $noeuds = implode(', ', array_keys($this->noeuds));

        $data = [];

        $sql = "
        SELECT
          sn.id,
          sn.scenario_id,
          sn.noeud_id,
          sn.choix_minimum,
          sn.choix_maximum,
          sn.assiduite
        FROM
          scenario_noeud sn
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( sn.histo_creation, sn.histo_destruction )
          AND sn.noeud_id IN ($noeuds)
        ";
        $snd = $this->getBdd()->fetch($sql);
        foreach ($snd as $d) {
            $nid              = (int)$d['NOEUD_ID'];
            $sid              = (int)$d['SCENARIO_ID'];
            $data[$nid][$sid] = $d;
        }

        $sql  = "
        SELECT
          sn.scenario_id,
          sn.noeud_id,
          sne.type_heures_id,
          sne.effectif
        FROM
          scenario_noeud sn
          JOIN scenario_noeud_effectif sne ON sne.scenario_noeud_id = sn.id
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( sn.histo_creation, sn.histo_destruction )
          AND sn.noeud_id IN ($noeuds)
        ";
        $data = $this->getBdd()->fetch($sql);
        foreach ($data as $d) {
            $nid = (int)$d['NOEUD_ID'];
            $sid = (int)$d['SCENARIO_ID'];
            $tid = (int)$d['TYPE_HEURES_ID'];

            $data[$nid][$sid]['EFFECTIFS'][$tid] = $d['EFFECTIF'];
        }

        $sql  = "
        SELECT
          sn.scenario_id,
          sn.noeud_id,
          sns.type_intervention_id,
          sns.ouverture,
          sns.dedoublement
        FROM
          scenario_noeud sn
          JOIN scenario_noeud_seuil sns ON sns.scenario_noeud_id = sn.id
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( sn.histo_creation, sn.histo_destruction )
          AND sn.noeud_id IN ($noeuds)
        ";
        $data = $this->getBdd()->fetch($sql);
        foreach ($data as $d) {
            $nid = (int)$d['NOEUD_ID'];
            $sid = (int)$d['SCENARIO_ID'];
            $tid = (int)$d['TYPE_INTERVENTION_ID'];

            $data[$nid][$sid]['SEUILS_OUVERTURE'][$tid]    = $d['OUVERTURE'];
            $data[$nid][$sid]['SEUILS_DEDOUBLEMENT'][$tid] = $d['DEDOUBLEMENT'];
        }

        foreach ($data as $nid => $d2) {
            foreach ($d2 as $sid => $d3) {
                $this->newScenarioNoeud($d3);
            }
        }

        return $this;
    }



    /**
     * @param array $data
     *
     * @return ScenarioNoeud
     */
    private function newScenarioNoeud(array $data = [])
    {
        if (!array_key_exists('ID', $data)) $data['ID'] = null;
        if (!array_key_exists('SCENARIO_ID', $data)) $data['SCENARIO_ID'] = $this->getScenario() ? $this->getScenario()->getId() : null;
        if (!array_key_exists('CHOIX_MINIMUM', $data)) $data['CHOIX_MINIMUM'] = '0';
        if (!array_key_exists('CHOIX_MAXIMUM', $data)) $data['CHOIX_MAXIMUM'] = '0';
        if (!array_key_exists('ASSIDUITE', $data)) $data['ASSIDUITE'] = '1';
        if (!array_key_exists('EFFECTIFS', $data)) $data['EFFECTIFS'] = [];
        if (!array_key_exists('SEUILS_OUVERTURE', $data)) $data['SEUILS_OUVERTURE'] = [];
        if (!array_key_exists('SEUILS_DEDOUBLEMENT', $data)) $data['SEUILS_DEDOUBLEMENT'] = [];

        $sn                                                                 = new ScenarioNoeud($this, $data);
        $this->scenarioNoeud[$sn->getNoeud(false)][$sn->getScenario(false)] = $sn;

        return $sn;
    }



    /**
     * @return $this
     */
    private function loadScenarioLien()
    {
        $liens = implode(', ', array_keys($this->liensById));

        $sql  = "
        SELECT
          sl.id,
          sl.scenario_id,
          sl.lien_id,
          sl.actif,
          sl.poids
        FROM
          scenario_lien sl
          JOIN scenario s ON s.id = sl.scenario_id
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( sl.histo_creation, sl.histo_destruction )
          AND sl.lien_id IN ($liens)
        ";
        $data = $this->getBdd()->fetch($sql);
        foreach ($data as $d) {
            $this->newScenarioLien($d);
        }

        return $this;
    }



    /**
     * @param array $data
     *
     * @return ScenarioLien
     */
    private function newScenarioLien(array $data = [])
    {
        if (!array_key_exists('ID', $data)) $data['ID'] = null;
        if (!array_key_exists('SCENARIO_ID', $data)) $data['SCENARIO_ID'] = $this->getScenario() ? $this->getScenario()->getId() : null;
        if (!array_key_exists('ACTIF', $data)) $data['ACTIF'] = '1';
        if (!array_key_exists('POIDS', $data)) $data['POIDS'] = '1';

        $sn                                                               = new ScenarioLien($this, $data);
        $this->scenarioLien[$sn->getLien(false)][$sn->getScenario(false)] = $sn;

        return $sn;
    }



    /**
     * @param $noeudId
     *
     * @return Noeud
     */
    public function getNoeud($noeudId)
    {
        if (!isset($this->noeuds[(int)$noeudId])) {
            $this->loadNoeud($noeudId);
        }

        return $this->noeuds[$noeudId];
    }



    /**
     * @param $lienId
     *
     * @return Lien
     */
    public function getLien($lienId)
    {
        return $this->liensById[$lienId];
    }



    /**
     * @param $etapeId
     *
     * @return Etape
     */
    public function getEtape($etapeId)
    {
        return $this->getBdd()->getEntityManager()->getRepository(Etape::class)->get($etapeId);
    }



    /**
     * @param $elementPedagogiqueId
     *
     * @return ElementPedagogique
     */
    public function getElementPedagogique($elementPedagogiqueId)
    {
        return $this->getBdd()->getEntityManager()->getRepository(ElementPedagogique::class)->get($elementPedagogiqueId);
    }



    /**
     * @param $typeInterventionId
     *
     * @return TypeIntervention
     */
    public function getTypeIntervention($typeInterventionId)
    {
        return $this->getBdd()->getEntityManager()->getRepository(TypeIntervention::class)->get($typeInterventionId);
    }



    /**
     * @param Noeud $noeud
     *
     * @return Lien[]|array
     */
    public function getLiensByNoeudSup(Noeud $noeud)
    {
        $nid = $noeud->getId();

        if (!isset($this->liensByNoeudSup[$nid])) {
            return [];
        } else {
            return $this->liensByNoeudSup[$nid];
        }
    }



    /**
     * @param Noeud $noeud
     *
     * @return Lien[]|array
     */
    public function getLiensByNoeudInf(Noeud $noeud)
    {
        $nid = $noeud->getId();

        if (!isset($this->liensByNoeudInf[$nid])) {
            return [];
        } else {
            return $this->liensByNoeudInf[$nid];
        }
    }



    public function getScenarioNoeud(Noeud $noeud)
    {
        $scenario = $this->getScenario();

        if (!$scenario || !isset($this->scenarioNoeud[$noeud->getId()])) {
            return $this->newScenarioNoeud(['NOEUD_ID' => $noeud->getId()]);
        }

        $sns = $this->scenarioNoeud[$noeud->getId()];
        if (isset($sns[$scenario->getId()])) {
            return $sns[$scenario->getId()]; // si on a le bon, on le retourne directement
        }

        /** @var ScenarioNoeud $scenarioNoeud */
        foreach ($sns as $scenarioNoeud) {
            $sn = $scenarioNoeud->getScenario();
            if ($sn->isDefinitif() && $sn->isReel() == $scenario->isReel()) {
                return $scenarioNoeud;
            }
        }

        return $this->newScenarioNoeud(['NOEUD_ID' => $noeud->getId()]);
    }



    public function getScenarioLien(Lien $lien)
    {
        $scenario = $this->getScenario();

        if (!$scenario || !isset($this->scenarioLien[$lien->getId()])) {
            return $this->newScenarioLien(['LIEN_ID' => $lien->getId()]);
        }

        $sls = $this->scenarioLien[$lien->getId()];
        if (isset($sls[$scenario->getId()])) {
            return $sls[$scenario->getId()]; // si on a le bon, on le retourne directement
        }

        /** @var ScenarioLien $scenarioLien */
        foreach ($sls as $scenarioLien) {
            $sl = $scenarioLien->getScenario();
            if ($sl->isDefinitif() && $sl->isReel() == $scenario->isReel()) {
                return $scenarioLien;
            }
        }

        return $this->newScenarioLien(['LIEN_ID' => $lien->getId()]);
    }



    /**
     * @return array
     */
    public function noeudsToArray()
    {
        $result = [];
        foreach ($this->noeuds as $noeud) {
            $result[$noeud->getId()] = $noeud->toArray();
        }

        return $result;
    }



    /**
     * @return array
     */
    public function liensToArray()
    {
        $result = [];
        foreach ($this->liensById as $lien) {
            $result[$lien->getId()] = $lien->toArray();
        }

        return $result;
    }



    /**
     * @return $this
     */
    public function sauvegarder()
    {
        var_dump($this->changes);

        return $this;
    }



    /**
     * @param null|int $id
     *
     * @return Scenario
     * @throws \Exception
     */
    public function getScenario($id = null)
    {
        if (!$id) {
            return $this->scenario;
        } else {
            if (!isset($this->scenarios[$id])) {
                /** @var Scenario $scenario */
                $scenario = $this->getBdd()->getEntityManager()->getRepository(Scenario::class)->find($id);
                if ($scenario) {
                    $this->scenarios[$scenario->getId()] = $scenario;
                } else {
                    throw new \Exception('Scénario ID=' . $id . ' introuvable');
                }
            }

            return $this->scenarios[$id];
        }
    }



    /**
     * @param Scenario $scenario
     *
     * @return ChargeProvider
     */
    public function setScenario($scenario)
    {
        $this->scenario = $scenario;
        $this->getBdd()->execPlsql('ose_charge.set_scenario(:scenario);', ['scenario' => $scenario]);

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