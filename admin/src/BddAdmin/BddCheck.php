<?php

namespace BddAdmin;



class BddCheck
{
    use BddAwareTrait;

    /**
     * @var array
     */
    private $errors;



    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(Bdd $bdd = null)
    {
        if ($bdd) {
            $this->bdd = $bdd;
        }
    }



    public function check( array $rules=null)
    {
        $this->errors = [];

        if (null === $rules){
            $rules = [];
            $rs = scandir(__DIR__ . '/BddCheck');
            foreach( $rs as $rule ){
                if (!in_array($rule, ['.','..','BddCheckRule.php'])){
                    $rules[] = substr($rule, 0, -4);
                }
            }
        }

        foreach ($rules as $rule) {
            $className = 'OseAdmin\\Bdd\\BddCheck\\' . $rule;
            $rule      = new $className($this);
            $ds        = $this->bdd->select($rule->sql());
            foreach ($ds as $d) {
                foreach ($d as $k => $v) {
                    $property = '';
                    $chars    = str_split($k);
                    $last_    = false;
                    foreach ($chars as $c) {
                        if ('_' != $c) {
                            if ($last_) {
                                $property .= strtoupper($c);
                            } else {
                                $property .= strtolower($c);
                            }
                        }
                        $last_ = ('_' == $c);
                    }
                    $rule->$property = $v;
                }
                $rule->check();
            }
        }

        return $this->errors;
    }



    public function addError($table, $column, array $error)
    {
        if (!isset($this->errors[$table][$column])) {
            $this->errors[$table][$column] = [];
        }
        $this->errors[$table][$column][] = $error;

        return $this;
    }

}