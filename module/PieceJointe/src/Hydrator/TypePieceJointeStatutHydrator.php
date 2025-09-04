<?php

namespace PieceJointe\Hydrator;

use PieceJointe\Entity\Db\TypePieceJointeStatut;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class TypePieceJointeStatutHydrator implements HydratorInterface
{
    use AnneeServiceAwareTrait;


    /**
     *
     * Hydrate $object with the provided $data.
     *
     * @param array                 $data
     * @param TypePieceJointeStatut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {

        $object->setChangementRIB($data['changement-rib']);
        $object->setNationaliteEtrangere($data['nationalite-etrangere']);
        $object->setObligatoire($data['obligatoire']);
        $object->setSeuilHetd((empty($data['seuil-hetd']) ? 0 : $data['seuil-hetd']));
        $object->setTypeHeureHetd($data['type-heure-hetd']);
        $object->setFC($data['fc']);
        $object->setFA($data['fa'] ?? false);
        $object->setDureeVie($data['duree-vie']);
        $object->setObligatoireHNP($data['obligatoire-hnp']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param TypePieceJointeStatut $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                    => $object->getId(),
            'obligatoire'           => $object->getObligatoire(),
            'seuil-hetd'            => $object->getSeuilHetd(),
            'type-heure-hetd'       => $object->getTypeHeureHetd(),
            'changement-rib'        => $object->getChangementRIB(),
            'nationalite-etrangere' => $object->isNationaliteEtrangere(),
            'fc'                    => $object->getFc(),
            'fa' => $object->getFa(),
            'duree-vie'             => $object->getDureeVie(),
            'obligatoire-hnp'       => $object->getObligatoireHNP(),
        ];

        return $data;
    }
}