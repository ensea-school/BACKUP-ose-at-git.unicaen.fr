<?php

namespace Application\Service;

use Application\Entity\Db\Periode;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\ElementPedagogique;
use Paiement\Service\MiseEnPaiementIntervenantStructureServiceAwareTrait;
use Paiement\Service\MiseEnPaiementServiceAwareTrait;

/**
 * Description of Periode
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PeriodeService extends AbstractEntityService
{
    use MiseEnPaiementServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use MiseEnPaiementIntervenantStructureServiceAwareTrait;

    /**
     * Périodes d'enseignement
     *
     * @var Periode[]
     */
    protected $enseignement;

    /**
     * @var Periode[]
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
        return Periode::class;
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
     * @return Periode
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
     * @return Periode
     */
    public function getPeriodePaiement(\DateTime $date = null)
    {
        $anneeDateDebut = $this->getServiceContext()->getAnnee()->getDateDebut();
        $aY             = (int)$anneeDateDebut->format('Y');
        $aM             = (int)$anneeDateDebut->format('n');

        if (empty($date)) $date = new \DateTime;
        $dY = (int)$date->format('Y');
        $dM = (int)$date->format('n');

        $ecartMoisPaiement = (($dY - $aY) * 12 + $dM - $aM) + 1;

        return $this->getRepo()->findOneBy(['paiement' => true, 'ecartMois' => $ecartMoisPaiement]);
    }



    public function findPeriodeByElementPedagogique(ElementPedagogique $elementPedagogique): array
    {
        $sql = "
            SELECT 
                p.id, p.libelle_long              
            FROM 
              element_pedagogique ep 
              JOIN periode p ON p.id = ep.periode_id
            WHERE 
              ep.id = :elementPedagogique
        ";

        $res = $this->getEntityManager()->getConnection()->fetchAllKeyValue($sql, ['elementPedagogique' => $elementPedagogique->getId()]);

        return $res;
    }



    public function finderByMiseEnPaiement(Structure $structure = null, ?QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        [$qb, $alias] = $this->initQuery($qb, $alias);

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
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->orderBy("$alias.ordre");

        return $qb;
    }



    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @return Periode[]
     */
    public function getEnseignement()
    {
        if (!$this->enseignement) {
            $this->enseignement = $this->getList($this->finderByEnseignement());
        }

        return $this->enseignement;
    }



    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @param QueryBuilder|null $queryBuilder
     *
     * @return QueryBuilder
     */
    public function finderByEnseignement(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.enseignement = 1");

        return $qb;
    }



    /**
     * Retourne la liste des périodes de paiement
     *
     * @return Periode[]
     */
    public function getPaiement()
    {

        [$qb, $alias] = $this->initQuery();
        $qb->andWhere("$alias.paiement = 1");
        $qb->andWhere("$alias.ordre <= 13");

        return $this->getList($qb);
    }



    /**
     * Retourne le semestre 1
     *
     * @return Periode
     */
    public function getSemestre1()
    {
        return $this->getRepo()->findOneBy(['code' => Periode::SEMESTRE_1]);
    }



    /**
     * Retourne le semestre 2
     *
     * @return Periode
     */
    public function getSemestre2()
    {
        return $this->getRepo()->findOneBy(['code' => Periode::SEMESTRE_2]);
    }



    /**
     * Retourne le paiement tardif
     *
     * @return Periode|null
     */
    public function getPaiementTardif(): ?Periode
    {
        return $this->getRepo()->findOneBy(['code' => Periode::PAIEMENT_TARDIF]);
    }



    /**
     * Sauvegarde la periode
     *
     * @param Periode $entity
     */
    public function save($entity)
    {
        if (empty($entity->getOrdre())) {
            $ordre = (int)$this->getEntityManager()->getConnection()->fetchOne("SELECT MAX(ORDRE) M FROM PERIODE P");
            $ordre++;
            $entity->setOrdre($ordre);
        }

        return parent::save($entity);
    }

}