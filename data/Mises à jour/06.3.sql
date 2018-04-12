-- Script de migration de la version 6.2.2 à 6.3

ALTER TABLE effectifs MODIFY (
  element_pedagogique_id NOT NULL
);

DROP VIEW V_TYPE_INTERVENTION_REGLE_EP;

ALTER TABLE import_tables ADD (
  ordre   NUMBER
);
ALTER TABLE import_tables ADD (
  sync_job   VARCHAR2(40 CHAR)
);
ALTER TABLE import_tables ADD (
  sync_hook_before   VARCHAR2(4000 CHAR)
);
ALTER TABLE import_tables ADD (
  sync_hook_after   VARCHAR2(4000 CHAR)
);


CREATE OR REPLACE FORCE VIEW "V_IMPORT_TAB_COLS" ("TABLE_NAME", "COLUMN_NAME", "DATA_TYPE", "LENGTH", "NULLABLE", "HAS_DEFAULT", "C_TABLE_NAME", "C_COLUMN_NAME", "IMPORT_ACTIF") AS
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
  it.ordre, tc.table_name, tc.column_id;


/
-- Suppression du JOB OSE_SRC_SYNC => Synchro effectuée maintenant par CRON
BEGIN
    DBMS_SCHEDULER.DROP_JOB(job_name => '"OSE"."OSE_SRC_SYNC"',
                                defer => false,
                                force => true);
END;
/
-- Suppression du JOB OSE_CHARGENS_CALCUL_EFFECTIFS
BEGIN
    DBMS_SCHEDULER.DROP_JOB(job_name => '"OSE"."OSE_CHARGENS_CALCUL_EFFECTIFS"',
                                defer => false,
                                force => true);
END;
/
-- Suppression du JOB OSE_FORMULE_REFRESH
BEGIN
    DBMS_SCHEDULER.DROP_JOB(job_name => '"OSE"."OSE_FORMULE_REFRESH"',
                                defer => false,
                                force => true);
END;
/
-- Suppression du JOB OSE_WF_REFRESH
BEGIN
    DBMS_SCHEDULER.DROP_JOB(job_name => '"OSE"."OSE_WF_REFRESH"',
                                defer => false,
                                force => true);
END;
/

drop package "OSE"."OSE_IMPORT";
drop trigger "OSE"."TYPE_INTERVENTION_STRUCTURE_CK";

/

-- ajout de nouveaux privilèges
INSERT INTO PRIVILEGE (
  ID,
  CATEGORIE_ID,
  CODE,
  LIBELLE,
  ORDRE
)
SELECT
  privilege_id_seq.nextval id,
  (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c ) CATEGORIE_ID,
  t1.p CODE,
  t1.l LIBELLE,
  (SELECT count(*) FROM PRIVILEGE WHERE categorie_id = (SELECT id FROM CATEGORIE_PRIVILEGE WHERE code = t1.c )) + rownum ORDRE
FROM (
  SELECT 'import' c, 'tables-visualisation' p, 'Tables (visualisation)' l FROM dual
  UNION SELECT 'import' c, 'tables-edition' p, 'Tables (édition)' l FROM dual
) t1;

-- association des nouveaux privilèges aux administrateurs
INSERT INTO role_privilege (
  role_id, privilege_id
) VALUES (
  (SELECT id FROM role where code
    = 'administrateur'
  ),(
  SELECT p.id FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE cp.code || '/' || p.code
    = 'import/tables-visualisation'
  )
);

INSERT INTO role_privilege (
  role_id, privilege_id
) VALUES (
  (SELECT id FROM role where code
    = 'administrateur'
  ),(
  SELECT p.id FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE cp.code || '/' || p.code
    = 'import/tables-edition'
  )
);