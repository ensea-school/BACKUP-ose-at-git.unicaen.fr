<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;

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
        $qb = $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->finderByCode(TypeVolumeHoraireEntity::CODE_PREVU);
        $type = $qb->getQuery()->getOneOrNullResult();
        
        $entity = parent::newEntity();
        $entity
                ->setValiditeDebut(new \DateTime())
                ->setTypeVolumeHoraire($type);
        
        return $entity;
    }
    
    /**
     * Recherche par intervenant concerné. 
     *
     * @param IntervenantEntity $intervenant
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByIntervenant(IntervenantEntity $intervenant, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.service", 'vhs2')
                ->join("vhs2.intervenant", 'i2')
                ->andWhere("i2 = :intervenant")
                ->setParameter('intervenant', $intervenant);

        return $qb;
    }
    
    /**
     * Recherche par structure d'intervention (i.e. structure où sont effectués les enseignements). 
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByStructureIntervention(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.service", 'vhs')
                ->andWhere("vhs.structureEns = :structure")
                ->setParameter('structure', $structure);

        return $qb;
    }
}