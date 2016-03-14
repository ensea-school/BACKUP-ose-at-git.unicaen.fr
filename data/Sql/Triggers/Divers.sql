
/* Requêtes utiles ... */
SELECT 
  'DROP TRIGGER ' || trigger_name || ';' drop_sql
  --'ALTER TRIGGER ' || trigger_name || ' DISABLE;' disable_sql
  --'ALTER TRIGGER ' || trigger_name || ' ENABLE;' enable_sql
FROM SYS.ALL_TRIGGERS WHERE owner = 'OSE' AND trigger_name like 'T_AGR_%' ORDER BY trigger_name;



/



-- Génération automatique des triggers de table
select 

'CREATE OR REPLACE TRIGGER ' || trigger_name || '_S
AFTER INSERT OR UPDATE OR DELETE ON ' || table_name || '
BEGIN
  OSE_CONTRAT.CALCULER_SUR_DEMANDE;
END;

/
' isql

from all_triggers
WHERE
  owner = 'OSE' 
  AND trigger_name like 'T_CRT_%' 
  AND trigger_type = 'AFTER EACH ROW'
ORDER BY trigger_name;


