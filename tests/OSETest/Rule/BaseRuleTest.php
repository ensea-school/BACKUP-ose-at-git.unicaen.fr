<?php

namespace OSETest\Rule;

use Application\Rule\RuleInterface;
use OSETest\Bootstrap;
use OSETest\Entity\Db\EntityProvider;
use OSETest\EntityManagerAwareTrait;
use PHPUnit_Framework_TestCase;

/**
 * Classe mère des tests de règles métiers.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class BaseRuleTest extends PHPUnit_Framework_TestCase
{
    use EntityManagerAwareTrait;
    
    /**
     * @var RuleInterface
     */
    protected $rule;
    
    /**
     * @var EntityProvider
     */
    protected $entityProvider;

    /**
     * @return string
     */
    abstract protected function getRuleName();

    /**
     * 
     */
    protected function setUp()
    {
        $this->rule = Bootstrap::getServiceManager()->get($this->getRuleName());
    }
    
    /**
     * 
     * @return EntityProvider
     */
    public function getEntityProvider()
    {
        if (null === $this->entityProvider) {
            $this->entityProvider = new EntityProvider($this->getEntityManager());
        }
        
        return $this->entityProvider;
    }
}