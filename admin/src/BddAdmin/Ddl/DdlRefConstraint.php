<?php

namespace BddAdmin\Ddl;



class DdlRefConstraint extends DdlAbstract
{
    const ALIAS = 'ref-constraint';

    public function get($includes = null, $excludes = null): array
    {
        list($f,$p) = $this->makeFilterParams('c.constraint_name', $includes, $excludes);
        $data = [];

        $sql = "SELECT
          c.constraint_name \"name\",
          c.table_name \"table\",
          cc.column_name \"column\",
          rc.table_name \"rtable\",
          rcc.column_name \"rcolumn\",
          c.delete_rule \"delete_rule\",
          c.index_name \"index\"
        FROM
          user_constraints c
          JOIN all_constraints rc ON rc.constraint_name = c.r_constraint_name AND rc.constraint_type = 'P'
          JOIN user_cons_columns cc ON cc.constraint_name = c.constraint_name
          JOIN user_cons_columns rcc ON rcc.constraint_name = rc.constraint_name AND rcc.position = cc.position
        WHERE
          c.constraint_type = 'R' $f
        ORDER BY
          c.constraint_name,
          cc.position";

        $rs = $this->bdd->select($sql, $p);
        foreach ($rs as $r) {
            if (!isset($data[$r['name']])) {
                $data[$r['name']] = [
                    'name'        => $r['name'],
                    'table'       => $r['table'],
                    'rtable'      => $r['rtable'],
                    'delete_rule' => ($r['delete_rule'] != 'NO ACTION') ? $r['delete_rule'] : null,
                    'index'       => $r['index'],
                    'columns'     => [],
                ];
            }
            $data[$r['name']]['columns'][$r['column']] = $r['rcolumn'];
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
        $cols  = implode(', ', array_keys($data['columns']));
        $rCols = implode(', ', array_values($data['columns']));

        $sql = "ALTER TABLE " . $data['table'] . " ADD CONSTRAINT " . $data['name'] . " FOREIGN KEY ($cols) 
        REFERENCES " . $data['rtable'] . " ($rCols) ";
        if ($data['index']) {
            if ($this->indexExists($data['index'])) {
                $sql .= 'USING INDEX ' . $data['index'] . ' ';
            } else {
                $sql .= "USING INDEX (\n\tCREATE UNIQUE INDEX " . $data['index'] . ' ON ' . $data['table'];
                $sql .= '(' . implode(' ASC, ', $data['columns']) . ' ASC)';
                $sql .= "\n) ";
            }
        }
        if ($data['delete_rule']) $sql .= 'ON DELETE ' . $data['delete_rule'] . ' ';
        $sql .= "ENABLE";

        return $sql;
    }



    public function create(array $data)
    {
        $sql = $this->makeCreate($data);
        $this->addQuery($sql);
    }



    public function drop(string $name)
    {
        $sql       = "SELECT table_name FROM all_constraints WHERE constraint_name = :name";
        $d         = $this->bdd->select($sql, compact('name'));
        $tableName = $d[0]['TABLE_NAME'];

        $this->addQuery("ALTER TABLE $tableName DROP CONSTRAINT $name");
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
        $tableName = $new['table'];
        $newName = $new['name'];

        $sql = "ALTER TABLE \"$tableName\" RENAME CONSTRAINT \"$oldName\" TO \"$newName\"";
        $this->addQuery($sql);
    }
}