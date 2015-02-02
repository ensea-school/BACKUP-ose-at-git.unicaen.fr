<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;
use Traversable;
use Zend\Filter\Callback;
use Zend\Filter\FilterInterface;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractIntervenantResultIndicateurImpl extends AbstractIndicateurImpl
{
    /**
     * Retourne la liste de résultats renvoyée par l'indicateur.
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
     * Retourne le filtre permettant de formater comme il se doit chaque item de résultat.
     * 
     * @return FilterInterface
     */
    public function getResultFormatter()
    {
        if (null === $this->resultFormatter) {
            $this->resultFormatter = new Callback(function(IntervenantEntity $resultItem) { 
                $out = sprintf("%s <small>(n°%s%s)</small>", 
                        $resultItem, 
                        $resultItem->getSourceCode(),
                        $resultItem->getStatut()->estPermanent() ? ", " . $resultItem->getStructure() : null);
                return $out;
            });
        }
        
        return $this->resultFormatter;
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
        
        $qb = $this->getQueryBuilder()->select("COUNT(DISTINCT int)");
        
        return (int) $qb->getQuery()->getSingleScalarResult();
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("int");
        $qb
                ->select("int, si, ti, str")
                ->join("int.statut", "si")
                ->join("int.type", "ti")
                ->join("int.structure", "str");
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}