<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;

/**
 * Description of Affectation
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Affectation extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Affectation';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'aff';
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Role|string $role
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     * @todo A REVOIR! ! ! !
     */
    public function finderByRole($role, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        if ($role instanceof \Application\Entity\Db\Role) {
            $role = $role->getCode();
        }
        
        $qb
                ->innerJoin($alias.'.role', $ralias = uniqid('r'))
                ->andWhere("$ralias.code = :code")->setParameter('code', $role);
        
        return $qb;
    }
}