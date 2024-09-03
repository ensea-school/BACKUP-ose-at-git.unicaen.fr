<?php

namespace Formule\Hydrator;

use Application\Hydrator\GenericHydrator;
use Doctrine\ORM\EntityManager;
use Formule\Entity\FormuleIntervenant;
use Formule\Entity\FormuleVolumeHoraire;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleIntervenantHydrator extends GenericHydrator
{
    protected int $extractType = self::EXTRACT_TYPE_JSON;

    private FormuleVolumeHoraireHydrator $fvhHydrator;



    public function __construct(EntityManager $entityManager, object|array|string $spec = [])
    {
        $this->spec(FormuleIntervenant::class);

        $this->fvhHydrator = new FormuleVolumeHoraireHydrator($entityManager);

        parent::__construct($entityManager, $spec);
    }



    public function extract($object): array
    {
        /** @var FormuleIntervenant $object */

        $data                    = parent::extract($object);
        $data['volumesHoraires'] = [];

        foreach( $object->getVolumesHoraires() as $volumeHoraire ){
            $data['volumesHoraires'][] = $this->fvhHydrator->extract($volumeHoraire);
        }

        return $data;
    }



    public function hydrate(array $data, $object)
    {
        /** @var FormuleIntervenant $object */

        parent::hydrate($data, $object);

        $object->getVolumesHoraires()->clear();

        if (isset($data['volumesHoraires'])){
            foreach($data['volumesHoraires'] as $dvh){
                $volumeHoraire = new FormuleVolumeHoraire();
                $this->fvhHydrator->hydrate($dvh, $volumeHoraire);
                $object->addVolumeHoraire($volumeHoraire);
            }
        }
    }
}


