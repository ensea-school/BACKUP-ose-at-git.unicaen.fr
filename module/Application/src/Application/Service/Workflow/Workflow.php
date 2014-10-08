<?php

namespace Application\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Expr;
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
use Application\Rule\Intervenant\ServiceValideRule;
use Application\Service\TypeAgrement as TypeAgrementService;
use Application\Service\TypeValidation as TypeValidationService;
use Application\Service\VolumeHoraire;
use Application\Service\Workflow\Step\AgrementStep;
use Application\Service\Workflow\Step\EditionContratStep;
use Application\Service\Workflow\Step\SaisieDossierStep;
use Application\Service\Workflow\Step\SaisiePiecesJointesStep;
use Application\Service\Workflow\Step\SaisieServiceStep;
use Application\Service\Workflow\Step\Step;
use Application\Service\Workflow\Step\ValidationDossierStep;
use Application\Service\Workflow\Step\ValidationServiceStep;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\RoleAwareTrait;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Workflow extends AbstractWorkflow
{
//    use IntervenantAwareTrait;
    use RoleAwareTrait;
    
    const KEY_SAISIE_DONNEES     = 'KEY_SAISIE_DOSSIER';
    const KEY_VALIDATION_DONNEES = 'KEY_VALIDATION_DONNEES';
    const KEY_SAISIE_SERVICE     = 'KEY_SAISIE_SERVICE';
    const KEY_VALIDATION_SERVICE = 'KEY_VALIDATION_SERVICE';
    const KEY_PIECES_JOINTES     = 'KEY_PIECES_JOINTES';
    const KEY_CONSEIL_RESTREINT  = 'KEY_CONSEIL_RESTREINT';  // NB: 'KEY_' . code type agrément
    const KEY_CONSEIL_ACADEMIQUE = 'KEY_CONSEIL_ACADEMIQUE'; // NB: 'KEY_' . code type agrément
    const KEY_EDITION_CONTRAT    = 'KEY_EDITION_CONTRAT';
    const KEY_FINAL              = 'KEY_FINAL';
    
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
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirDossierRule')->setIntervenant($this->getIntervenant());
        $crossingRule = $this->getServiceLocator()->get('PossedeDossierRule')->setIntervenant($this->getIntervenant());
        $this->addRule(
                self::KEY_SAISIE_DONNEES, 
                $relevanceRule, 
                $crossingRule
        );
        
        /**
         * Saisie des services ET du référentiel
         */
        $relevanceRule = new Expr(
                $this->getServiceLocator()->get('PeutSaisirServiceRule')->setIntervenant($this->getIntervenant()),
                $this->getServiceLocator()->get('PeutSaisirReferentielRule')->setIntervenant($this->getIntervenant()), 
                Expr::OPERATOR_OR);
        $crossingRule = new Expr(
                $this->getServiceLocator()->get('PossedeServicesRule')->setIntervenant($this->getIntervenant()),
                $this->getServiceLocator()->get('PossedeReferentielRule')->setIntervenant($this->getIntervenant()), 
                Expr::OPERATOR_OR);
        $this->addRule(
                self::KEY_SAISIE_SERVICE, 
                $relevanceRule,
                $crossingRule
        );
        
        /**
         * Pièces justificatives
         */
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirPieceJointeRule')->setIntervenant($this->getIntervenant());
        $crossingRule = clone $this->getPiecesJointesFourniesRule();
        $this->addRule(
                self::KEY_PIECES_JOINTES, 
                $relevanceRule, 
                $crossingRule
        );
        
        
        
        
        
        
        /**
         * Saisie des données personnelles
         */
        $peutSaisirDossier = new PeutSaisirDossierRule($this->getIntervenant());
        if (!$peutSaisirDossier->isRelevant() || $peutSaisirDossier->execute()) {
            $transitionRule = new PossedeDossierRule($this->getIntervenant());
            $this->addStep(
                    self::KEY_SAISIE_DONNEES,
                    new SaisieDossierStep(),
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
                    new SaisieServiceStep(),
                    $transitionRule
            );
        }
        
        /**
         * Checklist des pièces justificatives
         */
        $peutSaisirPj = new PeutSaisirPieceJointeRule($this->getIntervenant());
        if (!$peutSaisirPj->isRelevant() || $peutSaisirPj->execute()) {
            $transitionRule = clone $this->getPiecesJointesFourniesRule();
            $this->addStep(
                    self::KEY_PIECES_JOINTES,
                    new SaisiePiecesJointesStep(),
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
                    new ValidationDossierStep(),
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
                    new ValidationServiceStep(),
                    $transitionRule
            );
        }
        
        /**
         * Agrements des différents conseils
         */
        $necessiteAgrement = $this->getServiceLocator()->get('NecessiteAgrementRule'); /* @var $necessiteAgrement NecessiteAgrementRule */
        $necessiteAgrement->setIntervenant($this->getIntervenant());
        foreach ($necessiteAgrement->getTypesAgrementAttendus() as $typeAgrement) {
            $transitionRule = clone $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $transitionRule AgrementFourniRule */
            $transitionRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($typeAgrement)
                ->setStructure($this->getStructure());

            $this->addStep(
                     'KEY_' . $typeAgrement->getCode(),
                    new AgrementStep($typeAgrement),
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
                    new EditionContratStep(),
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
     * Retourne l'URL correspondant à l'étape spécifiée.
     * 
     * @param \Application\Service\Workflow\Step $step
     * @return string
     */
    public function getStepUrl(Step $step)
    {
        $params = array_merge(
                $step->getRouteParams(), 
                array('intervenant' => $this->getIntervenant()->getSourceCode()));
        
        $url = $this->getHelperUrl()->fromRoute($step->getRoute(), $params);
        
        return $url;
    }
    
    /**
     * Retourne l'URL correspondant à l'étape courante.
     * 
     * @return string
     */
    public function getCurrentStepUrl()
    {
        if (!$this->getCurrentStep()) {
            return null;
        }
        return $this->getStepUrl($this->getCurrentStep());
    }
    
    /**
     * @var PiecesJointesFourniesRule 
     */
    protected $piecesJointesFourniesRule;
    
    protected function getPiecesJointesFourniesRule()
    {
        if (null === $this->piecesJointesFourniesRule) {
            $this->piecesJointesFourniesRule = $this->getServiceLocator()->get('PiecesJointesFourniesRule');
        }
        $this->piecesJointesFourniesRule
                ->setIntervenant($this->getIntervenant())
//                ->setAvecFichier(true) // à décommenter ssi le dépôt de fichier devient obligatoire
                ->setAvecValidation(true);
        
        return $this->piecesJointesFourniesRule;
    }
    
    protected $serviceValideRule;
    
    protected function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            $this->serviceValideRule = new ServiceValideRule();
        }
        // teste si les enseignements ont été validés, MÊME PARTIELLEMENT
        $this->serviceValideRule
                ->setMemePartiellement()
                ->setIntervenant($this->getIntervenant())
                ->setTypeValidation($this->getTypeValidationService())
                ->setStructure($this->getStructure())
                ->setServiceVolumeHoraire($this->getServiceVolumeHoraire());
        
        return $this->serviceValideRule;
    }
    
    /**
     * 
     * @return Structure
     */
    protected function getStructure()
    {
        if ($this->getRole() instanceof ComposanteRole) {
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
    
    /**
     * 
     * @return TypeValidationService
     */
    protected function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
    
    /**
     * 
     * @return TypeAgrementService
     */
    protected function getServiceTypeAgrement()
    {
        return $this->getServiceLocator()->get('ApplicationTypeAgrement');
    }
    
    /**
     * 
     * @return VolumeHoraire
     */
    protected function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
    } 
    
    /**
     * @return TypeValidation
     */
    protected function getTypeValidationService()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_SERVICES_PAR_COMP);
        $typeValidation = $qb->getQuery()->getOneOrNullResult();
        
        return $typeValidation;
    }
    
    /**
     * @return TypeAgrement
     */
    protected function getTypeAgrementConseilRestreint()
    {
        $qb = $this->getServiceTypeAgrement()->finderByCode(TypeAgrement::CODE_CONSEIL_RESTREINT);
        $type = $qb->getQuery()->getSingleResult();
        
        return $type;
    }
    
    /**
     * @return TypeAgrement
     */
    protected function getTypeAgrementConseilAcademique()
    {
        $qb = $this->getServiceTypeAgrement()->finderByCode(TypeAgrement::CODE_CONSEIL_ACADEMIQUE);
        $type = $qb->getQuery()->getSingleResult();
        
        return $type;
    }
}