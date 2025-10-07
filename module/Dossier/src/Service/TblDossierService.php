<?php

namespace Dossier\Service;


use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Dossier\Entity\Db\TblDossier;
use Intervenant\Entity\Db\Intervenant;


/**
 * Description of TblDossierService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class TblDossierService extends AbstractEntityService
{


    public function getEntityClass(): string
    {
        return TblDossier::class;
    }



    public function getAlias(): string
    {
        return 'tbl_d';
    }



    public function finderByIntervenant(Intervenant $intervenant, ?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb,
         $alias] = $this->initQuery($qb, $alias);

        $qb->andWhere("$alias.intervenant = :intervenant")->setParameter('intervenant', $intervenant->getId());

        return $qb;
    }

}