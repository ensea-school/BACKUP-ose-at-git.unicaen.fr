<?php

namespace Application\Service;

use Application\Entity\Db\Pays;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Util;

/**
 * Description of Pays
 */
class PaysService extends AbstractEntityService
{
    CONST PAYS_FRANCE = 'france';
    CONST PAYS_ALGERIE = 'algerie';


    use ParametresServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return Pays::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'p';
    }



    /**
     * @return int|null
     */
    public function getIdByLibelle(string $libelle)
    {
        $sql = 'SELECT id FROM pays WHERE ose_divers.str_reduce(libelle_court) = :pays AND histo_destruction IS NULL';

        $res = $this->getEntityManager()->getConnection()->fetchAll($sql, ['pays' => Util::reduce($libelle)]);

        if (isset($res[0]['ID'])){
            return (int)$res[0]['ID'];
        }

        return null;
    }



    /**
     * @param Pays $pays
     *
     * @return bool
     */
    public function isFrance(Pays $pays): bool
    {
        return $pays->getId() == $this->getIdByLibelle(self::PAYS_FRANCE);
    }



    /**
     * Retourne la liste des pays, triés par libellé long.
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return PaysService[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelleLong");

        return parent::getList($qb, $alias);
    }
}