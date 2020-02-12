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

    /**
     * @var array
     */
    private $config;

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @var DdlInterface[]
     */
    private $ddlObjects = [];

    /**
     * @var bool
     */
    public $debug = false;



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
    public function exec(string $sql, array $params = [])
    {
        if ($this->debug) {
            echo $sql;
            var_dump($params);
        } else {
            $this->driver->exec($sql, $params);
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
     * @param int    $fetchMode
     *
     * @return resource
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function select(string $sql, array $params = [], $fetchMode = self::FETCH_ALL)
    {
        return $this->driver->select($sql, $params, $fetchMode);
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
     * @param bool   $autoClear
     *
     * @return DdlInterface
     * @throws Exception
     */
    public function getDdl(string $name, bool $autoClear = false): DdlInterface
    {
        $class = $this->driver->getDdlClass($name);

        if (!is_subclass_of($class, DdlInterface::class)) {
            throw new \Exception($class . ' n\'est pas un objet DDL valide!!');
        }

        if (!isset($this->ddlObjects[$class])) {
            $this->ddlObjects[$class] = new $class($this);
        }

        if ($autoClear) {
            $this->ddlObjects[$class]->clearQueries();
            $this->ddlObjects[$class]->clearOptions();
        }

        return $this->ddlObjects[$class];
    }



    public function fetch($statement)
    {
        return $this->driver->fetch($statement);
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
            $this->ddlObjects = [];
        }
        $driverClass  = isset($config['driver']) ? $config['driver'] : 'Oracle';
        $driverClass  = "\BddAdmin\Driver\\$driverClass\Driver";
        $this->driver = new $driverClass($this);
        $this->driver->connect();

        return $this;
    }
}