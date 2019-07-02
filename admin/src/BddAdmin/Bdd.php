<?php

namespace BddAdmin;

use BddAdmin\Exception\BddCompileException;
use BddAdmin\Exception\BddException;
use BddAdmin\Exception\BddIndexExistsException;
use mysql_xdevapi\Exception;

class Bdd
{
    const FETCH_ALL  = 32;
    const FETCH_EACH = 16;
    const FETCH_ONE  = 8;

    /**
     * @var string
     */
    private $host;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var resource
     */
    private $connexion;

    /**
     * @var string
     */
    private $currentSchema;

    /**
     * @var int
     */
    private $commitMode = OCI_COMMIT_ON_SUCCESS;



    /**
     * Bdd constructor.
     *
     * @param string $host
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->setConfig($config);
            $this->connect();
        }
    }



    /**
     * @return self
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function connect(): self
    {
        $cs              = $this->getHost() . ':' . $this->getPort() . '/' . $this->getDbname();
        $characterSet    = 'AL32UTF8';
        $this->connexion = oci_pconnect($this->getUsername(), $this->password, $cs, $characterSet);
        if (!$this->connexion) {
            $error = oci_error();
            throw $this->sendException($error);
        }

        return $this;
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



    private function execStatement($sql, array $params = [])
    {
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



    /**
     * @return string
     * @throws BddCompileException
     * @throws BddException
     * @throws BddIndexExistsException
     */
    public function getCurrentSchema(): string
    {
        if (!$this->currentSchema) {
            $sql                 = "SELECT user scname FROM dual";
            $this->currentSchema = $this->select($sql)[0]['SCNAME'];
        }

        return $this->currentSchema;
    }



    /**
     * @return self
     */
    public function beginTransaction(): self
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
    public function commitTransaction(): self
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
    public function rollbackTransaction(): self
    {
        oci_rollback($this->connexion);
        $this->commitMode = OCI_COMMIT_ON_SUCCESS;

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
        list($s, $p) = $this->prepareQuery($sql, $params);

        $statement = $this->execStatement($s, $p);
        oci_free_statement($statement);

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
        list($s, $p) = $this->prepareQuery($sql, $params);
//        $begin = microtime(true);
        $statement = $this->execStatement($s, $p);

        if ($fetchMode == self::FETCH_EACH) {
            return $statement;
        }

        if (false === oci_fetch_all($statement, $res, 0, -1, OCI_FETCHSTATEMENT_BY_ROW)) {
            $error = oci_error($statement);
            oci_free_statement($statement);
            throw $this->sendException($error);
        }
        oci_free_statement($statement);

        if ($fetchMode == self::FETCH_ONE && isset($res[0])) {
            return $res[0];
        }
//        var_dump($sql);
//        var_dump( round(microtime(true) - $begin, 3).' secondes' );
        return $res;
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



    private function prepareQuery($sql, array $params = [])
    {
        $s = $sql;
        $p = [];
        foreach ($params as $name => $val) {
            if ($val instanceof \DateTime) {
                $p[$name] = $val->format('Y-m-d H:i:s');
                $s        = str_replace(":$name", "to_date(:$name, 'YYYY-MM-DD HH24:MI:SS')", $s);
            } else {
                $p[$name] = $val;
            }
        }

        return [$s, $p];
    }



    public function fetch($statement)
    {
        $result = oci_fetch_array($statement, OCI_ASSOC);
        if (false == $result) {
            oci_free_statement($statement);
        }

        return $result;
    }



    public function __destruct()
    {
        oci_close($this->connexion);
    }



    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }



    /**
     * @param string $host
     *
     * @return Bdd
     */
    public function setHost(string $host): self
    {
        $this->host = $host;

        return $this;
    }



    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }



    /**
     * @param int $port
     *
     * @return Bdd
     */
    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }



    /**
     * @return string
     */
    public function getDbname(): string
    {
        return $this->dbname;
    }



    /**
     * @param string $dbname
     *
     * @return Bdd
     */
    public function setDbname(string $dbname): self
    {
        $this->dbname = $dbname;

        return $this;
    }



    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }



    /**
     * @param string $username
     *
     * @return Bdd
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }



    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }



    /**
     * @param string $password
     *
     * @return Bdd
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }



    public function getConfig(): array
    {
        return [
            'host'     => $this->getHost(),
            'port'     => $this->getPort(),
            'dbname'   => $this->getDbname(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];
    }



    public function setConfig(array $config): self
    {
        if (array_key_exists('host', $config)) {
            $this->setHost($config['host']);
        }
        if (array_key_exists('port', $config)) {
            $this->setPort($config['port']);
        }
        if (array_key_exists('dbname', $config)) {
            $this->setDbname($config['dbname']);
        }
        if (array_key_exists('username', $config)) {
            $this->setUsername($config['username']);
        }
        if (array_key_exists('password', $config)) {
            $this->setPassword($config['password']);
        }

        return $this;
    }
}