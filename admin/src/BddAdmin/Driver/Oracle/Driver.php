<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Driver\DriverInterface;
use BddAdmin\Exception\BddCompileException;
use BddAdmin\Exception\BddException;
use BddAdmin\Exception\BddIndexExistsException;

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

        $this->config['characterset'] = 'AL32UTF8';

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

        return $this;
    }



    public function disconnect(): DriverInterface
    {
        if ($this->connexion) {
            oci_close($this->connexion);
        }

        return $this;
    }



    private function execStatement($sql, array $params = [])
    {
        foreach ($params as $name => $val) {
            if ($val instanceof \DateTime) {
                $params[$name] = $val->format('Y-m-d H:i:s');
                $sql           = str_replace(":$name", "to_date(:$name, 'YYYY-MM-DD HH24:MI:SS')", $sql);
            } elseif (is_bool($val)) {
                $params[$name] = $val ? 1 : 0;
            } else {
                $params[$name] = $val;
            }
        }

        $statement = oci_parse($this->connexion, $sql);

        foreach ($params as $key => $val) {
            ${$key} = $val;
            oci_bind_by_name($statement, ':' . $key, ${$key});
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
    public function exec(string $sql, array $params = []): bool
    {
        $statement = $this->execStatement($sql, $params);
        oci_free_statement($statement);

        return true;
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
    public function select(string $sql, array $params = [], $fetchMode = Bdd::FETCH_ALL)
    {
        $statement = $this->execStatement($sql, $params);

        if ($fetchMode == Bdd::FETCH_EACH) {
            return $statement;
        }

        if (false === oci_fetch_all($statement, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW)) {
            $error = oci_error($statement);
            oci_free_statement($statement);
            throw $this->sendException($error);
        }
        oci_free_statement($statement);

        if ($fetchMode == Bdd::FETCH_ONE && isset($res[0])) {
            return $res[0];
        }

        return $res;
    }



    public function fetch($statement)
    {
        $result = oci_fetch_array($statement, OCI_ASSOC);
        if (false == $result) {
            oci_free_statement($statement);
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
            Bdd::DDL_SEQUENCE           => DdlSequence::class,
            Bdd::DDL_TABLE              => DdlTable::class,
            Bdd::DDL_PRIMARY_CONSTRAINT => DdlPrimaryConstraint::class,
            Bdd::DDL_PACKAGE            => DdlPackage::class,
            Bdd::DDL_VIEW               => DdlView::class,
            Bdd::DDL_MATERIALIZED_VIEW  => DdlMaterializedView::class,
            Bdd::DDL_REF_CONSTRAINT     => DdlRefConstraint::class,
            Bdd::DDL_UNIQUE_CONSTRAINT  => DdlUniqueConstraint::class,
            Bdd::DDL_TRIGGER            => DdlTrigger::class,
            Bdd::DDL_INDEX              => DdlIndex::class,
        ];
        if (!array_key_exists($name, $mapping)) {
            throw new \Exception('La Classe Ddl ' . $name . ' n\'existe pas.');
        }

        return $mapping[$name];
    }
}