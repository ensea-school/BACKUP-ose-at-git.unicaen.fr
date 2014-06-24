<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\Structure as StructureEntity;

/**
 * Description of Validation
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Validation extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Validation';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'v';
    }

    /**
     * Retourne une nouvelle entité de la classe donnée
     * 
     * @param TypeValidation|string $type
     * @return \Application\Entity\Db\Validation
     */
    public function newEntity($type = null)
    {
        $entity = parent::newEntity();
        $entity->setTypeValidation($this->normalizeTypeValidation($type));
        
        return $entity;
    }
    
    /**
     * Recherche par type 
     *
     * @param TypeValidation|string $type
     * @param QueryBuilder|null $qb
     * @return QueryBuilder
     */
    public function finderByType($type, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb
                ->join("$alias.typeValidation", 'tv')
                ->andWhere("tv = :tv")
                ->setParameter('tv', $this->normalizeTypeValidation($type));

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
                ->join("$alias.volumeHoraire", 'vh')
                ->join("vh.service", 'vhs')
                ->andWhere("vhs.structureEns = :structure")
                ->setParameter('structure', $structure);

        return $qb;
    }
    
    /**
     * 
     * @param TypeValidation|string $type
     * @return TypeValidationEntity
     * @throws RuntimeException
     */
    protected function normalizeTypeValidation($type)
    {
        if (null === $type) {
            return null;
        }
        if ($type instanceof TypeValidationEntity) {
            return $type;
        }
        
        $qb = $this->getServiceLocator()->get('ApplicationTypeValidation')->finderByCode($code = $type);
        $type = $qb->getQuery()->getOneOrNullResult();
        if (!$type) {
            throw new RuntimeException("Aucun type de validation trouvé avec le code '$code'.");
        }
        
        return $type;
    }
}