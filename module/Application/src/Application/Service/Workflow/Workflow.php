<?php

namespace Application\Service\Workflow;

use Application\Acl\ComposanteRole;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\Expr;
use Application\Rule\Intervenant\AbstractIntervenantRule;
use Application\Rule\Intervenant\PiecesJointesFourniesRule;
use Application\Rule\Intervenant\ServiceValideRule;
use Application\Rule\Intervenant\ReferentielValideRule;
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
use Application\Service\Workflow\Step\ValidationReferentielStep;
use Application\Traits\IntervenantAwareTrait;
use Application\Traits\RoleAwareTrait;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;
use PDO;

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
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirDossierRule')
                ->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getServiceLocator()->get('PossedeDossierRule')
                ->setIntervenant($this->getIntervenant());
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new SaisieDossierStep());
        }
        
        /**
         * Saisie des services
         */
        $key           = self::KEY_SERVICE_SAISIE;
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
        if ($relevanceRule->execute()) {
            $this->addStep($key, new SaisieServiceStep());
        }
        
        /**
         * Pièces justificatives
         */
        $key           = self::KEY_PIECES_JOINTES;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirPieceJointeRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = clone $this->getPiecesJointesFourniesRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new SaisiePiecesJointesStep());
        }
        
        /**
         * Validation des données personnelles
         */
        $key           = self::KEY_DONNEES_PERSO_VALIDATION;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirDossierRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getServiceLocator()->get('DossierValideRule')->setIntervenant($this->getIntervenant())->setTypeValidation($this->getTypeValidationDossier());
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new ValidationDossierStep());
        }
        
        /**
         * Validation des services
         */
        $key           = self::KEY_SERVICE_VALIDATION;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirServiceRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getServiceValideRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new ValidationServiceStep());
        }
        
        /**
         * Validation du référentiel
         */
        $key           = self::KEY_REFERENTIEL_VALIDATION;
        $relevanceRule = $this->getServiceLocator()->get('PeutSaisirReferentielRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getReferentielValideRule();
        $this->addRule($key, $relevanceRule, $crossingRule);
        if ($relevanceRule->execute()) {
            $this->addStep($key, new ValidationReferentielStep());
        }
        
        /**
         * Agréments des différents conseils
         */
        $typesAgrement = $this->getServiceTypeAgrement()->getList();
        foreach ($typesAgrement as $typeAgrement) {
            $relevanceRule = $this->getServiceLocator()->get('NecessiteAgrementRule')->setIntervenant($this->getIntervenant());
            $relevanceRule->setTypeAgrement($typeAgrement);
            
            $crossingRule  = clone $this->getServiceLocator()->get('AgrementFourniRule');
            $crossingRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeAgrement($typeAgrement)
                ->setStructure($this->getStructure());
            
            $key = $typeAgrement->getCode();
            $this->addRule($key, $relevanceRule, $crossingRule);
            if ($relevanceRule->execute()) {
                $this->addStep($key, new AgrementStep($typeAgrement));
            }
        }
        
        /**
         * Contrat / avenant
         */
        $key           = self::KEY_CONTRAT;
        $relevanceRule = $this->getServiceLocator()->get('NecessiteContratRule')->setIntervenant($this->getIntervenant());
        $crossingRule  = $this->getServiceLocator()->get('PossedeContratRule')->setIntervenant($this->getIntervenant());
        $crossingRule
                ->setStructure($this->getStructure())
                ->setValide(true);
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
     * @var ReferentielValideRule 
     */
    protected $referentielValideRule;
    
    /**
     * @return ReferentielValideRule
     */
    protected function getReferentielValideRule()
    {
        if (null === $this->referentielValideRule) {
            $this->referentielValideRule = $this->getServiceLocator()->get('ServiceValideRule');
        }
        // teste si le référentiel a été validé
        $this->referentielValideRule
                ->setIntervenant($this->getIntervenant())
                ->setTypeValidation($this->getTypeValidationService());
        
        return $this->referentielValideRule;
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
    protected function createRulesQuerySQL()
    {
        if (null !== $this->rulesCrossingSQL && null !== $this->rulesNotCrossingSQL) {
            return $this;
        }
        
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")->select("i.id")->distinct(); // tous les intervenants
        
        $rulesNotRelevantSQL = [];
        $rulesCrossingSQL    = [];
        $rulesNotCrossingSQL = [];

        $indentor = function($sql, $n = 1) {
            $lines = explode(PHP_EOL, $sql);
            array_walk($lines, function(&$line) use ($n) { $line = str_repeat(' ', $n * 4) . $line; });
            return implode(PHP_EOL, $lines);
        };
        
        $previousKey = null;
        foreach ($this->getRules() as $key => $rules) {
            
            $relevantRuleSQL = implode(PHP_EOL, [
                "-- Intervenants concernés par l'étape $key",
                $rules['relevance']->setIntervenant(null)->getQuerySQL(),
            ]);
            $crossingRuleSQL =  implode(PHP_EOL, [
                "-- Intervenants satisfaisant l'étape $key",
                $rules['crossing'] ->setIntervenant(null)->getQuerySQL(),
            ]);
            
            $inputSQL       = null;
            $notRelevantSQL = null;
            $crossingSQL    = null;
            $notCrossingSQL = null;
            
            if (!isset($rulesNotRelevantSQL[$previousKey]) || !isset($rulesCrossingSQL[$previousKey])) {
                $inputSQL = implode(PHP_EOL, [ 
                    "-- Intervenants en entrée de l'étape $key (1ère étape) :",
                    "--     Tous les intervenants existants",
                    $qb->getQuery()->getSQL(),
                ]);
            } else {
                $inputSQL = implode(PHP_EOL, [ 
                    "-- Intervenants en entrée de l'étape $key (i.e. en sortie de l'étape précédente $previousKey) :",
                    "--     Intervenants non concernés par l'étape précédente $previousKey",
                    "--     UNION",
                    "--     Intervenants satisfaisant l'étape précédente $previousKey",
                    '(',
                        $indentor($rulesNotRelevantSQL[$previousKey]),
                    ')',
                    'UNION',
                    '(',
                        $indentor($rulesCrossingSQL[$previousKey]),
                    ')',
                ]);
            }
            if (!$inputSQL) {
                throw new LogicException("Anomalie: la requête SQL des intervenants en entrée est vide.");
            }
            
            $notRelevantSQL = implode(PHP_EOL, [ 
                    "-- Intervenants non concernés par l'étape $key (i.e. 'not relevant') :",
                    "--     Intervenants en entrée de l'étape $key",
                    "--     MINUS",
                    "--     Intervenants concernés par l'étape $key",
                    '(',
                        $indentor($inputSQL),
                    ')',
                    'MINUS',
                    '(',
                        $indentor($relevantRuleSQL),
                    ')',
            ]);
            
            $crossingSQL = implode(PHP_EOL, [ 
                    "-- Intervenants concernés par l'étape $key et la satisfaisant (i.e. 'crossing') :",
                    "--     Intervenants en entrée de l'étape $key",
                    "--     INTERSECT",
                    "--     Intervenants concernés par l'étape $key",
                    "--     INTERSECT",
                    "--     Intervenants satisfaisant l'étape $key",
                    '(',
                        $indentor($inputSQL),
                    ')',
                    'INTERSECT',
                    '(',
                        $indentor($relevantRuleSQL),
                    ')',
                    'INTERSECT',
                    '(',
                        $indentor($crossingRuleSQL),
                    ')',
            ]);
            
            $notCrossingSQL = implode(PHP_EOL, [ 
                    "-- Intervenants concernés par l'étape $key mais ne la satisfaisant pas (i.e. 'not crossing') :",
                    "--     Intervenants en entrée de l'étape $key",
                    "--     INTERSECT",
                    "--     Intervenants concernés par l'étape $key",
                    "--     MINUS",
                    "--     Intervenants satisfaisant l'étape $key",
                    '(',
                        $indentor($inputSQL),
                    ')',
                    'INTERSECT',
                    '(',
                        $indentor($relevantRuleSQL),
                    ')',
                    'MINUS',
                    '(',
                        $indentor($crossingRuleSQL),
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

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
        $this->createRulesQuerySQL();
        
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
        $this->createRulesQuerySQL();
        
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
}