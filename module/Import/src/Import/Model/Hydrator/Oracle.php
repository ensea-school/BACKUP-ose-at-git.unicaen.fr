<?php

namespace Import\Model\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Code\Reflection\ClassReflection;
use ReflectionMethod;
use Import\Model\Hydrator\Strategy\DateStrategy;
use Import\Model\Hydrator\Strategy\BooleanStrategy;
use Import\Model\Hydrator\Strategy\IntegerStrategy;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Oracle extends ClassMethods
{

    /**
     * 
     *
     * @var array
     */
    private static $formats = array();




    /**
     * retourne le format d'un objet
     *
     * @param Stdclass $object
     * @return array
     */
    private function getFormat( $object )
    {
        $className = get_class($object);
        if (isset(self::$formats[$className])) return self::$formats[$className];
        $rc = new ClassReflection( $object );

        $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        $format = array();
        foreach ($methods as $method) {
            $attribute = $method->getName();
            if (preg_match('/^set/', $attribute)) {
                $attribute = substr($attribute, 3);
                if (!$rc->hasProperty($attribute)) {
                    $attribute = lcfirst($attribute);
                }
                if (($property = $rc->getProperty($attribute))){
                    if (($db = $property->getDocBlock()) && ($vars = $db->getTags('var'))){
                        $type = $vars[0]->returnValue(0);
                    }else{
                        $type = 'string'; // par défaut
                    }
                    $format[$attribute] = $type;
                }
            }
        }
        self::$formats[$className] = $format;
        return $format;
    }

    /**
     * Construction automatique des stratégies à partir du format de classe
     *
     * @param Stdclass $object
     */
    public function makeStrategies( $object )
    {
        $format = $this->getFormat($object);
        foreach( $format as $property => $type ){
            switch( $type ){
            case 'DateTime':
                $this->addStrategy($property, new DateStrategy());
            break;
            case 'boolean':
            case 'bool':
                $this->addStrategy($property, new BooleanStrategy());
            break;
            case 'integer':
            case 'int':
                $this->addStrategy($property, new IntegerStrategy());
            break;
            }
        }
    }

    /**
     * Hydrate an object by populating getter/setter methods
     *
     * Hydrates an object by getter/setter methods of the object.
     *
     * @param  array                            $data
     * @param  object                           $object
     * @return object
     * @throws Exception\BadMethodCallException for a non-object $object
     */
    public function hydrate(array $data, $object)
    {
        $oldData = $data;
        $data = array();
        foreach( $oldData as $key => $value ){
            /* Conversion en camelCase de la clé */
            $key = lcfirst( str_replace( ' ', '', ucwords( str_replace( '_', ' ', strtolower($key) ) ) ) );
            
            $data[$key] = $value;
        }
        return parent::hydrate($data, $object);
    }
}