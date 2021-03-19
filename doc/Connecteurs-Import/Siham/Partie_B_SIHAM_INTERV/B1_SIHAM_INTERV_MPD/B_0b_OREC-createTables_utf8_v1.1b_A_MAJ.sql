/* ====================================================================================================
	# Detail du connecteur PARTIE B/ SIHAM_INTERV : synchro des intervenants - Avec user OSE
   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>
	
	TABLES AVEC INFOS COMPLEMENTAIRES VACATAIRES, INFOS NON SAISIES DANS SIHAM MAIS DANS OREC(appli locale de gestion des candidatures) 
			
	--- Tables -------------- 
	ose.um_orec_info
	ose.um_orec_categorie
	ose.um_orec_autre_affectation
	----------------------------
	
	-- v1.1- 16/11/2020 - MYP : même niveau que version Connecteur_SIHAM_OSE_v1.1_2019-12
=====================================================================================================*/


create table ose.um_orec_info
(
       annee_id                 number(9,0)
     , orec_id_candidat         number(11)
     , matricule                varchar2(12 char)
     , profession               varchar2(50 char)
     , employeur                varchar2(255 char)
     , ville_employeur          varchar2(255 char)
     , nom_population           varchar2(45 char)
     , intitule_corps           varchar2(155 char)
     , libelle                  varchar2(255 char)
     , intitule_categorie       varchar2(255 char)
     , description_categorie    varchar2(1000 char)
	 , recrutement				varchar2(50 char)
	 , type_vac					varchar2(3 char)
	 , type_emp					varchar2(5 char)
	 , tem_fonc					varchar2(3 char)
	 , code_uo 					varchar2(10 char)
     , service_rectorat			varchar2(255 char)
	, constraint pk_orec_info primary key (annee_id,matricule )
	, constraint fk_orec_annee foreign key (annee_id ) references ose.annee (id)
  );
  

 
create table ose.um_orec_categorie 
   (	libelle_long 	varchar2(40 char), 
		libelle_court 	varchar2(20 char), 
		source_code 	varchar2(100 char), 
		constraint pk_oreccat_source_code primary key (source_code)
   ) 
  tablespace data_ose ;

-- ##A_PERSONNALISER_CHOIX_OSE## le statut OSE sera affecté suivant ces catégories de vacataires
insert into ose.um_orec_categorie values ('Etudiant',	'Etudiant',	'ETU');
insert into ose.um_orec_categorie values ('Retraité',	'Retraité',	'RET');
insert into ose.um_orec_categorie values ('Agent titulaire autres Ets',	'Agt TIT autres Ets',	'FP_TIT_EXT');
insert into ose.um_orec_categorie values ('Agent NON titulaire autres Ets',	'Agt NTIT autres Ets',	'FP_CTR_EXT');
insert into ose.um_orec_categorie values ('Dirigeant d’entreprise',	'Dirig. d’entreprise',	'PDG');
insert into ose.um_orec_categorie values ('Doctorant contractuel UM',	'Doct. CTR UM',	'DOC_UM');						-- v1.1b MYP le 03/12/2020
insert into ose.um_orec_categorie values ('Doctorant contractuel autres Ets',	'Doct. CTR autres Ets',	'DOC_EXT');
insert into ose.um_orec_categorie values ('Agent titulaire BIATSS UM',	'Agt TIT BIATSS UM',	'BIATS_TIT_UM');    	-- v1.1 MYP le 01/03/2019 : enlevé un S à BIATSS
insert into ose.um_orec_categorie values ('Agent NON titulaire BIATSS UM',	'Agt NTIT BIATSS UM',	'BIATS_CTR_UM'); 	-- v1.1 MYP le 01/03/2019 : enlevé un S à BIATSS
insert into ose.um_orec_categorie values ('Ens NON tit ETS privé ss contrat',	'Ens NTIT ETS privCTR',	'ENS_PRIVE');
insert into ose.um_orec_categorie values ('Intermittent du spectacle',	'Intermittent spect',	'SPECT');
insert into ose.um_orec_categorie values ('Salarié du secteur privé',	'Salarié sect.  privé',	'EMP_PRIVE');
insert into ose.um_orec_categorie values ('Travailleur non salarié',	'Non salarié',	'AUTO');
insert into ose.um_orec_categorie values ('Convention',	'Convention',	'CONV');										-- v1.1 MYP le 01/03/2019 : ajout CONV

create table ose.um_orec_autre_affectation
(
       annee_id                 number(9,0)
     , matricule                varchar2(12 char)
	 , code_uo 					varchar2(10 char)
	, constraint pk_orec_aff primary key (annee_id, matricule, code_uo )
	, constraint fk_orec_annee_aff foreign key (annee_id ) references ose.annee (id)
  );