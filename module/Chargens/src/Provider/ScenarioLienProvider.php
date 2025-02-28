<?php

namespace Chargens\Provider;

use Chargens\Entity\Db\Scenario;
use Chargens\Entity\Noeud;
use Chargens\Entity\ScenarioLien;
use Chargens\Hydrator\ScenarioLienDbHydrator;

class ScenarioLienProvider
{
    /**
     * @var ChargensProvider
     */
    private $chargens;



    /**
     * ScenarioLienProvider constructor.
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
        $liens = $this->chargens->getLiens()->getLiens();
        foreach ($liens as $lien) {
            $lien->removeScenarioLien();
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function load()
    {
        $lienIds = array_keys($this->chargens->getLiens()->getLiens());
        if (empty($lienIds)) return $this;
        $lienIds = implode(',', $lienIds);

        $sld         = $this->getScenarioLiensData($lienIds);
        $sldHydrator = new ScenarioLienDbHydrator();
        foreach ($sld as $d) {
            $lienId     = (int)$d['LIEN_ID'];
            $scenarioId = (int)$d['SCENARIO_ID'];

            $lien     = $this->chargens->getLiens()->getLien($lienId);
            $scenario = $this->chargens->getEntities()->get(Scenario::class, $scenarioId);

            $scenarioLien = new ScenarioLien($lien, $scenario);
            $sldHydrator->hydrate($d, $scenarioLien);

            $lien->addScenarioLien($scenarioLien);
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function persist(array $data)
    {
        if (array_key_exists('SCENARIO_LIEN', $data)) {
            foreach ($data['SCENARIO_LIEN'] as $d) {
                $object  = $d['object'];
                $changes = $d['data'];
                $this->persistScenarioLien($object, $changes);
            }
        }

        return $this;
    }



    /**
     * @param ScenarioLien $scenarioLien
     * @param array        $changes
     *
     * @return $this
     */
    private function persistScenarioLien(ScenarioLien $scenarioLien, array $changes)
    {
        $conn        = $this->chargens->getEntityManager()->getConnection();
        $userId      = $this->chargens->getServiceContext()->getUtilisateur()->getId();
        $date        = $conn->convertToDatabaseValue(new \DateTime(), 'datetime');
        $oseSourceId = $this->chargens->getServiceSource()->getOse()->getId();

        if ($scenarioLien->getId()) {
            unset($changes['ID']);
            $changes['SOURCE_ID']             = $oseSourceId;
            $changes['HISTO_MODIFICATEUR_ID'] = $userId;
            $changes['HISTO_MODIFICATION']    = $date;
            $conn->update('SCENARIO_LIEN', $changes, ['ID' => $scenarioLien->getId()]);
        } else {
            $scenarioLien->setId((int)$conn->fetchAssociative('SELECT SCENARIO_LIEN_ID_SEQ.NEXTVAL VAL FROM DUAL')['VAL']);
            $changes['ID']                    = $scenarioLien->getId();
            $changes['SCENARIO_ID']           = $scenarioLien->getScenario()->getId();
            $changes['LIEN_ID']               = $scenarioLien->getLien()->getId();
            $changes['SOURCE_ID']             = $oseSourceId;
            $changes['SOURCE_CODE']           = uniqid('ose-');
            $changes['HISTO_CREATEUR_ID']     = $userId;
            $changes['HISTO_CREATION']        = $date;
            $changes['HISTO_MODIFICATEUR_ID'] = $userId;
            $changes['HISTO_MODIFICATION']    = $date;
            $conn->insert('SCENARIO_LIEN', $changes);
        }

        $noeudSup = $scenarioLien->getLien()->getNoeudSup();
        if ($noeudSup->isListe()) {
            $noeuds   = [];
            $liensSup = $noeudSup->getLiensSup();
            foreach ($liensSup as $lienSup) {
                if ($lienSup->getScenarioLien()->isActif()) {
                    $noeuds[] = $lienSup->getNoeudSup();
                }
            }
        } else {
            $noeuds = [$noeudSup];
        }

        /** @var Noeud $noeud */
        foreach ($noeuds as $noeud) {
            $this->chargens->getScenarioNoeuds()->calculSousEffectifsByNoeud($noeud);
        }

        return $this;
    }



    /**
     * @return $this
     */
    private function getScenarioLiensData($lienIds)
    {
        $sql = "
        SELECT
          sl.id,
          sl.scenario_id,
          sl.lien_id,
          sl.actif,
          sl.poids,
          sl.choix_minimum,
          sl.choix_maximum
        FROM
          scenario_lien sl
          JOIN scenario s ON s.id = sl.scenario_id
        WHERE
          sl.histo_destruction IS NULL
          AND sl.lien_id IN ($lienIds)
        ";

        return $this->chargens->getEntityManager()->getConnection()->fetchAllAssociative($sql);
    }



    /**
     * @param array          $data
     * @param ScenarioLien[] $scenarioLiens
     *
     * @return $this
     */
    public function getDbData(&$data, array $scenarioLiens)
    {
        $slHydrator = new ScenarioLienDbHydrator();

        foreach ($scenarioLiens as $scenarioLien) {
            $slKey = $scenarioLien->getLien()->getId() . '-' . $scenarioLien->getScenario()->getId();

            $data['SCENARIO_LIEN'][$slKey] = [
                'object' => $scenarioLien,
                'data'   => $slHydrator->extract($scenarioLien),
            ];
        }

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