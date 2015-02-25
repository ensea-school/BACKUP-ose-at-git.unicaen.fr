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
class AttenteMepIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s intervenant peut faire l'objet d'une mise en paiement";
    protected $pluralTitlePattern   = "%s intervenants peuvent faire l'objet d'une mise en paiement";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'paiement/etat-demande-paiement', 
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
         * L'intervenant doit posséder des heures complémentaire ayant fait l'objet d'une *demande* de mise en paiement.
         */
        $qb
                ->join("int.vIndicAttenteMep", "v", Join::WITH, "v.annee = :annee")
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