<?php

namespace Intervenant\Service;

use Application\Service\AbstractEntityService;
use RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\Civilite;

/**
 * Description of Civilite
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Civilite get($id)
 * @method Civilite[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Civilite newEntity()
 */
class CiviliteService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Civilite::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'c';
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->orderBy($alias . '.libelleCourt', 'DESC');

        return $qb;
    }
}