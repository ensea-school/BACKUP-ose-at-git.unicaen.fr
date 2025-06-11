<?php

namespace Paiement\Service;


use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\Intervenant;
use Paiement\Entity\Db\TblPaiement;


/**
 * Description of TblPaiementService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class TblPaiementService extends AbstractEntityService
{



    public function getEntityClass (): string
    {
        return TblPaiement::class;
    }



    public function getAlias (): string
    {
        return 'tbl_p';
    }



    public function finderByHeuresAPayer (?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->where("$alias.heuresAPayerAA > $alias.heuresDemandeesAA OR  $alias.heuresAPayerAC > $alias.heuresDemandeesAC");

        return $qb;
    }



    public function finderByIntervenant (Intervenant $intervenant, ?QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->andWhere("$alias.intervenant = :intervenant")->setParameter('intervenant', $intervenant->getId());

        return $qb;
    }



    public function filteredByCentreCoutNotNull (QueryBuilder $qb, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.centreCout IS NOT NULL");

        return $qb;
    }



    /**
     * @return array|TblPaiement[]
     */
    public function getDemandesMisesEnPaiementByIntervenant (Intervenant $intervenant): array
    {
        $qb = $this->finderByHeuresAPayer();
        $qb = $this->finderByIntervenant($intervenant, $qb);
        $qb = $this->filteredByCentreCoutNotNull($qb);

        return $this->getList($qb);
    }
}