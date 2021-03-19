set heading off
set pagesize 0
set feedback off
set echo off
set verify off
set linesize 1000
set trimspool on
set serveroutput on
spool trace_sql_synchro_Siham_Ose.lst;
--spool C:\temp\Toad_exports\OSETEST_trace_synchro_Siham_Ose_sql.lst;

set serveroutput on size unlimited

DECLARE
/* ------------------------------------------------------------------------------------------ 
   PARTIE A/ SIHAM_REF : synchro de tables de référentiel
   
   SIHAM => OSE synchro en insert ou update (sauf OSE.ADRESSE_NUMERO_COMPL : que insert)
  
------------------------------------------------------------------------------------------ */
 
-- VARIABLES A PARAMETRER ----------------------------
v_date_systeme				date		:= trunc(sysdate);  -- date d'observation (par défaut sysdate, mais on peut générer tous les ACTIFS pour une date passée)
v_source_id					number(2) 	:= 0;				-- id correspondant à Siham dans OSE.SOURCE
  
BEGIN
	dbms_output.put_line(rpad('================ Synchro tables du referentiel : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'),106,'_'));
	select id INTO v_source_id from OSE.SOURCE where code = 'Siham';		-- ##A_PERSONNALISER_CHOIX_OSE## suivant votre declaration de connecteur
    
	dbms_output.put_line('_____');
	-- SYNCHRO DES TABLES DE REFERENTIEL 	
	UM_SYNCHRO_PAYS(v_source_id);
    UM_SYNCHRO_DEPARTEMENT();
	
	UM_SYNCHRO_VOIRIE(v_source_id);
	-- 1ER lancement V15 : activer UM_ALIM_ADRESSE_NUMERO_COMPL avant synchro structure : 
	-- !! synchro en insert seulement des adresse_numero_compl de Siham en plus de ceux livres, directement dans table OSE de Caen (pas de table intermédiaire)
	-- ensuite vous pouvez le désactiver sauf si Siham créé des nouveaux codes
	UM_ALIM_ADRESSE_NUMERO_COMPL();
	
	UM_SYNCHRO_STRUCTURE(v_source_id, v_date_systeme);
	UM_SYNCHRO_GRADE(v_source_id, v_date_systeme);			-- synchro tables UM_CORPS + UM_GRADE
	dbms_output.put_line('_____');
END;
/
select rpad('---------- Fin : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS')||' --------------',106,'_') from dual;
spool off;
