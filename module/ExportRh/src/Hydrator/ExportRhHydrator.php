<?php

namespace ExportRh\Hydrator;


use Zend\Hydrator\HydratorInterface;

/**
 *
 *
 */
class ExportRhHydrator implements HydratorInterface
{

    /**
     * Extract values from an object
     *
     * @param IntervenantDossier $object
     *
     * @return array
     */
    public function extract($object)
    {
        //On mappe automatiquement le bon statut RH selon le statut OSE
        $statut   = $object->getStatut();
        $statutRh = '';

        switch ($statut->getCode()) {
            case 'VACFONCSUAPS':
            case 'VACNONFONCSUAPS':
            case 'VAC.SUAPS':
                $statutRh = 'C1204';
            break;
            case 'INTERMITTENT':
                $statutRh = 'C1201';
            break;
            case 'SALAR_PRIVE':
            case 'AUTO_LIBER_INDEP':
                $statutRh = 'C2038';
            break;
            case 'SALAR_PUBLIC':
                $statutRh = 'C2052';
            break;
            case 'RETR_HORS_UCBN':
            case 'ETUD_UCBN':
                $statutRh = 'C2041';
            break;
            case 'PAMSU':
                $statutRh = 'C1210';
            break;
        }

        $data['connecteurForm'] = [
            'statut' => $statutRh,
        ];


        return $data;
    }



    /**
     * @param array  $data
     * @param object $object
     *
     * @return object
     */

    public function hydrate(array $data, $object)
    {

        return $object;
    }

}