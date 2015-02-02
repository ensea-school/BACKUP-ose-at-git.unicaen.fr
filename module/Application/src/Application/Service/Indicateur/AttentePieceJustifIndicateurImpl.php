<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\WfEtape;
use Application\Service\Intervenant;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttentePieceJustifIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire n'a pas fourni toutes les pièces justificatives obligatoires";
    protected $pluralTitlePattern   = "%s vacataires n'ont pas fourni toutes les pièces justificatives obligatoires";
    
    /**
     * Retourne l'URL de la page concernant une ligne de résultat de l'indicateur.
     * 
     * @param IntervenantEntity $result
     * @return string
     */
    public function getResultUrl($result)
    {
        return $this->getHelperUrl()->fromRoute(
                'piece-jointe/intervenant', 
                ['intervenant' => $result->getSourceCode()], 
                ['force_canonical' => true]);
    }
    
    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder()
    {
        $service = $this->getServiceLocator()->get('ApplicationIntervenant'); /* @var $service Intervenant */
        
        /**
         * Dans la progression de l'intervenant dans le WF, toutes les étapes précédant l'étape 
         * "Pièces justificatives" doivent avoir été franchies.
         */
        $qb = $service->finderByWfEtapeCourante(WfEtape::CODE_PJ_SAISIE);
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée.
         */
        if ($this->getStructure()) {
            $qb
                    ->join("int.service", "s", Join::WITH, "s.structureEns = :structure")
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}