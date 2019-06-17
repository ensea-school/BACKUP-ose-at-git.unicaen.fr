<?php

namespace BddAdmin\Ddl;



class DdlMaterializedView extends DdlAbstract
{
    const ALIAS = 'materialized-view';

    public function get($includes = null, $excludes = null): array
    {
        list($f, $p) = $this->makeFilterParams('mview_name', $includes, $excludes);
        $data = [];

        $q = "SELECT
            mview_name \"name\",
            query \"definition\"
          FROM
            USER_MVIEWS
          WHERE
            1=1 $f
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
        $sql = 'CREATE MATERIALIZED VIEW '.$data['name']." AS\n";
        $sql .= $data['definition'];
        $this->addQuery($sql);
    }



    public function drop(string $name)
    {
        $this->addQuery("DROP MATERIALIZED VIEW " . $name);
    }



    public function alter(array $old, array $new)
    {
        if ($old != $new) {
            $this->drop($old['name']);
            $this->create($new);
        }
    }



    public function rename(string $oldName, array $new)
    {
        $this->drop($oldName);
        $this->create($new);
    }
}