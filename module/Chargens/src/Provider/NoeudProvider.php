<?php

namespace Chargens\Provider;

use Application\Provider\Privilege\Privileges;
use Chargens\Entity\Noeud;
use Chargens\Hydrator\NoeudDbHydrator;
use Chargens\Hydrator\NoeudDiagrammeHydrator;
use OffreFormation\Entity\Db\ElementPedagogique;

class NoeudProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;

    /**
     * @var Noeud[]
     */
    private $noeuds = [];



    /**
     * NoeudProvider constructor.
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
        $this->noeuds = [];

        return $this;
    }



    /**
     * @return Noeud[]
     */
    public function getNoeuds(array $noeudIds = [])
    {
        if (empty($noeudIds)) {
            return $this->noeuds;
        }

        $noeudsToLoad = [];
        foreach ($noeudIds as $nid) {
            if (!$this->hasNoeud($nid)) {
                $noeudsToLoad[] = $nid;
            }
        }
        $this->loadNoeuds($noeudsToLoad);

        $noeuds = [];
        foreach ($noeudIds as $nid) {
            $noeuds[$nid] = $this->getNoeud($nid);
        }

        return $noeuds;
    }



    /**
     * @param $noeudId
     *
     * @return bool
     */
    public function hasNoeud($noeudId)
    {
        return array_key_exists($noeudId, $this->noeuds);
    }



    /**
     * @param $noeudId
     *
     * @return Noeud|null
     */
    public function getNoeud($noeudId)
    {
        if (!$this->hasNoeud($noeudId)) {
            $this->loadNoeuds([$noeudId]);
        }

        if ($this->hasNoeud($noeudId)) {
            return $this->noeuds[$noeudId];
        } else {
            return null;
        }
    }



    /**
     * @param array $noeudIds
     *
     * @throws \Exception
     */
    private function loadNoeuds(array $noeudIds)
    {
        $data        = $this->getNoeudsData($noeudIds);
        $hydrator    = new NoeudDbHydrator();
        $elementsIds = [];

        foreach ($data as $d) {
            $noeud = new Noeud($this->chargens);
            $hydrator->hydrate($d, $noeud);
            $this->initRules($noeud);

            if (!$noeud->getId()) {
                throw new \Exception('ID non mentionné pour le noeud');
            }

            if ($elementId = $noeud->getElementPedagogique(false)) {
                $elementsIds[] = $elementId;
            }

            $this->noeuds[$noeud->getId()] = $noeud;
        }

        $this->chargens->getEntities()->load(ElementPedagogique::class, $elementsIds);
    }



    /**
     * @return $this
     */
    public function loadSeuilsHeures()
    {
        $noeudIds = array_keys($this->noeuds);

        $data     = $this->getNoeudsSeuilsHeuresData($noeudIds);
        $hydrator = new NoeudDbHydrator();

        foreach ($this->noeuds as $noeud) {
            if (isset($data[$noeud->getId()])) {
                $hydrator->hydradeSeuilHeures($data[$noeud->getId()], $noeud);
            }
        }

        return $this;
    }



    /**
     * @param array $noeudIds
     *
     * @return array
     */
    private function getNoeudsData(array $noeudIds)
    {
        $anneeId = $this->chargens->getServiceContext()->getAnnee()->getId();

        if (empty($noeudIds)) return [];

        /* Récup des noeuds */
        $ids   = implode(',', $noeudIds);
        $sql   = "
        SELECT 
          n.id, 
          n.code, 
          n.libelle, 
          n.liste, 
          n.etape_id, 
          n.element_pedagogique_id,
          n.structure_id,
          0 nb_liens_sup,
          0 nb_liens_inf
        FROM 
          noeud n
        WHERE 
          n.histo_destruction IS NULL
          AND n.annee_id = :annee
          AND n.id IN (" . $ids . ")
        ORDER BY
            n.code
        ";
        $qdata = $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['annee' => $anneeId]);
        $data  = [];
        foreach ($qdata as $d) {
            $data[(int)$d['ID']] = $d;
        }

        if (empty($data)) return $data;

        $ids = implode(',', array_keys($data));

        /* Récup des liens associés (nombre uniquement) */
        $sql    = "
        SELECT 
          l.id, 
          l.noeud_sup_id, 
          l.noeud_inf_id 
        FROM 
          lien l 
        WHERE
          l.histo_destruction IS NULL
          AND (
            l.noeud_sup_id IN (" . $ids . ")
            OR l.noeud_inf_id IN (" . $ids . ")
          )
        ";
        $dliens = $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        foreach ($dliens as $lien) {
            $noeudSupId = (int)$lien['NOEUD_SUP_ID'];
            $noeudInfId = (int)$lien['NOEUD_INF_ID'];
            if (array_key_exists($noeudSupId, $data)) {
                $data[$noeudSupId]['NB_LIENS_INF'] += 1;
            }
            if (array_key_exists($noeudInfId, $data)) {
                $data[$noeudInfId]['NB_LIENS_SUP'] += 1;
            }
        }

        $sql = "
        SELECT
          n.id noeud_id,
          ti.id type_intervention_id
        FROM
          noeud n 
          JOIN volume_horaire_ens vhe ON 
            vhe.element_pedagogique_id = n.element_pedagogique_id
            AND vhe.heures > 0
            AND vhe.histo_destruction IS NULL
            AND n.annee_id = " . $this->chargens->getServiceContext()->getAnnee()->getId() . "
          JOIN type_intervention ti ON
            ti.id = vhe.type_intervention_id
            AND ti.histo_destruction IS NULL
        WHERE
          n.element_pedagogique_id IS NOT NULL
          AND n.id IN (" . $ids . ")

        UNION

        SELECT
          n.id noeud_id,
          ti.id type_intervention_id
        FROM
          noeud n 
          JOIN etape e ON e.id = n.etape_id
          JOIN type_intervention ti ON ti.histo_destruction IS NULL
          
          LEFT JOIN type_intervention_structure tis ON 
            tis.structure_id = e.structure_id 
            AND tis.type_intervention_id = ti.id
            AND tis.histo_destruction IS NULL

        WHERE
          n.id IN (" . $ids . ")
          AND 1 = COALESCE(tis.visible, ti.visible)
        ";
        $dti = $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        foreach ($dti as $d) {
            $nid = $d['NOEUD_ID'];

            if (!isset($data[$nid])) {
                $data[$nid]['TYPE_INTERVENTION_IDS'] = [];
            }
            $data[$nid]['TYPE_INTERVENTION_IDS'][] = (int)$d['TYPE_INTERVENTION_ID'];
        }

        return $data;
    }



    /**
     * @param array $noeudIds
     *
     * @return array
     */
    private function getNoeudsSeuilsHeuresData(array $noeudIds)
    {
        if (empty($noeudIds)) return [];
        $ids = implode(',', $noeudIds);

        $data = [];

        $sql = "
        SELECT
          csdd.noeud_id,
          csdd.scenario_id,
          csdd.type_intervention_id,
          csdd.dedoublement
        FROM
          tbl_chargens csdd
        WHERE
          csdd.noeud_id IN (" . $ids . ")
        ";

        $csdd = $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        foreach ($csdd as $d) {
            $nid              = $d['NOEUD_ID'];
            $scenario         = (int)$d['SCENARIO_ID'];
            $typeIntervention = (int)$d['TYPE_INTERVENTION_ID'];
            $dedoublement     = (int)$d['DEDOUBLEMENT'];

            $data[$nid]['SEUILS_PAR_DEFAUT'][$scenario][$typeIntervention] = $dedoublement;
        }

        $params = [
            'annee'    => $this->chargens->getServiceContext()->getAnnee()->getId(),
            'scenario' => $this->chargens->getScenario()->getId(),
        ];

        $sql = "
        SELECT
          noeud_id noeud_id,
          scenario_id,
          SUM(heures) heures,
          SUM(hetd) hetd
        FROM
          tbl_chargens cph
        WHERE
          cph.annee_id = :annee
          AND cph.scenario_id = :scenario
          AND cph.noeud_id IN (" . $ids . ")
        GROUP BY
          noeud_id,
          scenario_id

        UNION

        SELECT
          n.id noeud_id,
          scenario_id,
          SUM(heures) heures,
          SUM(hetd) hetd
        FROM
          noeud n 
          JOIN tbl_chargens cph ON cph.etape_id = n.etape_id
        WHERE
          cph.annee_id = :annee
          AND cph.scenario_id = :scenario
          AND n.etape_id IS NOT NULL
          AND n.id IN (" . $ids . ")
        GROUP BY
          n.id,
          scenario_id
        ";

        $csdd = $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        foreach ($csdd as $d) {
            $nid      = $d['NOEUD_ID'];
            $scenario = (int)$d['SCENARIO_ID'];
            $heures   = (float)$d['HEURES'];
            $hetd     = (float)$d['HETD'];

            $data[$nid]['HEURES'][$scenario] = $heures;
            $data[$nid]['HETD'][$scenario]   = $hetd;
        }


        return $data;
    }



    /**
     * @param $data
     *
     * @return $this
     */
    public function getDbData(&$data)
    {
        $scenarioNoeuds = [];

        foreach ($this->noeuds as $noeud) {
            $scenarioNoeuds[] = $noeud->getScenarioNoeud();
        }

        $this->chargens->getScenarioNoeuds()->getDbData($data, $scenarioNoeuds);

        return $this;
    }



    /**
     * @return array
     */
    public function getDiagrammeData()
    {
        $hydrator = new NoeudDiagrammeHydrator($this->chargens);

        $data = [];
        foreach ($this->noeuds as $noeud) {
            $data[$noeud->getId()] = $hydrator->extract($noeud);
        }

        return $data;
    }



    /**
     * @param array $data
     *
     * @return $this
     */
    public function updateDiagrammeData(array $data)
    {
        $hydrator = new NoeudDiagrammeHydrator($this->chargens);

        foreach ($data as $d) {
            $noeudId = (int)$d['id'];

            $noeud = $this->getNoeud($noeudId);
            $hydrator->hydrate($d, $noeud);
        }

        return $this;
    }



    /**
     * @param Noeud $noeud
     *
     * @return $this
     */
    protected function initRules(Noeud $noeud)
    {
        $cStructure = $this->chargens->getServiceContext()->getStructure();
        $canEdit    = false;
        if ($cStructure) {
            $nStructure = $noeud->getStructure(true);

            if (!$nStructure || $nStructure->inStructure($cStructure)) {
                $canEdit = true;
            }
        } else {
            $canEdit = true;
        }

        if ($canEdit) {
            $sa = $this->chargens->getServiceAuthorize();

            $a = $sa->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_FORMATION_ASSIDUITE_EDITION));
            $noeud->setCanEditAssiduite($a);

            $e = $sa->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_FORMATION_EFFECTIFS_EDITION));
            $noeud->setCanEditEffectifs($e);

            $s = $sa->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_FORMATION_SEUILS_EDITION));
            $noeud->setCanEditSeuils($s);
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function persist(array $data)
    {
        $this->chargens->getScenarioNoeuds()->persist($data);

        return $this;
    }



    /**
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @since PHP 5.6.0
     *
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [];
    }

}