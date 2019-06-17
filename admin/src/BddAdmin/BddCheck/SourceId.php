<?php

namespace BddAdmin\BddCheck;

class SourceId extends BddCheckRule
{
    public $nullable;

    public $consNormName;

    public $constraintName;

    public $rConstraintName;

    public $deleteRule;

    public $status;



    public function sql()
    {
        return "
        WITH fks AS (
SELECT
  c.constraint_name,
  c.table_name,
  c.r_constraint_name,
  c.delete_rule,
  c.status,
  cc.column_name
FROM
  user_constraints c
  JOIN user_cons_columns cc ON cc.constraint_name = c.constraint_name
WHERE
  c.constraint_type = 'R'
)
SELECT
  t.table_name,
  tc.column_name,
  tc.nullable,
  substr(t.table_name,1,30-10) || '_SOURCE_FK' cons_norm_name,
  fks.constraint_name,
  fks.r_constraint_name,
  fks.delete_rule,
  fks.status
FROM
            user_tables        t
       JOIN user_tab_cols     tc ON tc.table_name = t.table_name
  LEFT JOIN                  fks ON fks.table_name = t.table_name AND fks.column_name = tc.column_name
WHERE
  tc.column_name = 'SOURCE_ID'
ORDER BY
  t.table_name
        ";
    }



    public function check()
    {
        if (!$this->constraintName) {
            $this->error('Pas de clé étrangère');
            return;
        }

        if ('Y' == $this->nullable){
            $this->error('Le champ n\'est pas obligatoire');
        }

        if ($this->consNormName != $this->constraintName){
            $this->error(
                'Contrainte mal nommée',
                "ALTER TABLE {$this->tableName} RENAME CONSTRAINT {$this->constraintName} TO {$this->consNormName};"
            );
        }
        if ($this->deleteRule != 'NO ACTION'){
            $this->error('Règle de destruction à positionner sur "RESTRICT"');
        }
        if ($this->status != 'ENABLED'){
            $this->error('Contrainte désactivée');
        }
    }

}