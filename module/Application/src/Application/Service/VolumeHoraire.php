<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeVolumeHoraire as TypeVolumeHoraireEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;

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
                ->join("$alias.service", 'vhs3')
                ->andWhere("vhs3.structureEns = :structure")
                ->setParameter('structure', $structure);

        return $qb;
    }
    
    /**
     * Recherche par type de validation.
     *
     * @param TypeValidationEntity|string $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByTypeValidation($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if (!is_object($type)) {
            $type = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeValidation')->findOneByCode($type);
        }
        
        $qb     ->join("$alias.validation", "v")
                ->join("v.typeValidation", 'tv')
                ->andWhere("tv = :tv")->setParameter('tv', $type);

        return $qb;
    }

    /**
     * Retourne les volumes horaires qui ont fait ou non l'objet d'un contrat/avenant.
     *
     * @param boolean|\Application\Entity\Db\Contrat $contrat <code>true</code>, <code>false</code> ou 
     * bien un Contrat précis
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContrat($contrat, QueryBuilder $qb = null, $alias = null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        if ($contrat instanceof \Application\Entity\Db\Contrat) {
            $qb     ->addSelect("c")
                    ->join("$alias.contrat", "c")
                    ->andWhere("c = :contrat")->setParameter('contrat', $contrat);
        }
        else {
            $value = $contrat ? 'is not null' : 'is null';
            $qb->andWhere("$alias.contrat $value");
        }
        
        return $qb;
    }
    
    /**
     * Recherche les volumes horaires
     *
     * @param TypeValidationEntity $typeValidation
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
//    public function finderByNotHavingValidation(TypeValidationEntity $typeValidation, QueryBuilder $qb = null, $alias = null)
//    {
//        list($qb, $alias) = $this->initQuery($qb, $alias);
//
//        $qb
//                ->andWhere($qb->expr()->not($qb->expr()->exists(
//                        "SELECT valid FROM Application\Entity\Db\Validation valid WHERE valid.typeValidation = :typev AND $alias.validation = valid")))
//                ->setParameter('typev', $typeValidation);
//
//        return $qb;
//    }
}