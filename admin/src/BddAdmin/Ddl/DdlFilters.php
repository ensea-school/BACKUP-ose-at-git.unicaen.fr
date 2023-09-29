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



    public function current(): mixed
    {
        return current($this->objects);
    }



    public function next(): void
    {
        next($this->objects);
    }



    public function key(): mixed
    {
        return key($this->objects);
    }



    public function valid(): bool
    {
        return key($this->objects) !== null;
    }



    public function rewind(): void
    {
        reset($this->objects);
    }



    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }



    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }



    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }



    public function offsetUnset($offset): void
    {
        unset($this->objects[$offset]);
    }

}