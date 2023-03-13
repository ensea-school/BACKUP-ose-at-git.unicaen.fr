<?php

namespace ExportRh\Hydrator;


use Dossier\Entity\Db\IntervenantDossier;
use Laminas\Hydrator\HydratorInterface;

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
    public function extract($object): array
    {
        //On mappe automatiquement le bon statut RH selon le statut OSE
        $statut = $object->getStatut();
        $statutRh = $statut->getCodesCorresp1();
        $typeEmploi = (strtolower(trim($statut->getCodesCorresp3())) == 'oui') ? 'UCNVA' : 'UCNVCE';

        $data['connecteurForm'] = [
            'statut' => $statutRh,
            'emploi' => $typeEmploi,
        ];

        return $data;
    }


    /**
     * @param array $data
     * @param object $object
     *
     * @return object
     */

    public function hydrate(array $data, $object)
    {

        return $object;
    }

}