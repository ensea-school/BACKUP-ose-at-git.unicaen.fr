<?php

namespace Application\Service\Indicateur\Contrat;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Service\Indicateur\AbstractIntervenantResultIndicateurImpl;
use Application\Service\Indicateur\DateAwareIndicateurImplInterface;
use Common\Constants;
use DateTime;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratAvenantDeposesIndicateurImpl extends AbstractIntervenantResultIndicateurImpl implements DateAwareIndicateurImplInterface
{
    protected $singularTitlePattern = "%s contrat/avenant de vacataire a été déposé";
    protected $pluralTitlePattern   = "%s contrats/avenants de vacataire ont été déposés";
    protected $dateDepuis;
    
    /**
     * Spécifie la date inférieure utilisée comme critère de recherche.
     * 
     * @param DateTime $dateDepuis
     * @return self
     */
    function setDate(DateTime $dateDepuis = null)
    {
        $this->dateDepuis = $dateDepuis;
        
        $this->setDirty();
        
        return $this;
    }

    /**
     * Retourne la date inférieure utilisée comme critère de recherche.
     * 
     * @return DateTime
     */
    public function getDate()
    {
        return $this->dateDepuis;
    }
    
    /**
     * 
     * @param bool $appendStructure
     * @return string
     */
    public function getTitle($appendStructure = true)
    {
        if ($this->getDate()) {
            $suffix = " depuis le " . $this->getDate()->format(Constants::DATETIME_FORMAT);
            $this->singularTitlePattern .= $suffix;
            $this->pluralTitlePattern   .= $suffix;
        }
        
        $count   = $this->getResultCount();
        $pattern = $count === 1 ? $this->singularTitlePattern : $this->pluralTitlePattern;
        $title   = sprintf($pattern, $count);
        
        if ($this->getStructure()) {
            $title .= " ({$this->getStructure()})";
        }
        
        return $title;
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
        $qb->join("c.fichier", "f");
        
        if ($this->getDate()) {
            $qb
                ->andWhere("f.histoCreation >= :dateDepuis")
                ->setParameter("dateDepuis", $this->getDate());
        }
        
        /**
         * NB: pas besoin de consulter la progression dans le workflow car si l'intervenant a déjà un contrat/avenant,
         * c'est qu'il a bien atteint l'étape "contrat".
         */
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("c.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        return $qb;
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $this->initFilters();
        
        // INDISPENSABLE si plusieurs requêtes successives sur IntervenantExterieur !
        $this->getEntityManager()->clear('Application\Entity\Db\IntervenantExterieur');
        
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\IntervenantExterieur')->createQueryBuilder("int");
        $qb
                ->join("int.contrat", "c")
                ->join("c.fichier", "f");
     
        /**
         * NB: pas besoin de consulter la progression dans le workflow car si l'intervenant a déjà un contrat/avenant,
         * c'est qu'il a bien atteint l'étape "contrat".
         */
        
        if ($this->getDate()) {
            $qb
                ->andWhere("f.histoCreation >= :dateDepuis")
                ->setParameter("dateDepuis", $this->getDate());
        }
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("c.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
    
    /**
     * Activation du filtrage Doctrine sur l'historique.
     */
    protected function initFilters()
    {
        $this->getEntityManager()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Contrat',
                'Application\Entity\Db\Fichier',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }
}