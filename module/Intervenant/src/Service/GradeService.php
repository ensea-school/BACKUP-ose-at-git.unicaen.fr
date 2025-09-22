<?php

namespace Intervenant\Service;

use Application\Service\AbstractEntityService;
use RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\Grade;


/**
 * Description of Grade
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Grade get($id)
 * @method Grade[] getList(?QueryBuilder $qb = null, $alias = null)
 * @method Grade newEntity()
 */
class GradeService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Grade::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'grade';
    }



    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.libelleLong");

        return $qb;
    }
}