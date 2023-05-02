<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\AbstractManager;
use BddAdmin\Manager\DdlCompilationInterface;
use BddAdmin\Manager\ViewManagerInterface;
use BddAdmin\Ddl\DdlFilter;

class ViewManager extends AbstractManager implements ViewManagerInterface
{
    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT OBJECT_NAME 
          FROM ALL_OBJECTS 
          WHERE 
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND OBJECT_TYPE = 'VIEW' AND GENERATED = 'N'
          ORDER BY OBJECT_NAME
        ";
        $r    = $this->bdd->select($sql);
        foreach ($r as $l) {
            $list[] = $l['OBJECT_NAME'];
        }

        return $list;
    }



    public function get($includes = null, $excludes = null): array
    {
        $filter = DdlFilter::normalize2($includes, $excludes);
        [$f, $p] = $filter->toSql('view_name');
        $data = [];

        $q = "SELECT
            view_name \"name\",
            text \"definition\"
          FROM
            ALL_VIEWS
          WHERE
            OWNER = sys_context( 'userenv', 'current_schema' )
            $f
          ORDER BY
            view_name
        ";
        $p = $this->bdd->select($q, $p);
        foreach ($p as $r) {
            $r['definition']  = 'CREATE OR REPLACE FORCE VIEW ' . $r['name'] . " AS\n" . $r['definition'];
            $data[$r['name']] = [
                'name'       => $r['name'],
                'definition' => $this->purger($r['definition'], true),
            ];
        }

        return $data;
    }



    public function exists(string $name): bool
    {
        $sql = "
            SELECT count(*) NBR FROM ALL_OBJECTS 
            WHERE 
              OWNER = sys_context( 'userenv', 'current_schema' )
              AND OBJECT_TYPE = 'VIEW' AND GENERATED = 'N' AND OBJECT_NAME = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
    }



    public function create(array $data, $test = null)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery($data['definition'], 'Ajout/modification de la vue ' . $data['name']);
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("DROP VIEW " . $name, 'Suppression de la vue ' . $name);
    }



    public function alter(array $old, array $new)
    {
        if ($old != $new) {
            if ($this->sendEvent()->getReturn('no-exec')) return;

            $this->create($new);
        }
    }



    public function rename(string $oldName, array $new)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->drop($oldName);
        $this->create($new);
    }



    /**
     * @param string|array $name
     *
     * @return mixed
     */
    public function compiler($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("ALTER VIEW $name COMPILE", 'Compilation de la vue ' . $name);
    }



    public function compilerTout()
    {
        $objects = $this->getList();
        foreach ($objects as $object) {
            $this->compiler($object);
        }
    }
}