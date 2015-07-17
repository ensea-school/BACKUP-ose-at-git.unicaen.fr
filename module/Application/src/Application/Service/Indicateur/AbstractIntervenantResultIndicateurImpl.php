<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\ORMException;
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
    protected $intervenantMessage;
    
    /**
     * Retourne la liste de résultats renvoyée par l'indicateur.
     * 
     * @return Traversable
     */
    public function getResult()
    {
        if (null === $this->result || $this->dirtyResult) {
            $qb = $this->getQueryBuilder();
            try {
                $this->result = $qb->getQuery()->getResult();
            }
            catch (ORMException $e) {
                throw new RuntimeException(
                        "Erreur rencontrée lors de la requete de l'indicateur {$this->getIndicateurEntity()->getCode()}.",
                        null, 
                        $e);
            }
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
            $method = __METHOD__;
            $this->resultFormatter = new Callback(function($resultItem) use ($method) {
                if (! $resultItem instanceof IntervenantEntity) {
                    throw new LogicException(sprintf(
                        "L'argument transmis au formatter de résultat n'est pas du type %s attendu, redéfinissez la méthode %s dans la classe %s pour fournir un autre formatter.",
                        'Application\Entity\Db\Intervenant',
                        $method,
                        get_called_class()
                    ));
                }
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
     * Collecte et retourne les adresses mails de tous les intervenants retournés par cet indicateur.
     * 
     * @return array
     */
    public function getResultEmails()
    {
        $resultEmails = [];
        foreach ($this->getResult() as $r) { /* @var $r IntervenantEntity */
            $resultEmails[$r->getEmailPerso(true)] = $r->getNomComplet();
        }
        
        return $resultEmails;
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
            
            try {
                $this->resultCount = (int) $qb->getQuery()->getSingleScalarResult();
            }
            catch (ORMException $e) {
                throw new RuntimeException(
                        "Erreur rencontrée lors de la requete COUNT de l'indicateur {$this->getIndicateurEntity()->getCode()}.",
                        null, 
                        $e);
            }
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
            ->setParameter("annee", $this->getServiceContext()->getAnnee())
            ->andWhere("1 = pasHistorise(int)");
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Retourne l'éventuel message s'adressant à l'intervenant à propos de cet indicateur.
     * 
     * @return string|null
     */
    public function getIntervenantMessage()
    {
        return $this->intervenantMessage;
    }
    
    /**
     * 
     * @return Annee
     */
    protected function getAnnee()
    {
        return $this->getServiceContext()->getAnnee();
    }
}