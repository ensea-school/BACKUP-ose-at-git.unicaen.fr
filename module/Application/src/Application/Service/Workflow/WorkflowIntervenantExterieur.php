<?php

namespace Application\Service\Workflow;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\DossierValideRule;
use Application\Rule\Intervenant\PeutSaisirDossierRule;
use Application\Rule\Intervenant\PeutSaisirPieceJointeRule;
use Application\Rule\Intervenant\PeutSaisirServiceRule;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;
use Application\Rule\Intervenant\PossedeDossierRule;
use Application\Rule\Intervenant\PossedeServicesRule;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Rule\Intervenant\NecessiteContratRule;
use Application\Rule\Intervenant\ContratEditeRule;
use Application\Service\Workflow\Step;
use Common\Exception\LogicException;

/**
 * Implémentation du workflow concernant un intervenant extérieur.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowIntervenantExterieur extends WorkflowIntervenant
{
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
        $this->getServiceLocator()->get('ApplicationTypePieceJointeStatut');
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
        
        $necessiteAgrement = $this->getServiceLocator()->get('NecessiteAgrementRule'); /* @var $necessiteAgrement NecessiteAgrementRule */
        $necessiteAgrement
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($this->getTypeAgrementConseilRestreint());
        if (!$necessiteAgrement->isRelevant() || $necessiteAgrement->execute()) {
            $transitionRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $transitionRule AgrementFourniRule */
            $transitionRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($this->getTypeAgrementConseilRestreint());
            
            $this->addStep(
                    self::INDEX_CONSEIL_RESTREINT,
                    new Step\AgrementStep($this->getTypeAgrementConseilRestreint()),
                    $transitionRule
            );
        }
        
        $necessiteAgrement
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($this->getTypeAgrementConseilAcademique());
        if (!$necessiteAgrement->isRelevant() || $necessiteAgrement->execute()) {
            $transitionRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $transitionRule AgrementFourniRule */
            $transitionRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($this->getTypeAgrementConseilAcademique());
            
            $this->addStep(
                    self::INDEX_CONSEIL_ACADEMIQUE,
                    new Step\AgrementStep($this->getTypeAgrementConseilAcademique()),
                    $transitionRule
            );
        }
        
        $necessiteContrat = new NecessiteContratRule($this->getIntervenant());
        if (!$necessiteContrat->isRelevant() || $necessiteContrat->execute()) {
            $this->addStep(
                    self::INDEX_EDITION_CONTRAT,
                    new Step\EditionContratStep(),
                    (new ContratEditeRule($this->getIntervenant()))->setTypeValidation($this->getTypeValidationContrat())
            );
        }
        
//        $this->addStep(
//                self::INDEX_FINAL,
//                new Step\FinalStep(),
//                null
//        );
            
        return $this;
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