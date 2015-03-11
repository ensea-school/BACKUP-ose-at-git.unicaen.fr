<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeContrat;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteRetourContratIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s contrat de vacataires est en attente de retour";
    protected $pluralTitlePattern   = "%s contrats de vacataires sont en attente de retour";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/contrat', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * 
     * @return int
     */
    public function getResultCount()
    {
        $qb = $this->getTitleQueryBuilder()->select("COUNT(DISTINCT c)");

        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getTitleQueryBuilder()
    {
        // INDISPENSABLE si plusieurs requêtes successives sur Contrat !
        $this->getEntityManager()->clear('Application\Entity\Db\Contrat');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Contrat')->createQueryBuilder("c");
        
        $this->initQueryBuilder($qb);
        
        return $qb;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("int");
        $qb->join("int.contrat", "c");
        
        $this->initQueryBuilder($qb);
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    protected function initQueryBuilder(QueryBuilder $qb)
    {
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\IntervenantExterieur');
        
        $qb
                ->join("c.typeContrat", "tc", Join::WITH, "tc.code = :codeTypeContrat")
                ->setParameter('codeTypeContrat', TypeContrat::CODE_CONTRAT)
                ->join("c.validation", "v")
                ->andWhere("c.dateRetourSigne IS NULL");
     
        /**
         * NB: pas besoin de consulter la progression dans le workflow car si l'intervenant a déjà un contrat/avenant,
         * c'est qu'il a bien atteint l'étape "contrat".
         */
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("c.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
    }
}