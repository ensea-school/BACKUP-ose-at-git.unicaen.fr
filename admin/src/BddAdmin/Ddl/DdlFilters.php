<?php

namespace BddAdmin\Ddl;

class DdlFilters implements \Iterator, \ArrayAccess
{
    /**
     * @var DdlFilter[]
     */
    protected $objects = [];

    /**
     * @var bool
     */
    protected $explicit = false;



    /**
     * @return DdlFilter
     */
    public function get(string $name): DdlFilter
    {
        if (!array_key_exists($name, $this->objects)) {
            $this->objects[$name] = new DdlFilter;
        }

        return $this->objects[$name];
    }



    /**
     * @param string    $name
     * @param DdlFilter $objects
     *
     * @return DdlFilters
     */
    public function set(string $name, DdlFilter $object): DdlFilters
    {
        $this->objects[$name] = $object;

        return $this;
    }



    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->objects);
    }



    public function toArray(): array
    {
        $a = [
            'explicit' => $this->explicit,
        ];
        foreach ($this->objects as $name => $object) {
            $a[$name] = $object->toArray();
        }

        return $a;
    }



    /**
     * @param $data
     *
     * @return DdlFilters
     */
    public static function normalize($data): DdlFilters
    {
        if ($data instanceof self) {
            return $data;
        }

        $config = new self;
        if (is_array($data)) {
            foreach ($data as $name => $objData) {
                switch ($name) {
                    case 'explicit':
                        $config->setExplicit((bool)$objData);
                    break;
                    default:
                        $config->set($name, DdlFilter::normalize($objData));
                }
            }
        }

        return $config;
    }



    /**
     * @return bool
     */
    public function isExplicit(): bool
    {
        return $this->explicit;
    }



    /**
     * @param bool $explicit
     *
     * @return DdlFilters
     */
    public function setExplicit(bool $explicit): DdlFilters
    {
        $this->explicit = $explicit;

        return $this;
    }



    public function __construct()
    {
        $this->position = 0;
    }



    /**
     * @inheritDoc
     */
    public function current()
    {
        return current($this->objects);
    }



    /**
     * @inheritDoc
     */
    public function next()
    {
        return next($this->objects);
    }



    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->objects);
    }



    /**
     * @inheritDoc
     */
    public function valid()
    {
        return key($this->objects) !== null;
    }



    /**
     * @inheritDoc
     */
    public function rewind()
    {
        reset($this->objects);
    }



    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }



    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }



    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }



    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        unset($this->objects[$offset]);
    }

}