<?php

namespace Application\Service;

use Application\Entity\Db\TypeVolumeHoraire;

/**
 * Description of VolumeHoraire
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraire extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\VolumeHoraire';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'vh';
    }

    /**
     * 
     * @return \Application\Entity\Db\VolumeHoraire
     */
    public function newEntity()
    {
        // type de volume horaire par défaut
        $qb = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->finderByCode(TypeVolumeHoraire::CODE_PREVU);
        $type = $qb->getQuery()->getOneOrNullResult();
        
        $entity = parent::newEntity();
        $entity
                ->setValiditeDebut(new \DateTime())
                ->setTypeVolumeHoraire($type);
        
        return $entity;
    }
}