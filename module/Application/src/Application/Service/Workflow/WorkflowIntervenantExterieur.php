<?php

namespace Application\Service\Workflow;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\ContratEditeRule;
use Application\Rule\Intervenant\DossierValideRule;
use Application\Rule\Intervenant\ServiceValideRule;
use Application\Rule\Intervenant\NecessiteContratRule;
use Application\Rule\Intervenant\PeutSaisirDossierRule;
use Application\Rule\Intervenant\PeutSaisirPieceJointeRule;
use Application\Rule\Intervenant\PeutSaisirServiceRule;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;
use Application\Rule\Intervenant\PossedeDossierRule;
use Application\Rule\Intervenant\PossedeServicesRule;
use Application\Service\Workflow\Step;
use Common\Exception\LogicException;

/**
 * Implémentation du workflow concernant un intervenant extérieur.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowIntervenantExterieur extends WorkflowIntervenant
{
    const INDEX_SAISIE_DOSSIER     = 10;
    const INDEX_SAISIE_SERVICE     = 20;
    const INDEX_VALIDATION_DOSSIER = 30;
    const INDEX_VALIDATION_SERVICE = 35;
    const INDEX_PIECES_JOINTES     = 40;
    const INDEX_PASSAGE_CR         = 50;
    const INDEX_EDITION_CONTRAT    = 60;
    const INDEX_FINAL              = 100;
    
    /**
     * 
     * @param Intervenant $intervenant
     * @throws LogicException
     */
    public function setIntervenant(Intervenant $intervenant)
    {
        if (!$intervenant instanceof IntervenantExterieur) {
            throw new LogicException("Intervenant extérieur attendu.");
        }

        parent::setIntervenant($intervenant);
        
        return $this;
    }
    
    /**
     * 
     * @return WorkflowIntervenantExterieur
     */
    protected function createSteps()
    {        
        $this->steps = array();
        
        $peutSaisirDossier = new PeutSaisirDossierRule($this->getIntervenant());
        if (!$peutSaisirDossier->isRelevant() || $peutSaisirDossier->execute()) {
            $this->addStep(
                    self::INDEX_SAISIE_DOSSIER,
                    new Step\SaisieDossierStep(),
                    new PossedeDossierRule($this->getIntervenant())
            );
        }
        
        $peutSaisirServices = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirServices->isRelevant() || $peutSaisirServices->execute()) {
            $this->addStep(
                    self::INDEX_SAISIE_SERVICE,
                    new Step\SaisieServiceStep(),
                    new PossedeServicesRule($this->getIntervenant())
            );
        }
        
        $peutSaisirPj = new PeutSaisirPieceJointeRule($this->getIntervenant());
        if (!$peutSaisirPj->isRelevant() || $peutSaisirPj->execute()) {
            $serviceTypePieceJointeStatut = $this->getServiceLocator()->get('ApplicationTypePieceJointeStatut');
            $this->addStep(
                    self::INDEX_PIECES_JOINTES,
                    new Step\SaisiePiecesJointesStep(),
                    new PiecesJointesFourniesRule($this->getIntervenant(), $serviceTypePieceJointeStatut)
            );
        }
        
        if (!$peutSaisirDossier->isRelevant() || $peutSaisirDossier->execute()) {
            $this->addStep(
                    self::INDEX_VALIDATION_DOSSIER,
                    new Step\ValidationDossierStep(),
                    (new DossierValideRule($this->getIntervenant()))->setTypeValidation($this->getTypeValidationDossier())
            );
        }
        
        $peutSaisirService = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirService->isRelevant() || $peutSaisirService->execute()) {
            $this->addStep(
                    self::INDEX_VALIDATION_SERVICE,
                    new Step\ValidationServiceStep(),
                    $this->getServiceValideRule()
            );
        }
        
//        $necessiteContrat = new NecessiteContratRule($this->getIntervenant());
//        if (!$necessiteContrat->isRelevant() || $necessiteContrat->execute()) {
//            $this->addStep(
//                    self::INDEX_EDITION_CONTRAT,
//                    new Step\EditionContratStep(),
//                    (new ContratEditeRule($this->getIntervenant()))->setTypeValidation($this->getTypeValidationContrat())
//            );
//        }
        
//        $this->addStep(
//                self::INDEX_FINAL,
//                new Step\FinalStep(),
//                null
//        );
            
        return $this;
    }
    
    private $serviceValideRule;
    
    private function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            // teste si les enseignements ont été validés, MÊME PARTIELLEMENT
            $this->serviceValideRule = new ServiceValideRule($this->getIntervenant(), true);
            $this->serviceValideRule
                    ->setTypeValidation($this->getTypeValidationService())
                    ->setStructure($this->getStructure())
                    ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
//            var_dump(
//                    $this->serviceValideRule->execute(), 
//                    $this->serviceValideRule->getMessage(), 
//                    \UnicaenApp\Util::collectionAsOptions($this->serviceValideRule->getVolumesHorairesNonValides()));
        }
        
        return $this->serviceValideRule;
    }
    
    /**
     * 
     * @return \Application\Entity\Db\Structure
     */
    protected function getStructure()
    {
        if ($this->getRole() instanceof \Application\Acl\ComposanteDbRole) {
            return $this->getRole()->getStructure();
        }
        
        return null;
    }
    
    /**
     * @return TypeValidation
     */
    private function getTypeValidationDossier()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_DONNEES_PERSO_PAR_COMP);
        $typeValidation = $qb->getQuery()->getOneOrNullResult();
        
        return $typeValidation;
    }
    
    /**
     * @return TypeValidation
     */
    private function getTypeValidationContrat()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_CONTRAT_PAR_COMP);
        $typeValidation = $qb->getQuery()->getOneOrNullResult();
        
        return $typeValidation;
    }
}