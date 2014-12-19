<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\WfEtape;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Traversable;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AttenteAgrementAbstractIndicateurImpl extends AbstractIndicateurImpl
{
    protected $titlePattern     = "%s vacataires sont en attente d'agrément %s";
    protected $codeTypeAgrement = TypeAgrement::CODE_CONSEIL_RESTREINT;
    protected $codeEtape        = WfEtape::CODE_CONSEIL_RESTREINT;
    
    /**
     * 
     */
    public function getTitle()
    {
        $title = sprintf($this->titlePattern, $this->getResultCount(), $this->getTypeAgrement());
        
        if ($this->getStructure()) {
            $title .= " ({$this->getStructure()})";
        }
        
        return $title;
    }
    
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
        return $this->getHelperUrl()->fromRoute('intervenant/agrement/liste', [
            'intervenant'  => $result->getSourceCode(),
            'typeAgrement' => $this->getTypeAgrement()->getId(),
        ]);
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
        
        /**
         * Dans la progression de l'intervenant dans le WF, toutes les étapes précédant l'étape 
         * "Agrément Conseil Restreint" doivent avoir été franchies
         */
        $qb
                ->join("i.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :codeEtape")
                ->setParameter('codeEtape', $this->codeEtape);
         
        return $qb;
    }
    
    protected $typeAgrement;
    
    /**
     * Retourne le type d'agrément concerné.
     * 
     * @return TypeAgrement
     */
    public function getTypeAgrement()
    {
        if (null === $this->typeAgrement) {
            $service            = $this->getServiceLocator()->get('ApplicationTypeAgrement');
            $qb                 = $service->finderByCode($this->codeTypeAgrement);
            $this->typeAgrement = $qb->getQuery()->getOneOrNullResult();
        }
        
        return $this->typeAgrement;
    }
}