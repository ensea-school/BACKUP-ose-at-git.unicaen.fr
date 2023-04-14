<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\AbstractManager;
use BddAdmin\Manager\TriggerManagerInterface;
use BddAdmin\Ddl\DdlFilter;

class TriggerManager extends AbstractManager implements TriggerManagerInterface
{
    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT OBJECT_NAME 
          FROM ALL_OBJECTS 
          WHERE 
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND OBJECT_TYPE = 'TRIGGER' AND GENERATED = 'N'
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
        [$f, $p] = $filter->toSql('name');
        $data = [];

        $q = "SELECT 
            name \"name\",
            text \"ddl\"
          FROM
            all_source 
          WHERE
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND type = 'TRIGGER' $f
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



    public function exists(string $name): bool
    {
        $sql = "SELECT count(*) NBR 
          FROM ALL_OBJECTS 
          WHERE 
            OWNER = sys_context( 'userenv', 'current_schema' )
            AND OBJECT_TYPE = 'TRIGGER' AND GENERATED = 'N' AND OBJECT_NAME = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $this->addQuery($data['definition'], 'Ajout/modification du trigger ' . $data['name']);
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("DROP TRIGGER $name", 'Suppression du trigger ' . $name);
    }



    /***
     * @param string $name
     */
    public function enable($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("alter trigger $name enable", 'Activation du trigger ' . $name);
    }



    /***
     * @param string $name
     */
    public function disable($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("alter trigger $name disable", 'Désactivation du trigger ' . $name);
    }



    /**
     * @return TriggerManagerInterface
     */
    public function enableAll(): TriggerManagerInterface
    {
        $this->bdd->logBegin("Activation de tous les déclencheurs");
        $l = $this->get();
        foreach ($l as $d) {
            $this->bdd->logMsg("Déclencheur " . $d['name'], true);
            try {
                $this->enable($d);
            } catch (\Throwable $e) {
                $this->bdd->logError($e);
            }
        }
        $this->bdd->logEnd('Tous les déclencheurs ont été activés');

        return $this;
    }



    /**
     * @return TriggerManagerInterface
     */
    public function disableAll(): TriggerManagerInterface
    {
        $this->bdd->logBegin("Désactivation de tous les déclencheurs");
        $l = $this->get();
        foreach ($l as $d) {
            $this->bdd->logMsg("Déclencheur " . $d['name'], true);
            try {
                $this->disable($d);
            } catch (\Throwable $e) {
                $this->bdd->logError($e);
            }
        }
        $this->bdd->logEnd('Tous les déclencheurs ont été désactivés');

        return $this;
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



    /**
     * @param string|array $name
     *
     * @return mixed
     */
    public function compiler($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("ALTER TRIGGER $name COMPILE", 'Compilation du trigger ' . $name);
    }



    public function compilerTout()
    {
        $objects = $this->getList();
        foreach ($objects as $object) {
            $this->compiler($object);
        }
    }
}