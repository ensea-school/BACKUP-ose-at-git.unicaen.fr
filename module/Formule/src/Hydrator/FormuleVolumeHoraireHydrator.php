<?php

namespace Formule\Hydrator;

use Application\Hydrator\GenericHydrator;
use Doctrine\ORM\EntityManager;
use Formule\Entity\FormuleVolumeHoraire;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class FormuleVolumeHoraireHydrator extends GenericHydrator
{
    protected int $extractType = self::EXTRACT_TYPE_JSON;



    public function __construct(EntityManager $entityManager, object|array|string $spec = [])
    {
        $this->spec(FormuleVolumeHoraire::class, ['formuleIntervenant']);

        parent::__construct($entityManager, $spec);
    }
}
