<?php

namespace Intervenant\Service;

use Application\Service\AbstractEntityService;
use Intervenant\Entity\Db\Statut;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of Statut
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StatutService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Statut::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'statut';
    }



    /**
     * @param string $code
     *
     * @return Statut
     */
    public function getByCode(string $code): Statut
    {
        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    /**
     * @return Statut
     */
    public function getAutres()
    {
        return $this->getByCode(Statut::CODE_AUTRES);
    }



    public function getStatutSelectable(Statut $statut, QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.dossierSelectionnable = 1");
        $qb->addOrderBy("$alias.code");

        $entities    = $qb->getQuery()->execute();
        $result      = [];
        $entityClass = $this->getEntityClass();
        foreach ($entities as $entity) {
            if ($entity instanceof $entityClass) {
                /**
                 * @var Statut $entity
                 */
                //Je prends le statut si il n'est pas détruit ou si l'intervenant a ce statut
                if (is_null($entity->getHistoDestruction()) ||
                    $statut->getCode() == $entity->getCode()) {
                    $result[] = $entity;
                }
            }
        }

        return $result;
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.ordre");

        return $qb;
    }



    public function fetchMaxOrdre(): int
    {
        $sql = 'SELECT MAX(ORDRE) MAX_ORDRE FROM STATUT WHERE HISTO_DESTRUCTION IS NULL';

        return (int)$this->getEntityManager()->getConnection()->fetchOne($sql);
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     */
    public function newEntity(): Statut
    {
        /** @var Statut $entity */
        $statut = parent::newEntity();

        $statut->setOrdre($this->fetchMaxOrdre() + 1);

        return $statut;
    }
}