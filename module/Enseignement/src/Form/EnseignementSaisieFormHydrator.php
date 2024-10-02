<?php

namespace Enseignement\Form;

use Application\Entity\Db\Periode;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Enseignement\Entity\Db\Service;
use Laminas\Hydrator\HydratorInterface;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EnseignementSaisieFormHydrator implements HydratorInterface
{
    use PeriodeServiceAwareTrait;


    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @return Periode[]
     */
    public function getPeriodes()
    {
        $periodes = $this->getServicePeriode()->getEnseignement();

        return $periodes;
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array   $data
     * @param Service $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object = $data['service'];

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param Service $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data            = [];
        $data['service'] = $object;
        foreach ($this->getPeriodes() as $periode) {
            $data[$periode->getCode()] = $object->getVolumeHoraireListe($periode);
        }

        return $data;
    }
}