<?php

namespace Lieu\Service;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Lieu\Entity\Db\Pays;
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
     * Retourne une liste de pays avec des dates de validitée valide
     *
     * @return Pays[]|array
     */
    public function getListValide(): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('p');
        $qb->from($this->getEntityClass(), 'p');
        $qb->where('(p.validiteFin > :date AND p.validiteDebut < :date AND p.histoDestruction IS NULL) OR (p.validiteFin IS NULL AND p.histoDestruction IS NULL)');
        $qb->setParameter('date', new \DateTime('now'));

        return $this->getList($qb);
    }


    /**
     * @return int|null
     */
    public function getIdByLibelle(string $libelle)
    {
        if (!isset($this->idsByLibelle[$libelle])) {
            $sql = 'SELECT id FROM pays WHERE ose_divers.str_reduce(libelle) = :pays AND histo_destruction IS NULL';

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
     * @param null $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return $qb;
    }


    public function save($entity)
    {
        if ($entity->getSourceCode() == null) {
            $entity->setSourceCode($entity->getCode());
        }

        return parent::save($entity);
    }
}