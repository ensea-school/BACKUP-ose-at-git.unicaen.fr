<?php

namespace Chargens\Provider;

use Application\Provider\Privileges;
use Chargens\Entity\Lien;
use Chargens\Entity\Noeud;
use Chargens\Hydrator\LienDbHydrator;
use Chargens\Hydrator\LienDiagrammeHydrator;


class LienProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;

    /**
     * @var Lien[]
     */
    private $liens = [];



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
     * @return $this
     */
    public function clear()
    {
        $this->liens = [];

        return $this;
    }



    /**
     * @return Lien[]
     */
    public function getLiens(array $lienIds = [])
    {
        if (empty($lienIds)) {
            return $this->liens;
        }

        $liensToLoad = [];
        foreach ($lienIds as $lid) {
            if (!$this->hasLien($lid)) {
                $liensToLoad[] = $lid;
            }
        }
        $this->loadLiens($liensToLoad);

        $liens = [];
        foreach ($lienIds as $lid) {
            $liens[$lid] = $this->getLien($lid);
        }

        return $liens;
    }



    /**
     * @param Noeud|int $noeud
     *
     * @return Lien[]
     */
    public function getLiensByNoeudSup($noeud)
    {
        if ($noeud instanceof Noeud) {
            $noeud = $noeud->getId();
        }

        $liens = [];
        foreach ($this->liens as $lien) {
            if ($lien->getNoeudSup(false) == $noeud) {
                $liens[$lien->getId()] = $lien;
            }
        }

        return $liens;
    }



    /**
     * @param Noeud|int $noeud
     *
     * @return Lien[]
     */
    public function getLiensByNoeudInf($noeud)
    {
        if ($noeud instanceof Noeud) {
            $noeud = $noeud->getId();
        }

        $liens = [];
        foreach ($this->liens as $lien) {
            if ($lien->getNoeudInf(false) == $noeud) {
                $liens[$lien->getId()] = $lien;
            }
        }

        return $liens;
    }



    /**
     * @param $lienId
     *
     * @return bool
     */
    public function hasLien($lienId)
    {
        return array_key_exists($lienId, $this->liens);
    }



    /**
     * @param $lienId
     *
     * @return Lien|null
     */
    public function getLien($lienId)
    {
        if (!$this->hasLien($lienId)) {
            $this->loadLiens([$lienId]);
        }

        if ($this->hasLien($lienId)) {
            return $this->liens[$lienId];
        } else {
            return null;
        }
    }



    /**
     * @param array $lienIds
     *
     * @throws \Exception
     */
    private function loadLiens(array $lienIds)
    {
        $data     = $this->getLiensData($lienIds);
        $hydrator = new LienDbHydrator();

        foreach ($data as $d) {
            $lien = new Lien($this->chargens);
            $hydrator->hydrate($d, $lien);
            $this->initRules($lien);

            if (!$lien->getId()) {
                throw new \Exception('ID non mentionné pour le lien');
            }

            $this->liens[$lien->getId()] = $lien;
        }
    }



    /**
     * @param array $lienIds
     *
     * @return array
     */
    private function getLiensData(array $lienIds)
    {
        if (empty($lienIds)) return [];

        $ids = implode(',', $lienIds);

        $sql  = "
                SELECT 
                  id, noeud_sup_id, noeud_inf_id, structure_id
                FROM 
                  LIEN l 
                WHERE 
                  l.id IN ($ids)
                  AND l.histo_destruction IS NULL
                ";
        $data = $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql);
        $res  = [];
        foreach ($data as $d) {
            $res[(int)$d['ID']] = $d;
        }

        return $res;
    }



    /**
     * @param $data
     *
     * @return $this
     */
    public function getDbData(&$data)
    {
        $scenarioLiens = [];

        foreach ($this->liens as $lien) {
            $scenarioLiens[] = $lien->getScenarioLien();
        }

        $this->chargens->getScenarioLiens()->getDbData($data, $scenarioLiens);

        return $this;
    }



    /**
     * @return array
     */
    public function getDiagrammeData()
    {
        $hydrator = new LienDiagrammeHydrator();

        $data = [];
        foreach ($this->liens as $lien) {
            $data[$lien->getId()] = $hydrator->extract($lien);
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
        $hydrator = new LienDiagrammeHydrator();

        foreach ($data as $d) {
            $lienId = (int)$d['id'];

            $lien = $this->getLien($lienId);
            $hydrator->hydrate($d, $lien);
        }

        return $this;
    }



    /**
     * @param Lien $lien
     *
     * @return $this
     */
    protected function initRules(Lien $lien)
    {
        $cStructure = $this->chargens->getServiceContext()->getStructure();
        $canEdit    = false;
        if ($cStructure) {
            $lStructure = $lien->getStructure(true);

            if (!$lStructure || $lStructure->inStructure($cStructure)) {
                $canEdit = true;
            }
        } else {
            $canEdit = true;
        }

        if ($canEdit) {
            $sa = $this->chargens->getServiceAuthorize();

            $a = $sa->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_FORMATION_ACTIF_EDITION));
            $lien->setCanEditActif($a);

            $p = $sa->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_FORMATION_POIDS_EDITION));
            $lien->setCanEditPoids($p);

            $c = $sa->isAllowed(Privileges::getResourceId(Privileges::CHARGENS_FORMATION_CHOIX_EDITION));
            $lien->setCanEditChoix($c);
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function persist(array $data)
    {
        $this->chargens->getScenarioLiens()->persist($data);

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