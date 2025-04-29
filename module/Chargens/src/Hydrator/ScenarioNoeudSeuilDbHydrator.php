<?php

namespace Chargens\Hydrator;

use Chargens\Entity\ScenarioNoeudSeuil;
use Laminas\Hydrator\HydratorInterface;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ScenarioNoeudSeuilDbHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param array              $data
     * @param ScenarioNoeudSeuil $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['ID']) ? (int)$data['ID'] : 0;
        $object->setId($id == 0 ? null : $id);

        $ouverture = isset($data['OUVERTURE']) ? (int)$data['OUVERTURE'] : null;
        $object->setOuverture($ouverture);

        $dedoublement = isset($data['DEDOUBLEMENT']) ? (int)$data['DEDOUBLEMENT'] : null;
        $object->setDedoublement($dedoublement);

        $assiduite = isset($data['ASSIDUITE']) ? (float)$data['ASSIDUITE'] : null;
        $object->setAssiduite($assiduite);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param ScenarioNoeudSeuil $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'ID'                   => $object->getId(),
            'SCENARIO_NOEUD_ID'    => $object->getScenarioNoeud()->getId(),
            'TYPE_INTERVENTION_ID' => $object->getTypeIntervention()->getId(),
            'OUVERTURE'            => $object->getOuverture(),
            'DEDOUBLEMENT'         => $object->getDedoublement(),
            'ASSIDUITE'            => $object->getAssiduite(),
        ];

        return $data;
    }
}