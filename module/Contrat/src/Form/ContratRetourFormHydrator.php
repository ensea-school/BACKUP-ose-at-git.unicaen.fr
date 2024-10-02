<?php

namespace Contrat\Form;

use Application\Filter\DateTimeFromString;
use Laminas\Hydrator\HydratorInterface;
use Service\Entity\Db\CampagneSaisie;


class ContratRetourFormHydrator implements HydratorInterface
{

    /**
     * @param array          $data
     * @param CampagneSaisie $object
     *
     * @return CampagneSaisie
     */
    public function hydrate(array $data, $object)
    {
        $object->setDateRetourSigne(DateTimeFromString::run($data['dateRetourSigne'] ?? null));

        return $object;
    }



    /**
     * @param CampagneSaisie $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'dateRetourSigne' => $object->getDateRetourSigne(),
        ];

        return $data;
    }
}