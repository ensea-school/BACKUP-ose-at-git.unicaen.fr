<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\AbstractManager;
use BddAdmin\Manager\IndexManagerInterface;
use BddAdmin\Ddl\DdlFilter;

class IndexManager extends AbstractManager implements IndexManagerInterface
{
    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT OBJECT_NAME 
          FROM ALL_OBJECTS 
          WHERE 
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND OBJECT_TYPE = 'INDEX' AND GENERATED = 'N'
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
        [$f, $p] = $filter->toSql('index_name');

        $data = [];

        $sql = "SELECT
          index_name \"name\",
          uniqueness \"unique\",
          table_name \"table\"
        FROM
          all_indexes
        WHERE
          owner = sys_context( 'userenv', 'current_schema' )
          AND index_type <> 'LOB'
          AND index_name NOT LIKE 'BIN$%'
          $f
        ORDER BY
          index_name";

        $rs = $this->bdd->select($sql, $p);
        foreach ($rs as $r) {
            $data[$r['name']] = [
                'name'    => $r['name'],
                'unique'  => ($r['unique'] === 'UNIQUE'),
                'table'   => $r['table'],
                'columns' => [],
            ];
        }

        $sql = "
        SELECT index_name \"name\", column_name \"column\" 
        FROM user_ind_columns 
        WHERE index_name NOT LIKE 'BIN$%' $f 
        ORDER BY column_position";
        $rs  = $this->bdd->select($sql, $p);
        foreach ($rs as $r) {
            $data[$r['name']]['columns'][] = $r['column'];
        }

        return $data;
    }



    public function exists(string $name): bool
    {
        $sql = "SELECT count(*) NBR FROM all_indexes WHERE index_name = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
    }



    protected function makeCreate(array $data)
    {
        $sql = "CREATE ";
        if ($data['unique']) $sql .= "UNIQUE ";
        $sql .= "INDEX " . $data['name'] . " ON " . $data['table'] . ' (' . implode(', ', $data['columns']) . ')';

        return $sql;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $sql = $this->makeCreate($data);
        $this->addQuery($sql, 'Ajout de l\'index ' . $data['name']);
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("DROP INDEX $name", 'Suppression de l\'index ' . $name);
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

        $newName = $new['name'];

        $sql = "ALTER INDEX \"$oldName\" RENAME TO \"$newName\"";
        $this->addQuery($sql, 'Renommage de l\'index ' . $oldName . ' en ' . $newName);
    }

}