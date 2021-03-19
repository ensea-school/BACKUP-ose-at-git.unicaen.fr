CREATE OR REPLACE FORCE VIEW V_IMPORT_TAB_COLS AS
WITH importable_tables (table_name )AS (
  SELECT
  t.table_name
FROM
  user_tab_cols c
  join user_tables t on t.table_name = c.table_name
WHERE
  c.column_name = 'SOURCE_CODE'

MINUS

SELECT
  mview_name table_name
FROM
  USER_MVIEWS
), c_values (table_name, column_name, c_table_name, c_column_name) AS (
SELECT
  tc.table_name,
  tc.column_name,
  pcc.table_name c_table_name,
  pcc.column_name c_column_name
FROM
  user_tab_cols tc
  JOIN USER_CONS_COLUMNS cc ON cc.table_name = tc.table_name AND cc.column_name = tc.column_name
  JOIN USER_CONSTRAINTS c ON c.constraint_name = cc.constraint_name
  JOIN USER_CONSTRAINTS pc ON pc.constraint_name = c.r_constraint_name
  JOIN USER_CONS_COLUMNS pcc ON pcc.constraint_name = pc.constraint_name
WHERE
  c.constraint_type = 'R' AND pc.constraint_type = 'P'
)
SELECT
  tc.table_name,
  tc.column_name,
  CASE WHEN ',' || it.key_columns || ',' LIKE '%,' || tc.column_name || ',%' THEN 1 ELSE 0 END is_key,
  tc.data_type,
  CASE WHEN tc.char_length = 0 THEN NULL ELSE tc.char_length END length,
  CASE WHEN tc.nullable = 'Y' THEN 1 ELSE 0 END nullable,
  CASE WHEN tc.data_default IS NOT NULL THEN 1 ELSE 0 END has_default,
  cv.c_table_name,
  cv.c_column_name,
  CASE WHEN stc.table_name IS NULL THEN 0 ELSE 1 END AS import_actif
FROM
  user_tab_cols tc
  JOIN importable_tables t ON t.table_name = tc.table_name
  LEFT JOIN import_tables it ON it.table_name = tc.table_name
  LEFT JOIN c_values cv ON cv.table_name = tc.table_name AND cv.column_name = tc.column_name
  LEFT JOIN user_tab_cols stc ON stc.table_name = 'SRC_' || tc.table_name AND stc.column_name = tc.column_name
WHERE
  tc.column_name not like 'HISTO_%'
  AND tc.column_name <> 'ID'
  AND tc.table_name <> 'SYNC_LOG'
ORDER BY
  it.ordre, tc.table_name, tc.column_id