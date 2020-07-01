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

    const CODE_AUTRES = 'AUTRES';



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
    public function getAlias()
    {
        return 'si';
    }



    /**
     * @param string $code
     *
     * @return StatutIntervenant|null
     */
    public function getByCode(string $code = null)
    {
        if (!$code) $code = self::CODE_AUTRES;

        return $this->getRepo()->findOneBy(['code' => $code]);
    }



    /**
     * @return StatutIntervenant|null
     */
    public function getAutres()
    {
        return $this->getByCode();
    }



    public function getStatutSelectable($criteria = [])
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\StatutIntervenant')->createQueryBuilder('s');
        foreach ($criteria as $key => $value) {
            $qb->orWhere($key, $value);
        }
        $qb->orderBy('ordre', 'ASC');

        return $qb->getQuery()->getResult();
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



    /**
     * @return int
     */
    public function fetchMaxOrdre(): int
    {
        $sql = 'SELECT MAX(ORDRE) MAX_ORDRE FROM STATUT_INTERVENANT WHERE HISTO_DESTRUCTION IS NULL';

        $res = $this->getEntityManager()->getConnection()->fetchColumn($sql, [], 0);

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

        return $entity;
    }
}