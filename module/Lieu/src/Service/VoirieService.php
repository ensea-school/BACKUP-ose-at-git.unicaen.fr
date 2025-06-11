<?php

namespace Lieu\Service;

use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Voirie;

/**
 * Description of VoirieService
 */
class VoirieService extends AbstractEntityService
{
    use SourceServiceAwareTrait;

    public function getEntityClass()
    {
        return Voirie::class;
    }



    public function getAlias()
    {
        return 'voirie';
    }



    public function newEntity()
    {
        /** @var Voirie $entity */
        $entity = parent::newEntity();
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }



    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }
}