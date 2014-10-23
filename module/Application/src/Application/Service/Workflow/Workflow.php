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
use Application\Service\Intervenant as IntervenantService;
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
use Common\Exception\RuntimeException;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Workflow extends AbstractWorkflow
{
    use IntervenantAwareTrait;
    use RoleAwareTrait;
    
    const KEY_SAISIE_DOSSIER     = 'SAISIE_DOSSIER';
    const KEY_VALIDATION_DONNEES = 'ALIDATION_DONNEES';
    const KEY_SAISIE_SERVICE     = 'SAISIE_SERVICE';
    const KEY_VALIDATION_SERVICE = 'VALIDATION_SERVICE';
    const KEY_PIECES_JOINTES     = 'PIECES_JOINTES';
    const KEY_CONSEIL_RESTREINT  = 'CONSEIL_RESTREINT';  // NB: c'est texto le code du type d'agrément
    const KEY_CONSEIL_ACADEMIQUE = 'CONSEIL_ACADEMIQUE'; // NB: c'est texto le code du type d'agrément
    const KEY_EDITION_CONTRAT    = 'EDITION_CONTRAT';
    const KEY_FINAL              = 'FINAL';
    
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
            $this->addStep($key, new SaisieDossierStep());
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
            $this->addStep($key, new SaisieServiceStep());
        }
        
        /**
         * Checklist des pièces justificatives
         */
        $key           = self::KEY_PIECES_JOINTES;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirPieceJointeRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = clone $this->getPiecesJointesFourniesRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if (!$relevanceRule->isRelevant() || $relevanceRule->execute()) {
            $this->addStep($key, new SaisiePiecesJointesStep());
        }
        
        /**
         * Validation des données personnelles
         */
        $key           = self::KEY_VALIDATION_DONNEES;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirDossierRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getServiceLocator()->get('DossierValideRule')->setIntervenant($this->getIntervenant())->setTypeValidation($this->getTypeValidationDossier());
        $this->addRule($key, $relevanceRule, $crossingRule);
        if (!$relevanceRule->isRelevant() || $relevanceRule->execute()) {
            $this->addStep($key, new ValidationDossierStep());
        }
        
        /**
         * Validation des services
         */
        $key           = self::KEY_VALIDATION_SERVICE;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirServiceRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getServiceValideRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if (!$relevanceRule->isRelevant() || $relevanceRule->execute()) {
            $this->addStep($key, new ValidationServiceStep());
        }
        
        /**
         * Agrements des différents conseils
         */
        $relevanceRule = $this->getServiceLocator()->get('NecessiteAgrementRule')->setIntervenant($this->getIntervenant());
        $typesAgrement = $relevanceRule->getTypesAgrementAttendus();
        foreach ($typesAgrement as $typeAgrement) {
            $crossingRule  = clone $this->getServiceLocator()->get('AgrementFourniRule');
            $crossingRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($typeAgrement)
                ->setStructure($this->getStructure());
            
            $key = $typeAgrement->getCode();
            $this->addRule($key, $relevanceRule, $crossingRule);
            if (!$relevanceRule->isRelevant() || $relevanceRule->execute()) {
                $this->addStep($key, new AgrementStep($typeAgrement));
            }
        }
        
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
    
    /**
     * @return PiecesJointesFourniesRule
     */
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
    
    /**
     * @var ServiceValideRule 
     */
    protected $serviceValideRule;
    
    /**
     * @return ServiceValideRule
     */
    protected function getServiceValideRule()
    {
        if (null === $this->serviceValideRule) {
            $this->serviceValideRule = $this->getServiceLocator()->get('ServiceValideRule');
        }
        // teste si les enseignements ont été validés, MÊME PARTIELLEMENT
        $this->serviceValideRule
                ->setMemePartiellement()
                ->setIntervenant($this->getIntervenant())
                ->setTypeValidation($this->getTypeValidationService())
                ->setStructure($this->getStructure());
        
        return $this->serviceValideRule;
    }
    
    /**
     * Retourne l'éventuelle structure unique prise en compte.
     * NB: Cette structure est injectée dans les règles métiers qui prennent en compte les structures.
     * 
     * @return Structure
     */
    public function getStructure()
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
//    protected function processRulesQuerySQL()
//    {
//        if (null !== $this->rulesCrossingSQL && null !== $this->rulesNotCrossingSQL) {
//            return $this;
//        }
//        
//        $rulesCrossingSQLParts    = [];
//        $rulesNotCrossingSQLParts = [];
//        
//        $previousKey = null;
//        foreach ($this->getRules() as $key => $rules) {
//            
//            $relevanceRule = $rules['relevance'];
//            $crossingRule  = $rules['crossing'];
//            
//            /**
//             * Construction des requêtes SQL
//             */
//            
//            $relevanceRuleSQL = $relevanceRule->setIntervenant(null)->getQuerySQL(); /* @var $relevanceRuleSQL AbstractIntervenantRule */
//            $crossingRuleSQL  = $crossingRule ->setIntervenant(null)->getQuerySQL(); /* @var $crossingRuleSQL  AbstractIntervenantRule */
//            
//            $crossingSQLParts    = [];
//            $notCrossingSQLParts = [];
//            
//            if (isset($rulesCrossingSQLParts[$previousKey])) {
//                $notCrossingSQLParts[] = $rulesCrossingSQLParts[$previousKey];
//                $crossingSQLParts[]    = $rulesCrossingSQLParts[$previousKey];
//            }
//            
//            $crossingSQLParts[]    = $relevanceRuleSQL . PHP_EOL . 'INTERSECT' . PHP_EOL . $crossingRuleSQL . PHP_EOL;
//            $notCrossingSQLParts[] = $relevanceRuleSQL . PHP_EOL . 'MINUS'     . PHP_EOL . $crossingRuleSQL . PHP_EOL;
//            
//            $rulesCrossingSQLParts   [$key] = implode('INTERSECT' . PHP_EOL, $crossingSQLParts)    . PHP_EOL;
//            $rulesNotCrossingSQLParts[$key] = implode('INTERSECT' . PHP_EOL, $notCrossingSQLParts) . PHP_EOL;
//
//            $previousKey = $key;
//        }
//        
//        $this->rulesCrossingSQL    = $rulesCrossingSQLParts;
//        $this->rulesNotCrossingSQL = $rulesNotCrossingSQLParts;
//        
//        return $this;
//    }
    protected function processRulesQuerySQL()
    {
        if (null !== $this->rulesCrossingSQL && null !== $this->rulesNotCrossingSQL) {
            return $this;
        }
        
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")->select("i.id")->distinct(); // tous les intervenants
        
        $rulesNotRelevantSQL = [];
        $rulesCrossingSQL    = [];
        $rulesNotCrossingSQL = [];
        
        $previousKey = null;
        foreach ($this->getRules() as $key => $rules) {
            
            $relevantRuleSQL = implode(PHP_EOL, [
                "-- Requête SQL des intervenants concernés par l'étape $key",
                $rules['relevance']->setIntervenant(null)->getQuerySQL(),
            ]);
            $crossingRuleSQL =  implode(PHP_EOL, [
                "-- Requête SQL des intervenants (pas forcément concernés) franchissant l'étape $key",
                $rules['crossing'] ->setIntervenant(null)->getQuerySQL(),
            ]);
            
            $inputSQL       = null;
            $notRelevantSQL = null;
            $crossingSQL    = null;
            $notCrossingSQL = null;
            
            if (!isset($rulesNotRelevantSQL[$previousKey]) || !isset($rulesCrossingSQL[$previousKey])) {
                $inputSQL = implode(PHP_EOL, [ 
                    "-- Requête SQL des intervenants en entrée de l'étape $key",
                    "-- > Pour l'étape 1, ce sont tous les intervenants existants",
                    $qb->getQuery()->getSQL(),
                ]);
            } else {
                $inputSQL = implode(PHP_EOL, [ 
                    "-- Requête SQL des intervenants en entrée de l'étape $key",
                    "-- > Pour les étapes 2+, ce sont les intervenants non concernés par l'étape précédente UNION les intervenants satisfaisant l'étape précédente $previousKey",
                    '(',
                        $rulesNotRelevantSQL[$previousKey],
                    ')',
                    'UNION',
                    '(',
                        $rulesCrossingSQL[$previousKey],
                    ')',
                ]);
            }
            if (!$inputSQL) {
                throw new \Common\Exception\LogicException("Anomalie: la requête SQL des intervenants en entrée est vide.");
            }
            
            $notRelevantSQL = implode(PHP_EOL, [ 
                    "-- Requête SQL des intervenants non concernés par l'étape $key (i.e. 'not relevant')",
                    "-- > Ce sont les intervenants en entrée MINUS les intervenants concernés",
                    '(',
                        $inputSQL,
                    ')',
                    'MINUS',
                    '(',
                        $relevantRuleSQL,
                    ')',
            ]);
            
            $crossingSQL = implode(PHP_EOL, [ 
                    "-- Requête SQL des intervenants concernés franchissant l'étape $key (i.e. 'crossing')",
                    "-- > Ce sont les intervenants en entrée INTERSECT les intervenants concernés INTERSECT les intervenants satisfaisant l'étape",
                    '(',
                        $inputSQL,
                    ')',
                    'INTERSECT',
                    '(',
                        $relevantRuleSQL,
                    ')',
                    'INTERSECT',
                    '(',
                        $crossingRuleSQL,
                    ')',
            ]);
            
            $notCrossingSQL = implode(PHP_EOL, [ 
                    "-- Requête SQL des intervenants concernés ne franchissant pas l'étape $key (i.e. 'not crossing')",
                    "-- > Ce sont les intervenants en entrée INTERSECT les intervenants concernés MINUS les intervenants satisfaisant l'étape",
                    '(',
                        $inputSQL,
                    ')',
                    'INTERSECT',
                    '(',
                        $relevantRuleSQL,
                    ')',
                    'MINUS',
                    '(',
                        $crossingRuleSQL,
                    ')',
            ]);
            
            $rulesNotRelevantSQL[$key] = $notRelevantSQL  . PHP_EOL;
            $rulesCrossingSQL   [$key] = $crossingRuleSQL . PHP_EOL;
            $rulesNotCrossingSQL[$key] = $notCrossingSQL  . PHP_EOL;

            $previousKey = $key;
        }
        
        $this->rulesCrossingSQL    = $rulesCrossingSQL;
        $this->rulesNotCrossingSQL = $rulesNotCrossingSQL;
        
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
     * @return IntervenantService
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
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