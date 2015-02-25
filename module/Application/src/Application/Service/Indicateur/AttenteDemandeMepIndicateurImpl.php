<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteDemandeMepIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s intervenant peut faire l'objet d'une demande de mise en paiement";
    protected $pluralTitlePattern   = "%s intervenants peuvent faire l'objet d'une demande de mise en paiement";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'intervenant/demande-mise-en-paiement', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * 
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("int");
        $qb
                ->join("int.structure", "aff")
                ->join("int.statut", "si");
     
        /**
         * L'intervenant doit posséder des heures complémentaire pouvant faire l'objet d'une (demande de) mise en paiement.
         */
        $qb
                ->join("int.vIndicAttenteDemandeMep", "v", Join::WITH, "v.annee = :annee")
                ->setParameter("annee", $this->getContextProvider()->getGlobalContext()->getAnnee());
        
        if ($this->getStructure()) {
            $qb
                    ->andWhere("v.structure = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}