<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\AbstractManager;

abstract class AbstractManagerDdlConstraint extends AbstractManager
{
    protected $description = '';



    protected function indexExists($indexName)
    {
        return $this->bdd->index()->exists($indexName);
    }



    /**
     * @param string|array $name
     *
     * @return array
     * @throws \BddAdmin\Exception\BddCompileException
     * @throws \BddAdmin\Exception\BddException
     * @throws \BddAdmin\Exception\BddIndexExistsException
     */
    protected function getNameAndTable($name)
    {
        $tableName = null;
        if (is_array($name)) {
            $tableName = $name['table'];
            $name      = $name['name'];
        }

        if (!$tableName) {
            $sql       = "SELECT TABLE_NAME FROM ALL_CONSTRAINTS WHERE CONSTRAINT_NAME = :name";
            $d         = $this->bdd->select($sql, compact('name'), ['fetch' => Bdd::FETCH_ONE]);
            $tableName = $d['TABLE_NAME'];
        }

        return [$tableName, $name];
    }



    public function isDiff(array $d1, array $d2)
    {
        unset($d1['index']);
        unset($d2['index']);

        return $d1 != $d2;
    }



    abstract public function makeCreate(array $data);



    public function create(array $data)
    {
        $sql = $this->makeCreate($data);
        $this->addQuery($sql, 'Ajout de la ' . $this->description . ' ' . $data['name']);
    }



    public function drop($name)
    {
        [$tableName, $name] = $this->getNameAndTable($name);

        $this->addQuery("ALTER TABLE $tableName DROP CONSTRAINT $name", 'Suppression de la ' . $this->description . ' ' . $name);
    }



    public function alter(array $old, array $new)
    {
        $this->drop($old);
        $this->create($new);
    }



    public function rename(string $oldName, array $new)
    {
        $tableName = $new['table'];
        $newName   = $new['name'];

        $sql = "ALTER TABLE \"$tableName\" RENAME CONSTRAINT \"$oldName\" TO \"$newName\"";
        $this->addQuery($sql, 'Renommage de la ' . $this->description . ' ' . $oldName . ' en ' . $newName);
    }



    /***
     * @param string|array $name
     */
    public function enable($name)
    {
        [$tableName, $name] = $this->getNameAndTable($name);

        $this->addQuery("alter table $tableName modify constraint $name enable", 'Activation de la ' . $this->description . ' ' . $name);
    }



    /***
     * @param string|array $name
     */
    public function disable($name)
    {
        [$tableName, $name] = $this->getNameAndTable($name);

        $this->addQuery("alter table $tableName modify constraint $name disable", 'Désactivation de la ' . $this->description . ' ' . $name);
    }
}