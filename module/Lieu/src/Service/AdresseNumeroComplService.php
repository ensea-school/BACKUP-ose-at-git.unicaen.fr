<?php

namespace Lieu\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\AdresseNumeroCompl;

class AdresseNumeroComplService extends AbstractEntityService
{

    public function getEntityClass()
    {
        return AdresseNumeroCompl::class;
    }



    public function getAlias()
    {
        return 'adrnc';
    }



    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return $qb;
    }
}