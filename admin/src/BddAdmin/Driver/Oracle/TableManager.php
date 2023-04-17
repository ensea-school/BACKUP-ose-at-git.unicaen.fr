<?php

namespace BddAdmin\Driver\Oracle;

use BddAdmin\Bdd;
use BddAdmin\Manager\AbstractManager;
use BddAdmin\Manager\TableManagerInterface;
use BddAdmin\Ddl\DdlFilter;
use BddAdmin\Exception\BddException;

class TableManager extends AbstractManager implements TableManagerInterface
{
    /**
     * @param string|null $commentaire
     *
     * @return array
     */
    private function interpreterCommentaire($commentaire): array
    {
        $data = [];
        if ($commentaire) {
            $keys = [
                'sequence',
                'columns-order',
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



    public function getList(): array
    {
        $list = [];
        $sql  = "
          SELECT O.OBJECT_NAME 
          FROM ALL_OBJECTS O 
            LEFT JOIN ALL_OBJECTS O2 ON O2.OBJECT_NAME = O.OBJECT_NAME AND O2.OBJECT_TYPE = 'MATERIALIZED VIEW'
          WHERE 
            O.OWNER = sys_context( 'userenv', 'current_schema' )
            AND O.OBJECT_TYPE = 'TABLE' AND O.GENERATED = 'N' AND O2.OBJECT_NAME IS NULL
          ORDER BY O.OBJECT_NAME
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
        [$f, $p] = $filter->toSql('t.table_name');
        $data = [];

        $q = "SELECT
            t.table_name      \"name\",
            t.temporary       \"temporary\",
            t.logging         \"logging\",
            c.column_name     \"cname\",
            c.data_type       \"type\",
            c.char_length     \"length\",
            c.data_scale      \"scale\",
            c.data_precision  \"precision\",
            c.nullable        \"nullable\",
            c.data_default    \"default\",
            c.column_id       \"position\",
            ccomm.comments    \"col_commentaire\",
            comm.comments     \"commentaire\",
            s.sequence_name   \"sequence\"
          FROM
                      all_tables          t
            LEFT JOIN all_mviews          m ON m.mview_name = t.table_name
            LEFT JOIN all_tab_comments comm ON comm.table_name = t.table_name
            LEFT JOIN all_sequences       s ON s.sequence_name = SUBSTR(t.table_name,1,23) || '_ID_SEQ'
            JOIN all_tab_cols c ON c.table_name = t.table_name AND c.hidden_column = 'NO' LEFT JOIN user_col_comments ccomm ON ccomm.table_name = c.table_name AND ccomm.column_name = c.column_name
          WHERE
            t.OWNER = sys_context( 'userenv', 'current_schema' )
            AND c.OWNER = sys_context( 'userenv', 'current_schema' )
            AND m.mview_name IS NULL 
            $f
          ORDER BY
            t.table_name, c.column_name
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
                    'columns'     => [],
                ];
                if ($commData = $this->interpreterCommentaire($paq['commentaire'])) {
                    foreach ($commData as $k => $v) {
                        $data[$paq['name']][$k] = $v;
                    }
                }
            }

            $default = $paq['default'] !== null ? $this->purger($paq['default']) : null;
            if ('NULL' === $default) $default = null;

            $type      = $paq['type'];
            $precision = $paq['precision'] ? (int)$paq['precision'] : null;

            switch ($type) {
                case 'NUMBER':
                    if (1 === $precision) {
                        $type = Bdd::TYPE_BOOL;
                    } else {
                        $type = Bdd::TYPE_INT;
                    }
                break;
                case 'VARCHAR2':
                case 'CHAR':
                case 'RAW':
                    $type = Bdd::TYPE_STRING;
                break;
                case 'DATE':
                    $type = Bdd::TYPE_DATE;
                break;
                case 'FLOAT':
                    $type = Bdd::TYPE_FLOAT;
                break;
                case 'BLOB':
                    $type = Bdd::TYPE_BLOB;
                break;
                case 'CLOB':
                case 'LONG':
                    $type = Bdd::TYPE_CLOB;
                break;
                default:
                    throw new BddException('Le type de colonne "' . $paq['type'] . '" n\'est pas reconnu pour ' . $paq['name'] . '.' . $paq['cname']);
            }

            $data[$paq['name']]['columns'][$paq['cname']] = [
                'name'        => $paq['cname'],
                'type'        => $type,
                'bdd-type'    => $paq['type'],
                'length'      => (int)$paq['length'],
                'scale'       => $paq['scale'],
                'precision'   => $precision,
                'nullable'    => $paq['nullable'] == 'Y',
                'default'     => $default,
                'position'    => $paq['position'],
                'commentaire' => $paq['col_commentaire'],
            ];
        }

        foreach ($data as $table => $tdata) {
            $colPos    = isset($tdata['columns-order']) ? explode(',', $tdata['columns-order']) : [];
            $oriColPos = [];
            foreach ($tdata['columns'] as $cname => $column) {
                $oriColPos[$column['position']] = $cname;
            }
            ksort($oriColPos);
            foreach ($oriColPos as $cname) {
                if (!in_array($cname, $colPos)) {
                    $colPos[] = $cname;
                }
            }
            $position = 1;
            foreach ($colPos as $cname) {
                if (isset($data[$table]['columns'][$cname])) {
                    $data[$table]['columns'][$cname]['position'] = $position;
                    $position++;
                }
            }
        }

        return $data;
    }



    public function exists(string $name): bool
    {
        $sql = "SELECT count(*) NBR 
          FROM ALL_OBJECTS O 
            LEFT JOIN ALL_OBJECTS O2 ON O2.OBJECT_NAME = O.OBJECT_NAME AND O2.OBJECT_TYPE = 'MATERIALIZED VIEW'
          WHERE 
            O.OWNER = sys_context( 'userenv', 'current_schema' )
            AND O.OBJECT_TYPE = 'TABLE' AND O.GENERATED = 'N' AND O2.OBJECT_NAME IS NULL
            AND O.OBJECT_NAME = :name";
        $params = ['name' => $name];

        $nbr = (int)$this->bdd->select($sql, $params, ['fetch' => Bdd::FETCH_ONE])['NBR'];

        return $nbr > 0;
    }



    protected function makeCreate(array $data)
    {
        $sql = "CREATE ";
        if ($data['temporary']) {
            $sql .= "GLOBAL TEMPORARY ";
        }
        $sql .= "TABLE \"" . $data['name'] . "\"\n   (\t";

        $cols    = [];
        $columns = $data['columns'];
        uasort($columns, function ($a, $b) {
            $apos = isset($a['position']) ? $a['position'] : 1;
            $bpos = isset($b['position']) ? $b['position'] : 1;

            return $apos - $bpos;
        });
        foreach ($columns as $column) {
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



    protected function makeCreateComm(array $data, $forceUpdateNull = false)
    {
        if ($data['commentaire']) {
            $comm = "'" . str_replace("'", "''", $data['commentaire']) . "'";

            return 'COMMENT ON TABLE "' . $data['name'] . '" IS ' . $comm;
        }

        if ($forceUpdateNull) {
            return 'COMMENT ON TABLE "' . $data['name'] . '" IS \'\'';
        } else {
            return null;
        }
    }



    private function makeColumnType(array $column): string
    {
        if (isset($column['bdd-type'])) {
            $resType = $column['bdd-type'];
        } else {
            $resType = null;
        }
        switch ($column['type']) {
            case Bdd::TYPE_BOOL:
                if (!$resType) $resType = 'NUMBER';
                $resType .= '(1)';
            break;
            case Bdd::TYPE_INT:
                if ($column['scale'] == '0') {
                    if (!$resType) $resType = 'NUMBER';
                    $resType .= '(' . ($column['precision'] ? $column['precision'] : '*') . ',0)';
                }
            break;
            case Bdd::TYPE_STRING:
                if (!$resType) $resType = 'VARCHAR2';
                $resType .= '(' . $column['length'] . ' CHAR)';
            break;
            case Bdd::TYPE_FLOAT:
                if (!$resType) $resType = 'FLOAT';
                $resType .= '(' . $column['precision'] . ')';
            break;
            case Bdd::TYPE_BLOB:
                if (!$resType) $resType = 'BLOB';
            break;
            case Bdd::TYPE_CLOB:
                if (!$resType) $resType = 'CLOB';
            break;
            case Bdd::TYPE_DATE:
                if (!$resType) $resType = 'DATE';
            break;
        }

        return $resType;
    }



    public function create(array $data)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        /* Création de la table */
        $this->addQuery($this->makeCreate($data), 'Ajout de la table ' . $data['name']);

        /* Création du commentaire éventuel de la table */
        if ($comm = $this->makeCreateComm($data)) {
            $this->addQuery($comm, 'Ajout de commentaire sur la table ' . $data['name']);
        }

        /* Création des commentaires éventuels de colonnes */
        foreach ($data['columns'] as $column) {
            $this->alterColumnComment($data['name'], ['commentaire' => null], $column);
        }
    }



    public function drop($name)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        if (is_array($name)) $name = $name['name'];

        $this->addQuery("DROP TABLE $name", 'Suppression de la table ' . $name);
    }



    public function majSequence(array $data)
    {
        if (!isset($data['sequence'])) return;
        if (!isset($data['columns']['ID'])) return;

        if ($this->sendEvent()->getReturn('no-exec')) return;

        $sql = 'DECLARE seqId NUMERIC;
BEGIN
  SELECT COALESCE(MAX(id+1),1) INTO seqId FROM ' . $data['name'] . ';
  EXECUTE IMMEDIATE \'DROP SEQUENCE ' . $data['sequence'] . '\';
  EXECUTE IMMEDIATE \'CREATE SEQUENCE ' . $data['sequence'] . ' INCREMENT BY 1 MINVALUE \' || seqId || \' NOCACHE\';
END;';
        $this->addQuery($sql, 'Mise à jour de la séquence ' . $data['sequence']);
    }



    public function isDiff(array $d1, array $d2): bool
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

            if ($this->sendEvent()->getReturn('no-exec')) return;

            if ($old['logging'] !== $new['logging']) {
                $log = $new['logging'] ? 'LOGGING' : 'NOLOGGING';
                $this->addQuery("ALTER TABLE \"$name\" $log", 'Modification du logging de la table ' . $new['name']);
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

            foreach ($delCols as $delCol) {
                $this->dropColumn($name, $old['columns'][$delCol]);
            }

            if ($old['commentaire'] !== $new['commentaire']) {
                $this->addQuery($this->makeCreateComm($new, true), 'Modification du commentaire de la table ' . $new['name']);
            }
        }
    }



    private function isEmpty(string $table): bool
    {
        $r = $this->bdd->select('SELECT * FROM ' . $table, [], ['fetch' => $this->bdd::FETCH_ONE]);

        return false === $r;
    }



    private function addColumn(string $table, array $column, $noNotNull = false)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $cp = ['"' . $column['name'] . '"', $this->makeColumnType($column)];
        if ($column['default'] !== null) {
            $cp[] = 'DEFAULT ' . $column['default'];
        }

        if (!$column['nullable'] && !$noNotNull) {
            if ($column['default'] === null && $this->isEmpty($table)) {
                $this->bdd->logError("La colonne $table." . $column['name'] . " n\'a pas pu être déclarée NOT NULL, car des données sont déjà présentes dans la table et aucune valeur par défaut n'a été configurée");
            } else {
                $cp[] = "NOT NULL ENABLE";
            }
        }

        $sql = "ALTER TABLE \"$table\" ADD (" . implode(" ", $cp) . ")";
        $this->addQuery($sql, 'Ajout de la colonne ' . $column['name'] . ' sur la table ' . $table);

        /* Ajout du commentaire éventuel de la colonne */
        $this->alterColumnComment($table, ['commentaire' => null], $column);
    }



    private function dropColumn(string $table, array $column)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $column = $column['name'];
        $this->addQuery(
            "ALTER TABLE \"$table\" DROP COLUMN \"$column\"",
            'Suppression de la colonne ' . $column . ' sur la table ' . $table
        );
    }



    private function alterColumnType(string $table, array $old, array $new)
    {
        if ($this->sendEvent()->getReturn('no-exec')) return;

        $column = $new['name'];
        if ($this->isColDiffType($old, $new)) {
            $sql = "ALTER TABLE \"$table\" MODIFY (\"$column\" " . $this->makeColumnType($new) . ")";
            $this->addQuery($sql, 'Changement du type de la colonne ' . $column . ' de la table ' . $table);
        }
    }



    private function alterColumnNullable(string $table, array $old, array $new)
    {
        $column = $new['name'];
        if ($this->isColDiffNullable($old, $new)) {
            if ($this->sendEvent()->getReturn('no-exec')) return;

            $sql = "ALTER TABLE \"$table\" MODIFY (\"$column\" " . ($new['nullable'] ? '' : 'NOT ') . "NULL)";
            $this->addQuery($sql, 'Changement d\'état de la colonne ' . $column . ' de la table ' . $table);
        }
    }



    private function alterColumnDefault(string $table, array $old, array $new)
    {
        $column = $new['name'];
        if ($this->isColDiffDefault($old, $new)) {
            if ($this->sendEvent()->getReturn('no-exec')) return;

            $default = $new['default'];
            if (null === $default) $default = 'NULL';
            $sql = "ALTER TABLE \"$table\" MODIFY (\"$column\" DEFAULT $default )";
            $this->addQuery($sql, 'Changement de valeur par défaut de la colonne ' . $column . ' de la table ' . $table);
        }
    }



    private function alterColumnComment(string $table, array $old, array $new)
    {
        $column = $new['name'];
        if ($this->isColDiffComment($old, $new)) {
            if ($this->sendEvent()->getReturn('no-exec')) return;

            $commentaire = $new['commentaire'];
            if ($commentaire) {
                $commentaire = "'" . str_replace("'", "''", $commentaire) . "'";
                $sql         = "COMMENT ON COLUMN \"$table\".\"$column\" IS $commentaire";
                $this->addQuery($sql, 'Modification du commentaire de la colonne ' . $column . ' de la table ' . $table);
            } else {
                $sql = "COMMENT ON COLUMN \"$table\".\"$column\" IS ''";
                $this->addQuery($sql, 'Suppression du commentaire de la colonne ' . $column . ' de la table ' . $table);
            }
        }
    }



    public function rename(string $oldName, array $new)
    {
        $newName = $new['name'];

        if ($this->sendEvent()->getReturn('no-exec')) return;

        $sql = "ALTER TABLE \"$oldName\" RENAME TO \"$newName\"";
        $this->addQuery($sql, 'Renommage de la table ' . $oldName . ' en ' . $newName);
    }
}