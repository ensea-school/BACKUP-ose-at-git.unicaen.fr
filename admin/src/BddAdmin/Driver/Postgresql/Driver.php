<?php

namespace BddAdmin\Driver\Postgresql;

use BddAdmin\Bdd;
use BddAdmin\Driver\DriverInterface;
use BddAdmin\Exception\BddCompileException;
use BddAdmin\Exception\BddException;
use BddAdmin\Exception\BddIndexExistsException;
use BddAdmin\SelectParser;
use PDO;

/**
 * Class Driver
 *
 * Attention : Driver non finalisé
 * Manquent
 * - toute la partie DDL
 * - La gestion des types spécifiques (bool, Date, LOBs, à fiabiliser
 *
 * @package BddAdmin\Driver\Mysql
 */
class Driver implements DriverInterface
{
    /**
     * @var Bdd
     */
    private $bdd;

    /**
     * @var PDO
     */
    private $connexion;



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
        $config   = $this->bdd->getConfig();
        $host     = isset($config['host']) ? $config['host'] : null;
        $port     = isset($config['port']) ? $config['port'] : null;
        $dbname   = isset($config['dbname']) ? $config['dbname'] : null;
        $username = isset($config['username']) ? $config['username'] : null;
        $password = isset($config['password']) ? $config['password'] : null;
        $charset  = isset($config['charset']) ? $config['charset'] : 'UTF8';

        if (!$host) throw new BddException('host non fourni');
        if (!$port) throw new BddException('port non fourni');
        if (!$dbname) throw new BddException('dbname non fourni');
        if (!$username) throw new BddException('username non fourni');
        if (!$password) throw new BddException('password non fourni');

        try {
            $dsn             = "pgsql:dbname=$dbname;host=$host;port=$port";
            $this->connexion = new PDO($dsn, $username, $password);
        } catch (\PDOException $e) {
            throw new BddException($e->getMessage(), $e->getCode(), $e);
        }

        return $this;
    }



    public function disconnect(): DriverInterface
    {
        if ($this->connexion) {
            unset($this->connexion);
        }

        return $this;
    }



    private function execStatement($sql, array $params = [], array $types = [])
    {
        /*foreach ($params as $name => $val) {
            if (is_bool($val)) {
                $params[$name] = $val ? 'TRUE' : 'FALSE';
            } elseif ($val instanceof \DateTime) {
                $params[$name] = $val->format('Y-m-d H:i:s');
            } else {
                $params[$name] = $val;
            }
        }*/

        $statement = $this->connexion->prepare($sql);

        /*
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
                    $statement->bindParam(':' . $key, $val);
                break;
            }
        }*/

        $statement->execute($params);
        if (0 !== ($errCode = (int)$statement->errorCode())) {
            $errInfo = $statement->errorInfo();
            unset($statement);
            throw new BddException($errInfo[2], $errCode);
        }

        return $statement;
    }



    /**
     * @return self
     */
    public function beginTransaction(): DriverInterface
    {
        $this->connexion->beginTransaction();

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
        $this->connexion->commit();

        return $this;
    }



    /**
     * @return $this
     */
    public function rollbackTransaction(): DriverInterface
    {
        $this->connexion->rollBack();

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

        return $statement->execute();
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
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            /*if ($res) {
                foreach ($res as $l => $r) {
                    foreach ($r as $c => $v) {
                        $type        = isset($options['types'][$c]) ? $options['types'][$c] : null;
                        $res[$l][$c] = $this->bddToPhpConvertVar($v, $type);
                    }
                }
            }

            return $res;*/
        }
    }



    public function fetch($statement, array $options = [])
    {
        $defaultOptions = [
            'types' => [],
        ];
        $options        = array_merge($defaultOptions, $options);
        $result         = $statement->fetch(PDO::FETCH_ASSOC);
        if (false == $result) {
            unset($statement);
        }/* else {
            foreach ($result as $c => $v) {
                $type       = isset($options['types'][$c]) ? $options['types'][$c] : null;
                $result[$c] = $this->bddToPhpConvertVar($v, $type);
            }
        }*/

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
            /*    Ddl::SEQUENCE           => SequenceManager::class,
                Ddl::TABLE              => TableManager::class,
                Ddl::PRIMARY_CONSTRAINT => PrimaryConstraintManager::class,
                Ddl::PACKAGE            => PackageManager::class,
                Ddl::VIEW               => ViewManager::class,
                Ddl::MATERIALIZED_VIEW  => MaterializedViewManager::class,
                Ddl::REF_CONSTRAINT     => RefConstraintManager::class,
                Ddl::UNIQUE_CONSTRAINT  => UniqueConstraintManager::class,
                Ddl::TRIGGER            => TriggerManager::class,
                Ddl::INDEX              => IndexManager::class,*/
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
