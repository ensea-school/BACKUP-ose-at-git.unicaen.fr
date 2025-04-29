<?php

namespace OffreFormation\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use OffreFormation\Entity\Db\TypeHeures;

/**
 * Description of TypeHeures
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class TypeHeuresService extends AbstractEntityService
{
    use ParametresServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeHeures::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'th';
    }



    /**
     *
     * @param string $code
     *
     * @return \OffreFormation\Entity\Db\TypeHeures
     */
    public function getByCode($code)
    {
        if (null == $code) return null;

        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    /**
     * Retourne la liste des types de formation
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return \OffreFormation\Entity\Db\TypeHeures[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return parent::getList($qb, $alias);
    }

}