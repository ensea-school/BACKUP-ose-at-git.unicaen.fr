<?php

namespace Application\Service;

use Application\Entity\Db\StatutIntervenant;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of StatutIntervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutIntervenantService extends AbstractEntityService
{
    use SourceServiceAwareTrait;
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return StatutIntervenant::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'si';
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return StatutIntervenant[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->orderBy("$alias.ordre");
        return parent::getList($qb, $alias);
    }



    /**
     * @return int
     */
    public function fetchMaxOrdre(): integer
    {
        $sql = 'SELECT MAX(ordre) max_ordre FROM statut_intervenant WHERE histo_destruction IS NULL';

        $res = $this->getEntityManager()->getConnection()->fetchColumn($sql, [0]);
        var_dump($res);

        return (int)$res;
    }


    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return StatutIntervenant
     */
    public function newEntity()
    {
        /** @var StatutIntervenant $entity */
        $entity = parent::newEntity();

        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());

        return $entity;
    }
}