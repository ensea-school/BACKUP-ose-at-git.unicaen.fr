<?php

namespace Application\Provider\Chargens;

use Application\Connecteur\Bdd\BddConnecteurAwareTrait;
use Application\Entity\Chargens\Noeud;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\SourceAwareTrait;

class ChargensProvider
{
    use BddConnecteurAwareTrait;
    use SourceAwareTrait;
    use ContextAwareTrait;

    /**
     * @var Scenario
     */
    private $scenario;

    /**
     * @var NoeudProvider
     */
    private $noeuds;

    /**
     * @var LienProvider
     */
    private $liens;

    /**
     * @var ScenarioNoeudProvider
     */
    private $scenarioNoeuds;

    /**
     * @var ScenarioLienProvider
     */
    private $scenarioLiens;

    /**
     * @var EntityProvider
     */
    private $entities;



    /**
     * @return NoeudProvider
     */
    public function getNoeuds()
    {
        if (empty($this->noeuds)) {
            $this->noeuds = new NoeudProvider($this);
        }

        return $this->noeuds;
    }



    /**
     * @return LienProvider
     */
    public function getLiens()
    {
        if (empty($this->liens)) {
            $this->liens = new LienProvider($this);
        }

        return $this->liens;
    }



    /**
     * @return ScenarioNoeudProvider
     */
    public function getScenarioNoeuds()
    {
        if (empty($this->scenarioNoeuds)) {
            $this->scenarioNoeuds = new ScenarioNoeudProvider($this);
        }

        return $this->scenarioNoeuds;
    }



    /**
     * @return ScenarioLienProvider
     */
    public function getScenarioLiens()
    {
        if (empty($this->scenarioLiens)) {
            $this->scenarioLiens = new ScenarioLienProvider($this);
        }

        return $this->scenarioLiens;
    }



    /**
     * @return EntityProvider
     */
    public function getEntities()
    {
        if (empty($this->entities)) {
            $this->entities = new EntityProvider($this);
        }

        return $this->entities;
    }



    /**
     * @param Etape $etape
     *
     * @return Noeud
     */
    public function loadEtape(Etape $etape)
    {
        $this->getEntities()->add($etape);

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
            $subTreeIds = $this->loadSubTreeIds($noeudId);
            $this->getNoeuds()->getNoeuds($subTreeIds['noeuds']);
            $this->getLiens()->getLiens($subTreeIds['liens']);
        } else {
            return null;
        }
    }



    /**
     * @param $noeudId
     *
     * @return Noeud|null
     */
    public function loadNoeud($noeudId)
    {
        return $this->getNoeuds()->getNoeud($noeudId);
    }



    /**
     * @param Noeud|integer $noeud
     *
     * @return array
     */
    private function loadSubTreeIds($noeud)
    {
        $liens  = [];
        $noeuds = [];

        if ($noeud instanceof Noeud) {
            $noeud = $noeud->getId();
        }

        if (!$noeud) {
            throw new \Exception('Le sous-arbre du noeud ne peut pas être chargé car le noeud n\'est pas transmis');
        }

        $this->getBdd()->execPlsql('OSE_CHARGENS.set_noeud(:noeud);', ['noeud' => $noeud]);
        $sql = "
        SELECT
          l.id,
          l.noeud_sup_id,
          l.noeud_inf_id
        FROM
          lien l
        WHERE
          1 = OSE_DIVERS.COMPRISE_ENTRE( l.histo_creation, l.histo_destruction )
        CONNECT BY
          l.noeud_sup_id = PRIOR l.noeud_inf_id
        START WITH
          l.noeud_sup_id = OSE_CHARGENS.GET_NOEUD
        ";

        $relations = $this->getBdd()->fetch($sql);
        foreach ($relations as $relation) {
            $liens[(int)$relation['ID']]            = true;
            $noeuds[(int)$relation['NOEUD_SUP_ID']] = true;
            $noeuds[(int)$relation['NOEUD_INF_ID']] = true;
        }

        return [
            'liens'  => array_keys($liens),
            'noeuds' => array_keys($noeuds),
        ];
    }



    /**
     * @return Scenario|null
     * @throws \Exception
     */
    public function getScenario()
    {
        return $this->scenario;
    }



    /**
     * @param Scenario $scenario
     *
     * @return ChargeProvider
     */
    public function setScenario(Scenario $scenario = null)
    {
        $this->scenario = $scenario;

        if ($scenario) {
            $this->getEntities()->add($scenario);
            $this->getBdd()->execPlsql('OSE_CHARGENS.set_scenario(:scenario);', ['scenario' => $scenario]);
            $this->getScenarioNoeuds()->load();
            $this->getScenarioLiens()->load();
        } else {
            $this->getScenarioNoeuds()->clear();
            $this->getScenarioLiens()->clear();
        }

        return $this;
    }



    /**
     * @return array
     */
    public function getDiagrammeData()
    {
        return [
            'hetd'   => 0,
            'noeuds' => $this->getNoeuds()->getDiagrammeData(),
            'liens'  => $this->getLiens()->getDiagrammeData(),
        ];
    }



    /**
     *
     * @return array
     */
    public function getDbData()
    {
        $data = [];
        $this->getNoeuds()->getDbData($data);
        $this->getLiens()->getDbData($data);

        return $data;
    }



    /**
     * @param array $old
     * @param array $new
     *
     * @return array
     */
    public function diffDbData(array $old, array $new)
    {
        $tables = array_unique(array_merge(array_keys($old), array_keys($new)));
        $res    = [];
        foreach ($tables as $table) {
            $ot = array_key_exists($table, $old) ? $old[$table] : [];
            $nt = array_key_exists($table, $new) ? $new[$table] : [];

            $keys = array_unique(array_merge(array_keys($ot), array_keys($nt)));
            foreach ($keys as $key) {
                $otk    = array_key_exists($key, $ot) ? $ot[$key] : ['object' => null, 'data' => []];
                $ntk    = array_key_exists($key, $nt) ? $nt[$key] : ['object' => null, 'data' => []];
                $object = array_key_exists('object', $ntk) ? $ntk['object'] : $otk['object'];

                $data = array_diff_assoc($ntk['data'], $otk['data']);
                if (!$object->getId() && !empty($data)) {
                    $data = array_merge($otk['data'], $ntk['data']);
                }

                if (!empty($data)) {
                    $res[$table][$key] = [
                        'object' => $object,
                        'data'   => $data,
                    ];
                }
            }
        }

        return $res;
    }



    /**
     * @param array $data
     *
     * @return $this
     */
    public function updateDiagrammeData(array $data)
    {
        $oldData = $this->getDbData();

        if (isset($data['noeuds'])) $this->getNoeuds()->updateDiagrammeData($data['noeuds']);
        if (isset($data['liens'])) $this->getLiens()->updateDiagrammeData($data['liens']);

        $newData = $this->getDbData();

        $diffData = $this->diffDbData($oldData, $newData);
        $this->persist($diffData);

        if ($this->getScenario()) {
            $this->setScenario($this->getScenario());
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function persist(array $data)
    {
        $this->getNoeuds()->persist($data);
        $this->getLiens()->persist($data);

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