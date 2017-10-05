select q'[o'connor]' from dual;

SELECT TRIM(TO_CHAR(100, '9999999.99')) FROM DUAL;


select 
annee_id, annee_debut_id, annee_fin_id, res res_attendu,
case when


annee_id BETWEEN GREATEST(NVL(annee_debut_id,0),annee_id) AND LEAST(NVL(annee_fin_id,9999),annee_id)


then 1 else 0 end res_calcule from (

          SELECT 2014 annee_id, null annee_debut_id, null annee_fin_id, 1 res FROM dual

UNION ALL SELECT 2014 annee_id, 2014 annee_debut_id, 2014 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, null annee_debut_id, 2014 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, 2014 annee_debut_id, null annee_fin_id, 1 res FROM dual

UNION ALL SELECT 2014 annee_id, 2012 annee_debut_id, 2015 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, null annee_debut_id, 2015 annee_fin_id, 1 res FROM dual
UNION ALL SELECT 2014 annee_id, 2012 annee_debut_id, null annee_fin_id, 1 res FROM dual

UNION ALL SELECT 2014 annee_id, 2015 annee_debut_id, 2017 annee_fin_id, 0 res FROM dual
UNION ALL SELECT 2014 annee_id, 2015 annee_debut_id, null annee_fin_id, 0 res FROM dual

UNION ALL SELECT 2014 annee_id, 2011 annee_debut_id, 2013 annee_fin_id, 0 res FROM dual
UNION ALL SELECT 2014 annee_id, null annee_debut_id, 2013 annee_fin_id, 0 res FROM dual
          
) t1;

select
  to_date('31/01/2015', 'dd/mm/YYYY')  + 1
  from dual;

select
  to_char( sysdate, 'dd/mm/YYYY' )
  from dual;
  
  
  
/* Création des indexs de clé étrangères */
SELECT 
  a.table_name, 
  a.columns fk_columns, 
  b.columns index_columns,
  
  'CREATE INDEX ' || a.table_name || '_' || a.columns || 'X ON ' || a.table_name || ' (' || a.columns || ' ASC);' isql

  
 FROM (SELECT a.table_name,
 a.constraint_name,
 LISTAGG(a.column_name, ',') within GROUP(ORDER BY a.position) columns
 FROM all_cons_columns a,
 all_constraints b
 WHERE a.constraint_name = b.constraint_name
 AND b.constraint_type = 'R'
 AND a.owner = b.owner AND a.owner='OSE'
 GROUP BY a.table_name, a.constraint_name) a,
 (SELECT table_name,
 index_name,
 LISTAGG(c.column_name, ',') within GROUP(ORDER BY c.column_position) columns
 FROM all_ind_columns c
 GROUP BY table_name, index_name) b
 WHERE a.table_name = b.table_name(+)  AND b.columns(+) LIKE a.columns || '%'
 AND b.table_name IS null;
 
 
              
              
              
              
              
              
              
              
              
              
              
              
              


  PROCEDURE C_CHARGENS( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'TRUNCATE TABLE t_tbl_chargens';
    EXECUTE IMMEDIATE 'INSERT INTO t_tbl_chargens SELECT * FROM v_tbl_chargens WHERE ' || conds;
    EXECUTE IMMEDIATE 'UPDATE TBL_CHARGENS SET to_delete = 1 WHERE ' || conds;

    MERGE INTO 
      TBL_CHARGENS t 
    USING (

      SELECT 
        tv.* 
      FROM 
        t_TBL_CHARGENS tv

    ) v ON (
            t.SCENARIO_NOEUD_ID1         = v.SCENARIO_NOEUD_ID1
        AND t.NOEUD_ID                   = v.NOEUD_ID
        AND t.ETAPE_ENS_ID               = v.ETAPE_ENS_ID
        AND COALESCE(t.TBL_CHARGENS_SEUILS_DEF_ID,0) = COALESCE(v.TBL_CHARGENS_SEUILS_DEF_ID,0)
        AND t.GROUPE_TYPE_FORMATION_ID   = v.GROUPE_TYPE_FORMATION_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID1,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID1,0)
        AND t.SCENARIO_ID                = v.SCENARIO_ID
        AND COALESCE(t.SCENARIO_NOEUD_ID2,0) = COALESCE(v.SCENARIO_NOEUD_ID2,0)
        AND t.ETAPE_ID                   = v.ETAPE_ID
        AND t.TYPE_HEURES_ID             = v.TYPE_HEURES_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID     = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.VOLUME_HORAIRE_ENS_ID      = v.VOLUME_HORAIRE_ENS_ID
        AND t.TYPE_INTERVENTION_ID       = v.TYPE_INTERVENTION_ID
        AND t.ANNEE_ID                   = v.ANNEE_ID
        AND t.STRUCTURE_ID               = v.STRUCTURE_ID
        AND t.SCENARIO_NOEUD_EFFECTIF_ID = v.SCENARIO_NOEUD_EFFECTIF_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID2,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID2,0)

    ) WHEN MATCHED THEN UPDATE SET

      ASSIDUITE                  = v.ASSIDUITE,
      GROUPES                    = v.GROUPES,
      HETD                       = v.HETD,
      DEDOUBLEMENT               = v.DEDOUBLEMENT,
      EFFECTIF                   = v.EFFECTIF,
      HEURES                     = v.HEURES,
      OUVERTURE                  = v.OUVERTURE,
      HEURES_ENS                 = v.HEURES_ENS,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      SCENARIO_NOEUD_ID1,
      NOEUD_ID,
      ETAPE_ENS_ID,
      TBL_CHARGENS_SEUILS_DEF_ID,
      ASSIDUITE,
      GROUPE_TYPE_FORMATION_ID,
      SCENARIO_NOEUD_SEUIL_ID1,
      SCENARIO_ID,
      SCENARIO_NOEUD_ID2,
      ETAPE_ID,
      GROUPES,
      TYPE_HEURES_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      HETD,
      VOLUME_HORAIRE_ENS_ID,
      TYPE_INTERVENTION_ID,
      DEDOUBLEMENT,
      EFFECTIF,
      ANNEE_ID,
      STRUCTURE_ID,
      SCENARIO_NOEUD_EFFECTIF_ID,
      HEURES,
      OUVERTURE,
      HEURES_ENS,
      SCENARIO_NOEUD_SEUIL_ID2,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_ID_SEQ.NEXTVAL,
      v.SCENARIO_NOEUD_ID1,
      v.NOEUD_ID,
      v.ETAPE_ENS_ID,
      v.TBL_CHARGENS_SEUILS_DEF_ID,
      v.ASSIDUITE,
      v.GROUPE_TYPE_FORMATION_ID,
      v.SCENARIO_NOEUD_SEUIL_ID1,
      v.SCENARIO_ID,
      v.SCENARIO_NOEUD_ID2,
      v.ETAPE_ID,
      v.GROUPES,
      v.TYPE_HEURES_ID,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.HETD,
      v.VOLUME_HORAIRE_ENS_ID,
      v.TYPE_INTERVENTION_ID,
      v.DEDOUBLEMENT,
      v.EFFECTIF,
      v.ANNEE_ID,
      v.STRUCTURE_ID,
      v.SCENARIO_NOEUD_EFFECTIF_ID,
      v.HEURES,
      v.OUVERTURE,
      v.HEURES_ENS,
      v.SCENARIO_NOEUD_SEUIL_ID2,
      0

    );

    EXECUTE IMMEDIATE 'DELETE TBL_CHARGENS WHERE to_delete = 1 AND ' || conds;
  END;




  PROCEDURE C_CHARGENS2( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    p    TBL_CHARGENS%rowtype;
    pcol VARCHAR2(30);
    upcol VARCHAR2(30);
    sql_query CLOB;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
    action varchar2(15);
    t tbl_chargens%ROWTYPE;
    
  BEGIN
    conds := params_to_conds( params );
ose_test.horoinit();
    EXECUTE IMMEDIATE 'TRUNCATE TABLE t_tbl_chargens';
ose_test.horodatage('delete t_tbl');
    EXECUTE IMMEDIATE 'INSERT INTO t_tbl_chargens SELECT * FROM v_tbl_chargens WHERE ' || conds;
ose_test.horodatage('populate t_tbl');
    EXECUTE IMMEDIATE 'DELETE FROM tbl_chargens WHERE id IN (WITH t AS (
      SELECT * FROM tbl_chargens WHERE ' || conds || '
    )
    SELECT
      t.id id
    FROM
      t
      FULL JOIN t_tbl_chargens v ON 
            t.SCENARIO_NOEUD_ID1         = v.SCENARIO_NOEUD_ID1
        AND t.NOEUD_ID                   = v.NOEUD_ID
        AND t.ETAPE_ENS_ID               = v.ETAPE_ENS_ID
        AND COALESCE(t.TBL_CHARGENS_SEUILS_DEF_ID,0) = COALESCE(v.TBL_CHARGENS_SEUILS_DEF_ID,0)
        AND t.GROUPE_TYPE_FORMATION_ID   = v.GROUPE_TYPE_FORMATION_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID1,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID1,0)
        AND t.SCENARIO_ID                = v.SCENARIO_ID
        AND COALESCE(t.SCENARIO_NOEUD_ID2,0) = COALESCE(v.SCENARIO_NOEUD_ID2,0)
        AND t.ETAPE_ID                   = v.ETAPE_ID
        AND t.TYPE_HEURES_ID             = v.TYPE_HEURES_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID     = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.VOLUME_HORAIRE_ENS_ID      = v.VOLUME_HORAIRE_ENS_ID
        AND t.TYPE_INTERVENTION_ID       = v.TYPE_INTERVENTION_ID
        AND t.ANNEE_ID                   = v.ANNEE_ID
        AND t.STRUCTURE_ID               = v.STRUCTURE_ID
        AND t.SCENARIO_NOEUD_EFFECTIF_ID = v.SCENARIO_NOEUD_EFFECTIF_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID2,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID2,0)
    WHERE
      v.rowid IS NULL
    )';
ose_test.horodatage('delete');
    MERGE INTO 
      TBL_CHARGENS t 
    USING (

      SELECT 
        tv.* 
      FROM 
        t_TBL_CHARGENS tv

    ) v ON (
            t.SCENARIO_NOEUD_ID1         = v.SCENARIO_NOEUD_ID1
        AND t.NOEUD_ID                   = v.NOEUD_ID
        AND t.ETAPE_ENS_ID               = v.ETAPE_ENS_ID
        AND COALESCE(t.TBL_CHARGENS_SEUILS_DEF_ID,0) = COALESCE(v.TBL_CHARGENS_SEUILS_DEF_ID,0)
        AND t.GROUPE_TYPE_FORMATION_ID   = v.GROUPE_TYPE_FORMATION_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID1,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID1,0)
        AND t.SCENARIO_ID                = v.SCENARIO_ID
        AND COALESCE(t.SCENARIO_NOEUD_ID2,0) = COALESCE(v.SCENARIO_NOEUD_ID2,0)
        AND t.ETAPE_ID                   = v.ETAPE_ID
        AND t.TYPE_HEURES_ID             = v.TYPE_HEURES_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID     = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.VOLUME_HORAIRE_ENS_ID      = v.VOLUME_HORAIRE_ENS_ID
        AND t.TYPE_INTERVENTION_ID       = v.TYPE_INTERVENTION_ID
        AND t.ANNEE_ID                   = v.ANNEE_ID
        AND t.STRUCTURE_ID               = v.STRUCTURE_ID
        AND t.SCENARIO_NOEUD_EFFECTIF_ID = v.SCENARIO_NOEUD_EFFECTIF_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID2,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID2,0)
  
    ) WHEN MATCHED THEN UPDATE SET

      ASSIDUITE                  = v.ASSIDUITE,
      GROUPES                    = v.GROUPES,
      HETD                       = v.HETD,
      DEDOUBLEMENT               = v.DEDOUBLEMENT,
      EFFECTIF                   = v.EFFECTIF,
      HEURES                     = v.HEURES,
      OUVERTURE                  = v.OUVERTURE,
      HEURES_ENS                 = v.HEURES_ENS,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      SCENARIO_NOEUD_ID1,
      NOEUD_ID,
      ETAPE_ENS_ID,
      TBL_CHARGENS_SEUILS_DEF_ID,
      ASSIDUITE,
      GROUPE_TYPE_FORMATION_ID,
      SCENARIO_NOEUD_SEUIL_ID1,
      SCENARIO_ID,
      SCENARIO_NOEUD_ID2,
      ETAPE_ID,
      GROUPES,
      TYPE_HEURES_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      HETD,
      VOLUME_HORAIRE_ENS_ID,
      TYPE_INTERVENTION_ID,
      DEDOUBLEMENT,
      EFFECTIF,
      ANNEE_ID,
      STRUCTURE_ID,
      SCENARIO_NOEUD_EFFECTIF_ID,
      HEURES,
      OUVERTURE,
      HEURES_ENS,
      SCENARIO_NOEUD_SEUIL_ID2,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_ID_SEQ.NEXTVAL,
      v.SCENARIO_NOEUD_ID1,
      v.NOEUD_ID,
      v.ETAPE_ENS_ID,
      v.TBL_CHARGENS_SEUILS_DEF_ID,
      v.ASSIDUITE,
      v.GROUPE_TYPE_FORMATION_ID,
      v.SCENARIO_NOEUD_SEUIL_ID1,
      v.SCENARIO_ID,
      v.SCENARIO_NOEUD_ID2,
      v.ETAPE_ID,
      v.GROUPES,
      v.TYPE_HEURES_ID,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.HETD,
      v.VOLUME_HORAIRE_ENS_ID,
      v.TYPE_INTERVENTION_ID,
      v.DEDOUBLEMENT,
      v.EFFECTIF,
      v.ANNEE_ID,
      v.STRUCTURE_ID,
      v.SCENARIO_NOEUD_EFFECTIF_ID,
      v.HEURES,
      v.OUVERTURE,
      v.HEURES_ENS,
      v.SCENARIO_NOEUD_SEUIL_ID2,
      0

    );
ose_test.horodatage('merge');
  END;
  
  
  
  
  
  PROCEDURE C_CHARGENS3( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;
    sql_query CLOB;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
    t tbl_chargens%ROWTYPE;
    rowcount NUMERIC;
  BEGIN
    conds := params_to_conds( params );
ose_test.horoinit;
    EXECUTE IMMEDIATE 'TRUNCATE TABLE t_tbl_chargens';
ose_test.horodatage('delete t_tbl');
    EXECUTE IMMEDIATE 'INSERT INTO t_tbl_chargens SELECT * FROM v_tbl_chargens WHERE ' || conds;
ose_test.horodatage('insert t_tbl');
    SELECT count(*) INTO rowcount FROM t_tbl_chargens;

    sql_query := 'WITH t AS (
  SELECT * FROM tbl_chargens WHERE ' || conds || '
)
SELECT
  t.id id,
  v.*,
  CASE WHEN v.rowid IS NULL THEN 1 ELSE 0 END to_delete
FROM
  t
  FULL JOIN t_tbl_chargens v ON 
            t.SCENARIO_NOEUD_ID1         = v.SCENARIO_NOEUD_ID1
        AND t.NOEUD_ID                   = v.NOEUD_ID
        AND t.ETAPE_ENS_ID               = v.ETAPE_ENS_ID
        AND COALESCE(t.TBL_CHARGENS_SEUILS_DEF_ID,0) = COALESCE(v.TBL_CHARGENS_SEUILS_DEF_ID,0)
        AND t.GROUPE_TYPE_FORMATION_ID   = v.GROUPE_TYPE_FORMATION_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID1,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID1,0)
        AND t.SCENARIO_ID                = v.SCENARIO_ID
        AND COALESCE(t.SCENARIO_NOEUD_ID2,0) = COALESCE(v.SCENARIO_NOEUD_ID2,0)
        AND t.ETAPE_ID                   = v.ETAPE_ID
        AND t.TYPE_HEURES_ID             = v.TYPE_HEURES_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID     = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.VOLUME_HORAIRE_ENS_ID      = v.VOLUME_HORAIRE_ENS_ID
        AND t.TYPE_INTERVENTION_ID       = v.TYPE_INTERVENTION_ID
        AND t.ANNEE_ID                   = v.ANNEE_ID
        AND t.STRUCTURE_ID               = v.STRUCTURE_ID
        AND t.SCENARIO_NOEUD_EFFECTIF_ID = v.SCENARIO_NOEUD_EFFECTIF_ID
        AND COALESCE(t.SCENARIO_NOEUD_SEUIL_ID2,0) = COALESCE(v.SCENARIO_NOEUD_SEUIL_ID2,0)
WHERE
  v.rowid IS NULL 
  OR t.rowid IS NULL
  OR t.ouverture <> v.ouverture
  OR t.dedoublement <> v.dedoublement
  OR t.assiduite <> v.assiduite
  OR t.effectif <> v.effectif
  OR t.heures_ens <> v.heures_ens
  OR t.groupes <> v.groupes
  OR t.heures <> v.heures
  OR t.hetd <> v.hetd';

    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO t; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        IF t.id IS NULL THEN -- INSERT
            INSERT INTO tbl_chargens (
              ID,
              SCENARIO_NOEUD_ID1,
              NOEUD_ID,
              ETAPE_ENS_ID,
              TBL_CHARGENS_SEUILS_DEF_ID,
              ASSIDUITE,
              GROUPE_TYPE_FORMATION_ID,
              SCENARIO_NOEUD_SEUIL_ID1,
              SCENARIO_ID,
              SCENARIO_NOEUD_ID2,
              ETAPE_ID,
              GROUPES,
              TYPE_HEURES_ID,
              ELEMENT_PEDAGOGIQUE_ID,
              HETD,
              VOLUME_HORAIRE_ENS_ID,
              TYPE_INTERVENTION_ID,
              DEDOUBLEMENT,
              EFFECTIF,
              ANNEE_ID,
              STRUCTURE_ID,
              SCENARIO_NOEUD_EFFECTIF_ID,
              HEURES,
              OUVERTURE,
              HEURES_ENS,
              SCENARIO_NOEUD_SEUIL_ID2,
              TO_DELETE
            ) VALUES (
              TBL_CHARGENS_ID_SEQ.NEXTVAL,
              t.SCENARIO_NOEUD_ID1,
              t.NOEUD_ID,
              t.ETAPE_ENS_ID,
              t.TBL_CHARGENS_SEUILS_DEF_ID,
              t.ASSIDUITE,
              t.GROUPE_TYPE_FORMATION_ID,
              t.SCENARIO_NOEUD_SEUIL_ID1,
              t.SCENARIO_ID,
              t.SCENARIO_NOEUD_ID2,
              t.ETAPE_ID,
              t.GROUPES,
              t.TYPE_HEURES_ID,
              t.ELEMENT_PEDAGOGIQUE_ID,
              t.HETD,
              t.VOLUME_HORAIRE_ENS_ID,
              t.TYPE_INTERVENTION_ID,
              t.DEDOUBLEMENT,
              t.EFFECTIF,
              t.ANNEE_ID,
              t.STRUCTURE_ID,
              t.SCENARIO_NOEUD_EFFECTIF_ID,
              t.HEURES,
              t.OUVERTURE,
              t.HEURES_ENS,
              t.SCENARIO_NOEUD_SEUIL_ID2,
              0
            );

          ELSIF t.to_delete = 1 THEN -- DELETE
            DELETE FROM tbl_chargens WHERE id = t.id;

          ELSE -- update
            UPDATE tbl_chargens SET
              ASSIDUITE                  = t.ASSIDUITE,
              GROUPES                    = t.GROUPES,
              HETD                       = t.HETD,
              DEDOUBLEMENT               = t.DEDOUBLEMENT,
              EFFECTIF                   = t.EFFECTIF,
              HEURES                     = t.HEURES,
              OUVERTURE                  = t.OUVERTURE,
              HEURES_ENS                 = t.HEURES_ENS,
              to_delete = 0
            WHERE id = t.id;

        END IF;
      END;
    END LOOP;
    CLOSE diff_cur;
ose_test.horodatage('merge');
  END;                