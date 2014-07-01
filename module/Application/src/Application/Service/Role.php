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
    
    /**
     * 
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByOffreFormationExistante(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb
                ->select("$alias, tr123, s")
                ->distinct()
                ->innerJoin('r.type', 'tr123')
                ->innerJoin('r.structure', 's')
                ->innerJoin('s.elementPedagogique', 'ep');
//                ->where('tr.code <> :code')->setParameter('code', 'IND')
//                ->andWhere('s.niveau = :niv')->setParameter('niv', 2);
        
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
    public function getList(QueryBuilder $qb=null, $alias=null )
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        
        $qb
                ->addSelect("tr987, s12")
                ->distinct()
                ->innerJoin('r.type', 'tr987')
                ->innerJoin('r.structure', 's12')
                ->andWhere('tr987.code <> :codeExclu')->setParameter('codeExclu', 'IND')/*
                ->andWhere('s.niveau = :niv')->setParameter('niv', 2)*/;
        
        return parent::getList($qb);
    }
}