<?php

namespace Application\Rule;

use LogicException;

/**
 * Expression AND ou OR de règles métiers.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Expr extends AbstractRule
{
    const OPERATOR_AND = 'AND';
    const OPERATOR_OR  = 'OR';

    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var string
     */
    protected $operator;

    /**
     * Instancie un expression.
     *
     * @param array $rules Règles
     * @param string $operator Opérateur, ex: Expr::OPERATOR_AND
     */
    public function __construct(array $rules = [], $operator = self::OPERATOR_AND)
    {
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }

        $this->operator = $operator;
    }

    /**
     * Instancie une expression OR avec les règles spécifiées.
     *
     * @param type $x
     * @return self
     */
    static public function orX($x = null)
    {
        return new static(func_get_args(), self::OPERATOR_OR);
    }

    /**
     * Instancie une expression AND avec les règles spécifiées.
     *
     * @param type $x
     * @return self
     */
    static public function andX($x = null)
    {
        return new static(func_get_args(), self::OPERATOR_AND);
    }

    /**
     * Ajoute une règle.
     *
     * @param RuleInterface $rule Règle
     * @return self
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * Spécifie l'opérateur de cette expression.
     *
     * @param string $operator Ex: Expr::OPERATOR_AND
     * @return self
     * @throws LogicException
     */
    public function setOperator($operator)
    {
        if (!in_array($operator, [self::OPERATOR_AND, self::OPERATOR_OR])) {
            throw new LogicException(sprintf("Opérateur invalide : %s.", $operator));
        }

        $this->operator = $operator;

        return $this;
    }

    /**
     * Exécute cette expression.
     *
     * @return boolean|array Résultat de l'exécution
     * @throws LogicException
     * @see RuleInterface
     */
    public function execute()
    {
        $this->message(null);

        if (!$this->rules) {
            throw new LogicException("Aucune règle spécifiée!");
        }

        $result = null;

        foreach ($this->rules as $rule) { /* @var $rule RuleInterface */
            if (!$rule->isRelevant()) {
                continue;
            }

            $execute = $rule->execute();

            /*if (is_boolean($execute)) {
                $result = $this->processBoolean($execute, $result);
            }
            else*/if (is_array($execute)) {
                $result = $this->processArray($execute, $result);
            }
            else {
                var_dump($execute);
                throw new LogicException("Résultat d'exécution de règle imprévu!");
            }
        }

        return $result;
    }

    /**
     * Retourne la pertinence de cette expression.
     * Si au moins une règle est pertinente, l'ensemble l'est.
     *
     * @return boolean
     * @see RuleInterface
     */
    public function isRelevant()
    {
        foreach ($this->rules as $rule) { /* @var $rule RuleInterface */
            if ($rule->isRelevant()) {
                return true;
            }
        }

        return false;
    }

//    /**
//     *
//     * @param boolean $b1
//     * @param boolean $b2
//     * @return boolean
//     * @throws LogicException
//     */
//    protected function processBoolean($b1, $b2 = null)
//    {
//        if (null === $b2) {
//            return $b1;
//        }
//
//        switch ($this->operator) {
//            case self::OPERATOR_AND:
//                return $b1 && $b2;
//            case self::OPERATOR_OR:
//                return $b1 || $b2;
//            default:
//                throw new LogicException("Opérateur imprévu : " . $this->operator);
//        }
//    }

    /**
     *
     * @param array $a1
     * @param array $a2
     * @return array
     * @throws LogicException
     */
    protected function processArray($a1, $a2 = null)
    {
        if (null === $a2) {
            return $a1;
        }

        switch ($this->operator) {
            case self::OPERATOR_AND:
                return array_uintersect_assoc($a1, $a2, function ($a, $b) { return $a == $b ? 0 : -1; });
            case self::OPERATOR_OR:
                return $a1 + $a2;
            default:
                throw new LogicException("Opérateur imprévu : " . $this->operator);
        }
    }
}