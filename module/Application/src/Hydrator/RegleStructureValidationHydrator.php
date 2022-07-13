<?php

namespace Application\Hydrator;

use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class RegleStructureValidationHydrator implements HydratorInterface
{
    use TypeVolumeHoraireServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                           $data
     * @param \Application\Entity\Db\RegleStructureValidation $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setMessage($data['message']);
        $object->setPriorite($data['priorite']);
        if (array_key_exists('type-volume-horaire', $data)) {
            $object->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->get($data['type-volume-horaire']));
        }
        if (array_key_exists('type-intervenant', $data)) {
            $object->setTypeIntervenant($this->getServiceTypeIntervenant()->get($data['type-intervenant']));
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\RegleStructureValidation $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                  => $object->getId(),
            'priorite'            => $object->getPriorite(),
            'message'             => $object->getMessage(),
            'type-volume-horaire' => ($tvh = $object->getTypeVolumeHoraire()) ? $tvh->getId() : null,
            'type-intervenant'    => ($ti = $object->getTypeIntervenant()) ? $ti->getId() : null,
        ];

        return $data;
    }
}
