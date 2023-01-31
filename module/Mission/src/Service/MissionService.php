<?php

namespace Mission\Service;

use Application\Controller\Plugin\Axios;
use Application\Entity\Db\Intervenant;
use Application\Hydrator\GenericHydrator;
use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;

/**
 * Description of MissionService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 *
 * @method Mission get($id)
 * @method Mission[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Mission newEntity()
 *
 */
class MissionService extends AbstractEntityService
{
    use TypeVolumeHoraireServiceAwareTrait;
    use SourceServiceAwareTrait;

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass(): string
    {
        return Mission::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'm';
    }



    /**
     * @param Mission $entity
     *
     * @return Mission
     */
    public function save($entity)
    {
        foreach ($entity->getVolumesHoraires() as $vh) {
            $this->saveVolumeHoraire($vh);
        }

        parent::save($entity);

        return $entity;
    }



    public function saveVolumeHoraire(VolumeHoraireMission $vhm): self
    {
        if (!$vhm->getTypeVolumeHoraire()) {
            $vhm->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getPrevu());
        }
        if (!$vhm->getSource()) {
            $vhm->setSource($this->getServiceSource()->getOse());
        }

        $this->getEntityManager()->persist($vhm);
        $this->getEntityManager()->flush($vhm);

        return $this;
    }

}