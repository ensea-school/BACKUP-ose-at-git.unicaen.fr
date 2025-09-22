<?php

namespace Paiement\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Paiement\Entity\Db\MotifNonPaiement;

/**
 * Description of MotifNonPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MotifNonPaiementService extends AbstractEntityService
{

    /**
     * retourne la classe des entités correcpondantes
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return MotifNonPaiement::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'mnp';
    }

    /**
     * Retourne la liste des motifs de non paiement
     *
     * @param QueryBuilder|null $queryBuilder
     * @return MotifNonPaiement[]
     */
    public function getList( ?QueryBuilder $qb = null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");
        return parent::getList($qb, $alias);
    }
}