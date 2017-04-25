<?php

namespace Application\Provider\Chargens;

use Application\Entity\Chargens\Noeud;
use Application\Entity\Chargens\ScenarioNoeud;
use Application\Entity\Chargens\ScenarioNoeudEffectif;
use Application\Entity\Chargens\ScenarioNoeudSeuil;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\TypeHeures;
use Application\Entity\Db\TypeIntervention;
use Application\Hydrator\Chargens\ScenarioNoeudDbHydrator;
use Application\Hydrator\Chargens\ScenarioNoeudEffectifDbHydrator;
use Application\Hydrator\Chargens\ScenarioNoeudSeuilDbHydrator;

class ScenarioNoeudProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;



    /**
     * ScenarioNoeudProvider constructor.
     *
     * @param ChargensProvider $chargens
     */
    public function __construct(ChargensProvider $chargens)
    {
        $this->chargens = $chargens;
    }



    /**
     * @return $this
     */
    public function clear()
    {
        $noeuds = $this->chargens->getNoeuds()->getNoeuds();
        foreach ($noeuds as $noeud) {
            $noeud->removeScenarioNoeud();
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function load()
    {
        $noeudIds = array_keys($this->chargens->getNoeuds()->getNoeuds());
        if (empty($noeudIds)) return $this;
        $noeudIds = implode(',', $noeudIds);

        /** @var ScenarioNoeud[] $scenarioNoeuds */
        $scenarioNoeuds = [];

        $snd         = $this->getScenarioNoeudsData($noeudIds);
        $sndHydrator = new ScenarioNoeudDbHydrator();
        foreach ($snd as $d) {
            $noeudId    = (int)$d['NOEUD_ID'];
            $scenarioId = (int)$d['SCENARIO_ID'];

            $noeud    = $this->chargens->getNoeuds()->getNoeud($noeudId);
            $scenario = $this->chargens->getEntities()->get(Scenario::class, $scenarioId);

            $scenarioNoeud = new ScenarioNoeud($noeud, $scenario);
            $sndHydrator->hydrate($d, $scenarioNoeud);

            $scenarioNoeuds[$scenarioNoeud->getId()] = $scenarioNoeud;
            $noeud->addScenarioNoeud($scenarioNoeud);
        }

        $sne         = $this->getScenarioNoeudEffectifsData($noeudIds);
        $sneHydrator = new ScenarioNoeudEffectifDbHydrator();
        foreach ($sne as $d) {
            $scenarioNoeudId = (int)$d['SCENARIO_NOEUD_ID'];
            $typeHeuresId    = (int)$d['TYPE_HEURES_ID'];
            $etapeId         = (int)$d['ETAPE_ID'];

            $scenarioNoeud = $scenarioNoeuds[$scenarioNoeudId];
            $typeHeures    = $this->chargens->getEntities()->get(TypeHeures::class, $typeHeuresId);
            $etape         = $this->chargens->getEntities()->get(Etape::class, $etapeId);

            $scenarioNoeudEffectif = new ScenarioNoeudEffectif($scenarioNoeud, $typeHeures, $etape);
            $sneHydrator->hydrate($d, $scenarioNoeudEffectif);

            $scenarioNoeud->addEffectif($scenarioNoeudEffectif);
        }

        $sns         = $this->getScenarioNoeudSeuilsData($noeudIds);
        $snsHydrator = new ScenarioNoeudSeuilDbHydrator();
        foreach ($sns as $d) {
            $scenarioNoeudId    = (int)$d['SCENARIO_NOEUD_ID'];
            $typeInterventionId = (int)$d['TYPE_INTERVENTION_ID'];

            $scenarioNoeud    = $scenarioNoeuds[$scenarioNoeudId];
            $typeIntervention = $this->chargens->getEntities()->get(TypeIntervention::class, $typeInterventionId);

            $scenarioNoeudSeuil = new ScenarioNoeudSeuil($scenarioNoeud, $typeIntervention);
            $snsHydrator->hydrate($d, $scenarioNoeudSeuil);

            $scenarioNoeud->addSeuil($scenarioNoeudSeuil);
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function persist(array $data)
    {
        if (array_key_exists('SCENARIO_NOEUD', $data)) {
            foreach ($data['SCENARIO_NOEUD'] as $d) {
                $object  = $d['object'];
                $changes = $d['data'];
                $this->persistScenarioNoeud($object, $changes);
            }
        }

        if (array_key_exists('SCENARIO_NOEUD_EFFECTIF', $data)) {
            foreach ($data['SCENARIO_NOEUD_EFFECTIF'] as $d) {
                $object  = $d['object'];
                $changes = $d['data'];
                $this->persistScenarioNoeudEffectif($object, $changes);
            }
        }

        if (array_key_exists('SCENARIO_NOEUD_SEUIL', $data)) {
            foreach ($data['SCENARIO_NOEUD_SEUIL'] as $d) {
                $object  = $d['object'];
                $changes = $d['data'];
                $this->persistScenarioNoeudSeuil($object, $changes);
            }
        }

        return $this;
    }



    /**
     * @param ScenarioNoeud $scenarioNoeud
     * @param array         $changes
     *
     * @return $this
     */
    private function persistScenarioNoeud(ScenarioNoeud $scenarioNoeud, array $changes = [])
    {
        $bdd         = $this->chargens->getBdd();
        $conn        = $bdd->getEntityManager()->getConnection();
        $userId      = $this->chargens->getServiceContext()->getUtilisateur()->getId();
        $date        = $conn->convertToDatabaseValue(new \DateTime(), 'datetime');
        $oseSourceId = $this->chargens->getServiceSource()->getOse()->getId();

        unset($changes['HEURES']); // non mis à jour depuis l'interface !!
        if ($scenarioNoeud->getId()) {
            unset($changes['ID']);
            $changes['SOURCE_ID']             = $oseSourceId;
            $changes['HISTO_MODIFICATEUR_ID'] = $userId;
            $changes['HISTO_MODIFICATION']    = $date;
            $conn->update('SCENARIO_NOEUD', $changes, ['ID' => $scenarioNoeud->getId()]);
        } else {
            $scenarioNoeud->setId($bdd->sequenceNextVal('SCENARIO_NOEUD_ID_SEQ'));
            $changes['ID']                    = $scenarioNoeud->getId();
            $changes['SCENARIO_ID']           = $scenarioNoeud->getScenario()->getId();
            $changes['NOEUD_ID']              = $scenarioNoeud->getNoeud()->getId();
            $changes['SOURCE_ID']             = $oseSourceId;
            $changes['SOURCE_CODE']           = uniqid('ose-');
            $changes['HISTO_CREATEUR_ID']     = $userId;
            $changes['HISTO_CREATION']        = $date;
            $changes['HISTO_MODIFICATEUR_ID'] = $userId;
            $changes['HISTO_MODIFICATION']    = $date;

            if (!isset($changes['ASSIDUITE'])) {
                $changes['ASSIDUITE'] = 1.0; // par défaut
            }

            $conn->insert('SCENARIO_NOEUD', $changes);
        }

        if (isset($changes['ASSIDUITE'])) {
            $liensSup = $scenarioNoeud->getNoeud()->getLiensSup();
            foreach ($liensSup as $lienSup) {
                $nListe         = $lienSup->getNoeudSup();
                $nListeLiensSup = $nListe->getLiensSup();
                foreach ($nListeLiensSup as $lienSup2) {
                    $this->chargens->getScenarioNoeuds()->calculSousEffectifsByNoeud($lienSup2->getNoeudSup());
                }
            }
        }

        return $this;
    }



    /**
     * @param ScenarioNoeudEffectif $scenarioNoeudEffectif
     * @param array                 $changes
     *
     * @return $this
     */
    private function persistScenarioNoeudEffectif(ScenarioNoeudEffectif $scenarioNoeudEffectif, array $changes)
    {
        $bdd  = $this->chargens->getBdd();
        $conn = $bdd->getEntityManager()->getConnection();

        if ($scenarioNoeudEffectif->getId()) {
            unset($changes['ID']);
            $changes['EFFECTIF'] = (int)$changes['EFFECTIF'];
            $conn->update('SCENARIO_NOEUD_EFFECTIF', $changes, ['ID' => $scenarioNoeudEffectif->getId()]);
        } else {
            if (!$scenarioNoeudEffectif->getScenarioNoeud()->getId()) {
                $this->persistScenarioNoeud($scenarioNoeudEffectif->getScenarioNoeud());
            }

            $scenarioNoeudEffectif->setId($bdd->sequenceNextVal('SCENARIO_NOEUD_EFFECTIF_ID_SEQ'));
            $changes['ID']                = $scenarioNoeudEffectif->getId();
            $changes['SCENARIO_NOEUD_ID'] = $scenarioNoeudEffectif->getScenarioNoeud()->getId();
            $changes['TYPE_HEURES_ID']    = $scenarioNoeudEffectif->getTypeHeures()->getId();
            $changes['ETAPE_ID']          = $scenarioNoeudEffectif->getEtape()->getId();
            $conn->insert('SCENARIO_NOEUD_EFFECTIF', $changes);
        }

        if (isset($changes['EFFECTIF'])) {
            $this->calculSousEffectifs($scenarioNoeudEffectif);
        }

        return $this;
    }



    /**
     * @param Noeud $noeud
     *
     * @return $this
     */
    public function calculSousEffectifsByNoeud(Noeud $noeud)
    {
        $sn   = $noeud->getScenarioNoeud();
        $effs = $sn->getEffectif();
        foreach ($effs as $efs) {
            /** @var ScenarioNoeudEffectif $eff */
            foreach ($efs as $eff) {
                $this->chargens->getScenarioNoeuds()->calculSousEffectifs($eff);
            }
        }

        return $this;
    }



    /**
     * @param ScenarioNoeudEffectif $scenarioNoeudEffectif
     *
     * @return $this
     */
    public function calculSousEffectifs(ScenarioNoeudEffectif $scenarioNoeudEffectif)
    {
        $bdd = $this->chargens->getBdd();
        $bdd->execPlsql('OSE_CHARGENS.CALC_SUB_EFFECTIF2(:noeud, :scenario, :typeHeures, :etape);', [
            'noeud'         => $scenarioNoeudEffectif->getScenarioNoeud()->getNoeud()->getId(),
            'scenario'      => $scenarioNoeudEffectif->getScenarioNoeud()->getScenario()->getId(),
            'typeHeures'    => $scenarioNoeudEffectif->getTypeHeures()->getId(),
            'etape'         => $scenarioNoeudEffectif->getEtape()->getId()
        ]);

        return $this;
    }



    /**
     * @param ScenarioNoeudSeuil $scenarioNoeudSeuil
     * @param array              $changes
     *
     * @return $this
     */
    private function persistScenarioNoeudSeuil(ScenarioNoeudSeuil $scenarioNoeudSeuil, array $changes)
    {
        $bdd  = $this->chargens->getBdd();
        $conn = $bdd->getEntityManager()->getConnection();

        $noData = $scenarioNoeudSeuil->getOuverture() === null
            && $scenarioNoeudSeuil->getDedoublement() === null
            && $scenarioNoeudSeuil->getAssiduite() === null;

        if ($scenarioNoeudSeuil->getId()) {
            if ($noData) {
                $conn->delete('SCENARIO_NOEUD_SEUIL', ['ID' => $scenarioNoeudSeuil->getId()]);
            } else {
                unset($changes['ID']);
                $conn->update('SCENARIO_NOEUD_SEUIL', $changes, ['ID' => $scenarioNoeudSeuil->getId()]);
            }
        } else {
            if (!$scenarioNoeudSeuil->getScenarioNoeud()->getId()) {
                $this->persistScenarioNoeud($scenarioNoeudSeuil->getScenarioNoeud());
            }

            $scenarioNoeudSeuil->setId($bdd->sequenceNextVal('SCENARIO_NOEUD_SEUIL_ID_SEQ'));
            $changes['ID']                   = $scenarioNoeudSeuil->getId();
            $changes['SCENARIO_NOEUD_ID']    = $scenarioNoeudSeuil->getScenarioNoeud()->getId();
            $changes['TYPE_INTERVENTION_ID'] = $scenarioNoeudSeuil->getTypeIntervention()->getId();
            $conn->insert('SCENARIO_NOEUD_SEUIL', $changes);
        }

        return $this;
    }



    /**
     * @return $this
     */
    private function getScenarioNoeudsData($noeudIds)
    {
        $sql = "
        SELECT
          sn.id,
          sn.scenario_id,
          sn.noeud_id,
          sn.assiduite
        FROM
          scenario_noeud sn
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( sn.histo_creation, sn.histo_destruction )
          AND sn.noeud_id IN ($noeudIds)
        ";

        return $this->chargens->getBdd()->fetch($sql);
    }



    /**
     * @param string $noeudsIds
     *
     * @return array
     */
    private function getScenarioNoeudEffectifsData($noeudIds)
    {
        $sql = "
        SELECT
          sne.id,
          sne.scenario_noeud_id,
          sne.type_heures_id,
          sne.etape_id,
          sne.effectif
        FROM
          scenario_noeud_effectif sne
          JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( sn.histo_creation, sn.histo_destruction )
          AND sn.noeud_id IN ($noeudIds)
        ";

        return $this->chargens->getBdd()->fetch($sql);
    }



    /**
     * @param string $noeudsIds
     *
     * @return array
     */
    private function getScenarioNoeudSeuilsData($noeudIds)
    {
        $sql = "
        SELECT
          sns.id,
          sns.scenario_noeud_id,
          sns.type_intervention_id,
          sns.ouverture,
          sns.dedoublement,
          sns.assiduite
        FROM
          scenario_noeud_seuil sns 
          JOIN scenario_noeud sn ON sn.id = sns.scenario_noeud_id
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( sn.histo_creation, sn.histo_destruction )
          AND sn.noeud_id IN ($noeudIds)
        ";

        return $this->chargens->getBdd()->fetch($sql);
    }



    /**
     * @param array $data
     * @params ScenarioNoeud[] $scenarioNoeuds
     *
     * @return $this
     */
    public function getDbData(&$data, array $scenarioNoeuds)
    {
        $snHydrator    = new ScenarioNoeudDbHydrator();
        $effHydrator   = new ScenarioNoeudEffectifDbHydrator();
        $seuilHydrator = new ScenarioNoeudSeuilDbHydrator();

        /** @var ScenarioNoeud $scenarioNoeud */
        foreach ($scenarioNoeuds as $scenarioNoeud) {
            $snKey = $scenarioNoeud->getNoeud()->getId() . '-' . $scenarioNoeud->getScenario()->getId();

            $data['SCENARIO_NOEUD'][$snKey] = [
                'object' => $scenarioNoeud,
                'data'   => $snHydrator->extract($scenarioNoeud),
            ];

            $effectifs = $scenarioNoeud->getEffectif();
            foreach ($effectifs as $effs) {
                foreach ($effs as $effectif) {
                    $effKey = $snKey . '-' . $effectif->getTypeHeures()->getId() . '-' . $effectif->getEtape()->getId();

                    $data['SCENARIO_NOEUD_EFFECTIF'][$effKey] = [
                        'object' => $effectif,
                        'data'   => $effHydrator->extract($effectif),
                    ];
                }
            }

            $seuils = $scenarioNoeud->getSeuil();
            foreach ($seuils as $seuil) {
                $seuilKey = $snKey . '-' . $seuil->getTypeIntervention()->getId();

                $data['SCENARIO_NOEUD_SEUIL'][$seuilKey] = [
                    'object' => $seuil,
                    'data'   => $seuilHydrator->extract($seuil),
                ];
            }
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