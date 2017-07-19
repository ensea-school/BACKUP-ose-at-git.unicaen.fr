SELECT
  'SELECT * FROM ' || t.table_name || ' WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = ''' || i.code || ''');'
FROM
  all_tab_cols c 
  JOIN all_tables t ON t.table_name = c.table_name AND t.owner = c.owner
  JOIN (SELECT 119531 code FROM dual) i ON 1=1
WHERE 
  c.COLUMN_NAME = 'INTERVENANT_ID' 
  AND t.table_name NOT LIKE 'TBL_%'
  AND t.table_name NOT LIKE 'TMP_CALCUL'
ORDER BY 
  t.table_name;
  


select id, annee_id, histo_destruction from intervenant where source_code = 119531 order by annee_id;
select id, annee_id, histo_destruction from intervenant where source_code = 3657 order by annee_id;




SELECT * FROM DOSSIER WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = '119531');
SELECT * FROM FORMULE_RESULTAT WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = '119531');
SELECT * FROM INDIC_MODIF_DOSSIER WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = '119531');
SELECT * FROM PIECE_JOINTE WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = '119531');
SELECT * FROM SERVICE WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = '119531');
SELECT * FROM SERVICE_REFERENTIEL WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = '119531');
SELECT * FROM VALIDATION WHERE intervenant_id IN (SELECT id FROM intervenant where source_code = '119531');

delete from intervenant where id = 10413;


update VALIDATION set intervenant_id = 15334 WHERE intervenant_id = 10413;

/
alter trigger "OSE"."SERVICE_HISTO_CK" enable;
/
alter trigger "OSE"."SERVICE_HISTO_CK" disable;
/

SELECT
  mep.*
FROM
  v_mep_intervenant_structure mis
  JOIN MISE_EN_PAIEMENT mep on mep.id = mis.MISE_EN_PAIEMENT_ID
WHERE
  intervenant_id IN (SELECT id FROM intervenant where source_code = 3657);
  
  
  delete from MISE_EN_PAIEMENT WHERE id = 49041;