<?php

namespace Application\Service;

use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Periode as PeriodeEntity;

use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\MiseEnPaiementAwareTrait;
use Application\Service\Traits\MiseEnPaiementIntervenantStructureAwareTrait;
use Doctrine\ORM\QueryBuilder;


/**
 * Description of Periode
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Periode extends AbstractEntityService
{
    use MiseEnPaiementAwareTrait;
    use IntervenantAwareTrait;
    use MiseEnPaiementIntervenantStructureAwareTrait;

    /**
     * Périodes d'enseignement
     *
     * @var PeriodeEntity[]
     */
    protected $enseignement;

    /**
     * @var PeriodeEntity[]
     */
    private $cache;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PeriodeEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'per';
    }



    /**
     *
     * @param string $code
     *
     * @return PeriodeEntity
     */
    public function getByCode($code)
    {
        if (!isset($this->cache[$code])) {
            $this->cache[$code] = $this->getRepo()->findOneBy(['code' => $code]);
        }

        return $this->cache[$code];
    }



    /**
     *
     * @param \DateTime $date
     *
     * @return PeriodeEntity
     */
    public function getPeriodePaiement(\DateTime $date = null)
    {
        $anneeDateDebut = $this->getServiceContext()->getAnnee()->getDateDebut();
        $aY             = (int)$anneeDateDebut->format('Y');
        $aM             = (int)$anneeDateDebut->format('n');

        if (empty($date)) $date = new \DateTime;
        $dY = (int)$date->format('Y');
        $dM = (int)$date->format('n');

        $ecartMoisPaiement = ($dY - $aY) * 12 + $dM - $aM;

        return $this->getRepo()->findOneBy(['paiement' => true, 'ecartMoisPaiement' => $ecartMoisPaiement]);
    }



    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByEnseignement(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.enseignement = 1");

        return $qb;
    }



    public function finderByMiseEnPaiement(StructureEntity $structure = null, QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->join($this->getServiceMiseEnPaiement(), $qb, 'miseEnPaiement');
        $serviceMIS->join($this->getServiceIntervenant(), $qb, 'intervenant', false);

        if ($structure) {
            $serviceMIS->finderByStructure($structure, $qb);
        }

        $this->getServiceIntervenant()->finderByAnnee($this->getServiceContext()->getAnnee(), $qb);

        return $qb;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->orderBy("$alias.ordre");

        return $qb;
    }



    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @return PeriodeEntity[]
     */
    public function getEnseignement()
    {
        if (!$this->enseignement) {
            $this->enseignement = $this->getList($this->finderByEnseignement());
        }

        return $this->enseignement;
    }



    /**
     * Retourne le semestre 1
     *
     * @return PeriodeEntity
     */
    public function getSemestre1()
    {
        return $this->getRepo()->findOneBy(['code' => PeriodeEntity::SEMESTRE_1]);
    }



    /**
     * Retourne le semestre 2
     *
     * @return PeriodeEntity
     */
    public function getSemestre2()
    {
        return $this->getRepo()->findOneBy(['code' => PeriodeEntity::SEMESTRE_2]);
    }



    /**
     * Retourne le paiement tardif
     *
     * @return PeriodeEntity
     */
    public function getPaiementTardif()
    {
        return $this->getRepo()->findOneBy(['code' => PeriodeEntity::PAIEMENT_TARDIF]);
    }
}