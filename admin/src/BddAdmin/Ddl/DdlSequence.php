<?php

namespace BddAdmin\Ddl;



class DdlSequence extends DdlAbstract
{
    const ALIAS = 'sequence';

    public function get($includes = null, $excludes = null): array
    {
        list($f,$p) = $this->makeFilterParams('sequence_name', $includes, $excludes);
        $data = [];

        $qr   = $this->bdd->select('SELECT sequence_name "name" FROM user_sequences WHERE 1=1 ' . $f, $p);
        foreach ($qr as $r) {
            $data[$r['name']] = $r;
        }

        return $data;
    }



    public function create(array $data)
    {
        $sql = "CREATE SEQUENCE " . $data['name'] . " INCREMENT BY 1 MINVALUE 1 NOCACHE";
        $this->addQuery($sql);
    }



    public function drop(string $name)
    {
        $sql = "DROP SEQUENCE $name";
        $this->addQuery($sql);
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



    /**
     * @inheritDoc
     */
    public function prepareRenameCompare(array $data): array
    {
        return $data;
    }

}