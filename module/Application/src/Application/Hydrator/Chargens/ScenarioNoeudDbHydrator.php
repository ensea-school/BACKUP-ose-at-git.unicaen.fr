<?php
namespace Application\Hydrator\Chargens;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Entity\Chargens\ScenarioNoeud;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ScenarioNoeudDbHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array         $data
     * @param  ScenarioNoeud $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['ID']) ? (int)$data['ID'] : 0;
        $object->setId($id == 0 ? null : $id);

        $assiduite = isset($data['ASSIDUITE']) ? (float)$data['ASSIDUITE'] : 1;
        $object->setAssiduite($assiduite);

        $hetd = isset($data['HETD']) ? (float)$data['HETD'] : null;
        $object->setHetd($hetd);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  ScenarioNoeud $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'ID'          => $object->getId(),
            'SCENARIO_ID' => $object->getScenario()->getId(),
            'NOEUD_ID'    => $object->getNoeud()->getId(),
            'ASSIDUITE'   => $object->getAssiduite(),
            'HETD'        => $object->getHetd(),
        ];

        return $data;
    }

}