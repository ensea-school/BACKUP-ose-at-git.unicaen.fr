<?php

namespace Application\Hydrator;

use Zend\Hydrator\HydratorInterface;

class RegleStructureValidationHydrator implements HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                             $data
     * @param  \Application\Entity\Db\RegleStructureValidation $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setMessage($data['message']);
        $object->setPriorite($data['priorite']);
        $object->setTypeVolumeHoraire($data['type-volume-horaire']);
        $object->setTypeIntervenant($data['type-intervenant']);

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\RegleStructureValidation $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                       => $object->getId(),
            'priorite'                 => $object->getPriorite(),
            'message'                  => $object->getMessage(),
            'type-volume-horaire'      => ($tvh = $object->getTypeVolumeHoraire()) ? $tvh->getId() : null,
            'type-intervenant'         =>  ($ti = $object->getTypeIntervenant()) ? $ti->getId() : null,
        ];

        return $data;
    }
}
