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
    use ParametresServiceAwareTrait;

    /**
     * @var array
     */
    private $idsByLibelle;



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
        if (!isset($this->idsByLibelle[$libelle])) {
            $sql = 'SELECT ID FROM PAYS WHERE OSE_DIVERS.str_reduce(LIBELLE) = :pays AND HISTO_DESTRUCTION IS NULL';

            $res = $this->getEntityManager()->getConnection()->fetchAll($sql, ['pays' => Util::reduce($libelle)]);

            if (isset($res[0]['ID'])) {
                $this->idsByLibelle[$libelle] = (int)$res[0]['ID'];
            } else {
                $this->idsByLibelle[$libelle] = null;
            }
        }

        return $this->idsByLibelle[$libelle];
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
        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }
}