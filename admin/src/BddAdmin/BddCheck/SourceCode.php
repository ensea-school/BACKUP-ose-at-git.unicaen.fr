<?php

namespace BddAdmin\BddCheck;

class SourceCode extends BddCheckRule
{
    public $nullable;

    public $consNormName;

    public $constraintName;

    public $status;

    public $cols;



    public function sql()
    {
        return "
        WITH fks AS (
SELECT
  c.constraint_name,
  c.table_name,
  c.status,
  cc.column_name,
  listagg(cc.column_name, ',') WITHIN GROUP (ORDER BY cc.column_name) OVER (PARTITION BY cc.constraint_name) cols
FROM
  user_constraints c
  JOIN user_cons_columns cc ON cc.constraint_name = c.constraint_name
WHERE
  c.constraint_type = 'U'
)
SELECT
  t.table_name,
  tc.column_name,
  tc.nullable,
  substr(t.table_name,1,30-10) || '_SOURCE_UN' cons_norm_name,
  fks.constraint_name,
  fks.status,
  cols
FROM
            user_tables        t
       JOIN user_tab_cols     tc ON tc.table_name = t.table_name
  LEFT JOIN                  fks ON fks.table_name = t.table_name AND fks.column_name = tc.column_name
WHERE
  tc.column_name = 'SOURCE_CODE'
  AND t.table_name NOT IN ('SYNC_LOG')
  AND t.table_name NOT LIKE 'MV_%'
ORDER BY
  t.table_name
        ";
    }



    public function check()
    {
        if (!$this->constraintName) {
            $this->error('Pas de contrainte d\'unicité');

            return;
        }

        if ('Y' == $this->nullable) {
            //$this->error('Le champ est obligatoire');
        }

        if ($this->consNormName != $this->constraintName) {
            $this->error(
                'Contrainte d\'unicité mal nommée',
                "ALTER TABLE {$this->tableName} RENAME CONSTRAINT {$this->constraintName} TO {$this->consNormName};"
            );
        }
        if (!in_array($this->cols, [
            'ANNEE_ID,HISTO_DESTRUCTION,SOURCE_CODE',
            'HISTO_DESTRUCTION,SOURCE_CODE'
        ])) {
            $this->error("Il manque des colonnes pour la contrainte (trouvées : {$this->cols})");
        }
        {
            if ($this->status != 'ENABLED') {
                $this->error('Contrainte d\'unicité désactivée');
            }
        }
    }

}