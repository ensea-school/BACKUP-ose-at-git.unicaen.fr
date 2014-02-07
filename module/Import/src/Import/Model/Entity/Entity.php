<?php

namespace Import\Model\Entity;

use DateTime;
use Zend\Code\Reflection\ClassReflection;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
abstract class Entity {

    /**
     * Format décrit à partir des annotations
     * 
     * @var array
     */
    private static $annotationFormats = array();

    /**
     * @return array
     */
    protected function getFormat()
    {
        return $this->getAnnotationFormat();
    }

    /**
     * Détection automatique du format à partir des tags JavaDoc
     * 
     * @return array
     */
    protected function getAnnotationFormat()
    {
        $className = get_class($this);
        if (! isset(self::$annotationFormats[$className])){
            self::$annotationFormats[$className] = array();
            $rc = new ClassReflection( $this );
            $tags = $rc->getDocBlock()->getTags('property');
            foreach( $tags as $tag ){
                $propertyName = substr( $tag->getPropertyName(), 1 ); // suppression du $
                self::$annotationFormats[$className][$propertyName] = array(
                    'type' => $tag->getType(),
                );
            }
        }
        return self::$annotationFormats[$className];
    }

    /**
     * Peuple l'objet à partir d'un tableau de données
     *
     * @param array $data
     * @param boolean $camelCaseConvert
     */
    public function hydrate( array $data, $camelCaseKeyConvert=true )
    {
        $format = $this->getFormat();
        foreach( $format as $attribute => $definition ){
            if ($camelCaseKeyConvert){
                $attribute = lcfirst( str_replace( ' ', '', ucwords( str_replace( '_', ' ', strtolower($attribute) ) ) ) );
            }
            $value = isset($data[$attribute]) ? $data[$attribute] : null;
            if (null !== $value){
                switch( $definition['type']){
                case 'integer':
                case 'int':
                    $value = (integer)$value;
                break;
                case 'DateTime':
                    $value = new DateTime( $value );
                break;
                case 'boolean':
                case 'bool':
                    $value = in_array( $value, array('O', 'Y', 'TRUE', 'o', 'y', 'true', '1') );
                break;
                }
            }

            /* Camel case conversion */
            $func = create_function('$c', 'return strtoupper($c[1]);');
            $attribute = preg_replace_callback('/_([a-z])/', $func, $attribute);
            $this->$attribute = $value;
        }
    }

    public function __construct( array $data=null, $camelCaseKeyConvert=true )
    {
        if (null !== $data) $this->hydrate ($data, $camelCaseKeyConvert);
    }
}