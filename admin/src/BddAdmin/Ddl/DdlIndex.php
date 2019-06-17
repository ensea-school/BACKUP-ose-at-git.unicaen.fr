<?php

namespace BddAdmin\Ddl;



class DdlIndex extends DdlAbstract
{
    const ALIAS = 'index';

    public function get($includes = null, $excludes = null): array
    {
        list($f,$p) = $this->makeFilterParams('index_name', $includes, $excludes);

        $data = [];

        $sql = "SELECT
          index_name \"name\",
          uniqueness \"unique\",
          table_name \"table\"
        FROM
          user_indexes
        WHERE
          index_name NOT LIKE 'BIN$%'
          AND index_name NOT LIKE 'SYS_IL%$$'
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
        $rs = $this->bdd->select($sql, $p);
        foreach( $rs as $r ){
            $data[$r['name']]['columns'][] = $r['column'];
        }

        return $data;
    }



    protected function makeCreate(array $data)
    {
        $sql = "CREATE ";
        if ($data['unique']) $sql .= "UNIQUE ";
        $sql .= "INDEX ".$data['name']." ON ".$data['table'].' ('.implode( ', ',$data['columns']).')';

        return $sql;
    }



    public function create(array $data)
    {
        $sql = $this->makeCreate($data);
        $this->addQuery($sql);
    }



    public function drop(string $name)
    {
        $this->addQuery("DROP INDEX $name");
    }



    public function alter(array $old, array $new)
    {
        if ($old != $new){
            $this->drop($old['name']);
            $this->create($new);
        }
    }



    public function rename(string $oldName, array $new)
    {
        $newName = $new['name'];

        $sql = "ALTER INDEX \"$oldName\" RENAME TO \"$newName\"";
        $this->addQuery($sql);
    }

}