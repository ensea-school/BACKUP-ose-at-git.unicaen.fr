<?php

namespace BddAdmin\BddCheck;

class RefsSansCles extends BddCheckRule
{
    public $nullable;

    public $consNormName;

    public $constraintName;

    public $status;

    public $cols;



    public function sql()
    {
        return "
       with cc as (
  SELECT
    cc.column_name,
    c.table_name,
    c.constraint_name
  FROM
    user_cons_columns cc
    JOIN user_constraints c ON c.constraint_name = cc.constraint_name AND c.constraint_type = 'R'
)
select 
  tc.table_name,
  tc.column_name
from 
  user_tables t
  JOIN user_tab_cols tc ON tc.table_name = t.table_name
  LEFT JOIN cc ON cc.column_name = tc.column_name AND cc.table_name = tc.table_name
  LEFT JOIN user_mviews mv ON mv.mview_name = t.table_name
where 
  tc.column_name like '%_ID'
  AND cc.constraint_name IS NULL
  AND mv.mview_name IS NULL
        ";
    }



    public function check()
    {
        $this->error('Clé étrangère manquante');
    }

}