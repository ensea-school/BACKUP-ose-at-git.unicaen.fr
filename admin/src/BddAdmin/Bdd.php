<?php

namespace BddAdmin;

use BddAdmin\Ddl\DdlInterface;
use BddAdmin\Driver\DriverInterface;
use BddAdmin\Exception\BddCompileException;
use BddAdmin\Exception\BddException;
use BddAdmin\Exception\BddIndexExistsException;
use \Exception;

class Bdd
{
    const FETCH_ALL  = 32;
    const FETCH_EACH = 16;
    const FETCH_ONE  = 8;

    const DDL_TABLE              = 'table';
    const DDL_VIEW               = 'view';
    const DDL_SEQUENCE           = 'sequence';
    const DDL_MATERIALIZED_VIEW  = 'materialized-view';
    const DDL_PRIMARY_CONSTRAINT = 'primary-constraint';
    const DDL_PACKAGE            = 'package';
    const DDL_REF_CONSTRAINT     = 'ref-constraint';
    const DDL_INDEX              = 'index';
    const DDL_UNIQUE_CONSTRAINT  = 'unique-constraint';
    const DDL_TRIGGER            = 'trigger';

    const TYPE_INT    = 'int';
    const TYPE_BOOL   = 'bool';
    const TYPE_FLOAT  = 'float';
    const TYPE_STRING = 'string';
    const TYPE_DATE   = 'date';
    const TYPE_BLOB   = 'blob';
    const TYPE_CLOB   = 'clob';

    /**
     * @var array
     */
    private $config;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @var Schema
     */
    protected $schema;



    /**
     * Bdd constructor.
     *
     * @param string $host
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->setConfig($config);
        }
    }



    public function getSchema(): Schema
    {
        if (!$this->schema) {
            $this->schema = new Schema($this);
        }

        return $this->schema;
    }



    /**
     * @return self
     */
    public function beginTransaction(): self
    {
        $this->driver->beginTransaction();

        return $this;
    }



    /**
     * @return $this
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function commitTransaction(): self
    {
        $this->driver->commitTransaction();

        return $this;
    }



    /**
     * @return $this
     */
    public function rollbackTransaction(): self
    {
        $this->driver->rollbackTransaction();

        return $this;
    }



    /**
     * @param string $sequenceName
     *
     * @return int
     */
    public function sequenceNextVal(string $sequenceName): int
    {
        $r = $this->select("SELECT $sequenceName.NEXTVAL seqval FROM DUAL");

        return (int)$r[0]['SEQVAL'];
    }



    /**
     * @param string $sql
     * @param array  $params
     *
     * @return bool
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function exec(string $sql, array $params = [], array $types = [])
    {
        var_dump($sql);
        if ($this->debug) {
            echo $sql;
            var_dump($params);
        } else {
            $this->driver->exec($sql, $params, $types);
        }

        return true;
    }



    /**
     * @param string $filename
     *
     * @return \Exception[]
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function execFile(string $filename): array
    {
        if (!file_exists($filename)) {
            throw new Exception('Le fichier ' . $filename . ' n \'existe pas.');
        }

        $file    = file_get_contents($filename);
        $queries = explode("/--", $file);
        $errors  = [];
        foreach ($queries as $q) {
            $q = trim($q);
            if (substr($q, -1) == ';') {
                $q = substr($q, 0, -1);
            }
            try {
                $this->exec($q);
            } catch (\Exception $e) {
                $errors[] = $e;
            }
        }

        return $errors;
    }



    /**
     * @param string $sql
     * @param array  $params
     * @param array  $options
     *
     * @return resource
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function select(string $sql, array $params = [], array $options = [])
    {
        return $this->driver->select($sql, $params, $options);
    }



    /**
     * @param string $name
     *
     * @return Table
     */
    public function getTable(string $name): Table
    {
        $table = new Table($this, $name);

        return $table;
    }



    /**
     * @param string $name
     *
     * @return string
     */
    public function getDdlClass(string $name): string
    {
        return $this->driver->getDdlClass($name);
    }



    public function fetch($statement, array $options = [])
    {
        return $this->driver->fetch($statement, $options);
    }



    public function __destruct()
    {
        $this->driver->disconnect();
    }



    public function getConfig(): array
    {
        return $this->config;
    }



    public function setConfig(array $config): self
    {
        $this->config = $config;
        if ($this->driver) {
            $this->driver->disconnect();
        }
        $driverClass  = isset($config['driver']) ? $config['driver'] : 'Oracle';
        $driverClass  = "\BddAdmin\Driver\\$driverClass\Driver";
        $this->driver = new $driverClass($this);
        $this->driver->connect();

        return $this;
    }
}