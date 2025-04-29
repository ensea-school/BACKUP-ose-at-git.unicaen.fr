<?php

namespace Chargens\Hydrator;

use Chargens\Entity\ScenarioLien;
use Laminas\Hydrator\HydratorInterface;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ScenarioLienDbHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param array        $data
     * @param ScenarioLien $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['ID']) ? (int)$data['ID'] : 0;
        $object->setId($id == 0 ? null : $id);

        $actif = isset($data['ACTIF']) ? $data['ACTIF'] == '1' : true;
        $object->setActif($actif);

        $poids = isset($data['POIDS']) ? (float)$data['POIDS'] : 1.0;
        $object->setPoids($poids);

        $choixMinimum = isset($data['CHOIX_MINIMUM']) && $data['CHOIX_MINIMUM'] != '' ? (float)$data['CHOIX_MINIMUM'] : null;
        $object->setChoixMinimum($choixMinimum);

        $choixMaximum = isset($data['CHOIX_MAXIMUM']) && $data['CHOIX_MAXIMUM'] != '' ? (float)$data['CHOIX_MAXIMUM'] : null;
        $object->setChoixMaximum($choixMaximum);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param ScenarioLien $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'ID'            => $object->getId(),
            'SCENARIO_ID'   => $object->getScenario()->getId(),
            'LIEN_ID'       => $object->getLien()->getId(),
            'ACTIF'         => $object->isActif() ? 1 : 0,
            'POIDS'         => $object->getPoids(),
            'CHOIX_MINIMUM' => $object->getChoixMinimum(),
            'CHOIX_MAXIMUM' => $object->getChoixMaximum(),
        ];

        return $data;
    }

}