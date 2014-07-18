<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of TypeRolePhpRole
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeRolePhpRole extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\TypeRolePhpRole';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'trpr';
    }
    
    /**
     * 
     * @param \Application\Entity\Db\TypeRole|string $typeRole
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByTypeRole($typeRole, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        if ($typeRole instanceof \Application\Entity\Db\TypeRole) {
            $typeRole = $typeRole->getCode();
        }
        
        $qb
                ->innerJoin("$alias.typeRole", $trAlias = uniqid('tr'))
                ->andWhere("$trAlias.code = :code")->setParameter('code', $typeRole);
        
        return $qb;
    }
    
    /**
     * 
     * @param \Zend\Permissions\Acl\Role\RoleInterface|string $phpRole
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByPhpRole($phpRole, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        if ($phpRole instanceof \Zend\Permissions\Acl\Role\RoleInterface) {
            $phpRole = $phpRole->getRoleId();
        }
        
        $qb
                ->distinct()
                ->andWhere("$alias.phpRoleId = :roleId")->setParameter('roleId', $phpRole);
        
        return $qb;
    }
    
    /**
     * Retourne une liste d'entités en fonction du QueryBuilder donné
     *
     * La liste de présente sous la forme d'un tableau associatif, dont les clés sont les ID des entités et les valeurs les entités elles-mêmes
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return array
     */
//    public function getList(QueryBuilder $qb=null, $alias=null )
//    {
//        list($qb, $alias) = $this->initQuery($qb, $alias);
//        
//        $qb
//                ->addSelect("tr, s")
//                ->distinct()
//                ->innerJoin('r.typeRole', $trAlias = uniqid('tr'))
//                ->andWhere("$trAlias.code <> :codeExclu")->setParameter('codeExclu', 'IND');
//        
//        return parent::getList($qb);
//    }
}