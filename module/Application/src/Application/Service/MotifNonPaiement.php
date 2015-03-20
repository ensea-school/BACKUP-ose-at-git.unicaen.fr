<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\MotifNonPaiement as Entity;

/**
 * Description of MotifNonPaiement
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class MotifNonPaiement extends AbstractEntityService
{

    /**
     * Liste des motifs de non paiement
     *
     * @var Entity[]
     */
    protected $motifsNonPaiement;


    /**
     * retourne la classe des entités correcpondantes
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\MotifNonPaiement';
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
     * @return Application\Entity\Db\Periode[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");
        return parent::getList($qb, $alias);
    }

    /**
     * Liste des motifs de non paiement
     *
     * @return Entity[]
     */
    public function getMotifsNonPaiement()
    {
        if (! $this->motifsNonPaiement){
            $this->motifsNonPaiement = $this->getList( $this->finderByHistorique() );
        }
        return $this->motifsNonPaiement;
    }
}