<?php

namespace Application\Service;

use Application\Entity\Db\Structure as StructureEntity;

use Doctrine\ORM\QueryBuilder;


/**
 * Description of Periode
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Periode extends AbstractEntityService
{
    /**
     * Périodes d'enseignement
     *
     * @var \Application\Entity\Db\Periode[]
     */
    protected $enseignement;




    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Periode';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'per';
    }

    /**
     *
     * @param \DateTime $date
     * @return PeriodeEntity
     */
    public function getDatePeriodePaiement( \DateTime $date=null )
    {
        if (empty($date)) $date = new \DateTime;
        $mois = (int)$date->format('m');
        if (0 == $mois) return null;
        return $this->getRepo()->findOneBy(['moisOriginePaiement' => $mois]);
    }

    /**
     * Retourne la liste des périodes d'enseignement
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByEnseignement( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->andWhere("$alias.enseignement = 1");
        return $qb;
    }

    public function finderByMiseEnPaiement(StructureEntity $structure=null, QueryBuilder $qb=null, $alias=null)
    {
        $serviceMIS = $this->getServiceLocator()->get('applicationMiseEnPaiementIntervenantStructure');
        /* @var $serviceMIS MiseEnPaiementIntervenantStructure */

        $serviceMiseEnPaiement = $this->getServiceLocator()->get('applicationMiseEnPaiement');
        /* @var $serviceMiseEnPaiement MiseEnPaiement */

        $serviceStructure = $this->getServiceLocator()->get('applicationStructure');
        /* @var $serviceStructure Structure */

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this               ->join( $serviceMIS             , $qb, 'miseEnPaiementIntervenantStructure', false, $alias );
        $serviceMIS         ->join( $serviceMiseEnPaiement  , $qb, 'miseEnPaiement'                                    );

        if ($structure){
            $serviceMIS->finderByStructure( $structure, $qb );
        }

        return $qb;
    }

    /**
     * Retourne la liste des périodes
     *
     * @param QueryBuilder|null $queryBuilder
     * @return Application\Entity\Db\Periode[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->orderBy("$alias.ordre");
        return parent::getList($qb, $alias);
    }

    /**
     * Retourne la liste dezs périodes d'enseignement
     *
     * @return \Application\Entity\Db\Periode[]
     */
    public function getEnseignement()
    {
        if (! $this->enseignement){
            $this->enseignement = $this->getList( $this->finderByEnseignement() );
        }
        return $this->enseignement;
    }

    /**
     * Retourne le semestre 1
     *
     * @return \Application\Entity\Db\Periode
     */
    public function getSemestre1()
    {
        return $this->getRepo()->findOneBy(['code' => PeriodeEntity::SEMESTRE_1]);
    }

    /**
     * Retourne le semestre 2
     *
     * @return \Application\Entity\Db\Periode
     */
    public function getSemestre2()
    {
        return $this->getRepo()->findOneBy(['code' => PeriodeEntity::SEMESTRE_2]);
    }
}