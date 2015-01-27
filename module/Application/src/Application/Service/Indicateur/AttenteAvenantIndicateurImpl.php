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
class AttenteAvenantIndicateurImpl extends AbstractIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire est en attente de son avenant";
    protected $pluralTitlePattern   = "%s vacataires sont en attente de leur avenant";
    
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
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("i");
        $qb
                ->join("i.statut", "st", Join::WITH, "st.peutAvoirContrat = 1")
                ->join("i.service", "s")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.validation", "v");
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("s.structureEns = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        /**
         * L'intervenant doit posséder un contrat initial validé.
         */
        $qb
                ->join("i.contrat", "ci", Join::WITH, "ci.validation IS NOT NULL")
                ->join("ci.typeContrat", "tc", Join::WITH, "tc.code = :codeTypeContratInitial")
                ->setParameter('codeTypeContratInitial', \Application\Entity\Db\TypeContrat::CODE_CONTRAT);
        
        /**
         * L'étape Contrat doit être l'étape courante pour la composante d'enseignement concernée.
         */
        $qb
                ->join("i.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :code")
                ->setParameter('code', WfEtape::CODE_CONTRAT);
        if ($this->getStructure()) {
            $qb
                    ->andWhere("p.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("i.nomUsuel, i.prenom");
        
        return $qb;
    }
}