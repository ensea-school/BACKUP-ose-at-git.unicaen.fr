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
        //Hydrator spécifique pour les options recipient
        $recipientMethod = $data['recipientMethod'];
        $role            = $data['roles'];
        if ($recipientMethod == 'by_role') {
            $options = [$recipientMethod => $role];
        } else {
            $options = [$recipientMethod => ''];
        }
        $object->setOptions($options);
        $object->setRecipientsMethod($recipientMethod);

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
        //On travaille sur l'extract du type de signataire
        $options = $object->getOptions();
        if (array_key_exists('by_role', $options)) {
            $data['recipientMethod'] = 'by_role';
            $data['roles']           = $options['by_role'];
        } else {
            $data['recipientMethod'] = 'by_intervenant';
        }

        return $data;
    }
}