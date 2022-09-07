<?php

namespace BddAdmin\Ddl;

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
     * Applique un filtre à la DDL : en retire tout objet qui ne passe pas le filtre
     *
     * @param DdlFilters|array|string|null $filters
     *
     * @return self
     */
    public function filter($filters): self
    {
        $filters = DdlFilters::normalize($filters);

        foreach ($this->data as $ddlType => $ddlConf) {
            foreach ($ddlConf as $name => $null) {
                if (!$filters[$ddlType]->match($name)) {
                    unset($this->data[$ddlType][$name]);
                }
            }
        }

        return $this;
    }



    /**
     *
     * Crée un filtre à partir de la DDL
     * Permet de ne modifier une base de donnée existance que sur le périmètre de la DDL courante sans toucher aux
     * autres objets
     *
     * @param DdlFilters|array|string|null $filters
     *
     * @return DdlFilters
     */
    public function filterOnlyDdl($filters = null): DdlFilters
    {
        $filters = DdlFilters::normalize($filters);
        $filters->setExplicit(true);
        foreach ($this->data as $ddlType => $ddlConf) {
            foreach ($ddlConf as $name => $null) {
                $filters->get($ddlType)->addInclude($name);
            }
        }

        return $filters;
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
     * On passe un tableau de positions de colonnes, et cela réorganise l'ordonnancement.
     *
     * @param array $positions
     *
     * @return array
     */
    public function applyColumnPositions(array $positions): array
    {
        $tables = $this->get(Ddl::TABLE);
        foreach ($tables as $tableName => $table) {
            $cols = [];
            if (isset($positions[$tableName])) {
                foreach ($positions[$tableName] as $idc => $col) {
                    if (!array_key_exists($col, $table['columns'])) {
                        unset($positions[$tableName][$idc]);
                    }
                }
            }
            foreach ($table['columns'] as $column) {
                $colname = $column['name'];
                if (!isset($positions[$tableName]) || !in_array($colname, $positions[$tableName])) {
                    $cols[$colname] = $column['position'];
                }
            }
            asort($cols);
            $cols = array_keys($cols);

            if (!isset($positions[$tableName])) {
                $positions[$tableName] = [];
            }
            $cols = array_merge($positions[$tableName], $cols);

            foreach ($cols as $pos => $col) {
                if (isset($this->data[Ddl::TABLE][$tableName]['columns'][$col])) {
                    $this->data[Ddl::TABLE][$tableName]['columns'][$col]['position'] = $pos + 1;
                }
            }

            $positions[$tableName] = $cols;
        }

        return $positions;
    }



    public function orderTabCols(): self
    {

        if (!$this->has(Ddl::TABLE)) return $this;

        foreach ($this->data[Ddl::TABLE] as $table) {
            $columns = $table['columns'];
            uasort($columns, function ($a, $b) {
                return $a['position'] - $b['position'];
            });

            $this->data[Ddl::TABLE][$table['name']]['columns'] = $columns;
        }

        return $this;
    }



    public function writeArray(string $filename, array $data)
    {
        $ddlString = "<?php\n\n//@" . "formatter:off\n\nreturn " . $this->arrayExport($data) . ";\n\n//@" . "formatter:on\n";

        file_put_contents($filename, $ddlString);
    }



    public function saveToString(): string
    {
        $data = $this->data;
        asort($data);

        $ddlString = "<?php\n\n//@" . "formatter:off\n\nreturn " . $this->arrayExport($data) . ";\n\n//@" . "formatter:on\n";

        return $ddlString;
    }



    /**
     * @param array  $ddl
     * @param string $filename
     */
    public function saveToFile(string $filename)
    {
        file_put_contents($filename, $this->saveToString());
    }



    protected function rrmdir($src)
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $src . '/' . $file;
                if (is_dir($full)) {
                    $this->rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($src);
    }



    public function saveToDir(string $dirname)
    {
        if (file_exists($dirname)) {
            $this->rrmdir($dirname);
        }
        mkdir($dirname);
        foreach ($this->data as $type => $ds) {
            $dir = $dirname . '/' . $type;

            if ($type == self::SEQUENCE) {
                $this->writeArray($dir . '.php', array_keys($ds));
            } else {
                mkdir($dir);
                foreach ($this->data[$type] as $d) {
                    $file = $dir . '/' . $d['name'];
                    switch ($type) {
                        case self::TABLE:
                        case self::PRIMARY_CONSTRAINT:
                        case self::REF_CONSTRAINT:
                        case self::UNIQUE_CONSTRAINT:
                        case self::INDEX:
                            $this->writeArray($file . '.php', $d);
                        break;
                        case self::VIEW:
                        case self::MATERIALIZED_VIEW:
                        case self::TRIGGER:
                            file_put_contents($file . '.sql', $d['definition']);
                        break;
                        case self::PACKAGE:
                            mkdir($file);
                            file_put_contents($file . '/definition.sql', $d['definition']);
                            file_put_contents($file . '/body.sql', $d['body']);
                        break;
                    }
                }
            }
        }
    }



    public function loadFromDir(string $dir)
    {
        if (substr($dir, -1) != '/') $dir .= '/';

        $this->data = [];
        if (file_exists($dir . self::SEQUENCE . '.php')) {
            $sequences                  = require $dir . self::SEQUENCE . '.php';
            $this->data[self::SEQUENCE] = [];
            foreach ($sequences as $sequence) {
                $this->data[self::SEQUENCE][$sequence] = ['name' => $sequence];
            }
        }

        if (file_exists($dir . self::PACKAGE) && is_dir($dir . self::PACKAGE)) {
            $this->data[self::PACKAGE] = [];
            $data                      = scandir($dir . self::PACKAGE);
            foreach ($data as $name) {
                if ($name != '.' && $name != '..') {
                    $this->data[self::PACKAGE][$name] = ['name' => $name];
                    if (file_exists($dir . self::PACKAGE . '/' . $name . '/definition.sql')) {
                        $this->data[self::PACKAGE][$name]['definition'] = file_get_contents($dir . self::PACKAGE . '/' . $name . '/definition.sql');
                    }
                    if (file_exists($dir . self::PACKAGE . '/' . $name . '/body.sql')) {
                        $this->data[self::PACKAGE][$name]['body'] = file_get_contents($dir . self::PACKAGE . '/' . $name . '/body.sql');
                    }
                }
            }
        }

        $arrays = [self::TABLE, self::PRIMARY_CONSTRAINT, self::REF_CONSTRAINT, self::UNIQUE_CONSTRAINT, self::INDEX];
        foreach ($arrays as $type) {
            if (file_exists($dir . $type) && is_dir($dir . $type)) {
                $this->data[$type] = [];
                $data              = scandir($dir . $type);
                foreach ($data as $name) {
                    if ($name != '.' && $name != '..') {
                        $def                             = require $dir . $type . '/' . $name;
                        $this->data[$type][$def['name']] = $def;
                    }
                }
            }
        }

        $sqls = [self::VIEW, self::MATERIALIZED_VIEW, self::TRIGGER];
        foreach ($sqls as $type) {
            if (file_exists($dir . $type) && is_dir($dir . $type)) {
                $this->data[$type] = [];
                $data              = scandir($dir . $type);
                foreach ($data as $name) {
                    if ($name != '.' && $name != '..') {
                        $def                      = file_get_contents($dir . $type . '/' . $name);
                        $name                     = substr($name, 0, -4);
                        $this->data[$type][$name] = ['name' => $name, 'definition' => $def];
                    }
                }
            }
        }
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