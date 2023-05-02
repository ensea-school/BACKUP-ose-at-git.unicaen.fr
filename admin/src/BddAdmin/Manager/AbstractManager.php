<?php

namespace BddAdmin\Manager;

use BddAdmin\Bdd;
use BddAdmin\Event\EventManagerAwareTrait;


abstract class AbstractManager implements ManagerInterface
{
    use EventManagerAwareTrait;

    /**
     * @var Bdd
     */
    protected $bdd;



    /**
     * DdlAbstract constructor.
     */
    public function __construct(Bdd $bdd)
    {
        $this->bdd = $bdd;
    }



    protected function purger(string $sql, $enleverPointVirgule = false): string
    {
        $s = explode("\n", $sql);
        foreach ($s as $i => $l) {
            $s[$i] = rtrim($l);
        }

        $res = trim(implode("\n", $s));
        if ('/' == substr($res, -1) && '*' != substr($res, -2, 1)) {
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
        $this->bdd->queryLogExec($sql, $description);
    }



    /**
     * @param string|string[]|null $includes
     * @param string|string[]|null $excludes
     *
     * @return array
     */
    abstract public function get($includes = null, $excludes = null): array;



    /**
     * @param string $name
     *
     * @return bool
     */
    abstract public function exists(string $name): bool;



    /**
     * @param array $data
     */
    abstract public function create(array $data);



    /**
     * @param string|array $name
     */
    abstract public function drop($name);



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