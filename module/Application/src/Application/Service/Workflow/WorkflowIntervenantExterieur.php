<?php

namespace Application\Service\Workflow;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Rule\Intervenant\DossierValideRule;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Rule\Intervenant\NecessiteContratRule;
use Application\Rule\Intervenant\PeutSaisirDossierRule;
use Application\Rule\Intervenant\PeutSaisirPieceJointeRule;
use Application\Rule\Intervenant\PeutSaisirServiceRule;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;
use Application\Rule\Intervenant\PossedeContratRule;
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
     * Création des différentes étapes composant le workflow.
     * 
     * @return self
     */
    protected function createSteps()
    {        
        $this->steps = array();
        
        /**
         * Saisie des données personnelles
         */
        $peutSaisirDossier = new PeutSaisirDossierRule($this->getIntervenant());
        if (!$peutSaisirDossier->isRelevant() || $peutSaisirDossier->execute()) {
            $transitionRule = new PossedeDossierRule($this->getIntervenant());
            $this->addStep(
                    self::KEY_SAISIE_DONNEES,
                    new Step\SaisieDossierStep(),
                    $transitionRule
            );
        }
        
        /**
         * Saisie des services
         */
        $peutSaisirServices = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirServices->isRelevant() || $peutSaisirServices->execute()) {
            $transitionRule = new PossedeServicesRule($this->getIntervenant());
            $this->addStep(
                    self::KEY_SAISIE_SERVICE,
                    new Step\SaisieServiceStep(),
                    $transitionRule
            );
        }
        
        /**
         * Checklist des pièces justificatives
         */
        $peutSaisirPj = new PeutSaisirPieceJointeRule($this->getIntervenant());
        if (!$peutSaisirPj->isRelevant() || $peutSaisirPj->execute()) {
            $serviceTypePieceJointeStatut = $this->getServiceLocator()->get('ApplicationTypePieceJointeStatut');
            $transitionRule = new PiecesJointesFourniesRule($this->getIntervenant(), $serviceTypePieceJointeStatut);
            $this->addStep(
                    self::KEY_PIECES_JOINTES,
                    new Step\SaisiePiecesJointesStep(),
                    $transitionRule
            );
        }
        
        /**
         * Validation des données personnelles
         */
        if (!$peutSaisirDossier->isRelevant() || $peutSaisirDossier->execute()) {
            $transitionRule = (new DossierValideRule($this->getIntervenant()))->setTypeValidation($this->getTypeValidationDossier());
            $this->addStep(
                    self::KEY_VALIDATION_DONNEES,
                    new Step\ValidationDossierStep(),
                    $transitionRule
            );
        }
        
        /**
         * Validation des services
         */
        $peutSaisirService = new PeutSaisirServiceRule($this->getIntervenant());
        if (!$peutSaisirService->isRelevant() || $peutSaisirService->execute()) {
            $transitionRule = $this->getServiceValideRule();
            $this->addStep(
                    self::KEY_VALIDATION_SERVICE,
                    new Step\ValidationServiceStep(),
                    $transitionRule
            );
        }
        
        /**
         * Agrements des différents conseils
         */
        $necessiteAgrement = $this->getServiceLocator()->get('NecessiteAgrementRule'); /* @var $necessiteAgrement NecessiteAgrementRule */
        $necessiteAgrement->setIntervenant($this->getIntervenant());
        foreach ($necessiteAgrement->getTypesAgrementAttendus() as $typeAgrement) {
            $transitionRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $transitionRule AgrementFourniRule */
            $transitionRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($typeAgrement)
                ->setStructure($this->getStructure());

            $this->addStep(
                     'KEY_' . $typeAgrement->getCode(),
                    new Step\AgrementStep($typeAgrement),
                    $transitionRule
            );
        }
        
        /**
         * Contrat / avenant
         */
        $necessiteContrat = new NecessiteContratRule($this->getIntervenant());
        if (!$necessiteContrat->isRelevant() || $necessiteContrat->execute()) {
            $transitionRule = new PossedeContratRule($this->getIntervenant());
            $transitionRule
//                    ->setTypeValidation($this->getTypeValidationContrat())
                    ->setStructure($this->getStructure())
                    ->setValide(true);
            $this->addStep(
                    self::KEY_EDITION_CONTRAT,
                    new Step\EditionContratStep(),
                    $transitionRule
            );
        }
        
//        $this->addStep(
//                self::KEY_FINAL,
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