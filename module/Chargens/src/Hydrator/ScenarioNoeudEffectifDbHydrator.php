<?php

namespace Chargens\Hydrator;

use Chargens\Entity\ScenarioNoeudEffectif;
use Laminas\Hydrator\HydratorInterface;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ScenarioNoeudEffectifDbHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                 $data
     * @param ScenarioNoeudEffectif $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['ID']) ? (int)$data['ID'] : 0;
        $object->setId($id == 0 ? null : $id);

        $effectif = isset($data['EFFECTIF']) ? (float)$data['EFFECTIF'] : 1;
        $object->setEffectif($effectif);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param ScenarioNoeudEffectif $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'ID'                => $object->getId(),
            'SCENARIO_NOEUD_ID' => $object->getScenarioNoeud()->getId(),
            'TYPE_HEURES_ID'    => $object->getTypeHeures()->getId(),
            'ETAPE_ID'          => $object->getEtape()->getId(),
            'EFFECTIF'          => (string)$object->getEffectif(),
        ];

        return $data;
    }

}