<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Ddl\DdlAbstract;

class DdlView extends DdlAbstract
{
    const ALIAS = 'view';
    const LABEL = 'Vues';



    public function get($includes = null, $excludes = null): array
    {
        [$f, $p] = $this->makeFilterParams('view_name', $includes, $excludes);
        $data = [];

        $q = "SELECT
            view_name \"name\",
            text \"definition\"
          FROM
            USER_VIEWS
          WHERE
            1=1 $f
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



    public function create(array $data, $test = null)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery($data['definition'], 'Ajout/modification de la vue ' . $data['name']);
    }



    public function drop(string $name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

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
}