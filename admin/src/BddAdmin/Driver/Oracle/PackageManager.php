<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\AbstractManager;
use BddAdmin\Manager\PackageManagerInteface;
use BddAdmin\Ddl\DdlFilter;

class PackageManager extends AbstractManager implements PackageManagerInteface
{
    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT OBJECT_NAME 
          FROM ALL_OBJECTS 
          WHERE 
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND OBJECT_TYPE = 'PACKAGE' AND GENERATED = 'N'
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
        [$f, $p] = $filter->toSql('name');
        $data = [];

        $q = "SELECT 
            name \"name\",
            text \"ddl\",
            CASE WHEN type = 'PACKAGE' THEN 'definition' ELSE 'body' END \"type\"
          FROM
            all_source 
          WHERE
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND (type = 'PACKAGE' OR type = 'PACKAGE BODY') $f 
          ORDER BY name, line
        ";
        $p = $this->bdd->select($q, $p);
        foreach ($p as $r) {
            if (!isset($data[$r['name']])) {
                $data[$r['name']] = [
                    'name'       => $r['name'],
                    'definition' => 'CREATE OR REPLACE ',
                    'body'       => 'CREATE OR REPLACE ',
                ];
            }

            $data[$r['name']][$r['type']] .= $r['ddl'];
        }
        foreach ($data as $name => $d) {
            $definition                = $this->purger($d['definition'], false);
            $body                      = $this->purger($d['body'], false);
            $data[$name]['definition'] = $definition;
            $data[$name]['body']       = $body;
        }

        return $data;
    }



    public function exists(string $name): bool
    {
        $sql = "SELECT count(*) NBR FROM all_source WHERE "
            ."OWNER = sys_context( 'userenv', 'current_schema' )"
            ."AND (type = 'PACKAGE' OR type = 'PACKAGE BODY') "
            ."AND ROWNUM = 1 "
            ."AND name = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery($data['definition'], 'Ajout/modification de la définition du package ' . $data['name']);
        $this->addQuery($data['body'], 'Ajout/modification du corps du package ' . $data['name']);
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("DROP PACKAGE $name", 'Suppression du package ' . $name);
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

        $this->addQuery("ALTER PACKAGE $name COMPILE PACKAGE", 'Compilation de la déclaration du package ' . $name);
        $this->addQuery("ALTER PACKAGE $name COMPILE BODY", 'Compilation du corps de package ' . $name);
    }



    public function compilerTout()
    {
        $objects = $this->getList();
        foreach ($objects as $object) {
            $this->compiler($object);
        }
    }
}