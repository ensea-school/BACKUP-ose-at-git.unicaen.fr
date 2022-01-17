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
    const PAYS_FRANCE  = 'france';
    const PAYS_ALGERIE = 'algerie';
    const PAYS_MAROC   = 'maroc';
    const PAYS_TUNISIE = 'tunisie';

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

            $res = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, ['pays' => Util::reduce($libelle)]);

            if (isset($res[0]['ID'])) {
                $this->idsByLibelle[$libelle] = (int)$res[0]['ID'];
            } else {
                $this->idsByLibelle[$libelle] = null;
            }
        }

        return $this->idsByLibelle[$libelle];
    }



    public function getByLibelle(string $libelle)
    {
        return $this->getRepo()->findOneBy(['libelle' => $libelle]);
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
     * @param Pays $pays
     *
     * @return bool
     */
    public function isAlgerie(Pays $pays): bool
    {
        return $pays->getId() == $this->getIdByLibelle(self::PAYS_ALGERIE);
    }



    /**
     * @param Pays $pays
     *
     * @return bool
     */
    public function isMaroc(Pays $pays): bool
    {
        return $pays->getId() == $this->getIdByLibelle(self::PAYS_MAROC);
    }



    /**
     * @param Pays $pays
     *
     * @return bool
     */
    public function isTunisie(Pays $pays): bool
    {
        return $pays->getId() == $this->getIdByLibelle(self::PAYS_TUNISIE);
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