<?php

namespace BddAdmin\Ddl;


class DdlPackage extends DdlAbstract
{
    const ALIAS = 'package';



    public function get($includes = null, $excludes = null): array
    {
        list($f, $p) = $this->makeFilterParams('name', $includes, $excludes);
        $data = [];

        $q = "SELECT 
            name \"name\",
            text \"ddl\",
            CASE WHEN type = 'PACKAGE' THEN 'definition' ELSE 'body' END \"type\"
          FROM
            user_source 
          WHERE
            (type = 'PACKAGE' OR type = 'PACKAGE BODY') $f 
          ORDER BY name, line
        ";
        $p = $this->bdd->select($q, $p);
        foreach ($p as $r) {
            if (!isset($data[$r['name']])) {
                $data[$r['name']] = [
                    'name'       => $r['name'],
                    'definition' => 'CREATE OR REPLACE ',
                    'body'       => 'CREATE OR REPLACE ',
                ];
            }

            $data[$r['name']][$r['type']] .= $r['ddl'];
        }
        foreach ($data as $name => $d) {
            $definition = $this->purger($d['definition'], false);
            $body       = $this->purger($d['body'], false);

            if ($this->hasOption('clearAutogen')){
                $definition = $this->clearAutogen($definition);
                $body = $this->clearAutogen($body);
            }

            $data[$name]['definition'] = $definition;
            $data[$name]['body']       = $body;
        }

        return $data;
    }



    private function clearAutogen(string $sql): string
    {
        return substr($sql, 0, strpos($sql, '-- AUTOMATIC GENERATION --') + 26)
            . substr($sql, strpos($sql, '-- END OF AUTOMATIC GENERATION --') - 4);
    }



    public function create(array $data)
    {
        if ($this->hasOption('definition')) {
            $this->addQuery($data['definition'], 'Ajout/modification de la définition du package '.$data['name']);
        } elseif ($this->hasOption('body')) {
            $this->addQuery($data['body'], 'Ajout/modification du corps du package '.$data['name']);
        } else {
            $this->addQuery($data['definition'], 'Ajout/modification de la définition du package '.$data['name']);
            $this->addQuery($data['body'], 'Ajout/modification du corps du package '.$data['name']);
        }
    }



    public function drop(string $name)
    {
        $this->addQuery("DROP PACKAGE $name", 'Suppression du package '.$name);
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