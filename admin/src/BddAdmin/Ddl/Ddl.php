<?php

namespace BddAdmin\Ddl;

use BddAdmin\Ddl\DdlFilters;

class Ddl implements \Iterator, \ArrayAccess
{
    const TABLE              = 'table';
    const VIEW               = 'view';
    const SEQUENCE           = 'sequence';
    const MATERIALIZED_VIEW  = 'materialized-view';
    const PRIMARY_CONSTRAINT = 'primary-constraint';
    const PACKAGE            = 'package';
    const REF_CONSTRAINT     = 'ref-constraint';
    const INDEX              = 'index';
    const UNIQUE_CONSTRAINT  = 'unique-constraint';
    const TRIGGER            = 'trigger';

    /**
     * @var array
     */
    protected $data = [];



    /**
     * @return array|null
     */
    public function get(string $name): ?array
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return null;
    }



    /**
     * @param string $name
     * @param array  $data
     *
     * @return self
     */
    public function set(string $name, ?array $data): self
    {
        $this->data[$name] = $data;

        return $this;
    }



    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->data);
    }



    public function toArray(): array
    {
        return $this->data;
    }



    /**
     * @param $data
     *
     * @return DdlFilters
     */
    public static function normalize($data): self
    {
        if ($data instanceof self) {
            return $data;
        }

        $ddl = new self;
        if (is_array($data)) {
            $ddl->data = $data;
        }

        return $ddl;
    }



    /**
     * @inheritDoc
     */
    public function current()
    {
        return current($this->data);
    }



    /**
     * @inheritDoc
     */
    public function next()
    {
        return next($this->data);
    }



    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->data);
    }



    /**
     * @inheritDoc
     */
    public function valid()
    {
        return key($this->data) !== null;
    }



    /**
     * @inheritDoc
     */
    public function rewind()
    {
        reset($this->data);
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
        unset($this->data[$offset]);
    }



    /**
     * @param DdlFilters|array|string|null $filters
     *
     * @return self
     */
    public function filter($filters): self
    {
        $filters = DdlFilters::normalize($filters);

        foreach ($this->data as $ddlType => $ddlConf) {
            foreach ($ddlConf as $name => $config) {
                if (!$filters[$ddlType]->match($name)) {
                    unset($this->data[$ddlType][$name]);
                }
            }
        }

        return $this;
    }



    private function arrayExport($var, $indent = "")
    {
        switch (gettype($var)) {
            case "array":
                $indexed   = array_keys($var) === range(0, count($var) - 1);
                $r         = [];
                $maxKeyLen = 0;
                foreach ($var as $key => $value) {
                    $key    = $this->arrayExport($key);
                    $keyLen = strlen($key);
                    if ($keyLen > $maxKeyLen) $maxKeyLen = $keyLen;
                }
                foreach ($var as $key => $value) {
                    $key = $this->arrayExport($key);
                    $r[] = "$indent    "
                        . ($indexed ? "" : str_pad($key, $maxKeyLen, ' ') . " => ")
                        . $this->arrayExport($value, "$indent    ");
                }

                return "[\n" . implode(",\n", $r) . ",\n" . $indent . "]";
            case "boolean":
                return $var ? "TRUE" : "FALSE";
            default:
                return var_export($var, true);
        }
    }



    /**
     * @param array  $ddl
     * @param string $filename
     */
    public function saveToFile(string $filename)
    {
        $ddlString = "<?php\n\n//@" . "formatter:off\n\nreturn " . $this->arrayExport($this->data) . ";\n\n//@" . "formatter:on\n";

        file_put_contents($filename, $ddlString);
    }



    /**
     * @param string $filename
     *
     * @return self
     */
    public function loadFromFile(string $filename): self
    {
        $this->data = require $filename;

        return $this;
    }
}