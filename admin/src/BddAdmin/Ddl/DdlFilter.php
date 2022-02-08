<?php

namespace BddAdmin\Ddl;

class DdlFilter implements \ArrayAccess
{
    /**
     * @var null|string|array
     */
    protected $includes = null;

    /**
     * @var null|string|array
     */
    protected $excludes = null;



    /**
     * @return array|string|null
     */
    public function getIncludes()
    {
        return $this->includes;
    }



    /**
     * @param array|string|null $includes
     *
     * @return DdlFilter
     */
    public function setIncludes($includes)
    {
        $this->includes = $includes;

        return $this;
    }



    public function addInclude(string $include): self
    {
        if (!$this->includes) {
            $this->includes = [];
        }
        if (!is_array($this->includes)) {
            throw new \Exception('On ne peut ajouter une règle d\'inclusion qu\'à un tableau');
        }
        $this->includes[] = $include;

        return $this;
    }



    /**
     * @return array|string|null
     */
    public function getExcludes()
    {
        return $this->excludes;
    }



    /**
     * @param array|string|null $excludes
     *
     * @return DdlFilter
     */
    public function setExcludes($excludes)
    {
        $this->excludes = $excludes;

        return $this;
    }



    public function addExclude(string $exclude): self
    {
        if (!$this->excludes) {
            $this->excludes = [];
        }
        if (!is_array($this->excludes)) {
            throw new \Exception('On ne peut ajouter une règle d\'exclusion qu\'à un tableau');
        }
        $this->excludes[] = $exclude;

        return $this;
    }



    public function toArray(): array
    {
        return [
            'includes' => $this->includes,
            'excludes' => $this->excludes,
        ];
    }



    /**
     * @param $data
     *
     * @return DdlFilter
     */
    static public function normalize($data): DdlFilter
    {
        if ($data instanceof self) {
            return $data;
        }
        $co = new self;
        if (is_array($data)) {
            $vars = ['includes', 'excludes'];
            foreach ($vars as $var) {
                if (array_key_exists($var, $data)) {
                    $co->$var = $data[$var];
                }
            }
        } else {
            $co->setIncludes($data);
        }

        return $co;
    }



    static public function normalize2($includes = [], $excludes = [])
    {
        if ($includes instanceof DdlFilter) return $includes;

        return self::normalize(compact('includes', 'excludes'));
    }



    /**
     * @param string $colName
     *
     * @return array
     */
    public function toSql(string $colName): array
    {
        $includes = $this->includes;
        $excludes = $this->excludes;

        $f = [];
        $p = [];

        if ($includes) {
            if (is_string($includes)) {
                $includes = [$includes];
            }
            $i = 0;
            if (!empty($includes)) {
                $f[] = 'AND (0=1';
                foreach ($includes as $include) {
                    $i++;
                    $f[]            = "OR $colName LIKE :include$i";
                    $p["include$i"] = $include;
                }
                $f[] = ')';
            }
        }

        if ($excludes) {
            if (is_string($excludes)) {
                $excludes = [$excludes];
            }
            $i = 0;
            foreach ($excludes as $exclude) {
                $i++;
                $f[]            = "AND $colName NOT LIKE :exclude$i";
                $p["exclude$i"] = $exclude;
            }
        }

        return [implode(' ', $f), $p];
    }



    /**
     * @param string $name
     *
     * @return bool
     */
    public function match(string $name): bool
    {
        if ($this->excludes) {
            $excludes = (array)$this->excludes;
            foreach ($excludes as $exclude) {
                if (preg_match('/^' . str_replace('%', '.*', $exclude) . '$/', $name, $out)) {
                    return false;
                }
            }
        }

        if ($this->includes) {
            $includes = (array)$this->includes;
            foreach ($includes as $include) {
                if (preg_match('/^' . str_replace('%', '.*', $include) . '$/', $name, $out)) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }



    public function isEmpty(): bool
    {
        return empty($this->includes) && empty($this->excludes);
    }



    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset);
    }



    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }



    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }



    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
    }
}