<?php

namespace Application\Service;

use Application\Service\Traits\IntervenantAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Indicateur;
use Application\Entity\Db\Structure;


/**
 * Description of IndicateurService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Indicateur get($id)
 * @method Indicateur newEntity()
 *
 */
class IndicateurService extends AbstractEntityService
{
    protected $countCache = [];

    use IntervenantAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return Indicateur::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'indic';
    }



    /**
     * @param Indicateur $indicateur Indicateur concerné
     * @param null       $structure
     *
     * @return QueryBuilder
     */
    private function getBaseQueryBuilder(Indicateur $indicateur, $structure = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->from(\Application\Entity\Db\Indicateur\Indicateur::class . $indicateur->getNumero(), 'indicateur');

        /* Filtrage par intervenant */
        //$qb->join('indicateur.intervenant', 'intervenant');

        //$this->getServiceIntervenant()->finderByHistorique($qb, 'intervenant');
        //$this->getServiceIntervenant()->finderByAnnee($this->getServiceContext()->getAnnee(), $qb, 'intervenant');

        $qb->andWhere('indicateur.annee = :annee')->setParameter('annee', $this->getServiceContext()->getAnnee());

        /* Filtrage par structure, si nécessaire */
        if (!$structure) {
            $role = $this->getServiceContext()->getSelectedIdentityRole();
            if ($role){
                $structure = $role->getStructure();
            }
        }
        if ($structure) {
            $sign = $indicateur->getNotStructure() ? '<>' : '=';
            $qb->andWhere('indicateur.structure IS NULL OR indicateur.structure ' . $sign . ' ' . $structure->getId());
        }

        return $qb;
    }



    /**
     * @param integer|Indicateur   $indicateur Indicateur concerné
     * @param Structure|null $structure
     */
    public function getCount(Indicateur $indicateur, Structure $structure = null)
    {
        $key = $indicateur->getNumero().'_'.($structure ? $structure->getId() : '0');

        if (!isset($this->countCache[$key])) {
            $qb = $this->getBaseQueryBuilder($indicateur, $structure);
            $qb->addSelect('COUNT(' . ($indicateur->getDistinct() ? 'DISTINCT ' : '') . 'indicateur.intervenant) result');

            $this->countCache[$key] = (integer)$qb->getQuery()->getResult()[0]['result'];
        }

        return $this->countCache[$key];
    }



    /**
     * @param Indicateur $indicateur Indicateur concerné
     *
     * @return Indicateur\AbstractIndicateur[]
     */
    public function getResult(Indicateur $indicateur, Structure $structure = null)
    {
        $qb = $this->getBaseQueryBuilder($indicateur, $structure);

        $qb->join('indicateur.intervenant', 'intervenant');

        $qb->addSelect('indicateur');
        $qb->addSelect('partial intervenant.{id, nomUsuel, prenom, email, code, sourceCode}');

        $qb->addSelect('partial structure.{id, libelleCourt, libelleLong}');
        $qb->leftJoin('indicateur.structure', 'structure');

        $indicateurClass = \Application\Entity\Db\Indicateur\Indicateur::class . $indicateur->getNumero();
        $indicateurClass::appendQueryBuilder($qb);

        $qb->addOrderBy('structure.libelleCourt');
        $this->getServiceIntervenant()->orderBy($qb, 'intervenant');

        $entities = $qb->getQuery()->execute();
        /* @var $entities Indicateur\AbstractIndicateur[] */
        $result = [];
        foreach ($entities as $entity) {
            $result[$entity->getId()] = $entity;
        }

        return $result;
    }



    /**
     *
     * @param integer $numero
     *
     * @return \Application\Entity\Db\Indicateur
     */
    public function getByNumero($numero)
    {
        if (null == $numero) return null;

        $indicateur = $this->getRepo()->findOneBy(['numero' => $numero]);
        $indicateur->setServiceIndicateur($this);

        return $indicateur;
    }



    /**
     *
     * @param string $code
     *
     * @return \Application\Entity\Db\Indicateur
     */
    public function getByCode($code)
    {
        if (null == $code) return null;

        $indicateur = $this->getRepo()->findOneBy(['code' => $code]);
        $indicateur->setServiceIndicateur($this);

        return $indicateur;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.type, $alias.ordre");

        return $qb;
    }



    /**
     *
     * @param QueryBuilder $qb
     * @param string|null  $alias
     *
     * @return Indicateur[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->andWhere("$alias.enabled = 1");

        $list = parent::getList($qb, $alias);
        /* @var $list Indicateur[] */

        foreach ($list as $indicateur) {
            $indicateur->setServiceIndicateur($this);
        }

        return $list;
    }

}