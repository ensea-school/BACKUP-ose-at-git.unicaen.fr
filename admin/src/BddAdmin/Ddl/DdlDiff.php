<?php

namespace BddAdmin\Ddl;

class DdlDiff implements \Iterator, \ArrayAccess
{

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



    public function __toString()
    {
        return $this->toScript();
    }



    /**
     * @param string|null $title
     * @param bool        $reduce
     *
     * @return string
     */
    public function toScript(string $title = null, bool $onlyFirstLine = false): string
    {
        $sql = '';
        if ($title) {
            $sql .= '--------------------------------------------------' . "\n";
            $sql .= '-- ' . "$title\n";
            $sql .= '--------------------------------------------------' . "\n";
            $sql .= "\n\n";
            $sql .= 'SET DEFINE OFF;' . "\n";
            $sql .= "\n\n";
        }
        if (empty($this->data)) {
            $sql .= "-- Aucune requête à exécuter.";
        } else {
            foreach ($this->data as $label => $qs) {
                if (!empty($qs)) {
                    $sql .= '--------------------------------------------------' . "\n";
                    $sql .= '-- ' . $label . "\n";
                    $sql .= '--------------------------------------------------' . "\n\n";
                    foreach ($qs as $qr => $description) {
                        $qr = str_replace("\t", "  ", $qr);
                        if ($onlyFirstLine && false !== strpos($qr, "\n")) {
                            $qr = substr($qr, 0, strpos($qr, "\n"));
                        }

                        if (substr(trim($qr), -1) != ';') {
                            $qr .= ';';
                        }
                        $sql .= "$qr\n/\n\n";
                    }
                    $sql .= "\n\n\n";
                }
            }
        }

        return $sql;
    }

}