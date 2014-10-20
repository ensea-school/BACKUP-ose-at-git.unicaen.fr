<?php

namespace Application\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\Expr;
use Application\Rule\Intervenant\AbstractIntervenantRule;
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
    use IntervenantAwareTrait;
    use RoleAwareTrait;
    
    const KEY_SAISIE_DOSSIER     = 'KEY_SAISIE_DOSSIER';
    const KEY_VALIDATION_DONNEES = 'KEY_VALIDATION_DONNEES';
    const KEY_SAISIE_SERVICE     = 'KEY_SAISIE_SERVICE';
    const KEY_VALIDATION_SERVICE = 'KEY_VALIDATION_SERVICE';
    const KEY_PIECES_JOINTES     = 'KEY_PIECES_JOINTES';
    const KEY_CONSEIL_RESTREINT  = 'KEY_CONSEIL_RESTREINT';  // NB: 'KEY_' . code type agrément
    const KEY_CONSEIL_ACADEMIQUE = 'KEY_CONSEIL_ACADEMIQUE'; // NB: 'KEY_' . code type agrément
    const KEY_EDITION_CONTRAT    = 'KEY_EDITION_CONTRAT';
    const KEY_FINAL              = 'KEY_FINAL';
    
    /**
     * Création des différentes étapes et règles métiers composant le workflow.
     * 
     * @return self
     */
    protected function createSteps()
    {        
        $this->steps = [];
        $this->rules = [];
        
        /**
         * Saisie des données personnelles
         */
        $key           = self::KEY_SAISIE_DOSSIER;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirDossierRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getServiceLocator()->get('PossedeDossierRule')->setIntervenant($this->getIntervenant());
        $this->addRule($key, $relevanceRule, $crossingRule);
        if (!$relevanceRule->isRelevant() || $relevanceRule->execute()) {
            $this->addStep(
                    $key,
                    new SaisieDossierStep(),
                    $crossingRule
            );
        }
        
        /**
         * Saisie des services
         */
        $key           = self::KEY_SAISIE_SERVICE;
        $relevanceRule = Expr::orX(
                $this->getServiceLocator()->get('PeutSaisirServiceRule')    ->setIntervenant($this->getIntervenant()),
                $this->getServiceLocator()->get('PeutSaisirReferentielRule')->setIntervenant($this->getIntervenant())
        );
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $crossingRule = Expr::orX(
                $this->getServiceLocator()->get('PossedeServicesRule')   ->setIntervenant($this->getIntervenant())->setAnnee($annee),
                $this->getServiceLocator()->get('PossedeReferentielRule')->setIntervenant($this->getIntervenant())->setAnnee($annee)
        );
        $this->addRule($key, $relevanceRule, $crossingRule);
        if (!$relevanceRule->isRelevant() || $relevanceRule->execute()) {
            $this->addStep(
                    $key,
                    new SaisieServiceStep(),
                    $crossingRule
            );
        }
        
        /**
         * Checklist des pièces justificatives
         */
        $key           = self::KEY_PIECES_JOINTES;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirPieceJointeRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = clone $this->getPiecesJointesFourniesRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if (!$relevanceRule->isRelevant() || $relevanceRule->execute()) {
            $this->addStep(
                    $key,
                    new SaisiePiecesJointesStep(),
                    $crossingRule
            );
        }
        
//        /**
//         * Validation des données personnelles
//         */
//        if (!$peutSaisirDossier->isRelevant() || $peutSaisirDossier->execute()) {
//            $transitionRule = (new DossierValideRule($this->getIntervenant()))->setTypeValidation($this->getTypeValidationDossier());
//            $this->addStep(
//                    self::KEY_VALIDATION_DONNEES,
//                    new ValidationDossierStep(),
//                    $transitionRule
//            );
//        }
//        
//        /**
//         * Validation des services
//         */
//        $peutSaisirService = new PeutSaisirServiceRule($this->getIntervenant());
//        if (!$peutSaisirService->isRelevant() || $peutSaisirService->execute()) {
//            $transitionRule = $this->getServiceValideRule();
//            $this->addStep(
//                    self::KEY_VALIDATION_SERVICE,
//                    new ValidationServiceStep(),
//                    $transitionRule
//            );
//        }
//        
//        /**
//         * Agrements des différents conseils
//         */
//        $necessiteAgrement = $this->getServiceLocator()->get('NecessiteAgrementRule'); /* @var $necessiteAgrement NecessiteAgrementRule */
//        $necessiteAgrement->setIntervenant($this->getIntervenant());
//        foreach ($necessiteAgrement->getTypesAgrementAttendus() as $typeAgrement) {
//            $transitionRule = clone $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $transitionRule AgrementFourniRule */
//            $transitionRule
//                ->setIntervenant($this->getIntervenant())
//                ->setTypeAgrement($typeAgrement)
//                ->setStructure($this->getStructure());
//
//            $this->addStep(
//                     'KEY_' . $typeAgrement->getCode(),
//                    new AgrementStep($typeAgrement),
//                    $transitionRule
//            );
//        }
//        
//        /**
//         * Contrat / avenant
//         */
//        $necessiteContrat = new NecessiteContratRule($this->getIntervenant());
//        if (!$necessiteContrat->isRelevant() || $necessiteContrat->execute()) {
//            $transitionRule = new PossedeContratRule($this->getIntervenant());
//            $transitionRule
////                    ->setTypeValidation($this->getTypeValidationContrat())
//                    ->setStructure($this->getStructure())
//                    ->setValide(true);
//            $this->addStep(
//                    self::KEY_EDITION_CONTRAT,
//                    new EditionContratStep(),
//                    $transitionRule
//            );
//        }
//        
////        $this->addStep(
////                self::KEY_FINAL,
////                new Step\FinalStep(),
////                null
////        );
            
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
     * @var array clé => SQL
     */
    protected $rulesCrossingSQL;
    
    /**
     * @var array clé => SQL
     */
    protected $rulesNotCrossingSQL;
    
    /**
     * 
     * @return self
     */
    protected function processRulesQuerySQL()
    {
        if (null !== $this->rulesCrossingSQL || null !== $this->rulesNotCrossingSQL) {
            return $this;
        }
        
        $rulesCrossingSQLParts    = [];
        $rulesNotCrossingSQLParts = [];
        
        $previousKey = null;
        foreach ($this->getRules() as $key => $rules) {
            
            $relevanceRule = $rules['relevance'];
            $crossingRule  = $rules['crossing'];
            
            /**
             * Construction des requêtes SQL
             */
            
            $relevanceRuleSQL = $relevanceRule->setIntervenant(null)->getQuerySQL(); /* @var $relevanceRuleSQL AbstractIntervenantRule */
            $crossingRuleSQL  = $crossingRule ->setIntervenant(null)->getQuerySQL(); /* @var $crossingRuleSQL  AbstractIntervenantRule */
            
            $crossingSQLParts    = [];
            $notCrossingSQLParts = [];
            
            if (isset($rulesCrossingSQLParts[$previousKey])) {
                $notCrossingSQLParts[] = $rulesCrossingSQLParts[$previousKey];
                $crossingSQLParts[]    = $rulesCrossingSQLParts[$previousKey];
            }
            
            $crossingSQLParts[]    = $relevanceRuleSQL . PHP_EOL . 'INTERSECT' . PHP_EOL . $crossingRuleSQL . PHP_EOL;
            $notCrossingSQLParts[] = $relevanceRuleSQL . PHP_EOL . 'MINUS'     . PHP_EOL . $crossingRuleSQL . PHP_EOL;
            
            $rulesCrossingSQLParts   [$key] = implode('INTERSECT' . PHP_EOL, $crossingSQLParts)    . PHP_EOL;
            $rulesNotCrossingSQLParts[$key] = implode('INTERSECT' . PHP_EOL, $notCrossingSQLParts) . PHP_EOL;

            $previousKey = $key;
        }
        
        $this->rulesCrossingSQL    = $rulesCrossingSQLParts;
        $this->rulesNotCrossingSQL = $rulesNotCrossingSQLParts;
        
        return $this;
    }
    
    /**
     * 
     * @param string $stepKey
     * @return array
     */
    public function executeNotCrossingQuerySQL($stepKey)
    {
        $sql = $this->getNotCrossingQuerySQL($stepKey);
        
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return AbstractIntervenantRule::normalizeResult($result);
    }
    
    /**
     * 
     * @param string $stepKey
     * @return string
     * @throws RuntimeException Aucune requête SQL trouvée avec la clé
     */
    public function getCrossingQuerySQL($stepKey)
    {
        $this->processRulesQuerySQL();
        
        if (!array_key_exists($stepKey, $this->rulesCrossingSQL)) {
            throw new RuntimeException("Aucune requête SQL trouvée avec la clé '$stepKey'.");
        }
        
        return $this->rulesCrossingSQL[$stepKey];
    }
    
    /**
     * 
     * @param string $stepKey
     * @return string
     * @throws RuntimeException Aucune requête SQL trouvée avec la clé
     */
    public function getNotCrossingQuerySQL($stepKey)
    {
        $this->processRulesQuerySQL();
        
        if (!array_key_exists($stepKey, $this->rulesNotCrossingSQL)) {
            throw new RuntimeException("Aucune requête SQL trouvée avec la clé '$stepKey'.");
        }
        
        return $this->rulesNotCrossingSQL[$stepKey];
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