<?php

namespace OSETest\Entity\Db;

use PHPUnit_Framework_TestCase;

/**
 * Description of BaseTest
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    use \OSETest\EntityManagerAwareTrait;
    
    /**
     * @var EntityProvider
     */
    protected $entityProvider;
    
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