<?php

namespace Agrement\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Agrement\Entity\Db\TypeAgrement;

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
        return TypeAgrement::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'ta';
    }



    public function getByCode(string $code): TypeAgrement
    {
        if ($code) {
            return $this->getRepo()->findOneBy(['code' => $code]);
        } else {
            return null;
        }
    }



    public function getTypesAgrement()
    {
        $this->getEntityManager()->find('TypeAgrement');
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return $qb;
    }
}