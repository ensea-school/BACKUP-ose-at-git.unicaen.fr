<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Annee;
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
        if (null === $this->result || $this->dirtyResult) {
            $qb                = $this->getQueryBuilder();
            $this->result      = $qb->getQuery()->getResult();
            $this->dirtyResult = false;
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
     * 
     * @return integer
     */
    public function getResultCount()
    {
        if (null !== $this->resultCount && !$this->dirtyResultCount) {
            return $this->resultCount;
        }
        
        if (null !== $this->result && !$this->dirtyResult) {
            $this->resultCount = count($this->result);
        }
        else {
            $qb = $this->getQueryBuilder()->select("COUNT(DISTINCT int)");
            $this->resultCount = (int) $qb->getQuery()->getSingleScalarResult();
        }
        
        $this->dirtyResultCount = false;
        
        return $this->resultCount;
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\Intervenant');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("int");
        $qb
                ->select("int, si, ti, str")
                ->join("int.statut", "si")
                ->join("int.type", "ti")
                ->join("int.structure", "str")
                ->andWhere("int.annee = :annee")
                ->setParameter("annee", $this->getContextProvider()->getGlobalContext()->getAnnee());
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * 
     * @return Annee
     */
    protected function getAnnee()
    {
        return $this->getContextProvider()->getGlobalContext()->getAnnee();
    }
}