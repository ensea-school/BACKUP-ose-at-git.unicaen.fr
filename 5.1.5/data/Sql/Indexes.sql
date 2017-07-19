SELECT
  i.*
FROM
  all_indexes i
  LEFT JOIN all_constraints c ON c.index_name = i.index_name
WHERE
  i.owner='OSE'
  AND c.constraint_name IS NULL;
  


-- enlever _IDX des indexes
select 

'ALTER INDEX ' || index_name || ' RENAME TO ' || new_index_name || ';' isql

from (
SELECT
  index_name, substr( index_name, 1, length(index_name)-4 ) new_index_name
FROM
  all_indexes i
WHERE
  owner='OSE'
  AND substr( index_name, -3 ) = 'IDX') t1;


  
-- création d'indexes manquants
SELECT

  'CREATE' || un || ' INDEX ' || constraint_name || ' ON ' || table_name || ' (' || cols || ') LOGGING;' isql

FROM (
  SELECT
    c.constraint_name,
    c.constraint_type,
    c.table_name,
    c.r_constraint_name,
    c.index_name,
    ose_divers.implode('SELECT column_name FROM all_cons_columns WHERE owner=''OSE'' AND constraint_name=q''[' || c.constraint_name || ']''', ' ASC,') || ' ASC' cols,
    CASE WHEN c.constraint_type IN ('P','U') THEN ' UNIQUE' ELSE '' END un
  FROM
    all_constraints c
    LEFT JOIN all_indexes i ON i.index_name = c.constraint_name
  WHERE
    c.owner = 'OSE'
    AND c.constraint_type IN ('P', 'U', 'R')
    AND c.table_name NOT LIKE 'BIN$%'
    AND c.index_name IS NULL AND i.index_name IS NULL
) t1;
  
SELECT * FROM all_tables where owner = 'OSE';
select * from all_views where owner='OSE';
  
  select * from SYS.all_cons_columns;
  
-- CREATE INDEX INTERVENANT_HDFK_IDX ON INTERVENANT (HISTO_DESTRUCTEUR_ID ASC) TABLESPACE OSE_TS LOGGING;
-- CREATE UNIQUE INDEX INTERVENANT_SOURCE__UN ON INTERVENANT ( SOURCE_CODE ASC, ANNEE_ID ASC ) LOGGING;

--SUBSTR(constraint_name,0,26)