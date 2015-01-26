<?php

namespace OSETest\Rule;

use PHPUnit_Framework_TestCase;
use Application\Rule\AbstractRule ;
use Application\Rule\Expr;

/**
 * Test fonctionnel de la classe Expr.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ExprTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractRule 
     */
    protected $rule1;
    
    /**
     * @var AbstractRule 
     */
    protected $rule2;
    
    /**
     * @var Expr 
     */
    protected $expr;
    
    /**
     * 
     */
    protected function setUp()
    {
        $this->expr = new Expr();
        
        $this->rule1 = $this->getMockForAbstractClass('Application\Rule\AbstractRule');
        $this->rule2 = $this->getMockForAbstractClass('Application\Rule\AbstractRule');
        
        $this->expr
                ->addRule($this->rule1)
                ->addRule($this->rule2);
    }
    
    /**
     * 
     * @return array
     */
    public function getExecuteDataset()
    {
        return [
            [
                Expr::OPERATOR_OR, // $operator
                [], // $rule1ExecuteResult
                [1 => ['id' => 1]], // $rule2ExecuteResult
                [1 => ['id' => 1]], // $expectedExecuteResult
            ],
            [
                Expr::OPERATOR_OR, // $operator
                [1 => ['id' => 1], 2 => ['id' => 2]], // $rule1ExecuteResult
                [1 => ['id' => 1], 3 => ['id' => 3]], // $rule2ExecuteResult
                [1 => ['id' => 1], 2 => ['id' => 2], 3 => ['id' => 3]], // $expectedExecuteResult
            ],
            [
                Expr::OPERATOR_AND, // $operator
                [], // $rule1ExecuteResult
                [1 => ['id' => 1]], // $rule2ExecuteResult
                [], // $expectedExecuteResult
            ],
            [
                Expr::OPERATOR_AND, // $operator
                [1 => ['id' => 1]], // $rule1ExecuteResult
                [2 => ['id' => 2]], // $rule2ExecuteResult
                [], // $expectedExecuteResult
            ],
            [
                Expr::OPERATOR_AND, // $operator
                [1 => ['id' => 1], 2 => ['id' => 2]], // $rule1ExecuteResult
                [1 => ['id' => 1], 3 => ['id' => 3]], // $rule2ExecuteResult
                [1 => ['id' => 1]], // $expectedExecuteResult
            ],
        ];
    }
    
    /**
     * 
     * @return array
     */
    public function getIsRelevantDataset()
    {
        return [
            [
                false, // $rule1IsRelevant
                false, // $rule2IsRelevant
                false, // $expectedIsRelevant
            ],
            [
                false, // $rule1IsRelevant
                true, // $rule2IsRelevant
                true, // $expectedIsRelevant
            ],
            [
                true, // $rule1IsRelevant
                true, // $rule2IsRelevant
                true, // $expectedIsRelevant
            ],
        ];
    }
    
    /**
     * @dataProvider getIsRelevantDataset
     */
    public function testIsRelevant($rule1IsRelevant, $rule2IsRelevant, $expectedIsRelevant)
    {
        $this->rule1
                ->expects($this->any())
                ->method('isRelevant')
                ->will($this->returnValue($rule1IsRelevant));
        $this->rule2
                ->expects($this->any())
                ->method('isRelevant')
                ->will($this->returnValue($rule2IsRelevant));
        
        $this->assertEquals($expectedIsRelevant, $this->expr->isRelevant());
    }
    
    /**
     * @dataProvider getExecuteDataset
     */
    public function testExecute($operator, $rule1ExecuteResult, $rule2ExecuteResult, $expectedExecuteResult)
    {
        $this->rule1
                ->expects($this->any())
                ->method('isRelevant')
                ->will($this->returnValue(true));
        $this->rule2
                ->expects($this->any())
                ->method('isRelevant')
                ->will($this->returnValue(true));
        
        $this->expr->setOperator($operator);
        
        $this->rule1
                ->expects($this->any())
                ->method('execute')
                ->will($this->returnValue($rule1ExecuteResult));
        $this->rule2
                ->expects($this->any())
                ->method('execute')
                ->will($this->returnValue($rule2ExecuteResult));
        
        $this->assertEquals($expectedExecuteResult, $this->expr->execute());
    }
    
    protected function tearDown()
    {
        parent::tearDown();
    }
}