<?php

namespace Application\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\AbstractIntervenantRule;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Rule\Intervenant\DossierValideRule;
use Application\Rule\Intervenant\Expr;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Rule\Intervenant\NecessiteContratRule;
use Application\Rule\Intervenant\PeutSaisirDossierRule;
use Application\Rule\Intervenant\PeutSaisirPieceJointeRule;
use Application\Rule\Intervenant\PeutSaisirServiceRule;
use Application\Rule\Intervenant\PeutSaisirReferentielRule;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;
use Application\Rule\Intervenant\PossedeContratRule;
use Application\Rule\Intervenant\PossedeDossierRule;
use Application\Rule\Intervenant\PossedeReferentielRule;
use Application\Rule\Intervenant\PossedeServicesRule;
use Application\Rule\Intervenant\ReferentielValideRule;
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
use Application\Service\Workflow\Step\ValidationReferentielStep;
use Application\Service\Workflow\Step\ValidationServiceStep;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\RoleAwareTrait;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;
use PDO;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Workflow extends AbstractWorkflow
{
    use IntervenantAwareTrait;
    use RoleAwareTrait;
    
    const KEY_DONNEES_PERSO_SAISIE     = 'DONNEES_PERSO_SAISIE';
    const KEY_DONNEES_PERSO_VALIDATION = 'DONNEES_PERSO_VALIDATION';
    const KEY_SERVICE_SAISIE           = 'SERVICE_SAISIE';
    const KEY_SERVICE_VALIDATION       = 'SERVICE_VALIDATION';
    const KEY_REFERENTIEL_SAISIE       = 'REFERENTIEL_SAISIE';
    const KEY_REFERENTIEL_VALIDATION   = 'REFERENTIEL_VALIDATION';
    const KEY_PIECES_JOINTES           = 'PIECES_JOINTES';
    const KEY_CONSEIL_RESTREINT        = TypeAgrement::CODE_CONSEIL_RESTREINT;  // NB: c'est texto le code du type d'agrément
    const KEY_CONSEIL_ACADEMIQUE       = TypeAgrement::CODE_CONSEIL_ACADEMIQUE; // NB: c'est texto le code du type d'agrément
    const KEY_CONTRAT                  = 'CONTRAT';
    const KEY_CONTRAT_VALIDE           = 'CONTRAT_VALIDE';
    const KEY_FINAL                    = 'FINAL';

    /**
     * Spécifie l'intervenant concerné.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @return self
     */
    public function setIntervenant(Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;
        $this->recreateSteps();
        
        return $this;
    }
    
    /**
     * Spécifie le rôle courant.
     * 
     * @param RoleInterface $role
     */
    public function setRole(RoleInterface $role)
    {
        $this->role = $role;
        $this->recreateSteps();
        
        return $this;
    }
    
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
        $key           = self::KEY_DONNEES_PERSO_SAISIE;
        $relevanceRule = $this->getPeutSaisirDossierRule();
        $crossingRule  = $this->getPossedeDossierRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new SaisieDossierStep());
        }
        
        /**
         * Saisie des services
         */
        $key           = self::KEY_SERVICE_SAISIE;
        $relevanceRule = Expr::orX(
                $this->getPeutSaisirServiceRule(), 
                $this->getPeutSaisirReferentielRule()
        );
        $crossingRule  = Expr::orX(
                $this->getPossedeServicesRule(), 
                $this->getPossedeReferentielRule()
        );
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new SaisieServiceStep());
        }
        
        /**
         * Pièces justificatives
         */
        $key           = self::KEY_PIECES_JOINTES;
        $relevanceRule = $this->getPeutSaisirPieceJointeRule();
        $crossingRule  = $this->getPiecesJointesFourniesRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new SaisiePiecesJointesStep());
        }
        
        /**
         * Validation des données personnelles
         */
        $key           = self::KEY_DONNEES_PERSO_VALIDATION;
        $relevanceRule = $this->getPeutSaisirDossierRule();
        $crossingRule  = $this->getDossierValideRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new ValidationDossierStep());
        }
        
        /**
         * Validation des services
         */
        $key           = self::KEY_SERVICE_VALIDATION;
        $relevanceRule = $this->getPossedeServicesRule();
        $crossingRule  = $this->getServiceValideRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new ValidationServiceStep());
        }
//        
//        /**
//         * Validation du référentiel
//         */
//        $key           = self::KEY_REFERENTIEL_VALIDATION;
//        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirReferentielRule'); /* @var $relevanceRule PeutSaisirReferentielRule */
//        $relevanceRule->setIntervenant($this->getIntervenant()); 
//        $crossingRule  = $this->getReferentielValideRule(); /* @var $crossingRule ReferentielValideRule */
//        $this->addRule($key, $relevanceRule, $crossingRule);
//        if ($relevanceRule->execute()) {
//            $this->addStep($key, new ValidationReferentielStep());
//        }
        
        /**
         * Agréments des différents conseils
         */
        $typesAgrement = $this->getServiceTypeAgrement()->getList();
        foreach ($typesAgrement as $typeAgrement) {
            $relevanceRule = Expr::andX(
                    $this->getNecessiteAgrementRule($typeAgrement), 
                    $this->getPossedeServicesRule()
            );
            $crossingRule  = $this->getAgrementFourniRule($typeAgrement);
            
            $key = $typeAgrement->getCode();
            $this->addRule($key, $relevanceRule, $crossingRule);
//            var_dump($typeAgrement."", $this->getIntervenant()."", $this->getRole()."", $this->getStructure()."", 
//                    $this->getNecessiteAgrementRule($typeAgrement)->execute(), $this->getPossedeServicesRule()->execute(), $relevanceRule->execute());
            if ($relevanceRule->execute()) {
                $this->addStep($key, new AgrementStep($typeAgrement));
            }
        }
        
        /**
         * Contrat / avenant
         */
        $key           = self::KEY_CONTRAT;
        $relevanceRule = Expr::andX(
                $this->getNecessiteContratRule(), 
                $this->getPossedeServicesRule()
        );
        $crossingRule  = $this->getPossedeContratRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new EditionContratStep());
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
     * @param Step $step
     * @param Intervenant $intervenant
     * @return string
     */
    public function getStepUrl(Step $step, Intervenant $intervenant = null)
    {
        if (null === $intervenant) {
            $intervenant = $this->getIntervenant();
        }
        
        $params = array_merge(
                $step->getRouteParams(), 
                array('intervenant' => $intervenant->getSourceCode()));
        
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
     * @return PeutSaisirDossierRule
     */
    protected function getPeutSaisirDossierRule()
    {
        $rule = clone $this->getServiceLocator()->get('PeutSaisirDossierRule');
        
        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return PossedeDossierRule
     */
    protected function getPossedeDossierRule()
    {
        $rule = clone $this->getServiceLocator()->get('PossedeDossierRule');
        
        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return DossierValideRule
     */
    protected function getDossierValideRule()
    {
        $rule = clone $this->getServiceLocator()->get('DossierValideRule');
        
        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return PeutSaisirServiceRule
     */
    protected function getPeutSaisirServiceRule()
    {
        $rule = clone $this->getServiceLocator()->get('PeutSaisirServiceRule');
        
        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
        
    /**
     * @return PossedeServicesRule
     */
    protected function getPossedeServicesRule()
    {
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        
        $rule = clone $this->getServiceLocator()->get('PossedeServicesRule');
        
        $rule
                ->setAnnee($annee)
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure());
            
        return $rule;
    }
    
    /**
     * @return PeutSaisirReferentielRule
     */
    protected function getPeutSaisirReferentielRule()
    {
        $rule = clone $this->getServiceLocator()->get('PeutSaisirReferentielRule');
        
        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return PossedeReferentielRule
     */
    protected function getPossedeReferentielRule()
    {
        $annee = $this->getContextProvider()->getGlobalContext()->getAnnee();
        
        $rule = clone $this->getServiceLocator()->get('PossedeReferentielRule');
        
        $rule
                ->setAnnee($annee)
                ->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return PeutSaisirPieceJointeRule
     */
    protected function getPeutSaisirPieceJointeRule()
    {
        $rule = clone $this->getServiceLocator()->get('PeutSaisirPieceJointeRule');
        
        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return PiecesJointesFourniesRule
     */
    protected function getPiecesJointesFourniesRule()
    {
        $rule = clone $this->getServiceLocator()->get('PiecesJointesFourniesRule');
        
        $rule
                ->setIntervenant($this->getIntervenant())
//                ->setAvecFichier(true) // à décommenter ssi le dépôt de fichier devient obligatoire
                ->setAvecValidation(true);
        
        return $rule;
    }
    
    /**
     * @return ServiceValideRule
     */
    protected function getServiceValideRule()
    {
        $rule = clone $this->getServiceLocator()->get('ServiceValideRule');
        
        // teste si les enseignements ont été validés, MÊME PARTIELLEMENT
        $rule
                ->setMemePartiellement()
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure());
        
        return $rule;
    }
    
    /**
     * @return ReferentielValideRule
     */
    protected function getReferentielValideRule()
    {
        $rule = clone $this->getServiceLocator()->get('ReferentielValideRule');

        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return NecessiteAgrementRule
     */
    protected function getNecessiteAgrementRule(TypeAgrement $typeAgrement)
    {
        $rule = clone $this->getServiceLocator()->get('NecessiteAgrementRule');
        $rule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($typeAgrement);
        
        return $rule;
    }
    
    /**
     * @return AgrementFourniRule
     */
    protected function getAgrementFourniRule(TypeAgrement $typeAgrement)
    {
        $rule = clone $this->getServiceLocator()->get('AgrementFourniRule');
        $rule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($typeAgrement)
                ->setStructure($this->getStructure());
        
        return $rule;
    }
    
    /**
     * @return NecessiteContratRule
     */
    protected function getNecessiteContratRule()
    {
        $rule = clone $this->getServiceLocator()->get('NecessiteContratRule');
        
        $rule->setIntervenant($this->getIntervenant());
        
        return $rule;
    }
    
    /**
     * @return PossedeContratRule
     */
    protected function getPossedeContratRule()
    {
        $rule = clone $this->getServiceLocator()->get('PossedeContratRule');
        
        $rule
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure())
                ->setValide(true);
        
        return $rule;
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
//    
//    /**
//     * @var array clé => SQL
//     */
//    protected $rulesCrossingSQL;
//    
//    /**
//     * @var array clé => SQL
//     */
//    protected $rulesNotCrossingSQL;
//    
//    /**
//     * 
//     * @return self
//     */
//    protected function createRulesQuerySQL()
//    {
//        if (null !== $this->rulesCrossingSQL && null !== $this->rulesNotCrossingSQL) {
//            return $this;
//        }
//        
//        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")->select("i.id")->distinct(); // tous les intervenants
//        
//        $rulesNotRelevantSQL = [];
//        $rulesCrossingSQL    = [];
//        $rulesNotCrossingSQL = [];
//
//        $indentor = function($sql, $n = 1) {
//            $lines = explode(PHP_EOL, $sql);
//            array_walk($lines, function(&$line) use ($n) { $line = str_repeat(' ', $n * 4) . $line; });
//            return implode(PHP_EOL, $lines);
//        };
//        
//        $previousKey = null;
//        foreach ($this->getRules() as $key => $rules) {
//            
//            $relevantRuleSQL = implode(PHP_EOL, [
//                "-- Intervenants concernés par l'étape $key",
//                $rules['relevance']->setIntervenant(null)->getQuerySQL(),
//            ]);
//            $crossingRuleSQL =  implode(PHP_EOL, [
//                "-- Intervenants satisfaisant l'étape $key",
//                $rules['crossing'] ->setIntervenant(null)->getQuerySQL(),
//            ]);
//            
//            $inputSQL       = null;
//            $notRelevantSQL = null;
//            $crossingSQL    = null;
//            $notCrossingSQL = null;
//            
//            if (!isset($rulesNotRelevantSQL[$previousKey]) || !isset($rulesCrossingSQL[$previousKey])) {
//                $inputSQL = implode(PHP_EOL, [ 
//                    "-- Intervenants en entrée de l'étape $key (1ère étape) :",
//                    "--     Tous les intervenants existants",
//                    $qb->getQuery()->getSQL(),
//                ]);
//            } else {
//                $inputSQL = implode(PHP_EOL, [ 
//                    "-- Intervenants en entrée de l'étape $key (i.e. en sortie de l'étape précédente $previousKey) :",
//                    "--     Intervenants non concernés par l'étape précédente $previousKey",
//                    "--     UNION",
//                    "--     Intervenants satisfaisant l'étape précédente $previousKey",
//                    '(',
//                        $indentor($rulesNotRelevantSQL[$previousKey]),
//                    ')',
//                    'UNION',
//                    '(',
//                        $indentor($rulesCrossingSQL[$previousKey]),
//                    ')',
//                ]);
//            }
//            if (!$inputSQL) {
//                throw new LogicException("Anomalie: la requête SQL des intervenants en entrée est vide.");
//            }
//            
//            $notRelevantSQL = implode(PHP_EOL, [ 
//                    "-- Intervenants non concernés par l'étape $key (i.e. 'not relevant') :",
//                    "--     Intervenants en entrée de l'étape $key",
//                    "--     MINUS",
//                    "--     Intervenants concernés par l'étape $key",
//                    '(',
//                        $indentor($inputSQL),
//                    ')',
//                    'MINUS',
//                    '(',
//                        $indentor($relevantRuleSQL),
//                    ')',
//            ]);
//            
//            $crossingSQL = implode(PHP_EOL, [ 
//                    "-- Intervenants concernés par l'étape $key et la satisfaisant (i.e. 'crossing') :",
//                    "--     Intervenants en entrée de l'étape $key",
//                    "--     INTERSECT",
//                    "--     Intervenants concernés par l'étape $key",
//                    "--     INTERSECT",
//                    "--     Intervenants satisfaisant l'étape $key",
//                    '(',
//                        $indentor($inputSQL),
//                    ')',
//                    'INTERSECT',
//                    '(',
//                        $indentor($relevantRuleSQL),
//                    ')',
//                    'INTERSECT',
//                    '(',
//                        $indentor($crossingRuleSQL),
//                    ')',
//            ]);
//            
//            $notCrossingSQL = implode(PHP_EOL, [ 
//                    "-- Intervenants concernés par l'étape $key mais ne la satisfaisant pas (i.e. 'not crossing') :",
//                    "--     Intervenants en entrée de l'étape $key",
//                    "--     INTERSECT",
//                    "--     Intervenants concernés par l'étape $key",
//                    "--     MINUS",
//                    "--     Intervenants satisfaisant l'étape $key",
//                    '(',
//                        $indentor($inputSQL),
//                    ')',
//                    'INTERSECT',
//                    '(',
//                        $indentor($relevantRuleSQL),
//                    ')',
//                    'MINUS',
//                    '(',
//                        $indentor($crossingRuleSQL),
//                    ')',
//            ]);
//            
//            $rulesNotRelevantSQL[$key] = $notRelevantSQL  . PHP_EOL;
//            $rulesCrossingSQL   [$key] = $crossingRuleSQL . PHP_EOL;
//            $rulesNotCrossingSQL[$key] = $notCrossingSQL  . PHP_EOL;
//
//            $previousKey = $key;
//        }
//        
//        $this->rulesCrossingSQL    = $rulesCrossingSQL;
//        $this->rulesNotCrossingSQL = $rulesNotCrossingSQL;
//        
//        return $this;
//    }
//    
//    /**
//     * 
//     * @param string $stepKey
//     * @return array
//     */
//    public function executeNotCrossingQuerySQL($stepKey)
//    {
//        $sql = $this->getNotCrossingQuerySQL($stepKey);
//        
//        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql);
//
//        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//        
//        return AbstractIntervenantRule::normalizeResult($result);
//    }
//    
//    /**
//     * 
//     * @param string $stepKey
//     * @return string
//     * @throws RuntimeException Aucune requête SQL trouvée avec la clé
//     */
//    public function getCrossingQuerySQL($stepKey)
//    {
//        $this->createRulesQuerySQL();
//        
//        if (!array_key_exists($stepKey, $this->rulesCrossingSQL)) {
//            throw new RuntimeException("Aucune requête SQL trouvée avec la clé '$stepKey'.");
//        }
//        
//        return $this->rulesCrossingSQL[$stepKey];
//    }
//    
//    /**
//     * 
//     * @param string $stepKey
//     * @return string
//     * @throws RuntimeException Aucune requête SQL trouvée avec la clé
//     */
//    public function getNotCrossingQuerySQL($stepKey)
//    {
//        $this->createRulesQuerySQL();
//        
//        if (!array_key_exists($stepKey, $this->rulesNotCrossingSQL)) {
//            throw new RuntimeException("Aucune requête SQL trouvée avec la clé '$stepKey'.");
//        }
//
//        return $this->rulesNotCrossingSQL[$stepKey];
//    }
    
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
    private function getServiceTypeValidation()
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
}