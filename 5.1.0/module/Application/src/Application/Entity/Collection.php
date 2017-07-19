<?php

namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Collection extends ArrayCollection
{

    /**
     * entityClass
     *
     * @var string
     */
    protected $entityClass;


    /**
     * Initializes a new Collection.
     *
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct([]);
        foreach( $elements as $key => $element ){
            $this->set( $key, $element );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $this->entityClassMatches( $value, true );
        return parent::set($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function add($value)
    {
        $this->entityClassMatches( $value, true );
        return parent::add($value);
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * 
     * @return string
     */
    function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     *
     * @param string $entityClass
     * @return self
     */
    function initEntityClass($entityClass)
    {
        if (empty($this->entityClass)){
            if (is_object($entityClass)){
                $entityClass = get_class($entityClass);
            }
            $this->entityClass = $entityClass;
        }
        return $this;
    }

    /**
     *
     * @param object $element
     * @param boolean $throwsException
     * @return boolean
     * @throws \LogicException
     */
    public function entityClassMatches( $element, $throwsException=false )
    {
        if (! is_object($element)){
            throw new \LogicException('Seuls des objets peuvent être ajoutés à des listes d\'entités');
        }

        if (empty($this->entityClass)){
            $this->entityClass = get_class($element);
        }

        $result = is_a( $element, $this->entityClass );

        if (! $result && $throwsException){
            throw new \LogicException('L\'entité ne correspond pas au type ('.$this->entityClass.') de classe acceptée par la liste');
        }

        return $result;
    }
}