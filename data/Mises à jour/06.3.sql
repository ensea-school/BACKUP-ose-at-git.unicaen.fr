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

UPDATE import_tables SET ordre = 1 WHERE table_name = 'PAYS';
UPDATE import_tables SET ordre = 2 WHERE table_name = 'DEPARTEMENT';
UPDATE import_tables SET ordre = 3 WHERE table_name = 'ETABLISSEMENT';
UPDATE import_tables SET ordre = 4 WHERE table_name = 'STRUCTURE';
UPDATE import_tables SET ordre = 5 WHERE table_name = 'ADRESSE_STRUCTURE';
UPDATE import_tables SET ordre = 6 WHERE table_name = 'DOMAINE_FONCTIONNEL';
UPDATE import_tables SET ordre = 7 WHERE table_name = 'CENTRE_COUT';
UPDATE import_tables SET ordre = 8 WHERE table_name = 'CENTRE_COUT_STRUCTURE';
UPDATE import_tables SET ordre = 9 WHERE table_name = 'AFFECTATION';
UPDATE import_tables SET ordre = 10 WHERE table_name = 'CORPS';
UPDATE import_tables SET ordre = 11 WHERE table_name = 'GRADE';
UPDATE import_tables SET ordre = 12 WHERE table_name = 'INTERVENANT';
UPDATE import_tables SET ordre = 13 WHERE table_name = 'AFFECTATION_RECHERCHE';
UPDATE import_tables SET ordre = 14 WHERE table_name = 'ADRESSE_INTERVENANT';
UPDATE import_tables SET ordre = 15 WHERE table_name = 'GROUPE_TYPE_FORMATION';
UPDATE import_tables SET ordre = 16 WHERE table_name = 'TYPE_FORMATION';
UPDATE import_tables SET ordre = 17 WHERE table_name = 'ETAPE';
UPDATE import_tables SET ordre = 18 WHERE table_name = 'ELEMENT_PEDAGOGIQUE';
UPDATE import_tables SET ordre = 19 WHERE table_name = 'EFFECTIFS';
UPDATE import_tables SET ordre = 20 WHERE table_name = 'ELEMENT_TAUX_REGIMES';
UPDATE import_tables SET ordre = 21 WHERE table_name = 'CHEMIN_PEDAGOGIQUE';
UPDATE import_tables SET ordre = 22 WHERE table_name = 'VOLUME_HORAIRE_ENS';
UPDATE import_tables SET ordre = 23 WHERE table_name = 'NOEUD';
UPDATE import_tables SET ordre = 24 WHERE table_name = 'LIEN';
UPDATE import_tables SET ordre = 25 WHERE table_name = 'SCENARIO_LIEN';
UPDATE import_tables SET ordre = 26 WHERE table_name = 'TYPE_INTERVENTION_EP';
UPDATE import_tables SET ordre = 27 WHERE table_name = 'TYPE_MODULATEUR_EP';

UPDATE import_tables SET sync_job = 'synchro' WHERE table_name IN (
'PAYS','DEPARTEMENT','ETABLISSEMENT','STRUCTURE','ADRESSE_STRUCTURE','DOMAINE_FONCTIONNEL','CENTRE_COUT',
'CENTRE_COUT_STRUCTURE','AFFECTATION','CORPS','GRADE','INTERVENANT','AFFECTATION_RECHERCHE',
'ADRESSE_INTERVENANT','GROUPE_TYPE_FORMATION','TYPE_FORMATION','ETAPE','ELEMENT_PEDAGOGIQUE','EFFECTIFS',
'CHEMIN_PEDAGOGIQUE','VOLUME_HORAIRE_ENS','NOEUD','LIEN','SCENARIO_LIEN','TYPE_INTERVENTION_EP',
'TYPE_MODULATEUR_EP');

UPDATE IMPORT_TABLES SET SYNC_HOOK_BEFORE = 'UNICAEN_IMPORT.REFRESH_MV(''MV_AFFECTATION'');
/* Import automatique des users des nouveaux directeurs */
INSERT INTO utilisateur (
  id, display_name, email, password, state, username
)
SELECT
  utilisateur_id_seq.nextval id,
  aff.*
FROM
  (SELECT DISTINCT display_name, email, password, state, username FROM mv_affectation) aff
WHERE
  username not in (select username from utilisateur);' WHERE table_name = 'AFFECTATION';

UPDATE IMPORT_TABLES SET SYNC_FILTRE = 'WHERE (
    IMPORT_ACTION IN (''delete'',''update'',''undelete'')
    OR STATUT_ID IN (
        SELECT si.id
        FROM statut_intervenant si
        JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
        WHERE ti.code = ''P''
    )
)', SYNC_HOOK_BEFORE = 'UNICAEN_IMPORT.REFRESH_MV(''MV_UNICAEN_STRUCTURE_CODES'');
UNICAEN_IMPORT.REFRESH_MV(''MV_INTERVENANT'');' WHERE table_name = 'INTERVENANT';

UPDATE IMPORT_TABLES SET SYNC_FILTRE = 'WHERE INTERVENANT_ID IS NOT NULL' WHERE table_name IN ('AFFECTATION_RECHERCHE','ADRESSE_INTERVENANT');

UPDATE IMPORT_TABLES SET SYNC_FILTRE = 'WHERE IMPORT_ACTION IN (''delete'',''insert'',''undelete'')' WHERE table_name = 'ELEMENT_TAUX_REGIMES';

UPDATE IMPORT_TABLES SET SYNC_HOOK_AFTER = 'UNICAEN_IMPORT.REFRESH_MV(''TBL_NOEUD'');
UNICAEN_TBL.CALCULER(''chargens'');' WHERE table_name = 'NOEUD';


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

/

create or replace PACKAGE UNICAEN_IMPORT AS

  z__SYNC_FILRE__z CLOB DEFAULT '';
  z__IGNORE_UPD_COLS__z CLOB DEFAULT '';

  PROCEDURE set_current_user(p_current_user IN INTEGER);
  FUNCTION get_current_user return INTEGER;

  FUNCTION get_current_annee RETURN INTEGER;
  PROCEDURE set_current_annee (p_current_annee INTEGER);

  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC;
  PROCEDURE REFRESH_MV( mview_name varchar2 );
  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL );

  PROCEDURE SYNCHRONISATION( table_name VARCHAR2, SYNC_FILRE CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '' );



END UNICAEN_IMPORT;

/

create or replace PACKAGE BODY UNICAEN_IMPORT AS

  v_current_user INTEGER;
  v_current_annee INTEGER;



  FUNCTION get_current_user RETURN INTEGER IS
  BEGIN
    IF v_current_user IS NULL THEN
      v_current_user := OSE_PARAMETRE.GET_OSE_USER();
    END IF;
    RETURN v_current_user;
  END get_current_user;

  PROCEDURE set_current_user (p_current_user INTEGER) is
  BEGIN
    v_current_user := p_current_user;
  END set_current_user;



  FUNCTION get_current_annee RETURN INTEGER IS
  BEGIN
    IF v_current_annee IS NULL THEN
      v_current_annee := OSE_PARAMETRE.GET_ANNEE_IMPORT();
    END IF;
    RETURN v_current_annee;
  END get_current_annee;

  PROCEDURE set_current_annee (p_current_annee INTEGER) IS
  BEGIN
    v_current_annee := p_current_annee;
  END set_current_annee;



  PROCEDURE SYNCHRONISATION( table_name VARCHAR2, SYNC_FILRE CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '' ) IS
    ok NUMERIC(1);
  BEGIN
    SELECT COUNT(*) INTO ok FROM import_tables it WHERE it.table_name = SYNCHRONISATION.table_name AND it.sync_enabled = 1 AND rownum = 1;

    IF 1 = ok THEN
      z__SYNC_FILRE__z      := SYNCHRONISATION.SYNC_FILRE;
      z__IGNORE_UPD_COLS__z := SYNCHRONISATION.IGNORE_UPD_COLS;
      EXECUTE IMMEDIATE 'BEGIN UNICAEN_IMPORT_AUTOGEN_PROCS__.' || table_name || '(); END;';
    END IF;
  END;



  PROCEDURE REFRESH_MV( mview_name varchar2 ) IS
  BEGIN
    DBMS_MVIEW.REFRESH(mview_name, 'C');
  EXCEPTION WHEN OTHERS THEN
    SYNC_LOG( SQLERRM, mview_name );
  END;



  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL ) IS
  BEGIN
    INSERT INTO SYNC_LOG("ID","DATE_SYNC","MESSAGE","TABLE_NAME","SOURCE_CODE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message,table_name,source_code);
  END SYNC_LOG;



  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;

END UNICAEN_IMPORT;