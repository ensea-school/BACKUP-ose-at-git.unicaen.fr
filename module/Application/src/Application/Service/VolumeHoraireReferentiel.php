<?php

namespace Application\Service;

use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;

/**
 * Description of VolumeHoraireReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireReferentiel extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\VolumeHoraireReferentiel';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'vhr';
    }

    /**
     * 
     * @return \Application\Entity\Db\VolumeHoraire
     */
    public function newEntity()
    {
        // type de volume horaire par défaut
        $qb = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->finderByCode(TypeVolumeHoraireEntity::CODE_PREVU);
        $type = $qb->getQuery()->getOneOrNullResult();
        
        $entity = parent::newEntity();
        $entity
                ->setTypeVolumeHoraire($type);
        
        return $entity;
    }

}