<?php

namespace Dossier\Hydrator;


use Dossier\Entity\Db\Employeur;
use Laminas\Hydrator\HydratorInterface;

class EmployeurHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array     $data
     * @param Employeur $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {

        $siret = preg_replace('/\D/', '', $data['siret']);
        $siren = preg_replace('/\D/', '', $data['siren']);
        $object->setSiret($siret);
        $object->setSiren($siren);
        $object->setRaisonSociale($data['raisonSociale']);
        $object->setNomCommercial($data['nomCommercial']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param Employeur $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'siret'         => $object->getSiret(),
            'siren'         => $object->getSiren(),
            'raisonSociale' => $object->getRaisonSociale(),
            'nomCommercial' => $object->getNomCommercial(),

        ];

        return $data;
    }
}