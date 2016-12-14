<?php

namespace Application\Entity;

class IntervenantSuppressionData implements \IteratorAggregate, \ArrayAccess {

    /**
     * @var string
     */
    private $subject;

    /**
     * @var mixed
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var integer
     */
    private $ordre = 0;

    /**
     * @var object
     */
    private $entity;

    /**
     * @var self[]
     */
    private $children = [];



    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }



    /**
     * @param string $subject
     *
     * @return IntervenantSuppressionData
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param mixed $id
     *
     * @return IntervenantSuppressionData
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }



    /**
     * @param string $label
     *
     * @return IntervenantSuppressionData
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }



    /**
     * @return int
     */
    public function getOrdre()
    {
        return $this->ordre;
    }



    /**
     * @param int $ordre
     *
     * @return IntervenantSuppressionData
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * @return object
     */
    public function getEntity()
    {
        return $this->entity;
    }



    /**
     * @param object $entity
     *
     * @return IntervenantSuppressionData
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getKey()
    {
        return $this->getId() ?: ($this->getEntity() ? $this->getEntity()->getId() : null);
    }



    /**
     * @param $key
     *
     * @return bool
     */
    public function has( $key )
    {
        return array_key_exists( $key, $this->children );
    }



    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }



    /**
     * @param $key
     *
     * @return self|null
     */
    public function get( $key )
    {
        if ($this->has($key)){
            return $this->children[$key];
        }

        return null;
    }



    /**
     * @param IntervenantSuppressionData $isd
     */
    public function add( IntervenantSuppressionData $isd )
    {
        $key = $isd->getKey();
        if (!$key){
            throw new Exception('Classe incomplète : merci de fournir un ID ou une entité');
        }

        $this->children[$key] = $isd;
    }



    /**
     * @param $key
     *
     * @return $this
     */
    public function remove( $key )
    {
        if ($key instanceof IntervenantSuppressionData){
            $key = $key->getKey();
        }

        if ($this->has($key)){
            unset($this->children[$key]);
        }

        return $this;
    }



    /**
     * @param array $children
     *
     * @return $this
     */
    public function setChildren( array $children )
    {
        $this->children = [];
        foreach( $children as $child ){
            if (!$child instanceof IntervenantSuppressionData){
                throw new \Exception('Un fils n\'est pas de classe '.__CLASS__);
            }
            $this->add($child);
        }

        return $this;
    }



    /**
     * @return IntervenantSuppressionData[]
     */
    public function getChildren()
    {
        return $this->children;
    }



    /**
     * @return $this
     */
    public function order()
    {
        uasort($this->children, function($a,$b){
            return $a->getOrdre() > $b->getOrdre();
        });

        return $this;
    }



    /**
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->children);
    }



    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }



    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }



    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->add($value);
    }



    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

}