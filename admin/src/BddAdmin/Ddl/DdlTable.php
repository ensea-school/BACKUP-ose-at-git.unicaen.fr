<?php

namespace BddAdmin\Ddl;


class DdlTable extends DdlAbstract
{
    const ALIAS = 'table';

    const COL_ACTION_CREATE         = 0b00001;
    const COL_ACTION_DROP           = 0b00010;
    const COL_ACTION_ALTER_TYPE     = 0b00100;
    const COL_ACTION_ALTER_NOT_NULL = 0b01000;
    const COL_ACTION_ALTER_DEFAULT  = 0b10000;
    const COL_ACTION_ALL            = 0b11111;

    const OPT_NO_COLUMNS   = 'no-columns';
    const OPT_NO_TEMPORARY = 'no-temporary';

    /**
     * @var int
     */
    private $colActions = 0;



    /**
     * @return int
     */
    public function getColActions(): int
    {
        return $this->colActions;
    }



    /**
     * @param int $colActions
     *
     * @return DdlTable
     */
    public function setColActions(int $colActions): DdlTable
    {
        $this->colActions = $colActions;

        return $this;
    }



    /**
     * @param int $colAction
     *
     * @return bool
     */
    public function canColAction(int $colAction): bool
    {
        return $this->colActions === 0 || (($this->colActions & $colAction) > 0);
    }



    /**
     * @param string|null $commentaire
     *
     * @return array
     */
    private function interpreterCommentaire($commentaire): array // (?string $commentaire): array
    {
        $data = [];
        if ($commentaire) {
            $keys = [
                'sequence',
            ];

            $commentaire = strtoupper($commentaire);
            $commentaire = str_replace(' ', '', $commentaire);
            $commentaire = str_replace("\t", '', $commentaire);
            $commentaire = str_replace("\n", '', $commentaire);

            foreach ($keys as $key) {
                $kpos = strpos($commentaire, strtoupper($key) . '=');
                if (false !== $kpos) {
                    $vend       = strpos($commentaire, ';', $kpos + 1);
                    $data[$key] = substr($commentaire, $kpos + strlen($key) + 1, $vend - ($kpos + strlen($key) + 1));
                }
            }
        }

        return $data;
    }



    public function get($includes = null, $excludes = null): array
    {
        list($f, $p) = $this->makeFilterParams('t.table_name', $includes, $excludes);
        $data = [];

        $withColumns = !$this->hasOption(self::OPT_NO_COLUMNS);

        $q = "SELECT
            t.table_name      \"name\",
            t.temporary       \"temporary\",
            t.logging         \"logging\",
            " . ($withColumns ? "
                c.column_name      \"cname\",
                c.data_type        \"type\",
                c.char_length      \"length\",
                c.data_scale       \"scale\",
                c.data_precision   \"precision\",
                c.nullable         \"nullable\",
                c.data_default     \"default\",
                ccomm.comments     \"col_commentaire\",
            " : '') . "
            comm.comments     \"commentaire\",
            s.sequence_name   \"sequence\"
          FROM
                      user_tables          t
            LEFT JOIN user_mviews          m ON m.mview_name = t.table_name
            LEFT JOIN user_tab_comments comm ON comm.table_name = t.table_name
            LEFT JOIN user_sequences       s ON s.sequence_name = SUBSTR(t.table_name,1,23) || '_ID_SEQ'
            " . ($withColumns ? "JOIN user_tab_cols c ON c.table_name = t.table_name LEFT JOIN user_col_comments ccomm ON ccomm.table_name = c.table_name AND ccomm.column_name = c.column_name" : '') . "
          WHERE
            m.mview_name IS NULL 
            " . ($this->hasOption(self::OPT_NO_TEMPORARY) ? "AND t.temporary <> 'Y'" : '') . "
            $f
          ORDER BY
            t.table_name
            " . ($withColumns ? ", c.column_id" : '') . "
        ";
        $p = $this->bdd->select($q, $p);
        foreach ($p as $paq) {
            if (!isset($data[$paq['name']])) {
                $data[$paq['name']] = [
                    'name'        => $paq['name'],
                    'temporary'   => $paq['temporary'] == 'Y',
                    'logging'     => $paq['logging'] == 'YES',
                    'commentaire' => $paq['commentaire'],
                    'sequence'    => $paq['sequence'],
                ];
                if ($withColumns) {
                    $data[$paq['name']]['columns'] = [];
                }
                if ($commData = $this->interpreterCommentaire($paq['commentaire'])) {
                    foreach ($commData as $k => $v) {
                        $data[$paq['name']][$k] = $v;
                    }
                }
            }

            if ($withColumns) {
                $default = $paq['default'] !== null ? $this->purger($paq['default']) : null;
                if ('NULL' === $default) $default = null;

                $data[$paq['name']]['columns'][$paq['cname']] = [
                    'name'        => $paq['cname'],
                    'type'        => $paq['type'],
                    'length'      => (int)$paq['length'],
                    'scale'       => $paq['scale'],
                    'precision'   => $paq['precision'] ? (int)$paq['precision'] : null,
                    'nullable'    => $paq['nullable'] == 'Y',
                    'default'     => $default,
                    'commentaire' => $paq['col_commentaire'],
                ];
            }
        }

        return $data;
    }



    protected function makeCreate(array $data)
    {
        $sql = "CREATE ";
        if ($data['temporary']) {
            $sql .= "GLOBAL TEMPORARY ";
        }
        $sql .= "TABLE \"" . $data['name'] . "\"\n   (\t";

        $cols = [];
        foreach ($data['columns'] as $column) {
            $cp = ['"' . $column['name'] . '"', $this->makeColumnType($column)];
            if ($column['default'] !== null) {
                $cp[] = 'DEFAULT ' . $column['default'];
            }
            if (!$column['nullable']) {
                $cp[] = "NOT NULL ENABLE";
            }

            $cols[] = implode(" ", $cp);
        }
        $sql .= implode(",\n\t", $cols);
        $sql .= "\n   )";
        if ($data['logging'] == false && !$data['temporary']) {
            $sql .= ' NOLOGGING';
        }

        return $sql;
    }



    protected function makeCreateComm(array $data)
    {
        if ($data['commentaire']) {
            $comm = "'" . str_replace("'", "''", $data['commentaire']) . "'";

            return 'COMMENT ON TABLE "' . $data['name'] . '" IS ' . $comm;
        }

        return null;
    }



    private function makeColumnType(array $column): string
    {
        $type = $column['type'];
        switch ($column['type']) {
            case 'NUMBER':
                if ($column['scale'] == '0') {
                    $type .= '(' . ($column['precision'] ? $column['precision'] : '*') . ',0)';
                }
            break;
            case 'VARCHAR2':
                $type .= '(' . $column['length'] . ' CHAR)';
            break;
            case 'FLOAT':
                $type .= '(' . $column['precision'] . ')';
            break;
        }

        return $type;
    }



    public function create(array $data)
    {
        /* Création de la table */
        $this->addQuery($this->makeCreate($data), 'Ajout de la table '.$data['name']);

        /* Création du commentaire éventuel de la table */
        if ($comm = $this->makeCreateComm($data)) {
            $this->addQuery($comm, 'Ajout de commentaire sur la table ' . $data['name']);
        }

        /* Création des commentaires éventuels de colonnes */
        foreach($data['columns'] as $column){
            $this->alterColumnComment($data['name'], ['commentaire' => null], $column);
        }
    }



    public function drop(string $name)
    {
        $this->addQuery("DROP TABLE $name", 'Suppression de la table '.$name);
    }



    public function majSequence(array $data)
    {
        if (!isset($data['sequence'])) return;
        if (!isset($data['columns']['ID'])) return;

        $sql = 'DECLARE seqId NUMERIC;
BEGIN
  SELECT COALESCE(MAX(id+1),1) INTO seqId FROM ' . $data['name'] . ';
  EXECUTE IMMEDIATE \'DROP SEQUENCE ' . $data['sequence'] . '\';
  EXECUTE IMMEDIATE \'CREATE SEQUENCE ' . $data['sequence'] . ' INCREMENT BY 1 MINVALUE \' || seqId || \' NOCACHE\';
END;';
        $this->addQuery($sql, 'Mise à jour de la séquence '.$data['sequence']);
    }



    public function isDiff(array $d1, array $d2)
    {
        foreach ($d1 as $key => $val) {
            if ($key != 'columns') {
                if ($d1[$key] !== $d2[$key]) {
                    return true;
                }
            }
        }

        if (array_keys($d1['columns']) != array_keys($d2['columns'])) {
            return true; // différences de colonnes
        }

        foreach ($d1['columns'] as $column => $colParams) {
            if ($this->isColDiff($d1['columns'][$column], $d2['columns'][$column])) {
                return true;
            }
        }

        return false;
    }



    private function isColDiff(array $col1, array $col2)
    {
        return $this->isColDiffType($col1, $col2)
            || $this->isColDiffDefault($col1, $col2)
            || $this->isColDiffNullable($col1, $col2)
            || $this->isColDiffComment($col1, $col2);
    }



    private function isColDiffType(array $col1, array $col2)
    {
        return $col1['type'] !== $col2['type']
            || $col1['length'] !== $col2['length']
            || $col1['scale'] !== $col2['scale']
            || $col1['precision'] !== $col2['precision'];
    }



    private function isColDiffNullable(array $col1, array $col2)
    {
        return $col1['nullable'] !== $col2['nullable'];
    }



    private function isColDiffDefault(array $col1, array $col2)
    {
        return $col1['default'] !== $col2['default'];
    }



    private function isColDiffComment(array $col1, array $col2)
    {
        return $col1['commentaire'] !== $col2['commentaire'];
    }



    public function alter(array $old, array $new)
    {
        if ($this->isDiff($old, $new)) {
            $name = $new['name'];

            if ($old['logging'] !== $new['logging']) {
                $log = $new['logging'] ? 'LOGGING' : 'NOLOGGING';
                $this->addQuery("ALTER TABLE \"$name\" $log", 'Modification du logging de la table '.$new['name']);
            }

            $newCols = array_diff(array_keys($new['columns']), array_keys($old['columns']));
            $updCols = array_intersect(array_keys($old['columns']), array_keys($new['columns']));
            $delCols = array_diff(array_keys($old['columns']), array_keys($new['columns']));

            foreach ($newCols as $newCol) {
                $this->addColumn($name, $new['columns'][$newCol]);
            }

            foreach ($updCols as $updCol) {
                $cOld = $old['columns'][$updCol];
                $cNew = $new['columns'][$updCol];
                $this->alterColumnType($name, $cOld, $cNew);
                $this->alterColumnNullable($name, $cOld, $cNew);
                $this->alterColumnDefault($name, $cOld, $cNew);
                $this->alterColumnComment($name, $cOld, $cNew);
            }

            if (!(isset($new['options']['noDropColumns']) && $new['options']['noDropColumns'])) {
                foreach ($delCols as $delCol) {
                    $this->dropColumn($name, $old['columns'][$delCol]);
                }
            }

            if ($old['commentaire'] !== $new['commentaire']) {
                $this->addQuery($this->makeCreateComm($new), 'Modification du commentaire de la table '.$new['name']);
            }
        }
    }



    private function addColumn(string $table, array $column, $noNotNull = false)
    {
        $cp = ['"' . $column['name'] . '"', $this->makeColumnType($column)];
        if ($column['default'] !== null) {
            $cp[] = 'DEFAULT ' . $column['default'];
        }

        if (!$column['nullable'] && !$noNotNull) {
            $cp[] = "NOT NULL ENABLE";
        }

        $sql = "ALTER TABLE \"$table\" ADD (" . implode(" ", $cp) . ")";
        $this->addQuery($sql, 'Ajout de la colonne '.$column['name'].' sur la table '.$table);

        /* Ajout du commentaire éventuel de la conne */
        $this->alterColumnComment($table, ['commentaire' => null], $column);
    }



    private function dropColumn(string $table, array $column)
    {
        $column = $column['name'];
        $this->addQuery(
            "ALTER TABLE \"$table\" DROP COLUMN \"$column\"",
            'Suppression de la colonne '.$column.' sur la table '.$table
        );
    }



    private function alterColumnType(string $table, array $old, array $new)
    {
        $column = $new['name'];
        if ($this->isColDiffType($old, $new)) {
            $sql = "ALTER TABLE \"$table\" MODIFY (\"$column\" " . $this->makeColumnType($new) . ")";
            $this->addQuery($sql,'Changement du type de la colonne '.$column.' de la table '.$table);
        }
    }



    private function alterColumnNullable(string $table, array $old, array $new)
    {
        $column = $new['name'];
        if ($this->isColDiffNullable($old, $new)) {
            $sql = "ALTER TABLE \"$table\" MODIFY (\"$column\" " . ($new['nullable'] ? '' : 'NOT ') . "NULL)";
            $this->addQuery($sql,'Changement d\'état de la colonne '.$column.' de la table '.$table);
        }
    }



    private function alterColumnDefault(string $table, array $old, array $new)
    {
        $column = $new['name'];
        if ($this->isColDiffDefault($old, $new)) {
            $default = $new['default'];
            if (null === $default) $default = 'NULL';
            $sql = "ALTER TABLE \"$table\" MODIFY (\"$column\" DEFAULT $default )";
            $this->addQuery($sql,'Changement de valeur par défaut de la colonne '.$column.' de la table '.$table);
        }
    }



    private function alterColumnComment(string $table, array $old, array $new)
    {
        $column = $new['name'];
        if ($this->isColDiffComment($old, $new)) {
            $commentaire = $new['commentaire'];
            if ($commentaire){
                $commentaire = "'" . str_replace("'", "''", $commentaire) . "'";
                $sql = "COMMENT ON COLUMN \"$table\".\"$column\" IS $commentaire";
                $this->addQuery($sql,'Modification du commentaire de la colonne '.$column.' de la table '.$table);
            }else{
                $sql = "COMMENT ON COLUMN \"$table\".\"$column\" IS ''";
                $this->addQuery($sql,'Suppression du commentaire de la colonne '.$column.' de la table '.$table);
            }
        }
    }



    public function rename(string $oldName, array $new)
    {
        $newName = $new['name'];

        $sql = "ALTER TABLE \"$oldName\" RENAME TO \"$newName\"";
        $this->addQuery($sql, 'Renommage de la table '.$oldName.' en '.$newName);
    }
}