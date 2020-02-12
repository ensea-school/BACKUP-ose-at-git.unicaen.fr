<?php

namespace BddAdmin\Ddl;

use BddAdmin\Bdd;
use BddAdmin\Event\EventManagerAwareTrait;
use BddAdmin\Exception\BddCompileException;
use BddAdmin\SchemaLoggerInterface;
use Exception;


abstract class DdlAbstract implements DdlInterface
{
    const ALIAS = 'no-alias';

    use EventManagerAwareTrait;

    /**
     * @var Bdd
     */
    protected $bdd;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    private $queries = [];

    /**
     * @var string[]
     */
    private $options = [];



    /**
     * DdlAbstract constructor.
     */
    public function __construct(Bdd $bdd)
    {
        $this->bdd = $bdd;
    }



    protected function makeFilterParams(string $colName, $includes = null, $excludes = null)
    {
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



    protected function purger(string $sql, $enleverPointVirgule = false): string
    {
        $s = explode("\n", $sql);
        foreach ($s as $i => $l) {
            $s[$i] = rtrim($l);
        }

        $res = trim(implode("\n", $s));
        if ('/' == substr($res, -1)) {
            $res = trim(substr($res, 0, -1));
        }

        $res = trim($res);

        if ($enleverPointVirgule && substr($res, -1) == ';') {
            $res = trim(substr($res, 0, -1));
        }

        return $res;
    }



    protected function addQuery(string $sql, string $description = null) // (?string $sql)
    {
        if ($sql) {
            $this->queries[$sql] = $description;
        }
    }



    /**
     * @param SchemaLoggerInterface|null $logger
     *
     * @return string[]
     */
    public function getQueries($logger = null): array
    {
        if ($logger) {
            foreach ($this->queries as $sql => $description) {
                $logger->log($description);
            }
        }

        return $this->queries;
    }



    public function clearQueries()
    {
        $this->queries = [];
    }



    /**
     * @param SchemaLoggerInterface|null $logger
     *
     * @return array
     */
    public function execQueries(?SchemaLoggerInterface $logger = null): array
    {
        $errors = [];
        foreach ($this->queries as $sql => $description) {
            try {
                if ($logger) {
                    $logger->log($description);
                }
                $this->bdd->exec($sql);
            } catch (Exception $e) {
                if (!$e instanceof BddCompileException) {
                    if ($logger) {
                        $logger->log($e->getMessage());
                    }
                    $errors[] = $e;
                }
            }
        }

        return $errors;
    }



    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }



    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }



    /**
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options = []): DdlInterface
    {
        $this->options = $options;

        return $this;
    }



    /**
     * @param string $option
     * @param null   $params
     *
     * @return self
     */
    public function addOption(string $option, $params = null): DdlInterface
    {
        $this->options[$option] = $params;

        return $this;
    }



    /**
     * @param array $options
     *
     * @return self
     */
    public function addOptions(array $options): DdlInterface
    {
        foreach ($options as $option => $params) {
            $this->addOption($option, $params);
        }

        return $this;
    }



    /**
     * @param string $option
     *
     * @return self
     */
    public function removeOption(string $option): DdlInterface
    {
        unset($this->options[$option]);

        return $this;
    }



    /**
     * @return self
     */
    public function clearOptions(): self
    {
        $this->options = [];

        return $this;
    }



    /**
     * @param string $option
     *
     * @return bool
     */
    public function hasOption(string $option): bool
    {
        return array_key_exists($option, $this->options);
    }



    /**
     * @param string|string[]|null $includes
     * @param string|string[]|null $excludes
     *
     * @return array
     */
    abstract public function get($includes = null, $excludes = null): array;



    /**
     * @param array $data
     */
    abstract public function create(array $data);



    /**
     * @param string $name
     */
    abstract public function drop(string $name);



    /**
     * @param array $old
     * @param array $new
     *
     * @return mixed
     */
    abstract public function alter(array $old, array $new);



    /**
     * @param string $oldName
     * @param array  $new
     *
     * @return mixed
     */
    abstract public function rename(string $oldName, array $new);



    /**
     * @param array $data
     *
     * @return array
     */
    public function prepareRenameCompare(array $data): array
    {
        unset($data['name']);

        return $data;
    }
}