/* ====================================================================================================
	B_1T_OSE_alter_tables.sql
	
	# Detail du connecteur PARTIE B/ SIHAM_INTERV : les intervenants existant dans SIHAM - Avec user OSE
   
	EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>

	ALTER TABLES : à executer POUR LES UNIVERSITES AYANT DEJA INSTALLE le Connecteur_SIHAM_OSE_v1.1_2019-12
	
=====================================================================================================*/

/* === V2.1 16/07/21 - MYP : suppression fonctions inutiles =========================================================*/

-- le 18/05/2021 : complément suite à install sur OSEPREP2 
drop function OSE.UM_CALCULE_DATE_STATUT;  			--: fc V14 supprimée et regroupée dans UM_AFFECTE_STATUT
drop function OSE.UM_AFFICH_INTERV_STATUT; 			--: fc V14 supprimée car info mce dans T_UM_ENREG_STATUT
drop function OSE.UM_RECUP_INTERV_NB_HEURE_MCE; 	--: fc V14 supprimée car info mce dans T_UM_ENREG_STATUT


/* === V2.0 12/2020 à 03/2021 - MYP : V15  =========================================================================*/

-- Infos INTERVENANTS -- OSETEST le 01/2021 -03/2021 -------------------------------------------------------------

alter table OSE.UM_INTERVENANT rename column VILLE_NAISSANCE_CODE_INSEE to LIBRE1;
-- raz car ce champs n est plus utile -- A FAIRE DANS 2EME TEMPS apres V15 fonctionnelle sans perte des data :
-- update OSE.UM_INTERVENANT set LIBRE1 = '';
alter table OSE.UM_INTERVENANT rename column EMAIL to EMAIL_PRO;
alter table OSE.UM_INTERVENANT rename column PREMIER_RECRUTEMENT to LIBRE2;
-- update OSE.UM_INTERVENANT set LIBRE2 = '';
commit;

alter table OSE.UM_INTERVENANT add RIB_HORS_SEPA 			NUMBER(1);
alter table OSE.UM_INTERVENANT add EMPLOYEUR_ID 			NUMBER(9);
alter table OSE.UM_INTERVENANT add DATE_DEB_STATUT			DATE;
alter table OSE.UM_INTERVENANT add DATE_FIN_STATUT			DATE;
alter table OSE.UM_INTERVENANT add DATE_HORODATAGE_STATUT	DATE;
update OSE.UM_INTERVENANT set RIB_HORS_SEPA = 0;
commit;

--- MULTI-STATUT : creation type d objet pour UM_AFFECTE_STATUT (une seule fonction affectant le statut, les dates de debut et de fin) ------
/*------------- TYPES_OBJETS ------------------------------*/
create or replace type T_UM_ENREG_STATUT is object
(
    ID 						NUMBER(9)			-- STATUT_ID
    ,CODE_STATUT 			VARCHAR2(20)		-- CODE_STATUT
	,CODE_TYPE_INTERVENANT	VARCHAR2(1)			-- CODE_TYPE_INTERVENANT P = PERM / E = IE
    ,DATE_DEB_STATUT		DATE				-- DATE debut statut
	,DATE_FIN_STATUT		DATE				-- DATE fin statut
	,NB_H_MCE				NUMBER(8,2)			-- Nombre d'heures avec Mission d'enseignement
);
/
--- MULTI-STATUT : modification de la cle unique -----------------------------------------
ALTER table OSE.UM_INTERVENANT DROP CONSTRAINT UK_UM_INTERV_SOURCE_CODE;
DROP INDEX UK_UM_INTERV_SOURCE_CODE;
commit;
ALTER table OSE.UM_INTERVENANT ADD CONSTRAINT UK_UM_INTERV_SOURCE_CODE UNIQUE (SOURCE_CODE, ANNEE_ID, STATUT_ID);  -- v2.0 + statut_id et source_code en 1er
-- OSETEST le 04/03/2021

--- MULTI-STATUT : initialisation passé et en cours du 01/09 au 31/12 AAAA+1 : un seul statut sur l'année  --------
update OSE.UM_INTERVENANT set DATE_DEB_STATUT = to_date('01/09/'||to_char(annee_id),'DD/MM/YYYY') where DATE_DEB_STATUT is null;
update OSE.UM_INTERVENANT set DATE_FIN_STATUT = to_date('31/08/'||to_char(annee_id+1),'DD/MM/YYYY') where DATE_FIN_STATUT is null;
update OSE.UM_INTERVENANT set DATE_HORODATAGE_STATUT = DATE_HORODATAGE;
commit;

-- + pour les adresses intervenants : -- OSETEST : le 26/01/2021---------------------------
ALTER TABLE OSE.UM_ADRESSE_INTERVENANT ADD NUMERO_COMPL_CODE	VARCHAR2(5 CHAR);
ALTER TABLE OSE.UM_ADRESSE_INTERVENANT ADD VOIRIE_CODE		VARCHAR2(5 CHAR);
update OSE.UM_ADRESSE_INTERVENANT set NUMERO_COMPL_CODE='',VOIRIE_CODE='';

--- UM_SYNCHRO_A_VALIDER : traces des changements de statut et multi statuts --------------
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD ACTU_STATUT_ID			NUMBER(9);		-- v2.0 09/03/21 stockage infos statut deja existant selon T_UM_ENREG_STATUT
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD ACTU_CODE_STATUT		VARCHAR2(20);
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD ACTU_CODE_TYPE_INT		VARCHAR2(1);
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD ACTU_DATE_DEB_STATUT	DATE;
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD ACTU_DATE_FIN_STATUT	DATE;
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD ACTU_NB_H_MCE			NUMBER(8,2);

ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD NEW_STATUT_ID			NUMBER(9);		-- v2.0 09/03/21 stockage infos statut nouveau proposé selon T_UM_ENREG_STATUT
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD NEW_CODE_STATUT		VARCHAR2(20);
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD NEW_CODE_TYPE_INT 		VARCHAR2(1);
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD NEW_DATE_DEB_STATUT	DATE;
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD NEW_DATE_FIN_STATUT	DATE;
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD NEW_NB_H_MCE			NUMBER(8,2);
ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER ADD PARAM_GESTION_STATUT	VARCHAR2(15 CHAR);

ALTER TABLE OSE.UM_SYNCHRO_A_VALIDER MODIFY TEM_VALIDATION 		VARCHAR2(2);	-- v2.0 09/03/21 valeurs possibles : '?'/'A'/'AI'/'N'/'O'/'I'  
/* '?' : à saisir manuellement parmis (si PARAM_GESTION_STATUT = 'UNIQUE_MANUEL' ou 'MULTI_MANUEL'):  
										 'N' : nouveau statut pas validé, on conserve le statut actuel sans modif
										 'O' : nouveau statut validé et écrase le statut actuel
										 'I' : nouveau statut validé et insert nouveau statut (donc multiple) avec adaptation dates de période
Remplit automatiquement par par programme :
										 'A'  : Auto unique et validation pour écraser le statut actuel (si PARAM_GESTION_STATUT = 'UNIQUE_AUTO')
										 'AI' : Auto multi statut - insertion (si PARAM_GESTION_STATUT = 'MULTI_AUTO')
*/
update OSE.UM_SYNCHRO_A_VALIDER set ACTU_DATE_DEB_STATUT 	= to_date('01/09/'||to_char(annee_id),'DD/MM/YYYY') where ACTU_DATE_DEB_STATUT is null;
update OSE.UM_SYNCHRO_A_VALIDER set NEW_DATE_DEB_STATUT 	= to_date('01/09/'||to_char(annee_id),'DD/MM/YYYY') where NEW_DATE_DEB_STATUT is null;
update OSE.UM_SYNCHRO_A_VALIDER set ACTU_DATE_FIN_STATUT 	= to_date('31/08/'||to_char(annee_id+1),'DD/MM/YYYY') where ACTU_DATE_FIN_STATUT is null;
update OSE.UM_SYNCHRO_A_VALIDER set NEW_DATE_FIN_STATUT 	= to_date('31/08/'||to_char(annee_id+1),'DD/MM/YYYY') where NEW_DATE_FIN_STATUT is null;
update OSE.UM_SYNCHRO_A_VALIDER set PARAM_GESTION_STATUT	= 'UNIQUE_MANUEL';	-- par defaut comme avant V15

update OSE.UM_SYNCHRO_A_VALIDER set changement_statut = replace(changement_statut, 'AUTO : ','') where changement_statut like 'AUTO :%';
commit;

--- Remplissage des nouvelles zones pour le passé : 
/*
select changement_statut as changement_statut
    --, instr(changement_statut,'(') as position1,  instr(changement_statut,')') as position2
    ,trim(substr(changement_statut,1, instr(changement_statut,'(')-1)) as actu_statut
    ,replace(substr(changement_statut, instr(changement_statut,'(')+1, 1),')','') as actu_code_type_int
  --  ,substr(changement_statut,instr(changement_statut,'->')+2) as new
    ,trim(substr(changement_statut,instr(changement_statut,'->')+2, instr(substr(changement_statut,instr(changement_statut,'->')+2),'(')-1 )) as new_statut
    --, substr(changement_statut, instr(changement_statut,'(')+1, 1) as new_code_type_int
    ,replace(substr( substr(changement_statut,instr(changement_statut,'->')+2),  instr( substr(changement_statut,instr(changement_statut,'->')+2),'(')+1,1),')','') as new_code_type_int
from OSE.UM_SYNCHRO_A_VALIDER;
*/
update OSE.UM_SYNCHRO_A_VALIDER s1 set s1.actu_code_statut = ( select trim(substr(s2.changement_statut,1, instr(s2.changement_statut,'(')-1)) from OSE.UM_SYNCHRO_A_VALIDER s2 where s2.id = s1.id); 
commit;
update OSE.UM_SYNCHRO_A_VALIDER s1 set s1.actu_code_type_int = ( select trim(replace(substr(s2.changement_statut, instr(s2.changement_statut,'(')+1, 1),')','')) from OSE.UM_SYNCHRO_A_VALIDER s2 where s2.id = s1.id); 
commit;
update OSE.UM_SYNCHRO_A_VALIDER s1 set s1.new_code_statut    = ( select trim(substr(s2.changement_statut,instr(s2.changement_statut,'->')+2, instr(substr(s2.changement_statut,instr(s2.changement_statut,'->')+2),'(')-1 )) from OSE.UM_SYNCHRO_A_VALIDER s2 where s2.id = s1.id); 
commit;
update OSE.UM_SYNCHRO_A_VALIDER s1 set s1.new_code_type_int  = ( select replace(substr( substr(s2.changement_statut,instr(s2.changement_statut,'->')+2),  instr( substr(s2.changement_statut,instr(s2.changement_statut,'->')+2),'(')+1,1),')','') from OSE.UM_SYNCHRO_A_VALIDER s2 where s2.id = s1.id); 
commit;

update OSE.UM_SYNCHRO_A_VALIDER s1 set s1.actu_statut_id = (select id from OSE.UM_STATUT_INTERVENANT where code_statut = s1.actu_code_statut);
commit;
update OSE.UM_SYNCHRO_A_VALIDER s2 set s2.new_statut_id = (select id from OSE.UM_STATUT_INTERVENANT where code_statut = s2.new_code_statut);
commit;
--------------------------- fin alter tables -------------------------------------------------