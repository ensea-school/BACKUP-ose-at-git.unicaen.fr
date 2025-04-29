<?php

namespace Chargens\Hydrator;

use Chargens\Entity\Lien;
use Laminas\Hydrator\HydratorInterface;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class LienDbHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param Lien  $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['ID']) ? (int)$data['ID'] : 0;
        $object->setId($id == 0 ? null : $id);

        $noeudSup = isset($data['NOEUD_SUP_ID']) ? stringToInt($data['NOEUD_SUP_ID']) : null;
        $object->setNoeudSup($noeudSup);

        $noeudInf = isset($data['NOEUD_INF_ID']) ? stringToInt($data['NOEUD_INF_ID']) : null;
        $object->setNoeudInf($noeudInf);

        $structure = isset($data['STRUCTURE_ID']) ? stringToInt($data['STRUCTURE_ID']) : null;
        $object->setStructure($structure);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param Lien $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'ID'           => $object->getId(),
            'NOEUD_SUP_ID' => $object->getNoeudSup(false),
            'NOEUD_INF_ID' => $object->getNoeudinf(false),
            'STRUCTURE_ID' => $object->getNoeudinf(false),
        ];

        return $data;
    }

}