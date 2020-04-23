<?php

namespace Application\Hydrator;


use Zend\Hydrator\HydratorInterface;

class CentreCoutActiviteHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\CcActivite $object
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

        return $object;
    }


    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\CcActivite $object
     *
     * @return array
     */
    public function extract($object)
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
        ];

        return $data;
    }
}