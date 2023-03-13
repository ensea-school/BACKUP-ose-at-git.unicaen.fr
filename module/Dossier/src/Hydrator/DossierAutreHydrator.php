<?php

namespace Dossier\Hydrator;


use Dossier\Service\Traits\DossierAutreTypeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class DossierAutreHydrator implements HydratorInterface
{

    use DossierAutreTypeServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                           $data
     * @param \Dossier\Entity\Db\DossierAutre $object
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
        $object->setJsonValue($data['json-value']);
        $object->setSqlValue($data['sql-value']);


        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Dossier\Entity\Db\DossierAutre $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'libelle'     => $object->getLibelle(),
            'contenu'     => $object->getContenu(),
            'description' => $object->getDescription(),
            'type'        => $object->getType()->getId(),
            'obligatoire' => $object->isObligatoire(),
            'json-value'  => $object->getJsonValue(),
            'sql-value'   => $object->getSqlValue(),
        ];

        return $data;
    }
}