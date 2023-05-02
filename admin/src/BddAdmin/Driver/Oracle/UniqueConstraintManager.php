<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\UniqueConstraintManagerInterface;
use BddAdmin\Ddl\DdlFilter;

class UniqueConstraintManager extends AbstractManagerDdlConstraint implements UniqueConstraintManagerInterface
{
    protected $description = 'contrainte d\'unicité';



    public function getList(): array
    {
        $list = [];
        $sql = "
          SELECT CONSTRAINT_NAME
          FROM ALL_CONSTRAINTS 
          WHERE OWNER = sys_context( 'userenv', 'current_schema' ) 
            AND CONSTRAINT_TYPE = 'U'
          AND CONSTRAINT_NAME NOT LIKE 'BIN" . "$%'
          ORDER BY CONSTRAINT_NAME
        ";
        $r = $this->bdd->select($sql);

        foreach ($r as $l) {
            $list[] = $l['CONSTRAINT_NAME'];
        }

        return $list;
    }



    public function get($includes = null, $excludes = null): array
    {
        $filter = DdlFilter::normalize2($includes, $excludes);
        [$f, $p] = $filter->toSql('c.constraint_name');
        $data = [];

        $sql = "SELECT
          c.constraint_name \"name\",
          c.table_name \"table\",
          c.index_name \"index\",
          cc.column_name \"column\"
        FROM
          all_constraints c
          JOIN all_cons_columns cc ON cc.constraint_name = c.constraint_name
        WHERE
          c.owner = sys_context( 'userenv', 'current_schema' )
          AND c.constraint_type = 'U'
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



    public function exists(string $name): bool
    {
        $sql = "SELECT count(*) NBR FROM ALL_CONSTRAINTS WHERE "
            . "OWNER = sys_context( 'userenv', 'current_schema' )"
            . "AND CONSTRAINT_TYPE = 'U'"
            . "AND CONSTRAINT_NAME NOT LIKE 'BIN" . "$%' "
            . "AND CONSTRAINT_NAME = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
    }



    public function makeCreate(array $data)
    {
        $cols = implode(', ', $data['columns']);
        $sql = "ALTER TABLE " . $data['table'] . " ADD CONSTRAINT " . $data['name'] . " UNIQUE ($cols) ";
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



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        parent::create($data);
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        parent::drop($name);
    }



    public function alter(array $old, array $new)
    {
        if ($this->isDiff($old, $new)) {
            if ($this->sendEvent()->getReturn('no-exec')) return;

            parent::alter($old, $new);
        }
    }



    public function rename(string $oldName, array $new)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        parent::rename($oldName, $new);
    }



    /***
     * @param string|array $name
     */
    public function enable($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        parent::enable($name);
    }



    /***
     * @param string|array $name
     */
    public function disable($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        parent::disable($name);
    }



    /**
     * @return UniqueConstraintManagerInterface
     */
    public function enableAll(): UniqueConstraintManagerInterface
    {
        $this->bdd->logBegin("Activation de toutes les contraintes d'unicité");
        $l = $this->get();
        foreach ($l as $d) {
            $this->bdd->logMsg("Clé primaire " . $d['name'], true);
            try {
                $this->enable($d);
            } catch (\Throwable $e) {
                $this->bdd->logError($e);
            }
        }
        $this->bdd->logEnd('Toutes les contraintes d\'unicité ont été activées');

        return $this;
    }



    /**
     * @return UniqueConstraintManagerInterface
     */
    public function disableAll(): UniqueConstraintManagerInterface
    {
        $this->bdd->logBegin("Désactivation de toutes les contraintes d'unicité");
        $l = $this->get();
        foreach ($l as $d) {
            $this->bdd->logMsg("Clé primaire " . $d['name'], true);
            try {
                $this->disable($d);
            } catch (\Throwable $e) {
                $this->bdd->logError($e);
            }
        }
        $this->bdd->logEnd('Toutes les contraintes d\'unicité ont été désactivées');

        return $this;
    }
}