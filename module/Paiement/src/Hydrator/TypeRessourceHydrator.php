<?php

namespace Paiement\Hydrator;


use Laminas\Hydrator\HydratorInterface;

class TypeRessourceHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                $data
     * @param \Paiement\Entity\Db\TypeRessource $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setFi($data['fi']);
        $object->setFa($data['fa']);
        $object->setFc($data['fc']);
        $object->setFcMajorees($data['fc_majore']);
        $object->setReferentiel($data['referentiel']);
        $object->setMission($data['mission']);
        $object->setEtablissement($data['etablissement']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Paiement\Entity\Db\TypeRessource $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'            => $object->getId(),
            'code'          => $object->getCode(),
            'libelle'       => $object->getLibelle(),
            'fi'            => $object->getFi(),
            'fa'            => $object->getFa(),
            'fc'            => $object->getFc(),
            'fc_majore'     => $object->getFcMajorees(),
            'referentiel'   => $object->getReferentiel(),
            'mission'       => $object->getMission(),
            'etablissement' => $object->getEtablissement(),
        ];

        return $data;
    }
}