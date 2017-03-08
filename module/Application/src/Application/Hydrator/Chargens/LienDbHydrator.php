<?php
namespace Application\Hydrator\Chargens;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Entity\Chargens\Lien;


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
     * @param  array $data
     * @param  Lien  $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = isset($data['ID']) ? (int)$data['ID'] : 0;
        $object->setId($id == 0 ? null : $id);

        $noeudSup = isset($data['NOEUD_SUP_ID']) ? (int)$data['NOEUD_SUP_ID'] : 0;
        $object->setNoeudSup($noeudSup == 0 ? null : $noeudSup);

        $noeudInf = isset($data['NOEUD_INF_ID']) ? (int)$data['NOEUD_INF_ID'] : 0;
        $object->setNoeudInf($noeudInf == 0 ? null : $noeudInf);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  Lien $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'ID'           => $object->getId(),
            'NOEUD_SUP_ID' => $object->getNoeudSup(false),
            'NOEUD_INF_ID' => $object->getNoeudinf(false),
        ];

        return $data;
    }

}