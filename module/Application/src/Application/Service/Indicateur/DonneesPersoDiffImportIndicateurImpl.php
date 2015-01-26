<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\QueryBuilder;
use Traversable;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DonneesPersoDiffImportIndicateurImpl extends AbstractIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire a saisi des données personnelles qui diffèrent de celles importées";
    protected $pluralTitlePattern   = "%s vacataires ont saisi des données personnelles qui diffèrent de celles importées";
    
    /**
     * 
     * @return Traversable
     */
    public function getResult()
    {
        if (null === $this->result) {
            $qb = $this->getQueryBuilder();

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
                'indicateur/result-item', 
                ['action' => 'result-item-donnees-perso-diff-import', 'intervenant' => $result->getSourceCode()], 
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
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("i");
        $qb
                ->join("i.statut", "st", \Doctrine\ORM\Query\Expr\Join::WITH, "st.peutSaisirDossier = 1")
                ->join("i.vIndicDiffDossier", "vidd")
                ->andWhere(
                        "vidd.adresseDossier IS NOT NULL OR " . 
                        "vidd.ribDossier IS NOT NULL OR " . 
                        "vidd.nomUsuelDossier IS NOT NULL OR " . 
                        "vidd.prenomDossier IS NOT NULL");
        
        return $qb;
    }
    
    /**
     * Surcharge pour ne renvoyer aucune structure car la contrat initial peut être
     * établi par n'importe quelle composante d'enseignement.
     * 
     * @return null
     */
    public function getStructure()
    {
        return null;
    }
}