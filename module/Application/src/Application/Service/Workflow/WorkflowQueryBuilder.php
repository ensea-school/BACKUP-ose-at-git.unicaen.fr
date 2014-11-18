<?php

namespace Application\Service\Workflow;

use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Application\Rule\Intervenant\AbstractIntervenantRule;
use Application\Service\Intervenant as IntervenantService;
use Common\Exception\LogicException;
use Common\Exception\RuntimeException;
use PDO;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class WorkflowQueryBuilder implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
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
        foreach ($this->getWorkflowIntervenant()->getRules() as $key => $rules) {
            
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
            $rulesCrossingSQL   [$key] = $crossingSQL     . PHP_EOL;
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
        
        $stmt = $this->getServiceIntervenant()->getEntityManager()->getConnection()->executeQuery($sql);
        
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return AbstractIntervenantRule::normalizeResult($result);
    }
    
    /**
     * 
     * @param string $stepKey
     * @return integer
     */
    public function executeNotCrossingCountQuerySQL($stepKey)
    {
        $sql = $this->getNotCrossingQuerySQL($stepKey);
        $sql = "select count(*) from ( $sql )";
        
        $stmt = $this->getServiceIntervenant()->getEntityManager()->getConnection()->executeQuery($sql);
        
        $result = intval($stmt->fetchColumn());
        
        return $result;
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
     * 
     * @return IntervenantService
     */
    protected function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
}