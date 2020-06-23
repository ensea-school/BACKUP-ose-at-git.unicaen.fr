<?php

namespace Application\Hydrator;


use Application\Service\Traits\DossierAutreTypeServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class DossierAutreHydrator implements HydratorInterface
{

    use DossierAutreTypeServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                               $data
     * @param \Application\Entity\Db\DossierAutre $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        // $object->setContenu($data['contenu']);
        $object->setDescription($data['description']);
        $object->setObligatoire($data['obligatoire']);
        $object->setType($this->getServiceDossierAutreType()->get($data['type']));


        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\DossierAutre $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'     => $object->getLibelle(),
            'contenu'     => $object->getContenu(),
            'description' => $object->getDescription(),
            'type'        => $object->getType()->getId(),
            'obligatoire' => $object->isObligatoire(),
        ];

        return $data;
    }
}