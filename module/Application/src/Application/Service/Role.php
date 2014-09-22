<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of Role
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Role extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Role';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'r';
    }
    
    /**
     * 
     * @param \Application\Entity\Db\TypeRole|string $typeRole
     * @param type $qb
     * @param type $alias
     * @return type
     */
    public function finderByTypeRole($typeRole, $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        if ($typeRole instanceof \Application\Entity\Db\TypeRole) {
            $typeRole = $typeRole->getCode();
        }
        
        $qb
                ->innerJoin('r.type', $alias = uniqid('tr'))
                ->andWhere("$alias.code = :code")->setParameter('code', $typeRole);
        
        return $qb;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Structure $structure
     * @param type $qb
     * @param type $alias
     * @return type
     */
    public function finderByStructure(\Application\Entity\Db\Structure $structure, $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb->andWhere("$alias.structure = :structure")->setParameter('structure', $structure);
        
        return $qb;
    }
}