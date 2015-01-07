<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\WfEtape;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Traversable;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteContratIndicateurImpl extends AbstractIndicateurImpl
{
    protected $singularTitlePattern   = "%s vacataire est en attente de son contrat initial";
    protected $pluralTitlePattern = "%s vacataires sont en attente de leur contrat initial";
    
    /**
     * 
     * @return Traversable
     */
    public function getResult()
    {
        if (null === $this->result) {
            $qb = $this->getQueryBuilder();
//            print_r($qb->getQuery()->getSQL());

            $this->result = $qb->getQuery()->getResult();
        }
            
        return $this->result;
    }
    
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
     * @return integer
     */
    public function getResultCount()
    {
        if (null !== $this->result) {
            return count($this->result);
        }
        
        $qb = $this->getQueryBuilder()->select("COUNT(DISTINCT i)");
//        print_r($qb->getQuery()->getSQL());die;
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("i");
        $qb
                ->join("i.statut", "st", Join::WITH, "st.peutAvoirContrat = 1")
                ->join("i.service", "s")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.validation", "v");
        
        /**
         * Dans la progression de l'intervenant dans le WF, toutes les étapes précédant l'étape Contrat doivent avoir été franchies
         */
        $qb
                ->join("i.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :codeEtape")
                ->setParameter('codeEtape', WfEtape::CODE_CONTRAT);
        
        /**
         * Aucun contrat validé ne doit exister
         */
        $notExists = 
                "SELECT c FROM Application\Entity\Db\Contrat c " .
                "JOIN c.typeContrat tc WITH tc.code = :codeTypeContrat " .
                "WHERE c.intervenant = i AND c.validation IS NOT NULL ";
        $qb
                ->andWhere("NOT EXISTS ( $notExists )")
                ->setParameter('codeTypeContrat', \Application\Entity\Db\TypeContrat::CODE_CONTRAT);
         
        return $qb;
    }
}