<?php

namespace BddAdmin\Ddl;



class DdlView extends DdlAbstract
{
    const ALIAS = 'view';
    const LABEL = 'Vues';

    public function get($includes = null, $excludes = null): array
    {
        list($f, $p) = $this->makeFilterParams('view_name', $includes, $excludes);
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



    public function create(array $data)
    {
        $this->addQuery($data['definition']);
    }



    public function drop(string $name)
    {
        $this->addQuery("DROP VIEW " . $name);
    }



    public function alter(array $old, array $new)
    {
        if ($old != $new) {
            $this->create($new);
        }
    }



    public function rename(string $oldName, array $new)
    {
        $this->drop($oldName);
        $this->create($new);
    }
}