<?php

namespace Contrat\Service;


use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\Intervenant;


/**
 * Description of TblContratService
 *
 */
class TblContratService extends AbstractEntityService
{

    use AnneeServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Paiement\Entity\Db\TblPaiement::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tbl_p';
    }



    public function finderByIntervenant(Intervenant $intervenant, QueryBuilder $qb = null, $alias = null): QueryBuilder
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->andWhere("$alias.intervenant = :intervenant")->setParameter('intervenant', $intervenant->getId());

        return $qb;
    }

}