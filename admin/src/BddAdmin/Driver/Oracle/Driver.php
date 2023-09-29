<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Ddl\Ddl;
use BddAdmin\Driver\DriverInterface;
use BddAdmin\Exception\BddCompileException;
use BddAdmin\Exception\BddException;
use BddAdmin\Exception\BddIndexExistsException;
use BddAdmin\SelectParser;

class Driver implements DriverInterface
{
    /**
     * @var Bdd
     */
    private $bdd;

    /**
     * @var resource
     */
    private $connexion;

    /**
     * @var int
     */
    private $commitMode = OCI_COMMIT_ON_SUCCESS;



    /**
     * Driver constructor.
     *
     * @param Bdd $bdd
     */
    public function __construct(Bdd $bdd)
    {
        $this->bdd = $bdd;
    }



    public function connect(): DriverInterface
    {
        $config       = $this->bdd->getConfig();
        $host         = isset($config['host']) ? $config['host'] : null;
        $port         = isset($config['port']) ? $config['port'] : null;
        $dbname       = isset($config['dbname']) ? $config['dbname'] : null;
        $username     = isset($config['username']) ? $config['username'] : null;
        $password     = isset($config['password']) ? $config['password'] : null;
        $characterset = isset($config['characterset']) ? $config['characterset'] : 'AL32UTF8';

        if (!$host) throw new BddException('host non fourni');
        if (!$port) throw new BddException('port non fourni');
        if (!$dbname) throw new BddException('dbname non fourni');
        if (!$username) throw new BddException('username non fourni');
        if (!$password) throw new BddException('password non fourni');
        if (!$characterset) throw new BddException('characterset non fourni');

        $cs              = $host . ':' . $port . '/' . $dbname;
        $this->connexion = oci_pconnect($username, $password, $cs, $characterset);
        if (!$this->connexion) {
            $error = oci_error();
            throw $this->sendException($error);
        }

        $this->exec('ALTER SESSION SET NLS_DATE_FORMAT = \'yyyy-mm-dd hh24:mi:ss\'');
        $this->exec('ALTER SESSION SET NLS_TIMESTAMP_TZ_FORMAT = \'yyyy-mm-dd"T"hh24:mi:ss\'');
        $this->exec('ALTER SESSION SET NLS_NUMERIC_CHARACTERS=". "');

        return $this;
    }



    public function disconnect(): DriverInterface
    {
        if ($this->connexion) {
            oci_close($this->connexion);
        }

        return $this;
    }



    private function execStatement($sql, array $params = [], array $types = [])
    {
        if ($this->bdd->isInCopy() && 0 === strpos($sql, 'INSERT INTO')) {
            $sql = 'INSERT /*+ APPEND */ INTO' . substr($sql, strlen('INSERT INTO'));
        }

        foreach ($params as $name => $val) {
            if (is_bool($val)) {
                $params[$name] = $val ? 1 : 0;
            } elseif ($val instanceof \DateTime) {
                $params[$name] = $val->format('Y-m-d H:i:s');
                $sql           = str_replace(":$name", "to_date(:$name, 'YYYY-MM-DD HH24:MI:SS')", $sql);
            } else {
                $params[$name] = $val;
            }
        }

        $statement = oci_parse($this->connexion, $sql);

        foreach ($params as $key => $val) {
            $type = isset($types[$key]) ? $types[$key] : null;
            switch ($type) {
                case Bdd::TYPE_CLOB:
                    ${$key} = oci_new_descriptor($this->connexion, OCI_D_LOB);
                    ${$key}->writeTemporary($params[$key], OCI_TEMP_CLOB);
                    oci_bind_by_name($statement, ':' . $key, ${$key}, -1, OCI_B_CLOB);
                break;
                case Bdd::TYPE_BLOB:
                    ${$key} = oci_new_descriptor($this->connexion, OCI_D_LOB);
                    ${$key}->writeTemporary($params[$key], OCI_TEMP_BLOB);
                    oci_bind_by_name($statement, ':' . $key, ${$key}, -1, OCI_B_BLOB);
                break;
                default:
                    ${$key} = $val;
                    oci_bind_by_name($statement, ':' . $key, ${$key});
                break;
            }
        }
        if (false === @oci_execute($statement, $this->commitMode)) {
            $error = oci_error($statement);
            oci_free_statement($statement);
            throw $this->sendException($error);
        }

        return $statement;
    }



    protected function sendException(array $error)
    {
        $message = $error['message'];
        $offset  = $error['offset'];
        $sqlText = $error['sqltext'];
        $code    = $error['code'];

        $msg = "$message (offset $offset\n$sqlText\n";
        switch ($code) {
            case 24344: // erreur de compilation
                return new BddCompileException($msg, $code);
            case 955:
            case 1408:
                return new BddIndexExistsException($msg, $code);
            default: // par défaut
                return new BddException($msg, $code);
        }
    }



    /**
     * @return self
     */
    public function beginTransaction(): DriverInterface
    {
        $this->commitMode = OCI_NO_AUTO_COMMIT;

        return $this;
    }



    /**
     * @return $this
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function commitTransaction(): DriverInterface
    {
        $this->commitMode = OCI_COMMIT_ON_SUCCESS;
        if (!oci_commit($this->connexion)) {
            $error = oci_error($this->connexion);
            throw $this->sendException($error);
        }

        return $this;
    }



    /**
     * @return $this
     */
    public function rollbackTransaction(): DriverInterface
    {
        oci_rollback($this->connexion);
        $this->commitMode = OCI_COMMIT_ON_SUCCESS;

        return $this;
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
    public function exec(string $sql, array $params = [], array $types = []): bool
    {
        $statement = $this->execStatement($sql, $params, $types);
        oci_free_statement($statement);

        return true;
    }



    /**
     * @param string $sql
     * @param array  $params
     * @param int    $fetchMode
     *
     * @return null|array|SelectParser
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function select(string $sql, array $params = [], array $options = [])
    {
        $defaultOptions = [
            'fetch' => Bdd::FETCH_ALL,
            'types' => [],
        ];
        $options        = array_merge($defaultOptions, $options);

        $statement = $this->execStatement($sql, $params);

        switch ($options['fetch']) {
            case Bdd::FETCH_ONE:
                return $this->fetch($statement, $options);
            case Bdd::FETCH_EACH:
                return new SelectParser($this, $options, $statement);
            case Bdd::FETCH_ALL:
                if (false === oci_fetch_all($statement, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW)) {
                    $error = oci_error($statement);
                    oci_free_statement($statement);
                    throw $this->sendException($error);
                }
                oci_free_statement($statement);
                if ($res) {
                    foreach ($res as $l => $r) {
                        foreach ($r as $c => $v) {
                            $type        = isset($options['types'][$c]) ? $options['types'][$c] : null;
                            $res[$l][$c] = $this->bddToPhpConvertVar($v, $type);
                        }
                    }
                }

                return $res;
        }
    }



    public function fetch($statement, array $options = [])
    {
        $defaultOptions = [
            'types' => [],
        ];
        $options        = array_merge($defaultOptions, $options);
        $result         = oci_fetch_array($statement, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS);
        if (false == $result) {
            oci_free_statement($statement);
        } else {
            foreach ($result as $c => $v) {
                $type       = isset($options['types'][$c]) ? $options['types'][$c] : null;
                $result[$c] = $this->bddToPhpConvertVar($v, $type);
            }
        }

        return $result;
    }



    /**
     * @param string $name
     *
     * @return string
     * @throws \Exception
     */
    public function getDdlClass(string $name): string
    {
        $mapping = [
            Ddl::SEQUENCE           => SequenceManager::class,
            Ddl::TABLE              => TableManager::class,
            Ddl::PRIMARY_CONSTRAINT => PrimaryConstraintManager::class,
            Ddl::PACKAGE            => PackageManager::class,
            Ddl::VIEW               => ViewManager::class,
            Ddl::MATERIALIZED_VIEW  => MaterializedViewManager::class,
            Ddl::REF_CONSTRAINT     => RefConstraintManager::class,
            Ddl::UNIQUE_CONSTRAINT  => UniqueConstraintManager::class,
            Ddl::TRIGGER            => TriggerManager::class,
            Ddl::INDEX              => IndexManager::class,
        ];
        if (!array_key_exists($name, $mapping)) {
            throw new \Exception('La Classe Ddl ' . $name . ' n\'existe pas.');
        }

        return $mapping[$name];
    }



    protected function bddToPhpConvertVar($variable, ?string $type = null)
    {
        if ($variable === null) return null;

        if (null === $type) {
            if (is_object($variable) && get_class($variable) == 'OCI-Lob') {
                return $variable->load();
            } else {
                return $variable;
            }
        }

        switch ($type) {
            case Bdd::TYPE_INT:
                return (int)$variable;
            case Bdd::TYPE_BOOL:
                return (bool)$variable;
            case Bdd::TYPE_FLOAT:
                return (float)$variable;
            case Bdd::TYPE_STRING:
                return (string)$variable;
            case Bdd::TYPE_DATE:
                $date = \DateTime::createFromFormat('Y-m-d H:i:s', $variable);
                if ($date instanceof \DateTime) {
                    return $date;
                } else {
                    return $variable;
                }

            case Bdd::TYPE_BLOB:
            case Bdd::TYPE_CLOB:
                if (is_object($variable) && get_class($variable) == 'OCI-Lob') {
                    return $variable->load();
                } else {
                    return $variable;
                }
            default:
                throw new \Exception("Type de donnée " . $type . " non géré.");
        }
    }
}