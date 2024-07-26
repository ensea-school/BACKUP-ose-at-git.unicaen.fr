<?php

namespace Signature\Hydrator;


use Laminas\Hydrator\HydratorInterface;
use UnicaenSignature\Entity\Db\SignatureFlow;
use UnicaenSignature\Entity\Db\SignatureFlowStep;

class SignatureFlowStepHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array             $data
     * @param SignatureFlowStep $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {

        /**
         * @var SignatureFlowStep $object
         */

        $object->setLabel($data['label']);
        $object->setLetterfileName($data['letterfileName']);
        $object->setLevel($data['level']);
        $object->setAllRecipientsSign($data['allRecipientsSign']);
        $object->setOrder($data['order']);


        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param SignatureFlowStep $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'label'             => $object->getLabel(),
            'letterfileName'    => $object->getLetterfileName(),
            'level'             => $object->getLevel(),
            'allRecipientsSign' => $object->isAllRecipientsSign(),
            'order'             => $object->getOrder(),
        ];

        return $data;
    }
}