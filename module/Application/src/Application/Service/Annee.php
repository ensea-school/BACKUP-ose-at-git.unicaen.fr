<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Annee as AnneeEntity;

/**
 * Description of Annee
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Annee extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\Annee';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'annee';
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param string $alias
     * @return QueryBuilder
     */
    public function finderByOffreFormation( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $sElementPedagogique = $this->getServiceLocator()->get('applicationElementPedagogique');
        /* @var $sElementPedagogique ElementPedagogique */

        $qb->andWhere(
            $qb->expr()->exists( "SELECT ep_0.id FROM ".$sElementPedagogique->getEntityClass()." ep_0 WHERE ep_0.annee = $alias AND 1=compriseEntre(ep_0.histoCreation,ep_0.histoDestruction)" )
        );

        return $qb;
    }

    /**
     * Retourne l'année N - x.
     *
     * @param AnneeEntity $annee Année de référence
     * @param int $x Entier supérieur ou égal à zéro
     * @return AnneeEntity
     */
    public function getNmoins(AnneeEntity $annee, $x)
    {
        return $this->get( $annee->getId() - (int) $x );
    }

    /**
     * 
     * @param AnneeEntity $annee
     * @return AnneeEntity
     */
    public function getPrecedente(AnneeEntity $annee)
    {
        return $this->get( $annee->getId() - 1 );
    }

    /**
     *
     * @param AnneeEntity $annee
     * @return AnneeEntity
     */
    public function getSuivante(AnneeEntity $annee)
    {
        return $this->get( $annee->getId() + 1 );
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
        $qb->orderBy("$alias.id");
        return parent::getList($qb, $alias);
    }

    /**
     * Retourne la liste des ID des années sélectionnables
     */
    public function getChoixAnnees()
    {
        $sql = 'SELECT DISTINCT 
            annee_id
        FROM 
            ELEMENT_PEDAGOGIQUE ep
        WHERE 
            1 = OSE_DIVERS.COMPRISE_ENTRE(ep.HISTO_CREATION, ep.HISTO_DESTRUCTION)
        ORDER BY 
            annee_id ASC';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $annees = [];
        foreach( $result as $annee ){
            $a = $annee['ANNEE_ID'];
            $annees[$a] = $a.'-'.($a+1);
        }
        return $annees;
    }

}