<?php

namespace Signature\Hydrator;


use Dossier\Entity\Db\Employeur;
use Laminas\Hydrator\HydratorInterface;
use UnicaenSignature\Entity\Db\SignatureFlow;

class SignatureFlowHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array         $data
     * @param SignatureFlow $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {

        /**
         * @var SignatureFlow $object
         */
        $object->setLabel($data['label']);
        $object->setDescription($data['description']);
        $object->setEnabled($data['enabled']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param SignatureFlow $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'label'       => $object->getLabel(),
            'description' => $object->getDescription(),
            'enabled'     => $object->isEnabled(),
        ];

        return $data;
    }
}