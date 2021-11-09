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
        $statutRh = $statut->getCodeRh();

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