<?php

namespace BddAdmin\BddCheck;

class ForeignSansIndex extends BddCheckRule
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
    c.index_name IS NULL AND c.constraint_type = 'R'
), inds as (
SELECT 
    i.*, 
    listagg(ic.column_name, ',') WITHIN GROUP (ORDER BY ic.column_name) OVER (PARTITION BY ic.index_name) cols
  FROM 
    USER_INDEXES i
    JOIN user_ind_columns ic ON ic.index_name = i.index_name
)
SELECT
  fks.*, inds.index_name
FROM
  fks
  LEFT JOIN inds ON inds.table_name = fks.table_name AND inds.cols = fks.cols";
    }



    public function check()
    {
        if (!$this->indexName) {
            $this->error('Pas d\'index',
                "CREATE INDEX $this->constraintName ON $this->tableName($this->cols ASC);"
            );

            return;
        }
        if ($this->indexName != $this->constraintName) {
            $this->error(
                'Index à renommer',
                "ALTER INDEX $this->indexName RENAME TO $this->constraintName;"

            );
        }
    }

}