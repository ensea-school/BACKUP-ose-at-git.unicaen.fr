<?php

namespace Mission\Form;

use Laminas\Hydrator\HydratorInterface;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Service\MissionServiceAwareTrait;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MissionSuiviFormHydrator implements HydratorInterface
{
    use MissionServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array        $data
     * @param VolumeHoraireMission $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setMission($this->getServiceMission()->get($data['mission']));
        $object->setDate($data['date']);
        $object->setHeureDebut($data['heureDebut']);
        $object->setHeureFin($data['heureFin']);
        $object->setFormation($data['formation']);
        $object->setDescription($data['description']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param VolumeHoraireMission $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'mission'     => $object->getMission()?->getId(),
            'date'        => $object->getDate(),
            'heureDebut'  => $object->getHeureDebut(),
            'heureFin'    => $object->getHeureFin(),
            'formation'   => $object->isFormation(),
            'description' => $object->getDescription(),
        ];

        return $data;
    }
}