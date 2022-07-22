<?php

namespace Application\Hydrator;


use Application\Service\Traits\CcActiviteServiceAwareTrait;
use Application\Service\Traits\CentreCoutServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\TypeRessourceServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class CentreCoutHydrator implements HydratorInterface
{
    use CcActiviteServiceAwareTrait;
    use TypeRessourceServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use SourceServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                             $data
     * @param \Application\Entity\Db\CentreCout $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setSourceCode($data['code']);
        $object->setUniteBudgetaire($data['unite-budgetaire']);
        if (array_key_exists('activite', $data)) {
            $object->setActivite($this->getServiceCcActivite()->get($data['activite']));
        }
        if (array_key_exists('type-ressource', $data)) {
            $object->setTypeRessource($this->getServiceTypeRessource()->get($data['type-ressource']));
        }
        if (array_key_exists('parent', $data)) {
            $object->setParent($this->getServiceCentreCout()->get($data['parent']));
        }
        $object->setSource($this->getServiceSource()->getOse());

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\CentreCout $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'               => $object->getId(),
            'code'             => $object->getCode(),
            'libelle'          => $object->getLibelle(),
            'source-code'      => $object->getSourceCode(),
            'unite-budgetaire' => $object->getUniteBudgetaire(),
            'activite'         => ($s = $object->getActivite()) ? $s->getId() : null,
            'type-ressource'   => ($s = $object->getTypeRessource()) ? $s->getId() : null,
            'parent'           => ($s = $object->getParent()) ? $s->getId() : null,

        ];

        return $data;
    }
}
