<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\TypeAgrement as TypeAgrementEntity;

/**
 * Description of TypeAgrement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeAgrementService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeAgrementEntity::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'ta';
    }



    /**
     * Retourne une entité à partir de son code
     * Retourne null si le code est null
     *
     * @param string $code
     * @return TypeAgrementEntity
     */
    public function getByCode($code)
    {
        if ($code){
            return $this->getRepo()->findOneBy(['code' => $code]);
        }else{
            return null;
        }
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");
        return $qb;
    }
}