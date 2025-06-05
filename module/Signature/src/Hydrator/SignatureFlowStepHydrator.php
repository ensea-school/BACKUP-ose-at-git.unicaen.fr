<?php

namespace Signature\Hydrator;


use Administration\Service\ParametresServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use Signature\Service\SignatureFlowStepServiceAwareTrait;
use UnicaenSignature\Entity\Db\SignatureFlowStep;

class SignatureFlowStepHydrator implements HydratorInterface
{
    use ParametresServiceAwareTrait;
    use SignatureFlowStepServiceAwareTrait;

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
        $paramLetterFile = $this->getServiceParametres()->get("signature_electronique_parapheur");

        /**
         * @var SignatureFlowStep $object
         */

        $object->setLabel($data['label']);
        $object->setLetterfileName($paramLetterFile);
        $object->setLevel($data['level']);
        $object->setAllRecipientsSign($data['allRecipientsSign']);
        $object->setOrder($data['order']);
        //Hydrator spécifique pour les options recipient
        $recipientMethod = $data['recipientMethod'];
        $role            = $data['roles'];
        if ($recipientMethod == 'by_etablissement' || $recipientMethod == 'by_etablissement_and_intervenant') {
            $options = [$recipientMethod => $role];
        } else {
            $options = [$recipientMethod => ''];
        }
        $object->setOptions($options);
        $object->setRecipientsMethod($recipientMethod);
        $object->setLevel($data['level']);

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
        //Lors de l'ajout d'une nouvelle étape on init l'ordre de l'étape à la valeur supérieur de la dernière étape
        $order = $object->getOrder();
        $signatureFlow = $object->getSignatureFlow();
        if(isset($signatureFlow) && $order == 0)
        {
            $steps = $signatureFlow->getSteps();
            if($steps)
            {
                foreach ($steps as $step) {
                    if($step->getOrder() >= $order)
                    {
                        $order = $step->getOrder()+1;
                    }
                }

            }
        }

        $data = [
            'label'             => $object->getLabel(),
            'letterfileName'    => $object->getLetterfileName(),
            'level'             => $object->getLevel(),
            'allRecipientsSign' => $object->isAllRecipientsSign(),
            'order'             => $order,
        ];
        //On travaille sur l'extract du type de signataire
        $options = $object->getOptions();
        if (array_key_exists('by_etablissement', $options)) {
            $data['recipientMethod'] = 'by_etablissement';
            $data['roles']           = $options['by_etablissement'];
        } elseif (array_key_exists('by_etablissement_and_intervenant', $options)) {
            $data['recipientMethod'] = 'by_etablissement_and_intervenant';
            $data['roles']           = $options['by_etablissement_and_intervenant'];
        } else {
            $data['recipientMethod'] = 'by_intervenant';
        }

        return $data;
    }
}