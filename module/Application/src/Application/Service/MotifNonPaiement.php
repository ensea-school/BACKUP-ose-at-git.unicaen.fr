<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\MotifNonPaiement as MotifNonPaiementEntity;

/**
 * Description of MotifNonPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MotifNonPaiement extends AbstractEntityService
{

    /**
     * retourne la classe des entités correcpondantes
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return MotifNonPaiementEntity::class;
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
     * @return MotifNonPaiementEntity[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");
        return parent::getList($qb, $alias);
    }
}