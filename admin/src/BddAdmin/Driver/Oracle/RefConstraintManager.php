<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\RefConstraintManagerInterface;
use BddAdmin\Ddl\DdlFilter;

class RefConstraintManager extends AbstractManagerDdlConstraint implements RefConstraintManagerInterface
{
    protected $description = 'clé étrangère';



    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT CONSTRAINT_NAME
          FROM ALL_CONSTRAINTS 
          WHERE OWNER = sys_context( 'userenv', 'current_schema' ) 
            AND CONSTRAINT_TYPE = 'R'
          AND CONSTRAINT_NAME NOT LIKE 'BIN" . "$%'
          ORDER BY CONSTRAINT_NAME
        ";
        $r    = $this->bdd->select($sql);

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
          cc.column_name \"column\",
          rc.table_name \"rtable\",
          rcc.column_name \"rcolumn\",
          c.delete_rule \"delete_rule\",
          c.index_name \"index\"
        FROM
          all_constraints c
          JOIN all_constraints rc ON rc.constraint_name = c.r_constraint_name AND rc.constraint_type = 'P'
          JOIN all_cons_columns cc ON cc.constraint_name = c.constraint_name
          JOIN all_cons_columns rcc ON rcc.constraint_name = rc.constraint_name AND rcc.position = cc.position
        WHERE
          c.OWNER = sys_context( 'userenv', 'current_schema' )
          AND c.constraint_type = 'R' $f
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



    public function exists(string $name): bool
    {
        $sql = "SELECT count(*) NBR FROM ALL_CONSTRAINTS WHERE "
            . "OWNER = sys_context( 'userenv', 'current_schema' )"
            . "AND CONSTRAINT_TYPE = 'R'"
            . "AND CONSTRAINT_NAME NOT LIKE 'BIN" . "$%' "
            . "AND CONSTRAINT_NAME = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
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
     * @return RefConstraintManagerInterface
     */
    public function enableAll(): RefConstraintManagerInterface
    {
        $this->bdd->logBegin("Activation de toutes les clés étrangères");
        $l = $this->get();
        foreach ($l as $d) {
            $this->bdd->logMsg("Contrainte " . $d['name'], true);
            try {
                $this->enable($d);
            } catch (\Throwable $e) {
                $this->bdd->logError($e);
            }
        }
        $this->bdd->logEnd('Toutes les clés étrangères ont été activées');

        return $this;
    }



    /**
     * @return RefConstraintManagerInterface
     */
    public function disableAll(): RefConstraintManagerInterface
    {
        $this->bdd->logBegin("Désactivation de toutes les clés étrangères");
        $l = $this->get();
        foreach ($l as $d) {
            $this->bdd->logMsg("Contrainte " . $d['name'], true);
            try {
                $this->disable($d);
            } catch (\Throwable $e) {
                $this->bdd->logError($e);
            }
        }
        $this->bdd->logEnd('Toutes les clés étrangères ont été désactivées');

        return $this;
    }
}