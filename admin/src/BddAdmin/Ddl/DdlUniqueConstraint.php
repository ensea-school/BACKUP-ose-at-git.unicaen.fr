<?php

namespace BddAdmin\Ddl;


class DdlUniqueConstraint extends DdlAbstract
{
    const ALIAS = 'unique-constraint';



    public function get($includes = null, $excludes = null): array
    {
        [$f, $p] = $this->makeFilterParams('c.constraint_name', $includes, $excludes);
        $data = [];

        $sql = "SELECT
          c.constraint_name \"name\",
          c.table_name \"table\",
          c.index_name \"index\",
          cc.column_name \"column\"
        FROM
          user_constraints c
          JOIN user_cons_columns cc ON cc.constraint_name = c.constraint_name
        WHERE
          c.constraint_type = 'U'
          AND c.constraint_name NOT LIKE 'BIN$%' $f
        ORDER BY
          c.constraint_name,
          cc.position";

        $rs = $this->bdd->select($sql, $p);
        foreach ($rs as $r) {
            if (!isset($data[$r['name']])) {
                $data[$r['name']] = [
                    'name'    => $r['name'],
                    'table'   => $r['table'],
                    'index'   => $r['index'],
                    'columns' => [],
                ];
            }
            $data[$r['name']]['columns'][] = $r['column'];
        }

        return $data;
    }



    private function indexExists($indexName)
    {
        $sql = "SELECT count(*) res FROM all_indexes WHERE index_name = :indexName AND rownum = 1";
        $res = $this->bdd->select($sql, compact('indexName'));

        return $res[0]['RES'] == '1';
    }



    public function makeCreate(array $data)
    {
        $cols = implode(', ', $data['columns']);
        $sql  = "ALTER TABLE " . $data['table'] . " ADD CONSTRAINT " . $data['name'] . " UNIQUE ($cols) ";
        if ($data['index']) {
            if ($this->indexExists($data['index'])) {
                $sql .= 'USING INDEX ' . $data['index'] . ' ';
            } else {
                $sql .= "USING INDEX (\n\tCREATE UNIQUE INDEX " . $data['index'] . ' ON ' . $data['table'];
                $sql .= '(' . implode(' ASC, ', $data['columns']) . ' ASC)';
                $sql .= "\n) ";
            }
        }
        $sql .= "ENABLE";

        return $sql;
    }



    public function isDiff(array $d1, array $d2)
    {
        unset($d1['index']);
        unset($d2['index']);

        return $d1 != $d2;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $sql = $this->makeCreate($data);
        $this->addQuery($sql, 'Ajout de la contrainte d\'unicité ' . $data['name']);
    }



    public function drop(string $name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $sql       = "SELECT table_name FROM all_constraints WHERE constraint_name = :name";
        $d         = $this->bdd->select($sql, compact('name'));
        $tableName = $d[0]['TABLE_NAME'];

        $this->addQuery("ALTER TABLE $tableName DROP CONSTRAINT $name", 'Suppression de la contrainte d\'unicité ' . $name);
    }



    public function alter(array $old, array $new)
    {
        if ($this->isDiff($old, $new)) {
            if ($this->sendEvent()->getReturn('no-exec')) return;

            $this->drop($old['name']);
            $this->create($new);
        }
    }



    public function rename(string $oldName, array $new)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $tableName = $new['table'];
        $newName   = $new['name'];

        $sql = "ALTER TABLE \"$tableName\" RENAME CONSTRAINT \"$oldName\" TO \"$newName\"";
        $this->addQuery($sql, 'Renommage de la contrainte d\'unicité ' . $oldName . ' en ' . $newName);
    }
}