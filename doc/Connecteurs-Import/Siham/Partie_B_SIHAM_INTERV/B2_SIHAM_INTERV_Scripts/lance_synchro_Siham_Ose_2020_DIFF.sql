set heading off
set pagesize 0
set feedback off
set echo off
set verify off
set linesize 1000
set trimspool on
set serveroutput on
spool trace_sql_synchro_Siham_Ose.lst append;
--spool C:\temp\Toad_exports\trace_sql_synchro_Siham_Ose.lstt;
--select '---------- '||name from v$database;
select rpad('_____________ Debut synchro_Siham_ose sql :'||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'),106,'_') from dual;

set serveroutput on size unlimited

DECLARE
/* ------------------------------------------------------------------------------------------ 
   PARTIE B/ SIHAM_REF : synchro des INTERVENANTS
   
   SIHAM => OSE synchro en insert ou update  dans UM_INTERVENANT
  -------------------------------------------------------------------------------------------  
*/ 
-- VARIABLES A PARAMETRER ----------------------------
v_annee_id              number(9)		:= 2020;  				-- ANNEE_ID de OSE : param manuellement et alim auto de v_d_deb_annee_univ v_d_fin_annee_univ
v_date_systeme          date			:= trunc(sysdate);  	-- date d'observation (par défaut sysdate, mais on peut générer tous les ACTIFS pour une date passée)
																-- !!  Pour que les SYNCHRO fonctionnent IL FAUT QUE  v_d_deb_annee_univ <= v_date_systeme <= v_d_fin_annee_univ.

v_type_transfert        varchar2(10)    := 'DIFF';          	-- Valeurs 'MANUEL'/'DIFF'/'ACTIFS'
v_date_profondeur		date			:= trunc(sysdate-1); 	-- dans le cas de DIFF : synchronise les dossiers modifiés dans Siham depuis cette date

/* ------------------------------------------------------------------------------------------
!! BIEN REGLER LES VARIABLES CI-DESSUS : cf. détails Doc MOP_SYNCHRO_OSE_RH_v1.3_L.docx

	MANUEL: 	Mode manuel = que les dossiers à forcer insérés manuellement dans UM_TRANSFERT_FORCE (ne tient pas compte de v_date_profondeur)
	
	DIFF : 	Différentiel journalier pour les dossiers modifiés dans Siham du v_date_profondeur au v_date_systeme
			OU Forcés (UM_TRANSFERT_FORCE) OU dossiers OREC							
				Par ex : diff depuis la veille : 2020/trunc(sysdate)/'DIFF'/trunc(sysdate-1)							
						 diff depuis 15 jours  : 2020/trunc(sysdate)/'DIFF'/trunc(sysdate-15)	
		
    ACTIFS : tous les ACTIFS à la date v_date_systeme (ne tient pas compte de v_date_profondeur)
				Par ex : tous les actifs à ce jour : 2020/trunc(sysdate)/'ACTIFS'/trunc(sysdate) 
						 tous les actifs au 02/10/2020 : 2020/trunc(to_date('02/10/2020','DD/MM/YYYY'))/'ROSE'/'ACTIFS'/trunc(sysdate)
------------------------------------------------------------------------------------------ */

-- VARIABLES DE TRAITEMENT ----------------------------
v_d_deb_annee_univ            	date := to_date('01/09/'||to_char(v_annee_id),'DD/MM/YYYY');
v_d_fin_annee_univ            	date := to_date('31/08/'||to_char(v_annee_id+1),'DD/MM/YYYY');
v_source_id                   	number(2) := 0;
v_nb_dossier_traites          	number(9) := 0;
v_nb_multi_statut_insert_auto 	number(9) := 0;
  
BEGIN
  -- Pour obliger à avoir une v_date_systeme dans l'année synchronisée
  IF v_date_systeme < v_d_deb_annee_univ THEN v_date_systeme := v_d_deb_annee_univ;
  ELSE
	IF v_date_systeme > v_d_fin_annee_univ THEN v_date_systeme := v_d_fin_annee_univ;
	END IF;
  END IF;

  IF v_type_transfert = 'DIFF' and v_date_systeme < v_date_profondeur THEN
    dbms_output.put_line('Lancement DIFF impossible, la date de profondeur doit etre <= a la date systeme ');
  ELSE
	select id INTO v_source_id from OSE.SOURCE where code = 'Siham'; 	-- ##A_PERSONNALISER_CHOIX_OSE## suivant votre declaration de connecteur

	dbms_output.put_line('_____');
	dbms_output.put_line(rpad('_____ ETAPE 0 : PURGE traces derniere synchro (UM_TRANSFERT_INDIVIDUS) :'||v_annee_id,106,'_'));
	UM_PURGE_UM_TRANSFERT_INDIVIDU(v_annee_id);
	
	select count(*) INTO v_nb_dossier_traites from UM_TRANSFERT_INDIVIDU where annee_id = v_annee_id;
	dbms_output.put_line('   Nb d''enreg restant sur '||v_annee_id||' : '||v_nb_dossier_traites);  -- v2.1
	
    /*==========================  SELECTION DOSSIER INTERVENANT =========================================*/
	dbms_output.put_line('_____');
	dbms_output.put_line(rpad('_____ ETAPE 1 : SELECTION dossiers (UM_TRANSFERT_INDIVIDUS) pour annee : '||to_char(v_d_deb_annee_univ,'DD/MM/YYYY')||' - '||to_char(v_d_fin_annee_univ,'DD/MM/YYYY'),106,'_'));
	UM_SELECT_INTERVENANT(v_annee_id, v_d_deb_annee_univ, v_d_fin_annee_univ, v_type_transfert, v_date_profondeur, v_date_systeme);
    dbms_output.put_line('      => Apres SELECT intervenant : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
	
	/*========================== MAJ table OSE.UM_INTERVENANT (INSERT 1ere fois ou UPDATE) ==============*/
	dbms_output.put_line('_____');
	dbms_output.put_line(rpad('_____ ETAPE 2 : MAJ (UM_INTERVENANT) pour annee : '||to_char(v_d_deb_annee_univ,'DD/MM/YYYY')||' - '||to_char(v_d_fin_annee_univ,'DD/MM/YYYY'),106,'_'));
    UM_INSERT_INTERVENANT(v_source_id, v_annee_id, v_d_deb_annee_univ, v_d_fin_annee_univ, v_date_systeme);
	dbms_output.put_line('      => Apres INSERT intervenant : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
	
	/*========================== Traitement DES INSERT AUTO MULTI STATUT ================================*/
	select count(*) INTO v_nb_multi_statut_insert_auto from UM_TRANSFERT_INDIVIDU where annee_id = v_annee_id and tem_ose_update = 'A_INS';
	if v_nb_multi_statut_insert_auto <> 0 then 
		dbms_output.put_line('_____');
		dbms_output.put_line(rpad('_____ ETAPE 2_COMPL : Traitement des multi statut auto : '||v_nb_multi_statut_insert_auto||' pour annee : '||to_char(v_d_deb_annee_univ,'YYYY')||' - '||to_char(v_d_fin_annee_univ,'YYYY'),106,'_'));
		UM_SELECT_MULTI_STATUT(v_annee_id);
		UM_INSERT_INTERVENANT(v_source_id, v_annee_id, v_d_deb_annee_univ, v_d_fin_annee_univ, v_date_systeme);
		dbms_output.put_line('      => Apres INSERT intervenant Multi-Statut : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
	end if;
	/*===================================================================================================*/
	dbms_output.put_line('_____');
	dbms_output.put_line(rpad('_____ ETAPE 3 : MAJ ADRESSES (UM_INTERVENANT_ADRESSE) : ',106,'_'));
	UM_SYNCHRO_ADRESSE_INTERVENANT(v_source_id, v_annee_id, trunc(sysdate));  -- Synchro ADR pour les INTERVENANTS venant d être synchronisés
	dbms_output.put_line('      => Apres SYNCHRO ADR intervenant : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'));
	
  END IF;
  
END;
/
select rpad('_____________ Fin : '||to_char(sysdate,'DD/MM/YYYY HH24:MI:SS'),106,'_') from dual;
spool off;
