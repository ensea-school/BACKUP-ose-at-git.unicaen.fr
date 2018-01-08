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
 
 
              
              
              
              
-- Mise à jour des séquences             
SELECT
  'CREATE SEQUENCE ' || SUBSTR(TABLE_NAME,0,23) || '_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;'
FROM
  user_tables
  LEFT JOIN user_sequences ON (sequence_name = SUBSTR(TABLE_NAME,0,23) || '_ID_SEQ')
WHERE
  sequence_name IS NULL
  AND TABLE_NAME NOT LIKE 'MV_%'  -- pas de vue matérialisée
  AND TABLE_NAME NOT LIKE 'BCP_%' -- pas de table bcp (créées et non supprimées par ce ##ù"%" de DataModeler)
 
UNION
 
SELECT
  'DROP SEQUENCE OSE.' || sequence_name || ';'
FROM
  user_sequences
  LEFT JOIN user_tables ON (SUBSTR(TABLE_NAME,0,23) || '_ID_SEQ' = sequence_name)
WHERE
  TABLE_NAME IS NULL
;              
              
              
-- Gestion des séquences, informations de validité et des historiques
SELECT
 
-- Création de la séquence
'
CREATE SEQUENCE ' || SUBSTR(TABLE_NAME,0,23) || '_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;
' ||
 
-- Création des champs
'
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( validite_debut DATE ) ;
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( validite_fin DATE ) ;
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( histo_creation DATE ) ;
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( histo_createur_id NUMBER (*,0) ) ;
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( histo_modification DATE ) ;
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( histo_modificateur_id NUMBER (*,0) ) ;
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( histo_destruction DATE ) ;
ALTER TABLE OSE.' || TABLE_NAME || ' ADD ( histo_destructeur_id NUMBER (*,0) ) ;
' ||
 
 
 
-- Affectation des valeurs si des champs ont déjà été créés
'
UPDATE OSE.' || TABLE_NAME || ' SET VALIDITE_DEBUT = SYSDATE WHERE VALIDITE_DEBUT IS NULL;
UPDATE OSE.' || TABLE_NAME || ' SET HISTO_CREATION = SYSDATE WHERE HISTO_CREATION IS NULL;
UPDATE OSE.' || TABLE_NAME || ' SET HISTO_CREATEUR_ID = 1 WHERE HISTO_CREATEUR_ID IS NULL;
UPDATE OSE.' || TABLE_NAME || ' SET HISTO_MODIFICATION = SYSDATE WHERE HISTO_MODIFICATION IS NULL;
UPDATE OSE.' || TABLE_NAME || ' SET HISTO_MODIFICATEUR_ID = 1 WHERE HISTO_MODIFICATEUR_ID IS NULL;
' ||
 
 
 
-- Application des contraintes NOT NULL
'
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY (VALIDITE_DEBUT NOT NULL);
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY (HISTO_CREATION NOT NULL);
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY (HISTO_CREATEUR_ID NOT NULL);
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY (HISTO_MODIFICATION NOT NULL);
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY (HISTO_MODIFICATEUR_ID NOT NULL);
' ||
 
 
 
-- Création des valeurs par défaut
'
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY(VALIDITE_DEBUT DEFAULT SYSDATE);
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY(HISTO_CREATION DEFAULT SYSDATE);
ALTER TABLE OSE.' || TABLE_NAME || ' MODIFY(HISTO_MODIFICATION DEFAULT SYSDATE);
' ||
 
 
 
-- Création des clés étrangères
'
ALTER TABLE OSE.' || TABLE_NAME || ' ADD CONSTRAINT ' || SUBSTR(TABLE_NAME,0,25) || '_HCFK FOREIGN KEY (HISTO_CREATEUR_ID)     REFERENCES OSE.UTILISATEUR(ID);
ALTER TABLE OSE.' || TABLE_NAME || ' ADD CONSTRAINT ' || SUBSTR(TABLE_NAME,0,25) || '_HMFK FOREIGN KEY (HISTO_MODIFICATEUR_ID) REFERENCES OSE.UTILISATEUR(ID);
ALTER TABLE OSE.' || TABLE_NAME || ' ADD CONSTRAINT ' || SUBSTR(TABLE_NAME,0,25) || '_HDFK FOREIGN KEY (HISTO_DESTRUCTEUR_ID)  REFERENCES OSE.UTILISATEUR(ID);
'
 
FROM
  USER_TABLES
WHERE
  TABLE_NAME IN (SELECT TABLE_NAME FROM USER_TAB_COLS WHERE COLUMN_NAME = 'HISTO_CREATION') 
ORDER BY
  TABLE_NAME;
              
              
              
              

