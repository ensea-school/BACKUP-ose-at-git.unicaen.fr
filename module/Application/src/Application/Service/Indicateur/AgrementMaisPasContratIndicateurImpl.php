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
class AgrementMaisPasContratIndicateurImpl extends AbstractIndicateurImpl
{
    protected $singularTitlePattern   = "%s vacataire a reçu l'agrément du Conseil Académique et n'a pas encore de contrat/avenant";
    protected $pluralTitlePattern = "%s vacataires ont reçu l'agrément du Conseil Académique et n'ont pas encore de contrat/avenant";
    
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
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("i");
        $qb
                ->join("i.statut", "st", Join::WITH, "st.peutAvoirContrat = 1")
                ->join("i.agrement", "a")
                ->join("a.type", "ta", Join::WITH, "ta.code = :cta")
                ->setParameter('cta', \Application\Entity\Db\TypeAgrement::CODE_CONSEIL_ACADEMIQUE)
                // l'étape Contrat doit être courante
                ->join("i.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :ce")
                ->setParameter('ce', WfEtape::CODE_CONTRAT);
        
        if ($this->getStructure()) {
            $qb
                    ->leftJoin("i.contrat", "c", Join::WITH, "c.validation IS NOT NULL AND c.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        else {
            $qb->leftJoin("i.contrat", "c", Join::WITH, "c.validation IS NOT NULL");
        }
        
        $qb->andWhere("c.id IS NULL");
         
        return $qb;
    }
}