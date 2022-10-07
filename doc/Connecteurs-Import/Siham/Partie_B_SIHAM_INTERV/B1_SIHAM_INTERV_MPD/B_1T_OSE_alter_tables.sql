/* ====================================================================================================
	B_1T_OSE_alter_tables.sql
	
	# Detail du connecteur PARTIE B/ SIHAM_INTERV : les intervenants existant dans SIHAM - Avec user OSE
   
	EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>

	ALTER TABLES : à executer POUR LES UNIVERSITES AYANT DEJA INSTALLE le Connecteur_SIHAM_OSE_v1.1_2019-12
	
	-- V2.1 16/07/21 - MYP : suppression fonctions inutiles
	-- V2.2 27/08/21 - MAS/MYP : CODE EMPLOYEUR PROVENANT D'OREC
	-- V3.0 20/06/22 - MYP : creation table UM_STATUT annualisée + remplissage à partir de UM_STATUT_INTERVENANT
=====================================================================================================*/


/* === V2.1 16/07/21 - MYP : suppression fonctions inutiles =========================================================*/

-- le 18/05/2021 : complément suite à install sur OSEPREP2 
drop function OSE.UM_CALCULE_DATE_STATUT;  			--: fc V14 supprimée et regroupée dans UM_AFFECTE_STATUT
drop function OSE.UM_AFFICH_INTERV_STATUT; 			--: fc V14 supprimée car info mce dans T_UM_ENREG_STATUT
drop function OSE.UM_RECUP_INTERV_NB_HEURE_MCE; 	--: fc V14 supprimée car info mce dans T_UM_ENREG_STATUT


/* === V2.2 27/08/21 - MAS/MYP : CODE EMPLOYEUR PROVENANT D'OREC =========================================================*/
alter table OSE.UM_INTERVENANT add EMP_SOURCE_CODE VARCHAR2(100 CHAR);
alter table OSE.UM_TRANSFERT_INDIVIDU add EMP_SOURCE_CODE VARCHAR2(100 CHAR);
-- 27/08/21 : OSEPREP2 + 05/10/21 OSEPREP


/* === V3.0 06/22 - MYP : pour OSE V18  =========================================================================*/

-- remonter le sexe M/F pour ensuit déterminer la civilité
alter TABLE OSE.UM_TRANSFERT_INDIVIDU ADD SEXE varchar2(1 CHAR);
-- OSETEST le 30/05/22

/*------------- UM_STATUT v3.0 creation -----------------------*/
CREATE TABLE OSE.UM_STATUT
(
  ID						NUMBER(9) default 0,
  ANNEE_ID					NUMBER(9) not null,
  CODE_STATUT				VARCHAR2(20  CHAR),
  LIBELLE					VARCHAR2(128  CHAR),
  SERVICE_STATUTAIRE		FLOAT,	
  DEPASSEMENT				NUMBER(1) default 0,	
  PLAFOND_REFERENTIEL		FLOAT,	
  MAXIMUM_HETD				FLOAT default 9999,	
  FONCTION_E_C				NUMBER(1),	
  TYPE_INTERVENANT_ID		NUMBER(9),	
  SOURCE_ID					NUMBER(9),
  SOURCE_CODE				VARCHAR2(100  CHAR),
  ORDRE						NUMBER(2),	
  NON_AUTORISE				NUMBER(1),	
  PEUT_SAISIR_SERVICE		NUMBER(1),	
  PEUT_CHOISIR_DANS_DOSSIER	NUMBER(1),	
  PEUT_SAISIR_DOSSIER		NUMBER(1),	
  PEUT_SAISIR_MOTIF_NON_PAIEMENT	NUMBER(1),
  PEUT_AVOIR_CONTRAT		NUMBER(1),	
  PEUT_SAISIR_REFERENTIEL	NUMBER(1),	
  PLAFOND_HC_HORS_REMU_FC	FLOAT,	
  PLAFOND_HC_REMU_FC		FLOAT,	
  DEPASSEMENT_SERVICE_DU_SANS_HC	NUMBER(1),
  PEUT_CLOTURER_SAISIE		NUMBER(1),	
  TEM_BIATSS				NUMBER(1),	
  PEUT_SAISIR_SERVICE_EXT	NUMBER(1),	
  TEM_ATV					NUMBER(1),
  PROSE_LIB_STATUT			VARCHAR2(20  CHAR),
  CONSTRAINT PK_ID_UM_STATUT PRIMARY KEY (ID),
  CONSTRAINT UK_UM_STATUT_CODE  UNIQUE (ANNEE_ID,CODE_STATUT)
);

CREATE SEQUENCE OSE.SEQ_ID_UM_STATUT START WITH 1 INCREMENT BY 1 CACHE 10;

CREATE OR REPLACE TRIGGER  OSE.TRG_ID_UM_STATUT
BEFORE INSERT ON OSE.UM_STATUT
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_UM_STATUT.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

-- ?????????????????????????????????????????????????
-- ATTENTION CI-DESSOUS ACTIONS MANUELLES   -- ##A_PERSONNALISER_CHOIX_SIHAM##
-- ?????????????????????????????????????????????????
-- repérage nb de statut à  dupliquer 
select count(*)
from um_statut_intervenant
-- 31

-- repérage des années d'alimentation des intervenants
select annee_id, count(*)
from um_intervenant
group by annee_id
order by 1
/*
2018	6807
2019	6503
2020	6517
2021	6307
2022	1885
*/
------------------------------------------------------------------------------------
-- Lancement dédoublage des statuts pour chaque année renseigner l'annee debut et fin
------------------------------------------------------------------------------------
DECLARE
	v_annee_debut 	number(9) 	:= 2018;  -- ##A_PERSONNALISER_CHOIX_SIHAM##
	v_annee_fin 	number(9)	:= 2022;  -- ##A_PERSONNALISER_CHOIX_SIHAM##
	v_annee_id 		number(9)	:= v_annee_debut;
	
BEGIN
    if v_annee_debut <= v_annee_fin then

		for v_annee_id in v_annee_debut .. v_annee_fin loop
			dbms_output.put_line('      => Creation statuts pour : '||v_annee_id);
			OSE.UM_INSERT_UM_STATUT(v_annee_id);
		end loop;
	end if;
	
END;
/

-- verification des statuts generes dans UM_STATUT
select annee_id, count(*)
from um_statut
group by annee_id;

/*
2018	31
2019	31
2020	31
2021	31
2022	31
*/

--------------------------------------------------------------------------
-- Reaffectation des STATUT_ID pour les UM_INTERVENANT dejà existants
--------------------------------------------------------------------------

create table ose.sav_um_intervenant as select * from ose.um_intervenant;

--- verif du nb d'intervenant par annee et code_statut : AVANT
select i.annee_id, i.statut_id, s.code_statut, count(*)
from ose.um_intervenant i
  , ose.um_statut_intervenant s
where i.statut_id = s.id
group by i.annee_id, i.statut_id, s.code_statut
order by i.annee_id, i.statut_id, s.code_statut;

-- Reaffectation des STATUT_ID 

DECLARE

CURSOR cur_interv is 
	select i.id, i.annee_id, i.statut_id, st.code_statut
		, i.date_deb_statut, i.date_fin_statut, i.date_horodatage_statut, i.w_nb_heure_mce
		,i.w_statut_pip
		, i.grade_id
		, i.source_code, i.nom_usuel, i.prenom, i.email_pro
	from um_intervenant i,
		um_statut_intervenant st
	where i.statut_id = st.id(+)
;

BEGIN
	FOR c1 in cur_interv LOOP
		BEGIN
			update OSE.UM_INTERVENANT 
			SET statut_id = (select id from OSE.UM_STATUT 
							where annee_id = c1.annee_id and code_statut = c1.code_statut)
			WHERE id = c1.id;
		EXCEPTION
			when others then
				rollback;
				dbms_output.put_line('   !!! Pb update UM_INTERVENANT pour affecter STATUT_ID : ');
		END;
	
	END LOOP;
	COMMIT;    
END;
/

--- verif du nb d'intervenant par annee et code_statut : APRES
select i.annee_id, i.statut_id, s.code_statut, count(*)
from ose.um_intervenant i
  , ose.um_statut s
where i.statut_id = s.id
group by i.annee_id, i.statut_id, s.code_statut
order by i.annee_id, i.statut_id, s.code_statut;

-- si c'est ok alors on renomme l ancienne table
alter table um_statut_intervenant rename to sav_um_statut_intervenant;
commit;



--------------------------------------------------------------------------
-- Reaffectation des STATUT_ID pour les changements de statut déjà détectés 
-- et historiques dans UM_SYNCHRO_A_VALIDER
--------------------------------------------------------------------------

create table ose.sav_UM_SYNCHRO_A_VALIDER as select * from ose.UM_SYNCHRO_A_VALIDER;

--- verif du nb d'intervenant par annee et code_statut : AVANT
select v.annee_id, v.actu_statut_id, v.actu_code_statut, s1.code_statut, v.new_statut_id, v.new_code_statut, s2.code_statut, count(*)
from ose.UM_SYNCHRO_A_VALIDER v
  , ose.sav_um_statut_intervenant s1
  , ose.sav_um_statut_intervenant s2
where v.actu_statut_id = s1.id
	and v.new_statut_id = s2.id
group by v.annee_id, v.actu_statut_id, v.actu_code_statut, s1.code_statut, v.new_statut_id, v.new_code_statut, s2.code_statut
order by v.annee_id, v.actu_statut_id, v.actu_code_statut, s1.code_statut, v.new_statut_id, v.new_code_statut, s2.code_statut;

-- Reaffectation des STATUT_ID 
DECLARE

CURSOR cur_synchro is 
	select v.id, v.annee_id, v.actu_statut_id, v.actu_code_statut, s1.code_statut as code_statut1, v.new_statut_id, v.new_code_statut, s2.code_statut as code_statut2
	from ose.UM_SYNCHRO_A_VALIDER v
	  , ose.sav_um_statut_intervenant s1
	  , ose.sav_um_statut_intervenant s2
	where v.actu_statut_id = s1.id
		and v.new_statut_id = s2.id
;

BEGIN
	FOR c1 in cur_synchro LOOP
		BEGIN
			update OSE.UM_SYNCHRO_A_VALIDER 
			SET actu_statut_id = (select id from OSE.UM_STATUT 
							where annee_id = c1.annee_id and code_statut = c1.actu_code_statut)
				, new_statut_id = (select id from OSE.UM_STATUT 
							where annee_id = c1.annee_id and code_statut = c1.new_code_statut)
			WHERE id = c1.id;
		EXCEPTION
			when others then
				rollback;
				dbms_output.put_line('   !!! Pb update UM_INTERVENANT pour affecter STATUT_ID : ');
		END;
	
	END LOOP;
	COMMIT;    
END;
/

--- verif du nb d'intervenant par annee et code_statut : APRES
select v.annee_id, v.actu_statut_id, v.actu_code_statut, s1.code_statut, v.new_statut_id, v.new_code_statut, s2.code_statut, count(*)
from ose.UM_SYNCHRO_A_VALIDER v
  , ose.um_statut s1
  , ose.um_statut s2
where v.actu_statut_id = s1.id
	and v.new_statut_id = s2.id
group by v.annee_id, v.actu_statut_id, v.actu_code_statut, s1.code_statut, v.new_statut_id, v.new_code_statut, s2.code_statut
order by v.annee_id, v.actu_statut_id, v.actu_code_statut, s1.code_statut, v.new_statut_id, v.new_code_statut, s2.code_statut;


--------------------------- fin alter tables -------------------------------------------------