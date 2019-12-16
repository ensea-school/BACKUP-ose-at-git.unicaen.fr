<?php

namespace BddAdmin\Ddl;


class DdlTrigger extends DdlAbstract
{
    const ALIAS = 'trigger';



    public function get($includes = null, $excludes = null): array
    {
        [$f, $p] = $this->makeFilterParams('name', $includes, $excludes);
        $data = [];

        $q = "SELECT 
            name \"name\",
            text \"ddl\"
          FROM
            user_source 
          WHERE
            type = 'TRIGGER' $f
            AND name NOT LIKE 'BIN$%'
          ORDER BY name, line
        ";
        $p = $this->bdd->select($q, $p);
        foreach ($p as $r) {
            if (!isset($data[$r['name']])) {
                $data[$r['name']] = [
                    'name'       => $r['name'],
                    'definition' => 'CREATE OR REPLACE ',
                ];
            }
            $data[$r['name']]['definition'] .= $r['ddl'];
        }
        foreach ($data as $name => $d) {
            $data[$name]['definition'] = $this->purger($d['definition'], false);
        }

        return $data;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery($data['definition'], 'Ajout/modification du trigger ' . $data['name']);
    }



    public function drop(string $name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery("DROP TRIGGER $name", 'Suppression du trigger ' . $name);
    }



    /***
     * @param string $name
     */
    public function enable(string $name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery("alter trigger $name enable", 'Activation du trigger ' . $name);
    }



    /***
     * @param string $name
     */
    public function disable(string $name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery("alter trigger $name disable", 'Désactivation du trigger ' . $name);
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

        $newName = $new['name'];

        $sql = "ALTER TRIGGER \"$oldName\" RENAME TO \"$newName\"";
        $this->addQuery($sql, 'Renommage du trigger ' . $oldName . ' en ' . $newName);
    }

}