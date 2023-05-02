<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\AbstractManager;
use BddAdmin\Manager\SequenceManagerInterface;
use BddAdmin\Ddl\DdlFilter;

class SequenceManager extends AbstractManager implements SequenceManagerInterface
{
    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT OBJECT_NAME 
          FROM ALL_OBJECTS 
          WHERE 
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND OBJECT_TYPE = 'SEQUENCE' AND GENERATED = 'N'
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
        [$f, $p] = $filter->toSql('sequence_name');
        $data = [];

        $qr = $this->bdd->select('
          SELECT sequence_name "name" FROM all_sequences 
          WHERE SEQUENCE_OWNER = sys_context( \'userenv\', \'current_schema\' ) 
        ' . $f, $p);
        foreach ($qr as $r) {
            $data[$r['name']] = $r;
        }

        return $data;
    }



    public function exists(string $name): bool
    {
        $sql = "SELECT count(*) NBR FROM ALL_OBJECTS WHERE "
            ."OWNER = sys_context( 'userenv', 'current_schema' ) "
            ."AND OBJECT_TYPE = 'SEQUENCE' "
            ."AND GENERATED = 'N' "
            ."AND OBJECT_NAME = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;


        $sql = "CREATE SEQUENCE " . $data['name'] . " INCREMENT BY 1 MINVALUE 1 NOCACHE";
        $this->addQuery($sql, 'Ajout de la séquence ' . $data['name']);
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $sql = "DROP SEQUENCE $name";
        $this->addQuery($sql, 'Suppression de la séquence ' . $name);
    }



    public function alter(array $old, array $new)
    {
        if ($old != $new) {
            if ($this->sendEvent()->getReturn('no-exec')) return;

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



    /**
     * @inheritDoc
     */
    public function prepareRenameCompare(array $data): array
    {
        return $data;
    }

}