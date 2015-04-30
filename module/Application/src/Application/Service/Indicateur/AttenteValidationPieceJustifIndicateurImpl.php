<?php

namespace Application\Service\Indicateur;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\WfEtape;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AttenteValidationPieceJustifIndicateurImpl extends AbstractIntervenantResultIndicateurImpl
{
    protected $singularTitlePattern = "%s vacataire est en attente de validation de ses pièces justificatives obligatoires";
    protected $pluralTitlePattern   = "%s vacataires sont en attente de validation de leurs pièces justificatives obligatoires";
    
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
        // INDISPENSABLE si plusieurs requêtes successives sur Intervenant !
        $this->getEntityManager()->clear('Application\Entity\Db\Intervenant');
        
        $service = $this->getServiceLocator()->get('ApplicationIntervenant');
        
        /**
         * Dans la progression de l'intervenant dans le WF, toutes les étapes précédant l'étape 
         * "Validation des pièces justificatives" doivent avoir été franchies.
         */
        $qb = $service->finderByWfEtapeCourante(WfEtape::CODE_PJ_VALIDATION);
        
        /**
         * L'intervenant doit intervenir dans la structure spécifiée.
         */
        if ($this->getStructure()) {
            $qb
                    ->join("int.service", "s")
                    ->join("s.elementPedagogique", "ep", Join::WITH, "ep.structure = :structure")
                    ->join("s.volumeHoraire", "vh")
                    ->join("vh.typeVolumeHoraire", "tvh", Join::WITH, "tvh = :tvh")
                    ->setParameter('tvh', $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire')->getPrevu())
                    ->setParameter('structure', $this->getStructure());
        }
        
        $qb->orderBy("int.nomUsuel, int.prenom");
        
        return $qb;
    }
}