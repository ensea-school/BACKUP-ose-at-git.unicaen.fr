<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Structure as StructureEntity;


/**
 * Description of ServiceReferentiel
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentiel extends AbstractEntityService
{
    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\ServiceReferentiel';
    }
    
    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'seref';
    }

    /**
     * Initialise une requête
     * Permet de retourner des valeurs par défaut ou de les forcer en cas de besoin
     * Format de sortie : array( $qb, $alias ).
     *
     * @param QueryBuilder|null $qb      Générateur de requêtes
     * @param string|null $alias         Alias d'entité
     * @return array
     */
    public function initQuery(QueryBuilder $qb=null, $alias=null)
    {
        list($qb, $alias) = parent::initQuery($qb, $alias);

        $this->leftJoin( $this->getServiceStructure()       , $qb, 'structure'      , true, $alias )
             ->join( $this->getServiceFonctionReferentiel() , $qb, 'fonction'       , true, $alias )
             ->join( $this->getServiceIntervenant()         , $qb, 'intervenant'    , true, $alias );

        return array($qb,$alias);
    }

    /**
     * Retourne le query builder permettant de rechercher les services référentiels
     * selon la composante spécifiée.
     *
     * @param StructureEntity $structure
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByComposante(StructureEntity $structure, QueryBuilder $qb = null, $alias = null)
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $iAlias             = $this->getServiceIntervenant()->getAlias();
        $filter = "($iAlias.structure = :composante OR $alias.structure = :composante)";
        $qb->andWhere($filter)->setParameter('composante', $structure);

        return $qb;
    }

    /**
     * Retourne la liste des services selon le contexte donné
     *
     * @param QueryBuilder|null $queryBuilder
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this->finderByAnnee( $context->getannee(), $qb, $alias ); // Filtre d'année obligatoire

        if ($role instanceof \Application\Acl\IntervenantRole){ // Si c'est un intervenant
            $this->finderByIntervenant( $role->getIntervenant(), $qb, $alias );
        }

        return $qb;
    }

    /**
     * Retourne la liste des intervenants
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null $alias
     * @return \Application\Entity\Db\ServiceReferentiel[]
     */
    public function getList( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy( $this->getServiceIntervenant()->getAlias().'.nomUsuel, '.$this->getServiceStructure()->getAlias().'.libelleCourt' );
        return parent::getList($qb, $alias);
    }

    /**
     * @return Intervenant
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('applicationIntervenant');
    }

    /**
     * @return Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return FonctionReferentiel
     */
    protected function getServiceFonctionReferentiel()
    {
        return $this->getServiceLocator()->get('applicationFonctionReferentiel');
    }
}