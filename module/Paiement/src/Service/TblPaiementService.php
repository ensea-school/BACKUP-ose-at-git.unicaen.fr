<?php

namespace Paiement\Service;


use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\Intervenant;


/**
 * Description of TblPaiementService
 *
 * @author Antony LE COURTES <antony.lecourtes at unicaen.fr>
 */
class TblPaiementService extends AbstractEntityService
{

    use AnneeServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass ()
    {
        return \Paiement\Entity\Db\TblPaiement::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias ()
    {
        return 'tbl_p';
    }



    public function finderByHeuresAPayer (QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->where("$alias.heuresAPayerAA > $alias.heuresDemandeesAA OR  $alias.heuresAPayerAC > $alias.heuresDemandeesAC");

        return $qb;
    }



    public function finderByIntervenant (Intervenant $intervenant, QueryBuilder $qb = null, $alias = null): QueryBuilder
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



    public function getDemandesMisesEnPaiementByIntervenant (Intervenant $intervenant)
    {
        $qb = $this->finderByHeuresAPayer();
        $qb = $this->finderByIntervenant($intervenant, $qb);
        $qb = $this->filteredByCentreCoutNotNull($qb);

        return $this->getList($qb);
    }
}