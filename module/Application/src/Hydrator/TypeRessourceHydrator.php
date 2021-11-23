<?php

namespace Application\Hydrator;


use Laminas\Hydrator\HydratorInterface;

class TypeRessourceHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                $data
     * @param \Application\Entity\Db\TypeRessource $object
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
        $object->setEtablissement($data['etablissement']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\TypeRessource $object
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
            'etablissement' => $object->getEtablissement(),
        ];

        return $data;
    }
}