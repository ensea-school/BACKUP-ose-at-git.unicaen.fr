<?php

namespace Paiement\Hydrator;


use Laminas\Hydrator\HydratorInterface;

class CentreCoutActiviteHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param \Paiement\Entity\Db\CcActivite $object
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
        $object->setPrimes($data['primes']);
        $object->setReferentiel($data['referentiel']);
        $object->setMission($data['mission']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Paiement\Entity\Db\CcActivite $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'          => $object->getId(),
            'code'        => $object->getCode(),
            'libelle'     => $object->getLibelle(),
            'fi'          => $object->getFi(),
            'fa'          => $object->getFa(),
            'fc'          => $object->getFc(),
            'primes'      => $object->getPrimes(),
            'referentiel' => $object->getReferentiel(),
            'mission'     => $object->getMission(),
        ];

        return $data;
    }
}