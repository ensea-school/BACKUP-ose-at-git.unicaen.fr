<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Ddl\DdlAbstract;
use BddAdmin\Ddl\DdlMaterializedViewInteface;

class DdlMaterializedView extends DdlAbstract implements DdlMaterializedViewInteface
{
    const ALIAS = 'materialized-view';



    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT OBJECT_NAME 
          FROM ALL_OBJECTS 
          WHERE 
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND OBJECT_TYPE = 'MATERIALIZED VIEW' AND GENERATED = 'N'
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
        [$f, $p] = $this->makeFilterParams('mview_name', $includes, $excludes);
        $data = [];

        $q = "SELECT
            mview_name \"name\",
            query \"definition\"
          FROM
            ALL_MVIEWS
          WHERE
            OWNER = sys_context( 'userenv', 'current_schema' )
            $f
          ORDER BY
            mview_name
        ";
        $p = $this->bdd->select($q, $p);
        foreach ($p as $r) {
            $data[$r['name']] = [
                'name'       => $r['name'],
                'definition' => $this->purger($r['definition'], true),
            ];
        }

        return $data;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $sql = 'CREATE MATERIALIZED VIEW ' . $data['name'] . " AS\n";
        $sql .= $data['definition'];
        $this->addQuery($sql, 'Ajout de la vue matérialisée ' . $data['name']);
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("DROP MATERIALIZED VIEW " . $name, 'Suppression de la vue matérialisée ' . $name);
    }



    public function alter(array $old, array $new)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if ($old != $new) {
            $this->drop($old['name']);
            $this->create($new);
        }
    }



    public function rename(string $oldName, array $new)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->drop($oldName);
        $this->create($new);
    }
}