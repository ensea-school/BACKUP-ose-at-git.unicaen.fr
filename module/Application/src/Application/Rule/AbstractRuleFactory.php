<?php

namespace Application\Rule;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of AbstractRule
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AbstractRuleFactory implements \Zend\ServiceManager\AbstractFactoryInterface
{
    const SUFFIX = 'Rule';
    
    static private $instances = array();
    
    /**
     * 
     * @param string $className
     * @return \Application\Rule\class
     */
    static public function getRuleInstance($className)
    {
        if (isset(static::$instances[$className])) {
            return static::$instances[$className];
        }
        
        $class    = "\\Application\\Rule\\Intervenant\\$className";
        $instance = new $class();
        
        static::$instances[$className] = $instance;
        
        return $instance;
    }
    
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        // le nom doit se terminer par 'Rule'
        if (self::SUFFIX === substr($requestedName, -1 * strlen(self::SUFFIX))) {
            return true;
        }
        
        return false;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return mixed
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return static::getRuleInstance($requestedName);
    }
}