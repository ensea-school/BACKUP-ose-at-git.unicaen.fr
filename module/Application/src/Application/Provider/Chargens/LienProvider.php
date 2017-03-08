<?php

namespace Application\Provider\Chargens;

use Application\Entity\Chargens\Lien;
use Application\Entity\Chargens\Noeud;
use Application\Hydrator\Chargens\LienDbHydrator;
use Application\Hydrator\Chargens\LienDiagrammeHydrator;


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
                  id, noeud_sup_id, noeud_inf_id 
                FROM 
                  LIEN l 
                WHERE 
                  l.id IN ($ids)
                  AND 1 = OSE_DIVERS.COMPRISE_ENTRE( l.histo_creation, l.histo_destruction )
                ";
        $data = $this->chargens->getBdd()->fetch($sql, [], 'ID', 'int');

        return $data;
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