--
-- CREATION ET INITIALISATION DES TABLES POUR IMPORT Actul - OSE
--
--

-- Connecteur ODF


create table act_etape (
  annee_id                       varchar2(4 char)             not null,
  libelle                        varchar2(120 char)           not null,
  libelle_court                  varchar2(25 char)            not null,
  z_type_formation_id            varchar2(20 char)            not null,
  niveau                         number(2),
  z_structure_id                 varchar2(20 char)            ,
  cmp_apo						 varchar2(3 char),
  source_code                    varchar2(20 char)            not null,
  specifique_echanges            number(1)    default  0 	  not null,
  domaine_fonctionnel            varchar2(20 char)            not null,
  fi                             number(1)    default  1 	  not null,
  fa                             number(1)    default  0 	  not null,
  fc                             number(1)    default  0 	  not null,
  cod_etp                        varchar2(6 char)             not null,
  cod_vrs_vet                    number(3)               	  not null,
  id                             varchar2(20 char)            not null,
  tem_dn						 number,
  statut_actul					 varchar2(32 char),
  tem_exported					 varchar2(1  char),
  parcours_type_apo				 varchar2(32 char),
  prem_sem						 varchar2(15 char),
  cod_cge						 varchar2(6 char),
  droit_bourse					 varchar2(10 char),
  libelle_web					 varchar2(120 char),
  collegium						 varchar2(6 char),
  duree_etape					 varchar2(10 char),
  vdi_vet_annee_min				 number,
  vdi_vet_annee_max				 number,
  check (specifique_echanges in (0, 1)),
  check (fi + fa + fc > 0),
  constraint  pk_act_etp primary key  ( id)
  )
;



create table act_chemin_pedagogique (
  annee_id                       varchar2(4 char)             not null,
  z_element_pedagogique_id       varchar2(20 char)            not null,
  z_etape_id                     varchar2(20 char)            not null,
  source_code                    varchar2(40 char)            not null,
  z_periode_id                   varchar2(20 char),
  taux_foad                      number(1)    default  0 	  not null,
  id                             varchar2(40 char)            not null,
  tem_elp_comm					 varchar2(1 char),
  tem_vet_porteuse				 varchar2(1 char),
  tem_exported					 varchar2(1  char),
  check (z_periode_id in ('S1', 'S2')),
  check (taux_foad in (0, 1)),
  constraint pk_act_chp primary key ( id)
  )
;



create table act_element_pedagogique (
  annee_id                       varchar2(4   char)            not null,
  libelle                        varchar2(120 char)            not null,
  z_etape_id                     varchar2(20  char)            not null,
  z_structure_id                 varchar2(20  char)            not null,
  cmp_apo						 varchar2(3   char),
  z_periode_id                   varchar2(20  char),
  taux_foad                      number(1)    default  0 	   not null,
  source_code                    varchar2(20  char)            not null,
  z_discipline_id                varchar2(4   char),
  fi                             number(1)    default  0 	   not null,
  fa                             number(1)    default  0 	   not null,
  fc                             number(1)    default  0 	   not null,
  id                             varchar2(20  char)            not null,
  ects							 number,
  check (z_periode_id in ('S1', 'S2')),
  check (taux_foad in (0, 1)),
  check (fi in (0, 1)),
  check (fa in (0, 1)),
  check (fc in (0, 1)),
  constraint pk_act_elp primary key ( id)
  )
;



--
-- Effectifs par annee par element pedagogique et par regime d inscription
--
create table act_element_effectifs (
  z_element_pedagogique_id       varchar2(20 char)            not null,
  annee_id                       varchar2(4  char)             not null,
  effectif_fi                    number(5)    default  0 not null,
  effectif_fa                    number(5)    default  0 not null,
  effectif_fc                    number(5)    default  0 not null,
	  constraint pk_act_elp_eff primary key ( z_element_pedagogique_id, annee_id)
  )
;





create table act_odf_relations (
  annee_id    				number,
  noeud_sup_id				varchar2(20  char),
  noeud_sup_exported		varchar2(1   char),
  structure_sup_id			varchar2(20  char),
  comp_sup_id				varchar2(3   char),
  choix_minimum				number(2),
  choix_maximum				number(2),
  liste_id					varchar2(20  char),
  lse_exported				varchar2(1   char),
  lien_lse_exported 		varchar2(1   char),
  type_choix				varchar2(1   char),
  libelle_liste				varchar2(120 char),
  libelle_court_liste		varchar2(25  char),
  noeud_inf_id				varchar2(20  char),
  noeud_inf_exported		varchar2(20  char),
  structure_inf_id			varchar2(20  char),
  comp_inf_id				varchar2(3   char),
  libelle					varchar2(120 char),
  libelle_court				varchar2(25  char),
  nature					varchar2(20   char),
  cod_pel					varchar2(20   char),
  tem_a_dis_elp				varchar2(20   char),
  ects						number,
  constraint act_odf_rel_uk       unique    ( annee_id, noeud_inf_id, liste_id, noeud_sup_id)
  )
;





--
-- Table recursive de l offre de formation
--

create table act_offre_de_formation (
  annee_id                       varchar2(4   char)             not null,
  noeud_sup_id                   varchar2(20  char),
  noeud_sup_exported			 varchar2(1   char),
  structure_sup_id               varchar2(20  char),
  comp_sup_id					 varchar2(3   char),
  choix_minimum                  number(2),
  choix_maximum                  number(2),
  liste_id                       varchar2(20  char),
  lse_exported					 varchar2(1   char),
  lien_lse_exported 			 varchar2(1   char),
  libelle_liste                  varchar2(120 char),
  libelle_court_liste            varchar2(25  char),
  noeud_inf_id                   varchar2(20  char)            not null,
  noeud_inf_exported			 varchar2(1   char),
  structure_inf_id               varchar2(20  char),
  comp_inf_id					 varchar2(3   char),
  libelle                        varchar2(120 char)           not null,
  libelle_court                  varchar2(25  char)            not null,
  nature                         varchar2(20  char)            not null,
  periode                        varchar2(20  char),
  taux_foad                      number(1)     default 0 not null,
  ects							 number,
--  type_choix   					 varchar2(10),
  check (periode   in ('S1', 'S2')),
  check (taux_foad in (0, 1)),
  constraint act_odf_uk       unique      ( annee_id, noeud_inf_id, liste_id, noeud_sup_id)
  )
;




create table act_volume_horaire_ens (
  annee_id                       varchar2(4 char)             not null,
  z_element_pedagogique_id       varchar2(20 char)            not null,
  z_type_intervention_id         varchar2(20 char)            not null,
  heures                         number(6,2),
  source_code                    varchar2(20 char)            not null,
  id                             varchar2(20 char)            not null,
  groupes                        number(6),
  constraint act_vhe_id primary key ( id)
  )
;




-- Utiles pour l'automation de l'alimentation de scenaris

create table act_etape_effectifs (
  annee_id    				varchar2(4 char) not null,
  etape_id                  varchar2( 20 char),
  element_pedagogique_id	varchar2( 20 char),
  act_etape_porteuse_id     varchar2( 20 char),
  eff_etp_fi                number,
  eff_etp_fa                number,
  eff_etp_fc                number,
  constraint act_etp_eff unique ( annee_id,etape_id,element_pedagogique_id )
  )
;




create table act_vet_effectifs (
  annee_id    				varchar2(4 char) not null,
  etape_id                  varchar2( 20 char),
  etp_source_code			varchar2( 50 char ),
  eff_etp_fi                number,
  eff_etp_fa                number,
  eff_etp_fc                number,
  constraint act_vet_eff unique ( annee_id,etape_id )
  )
;




-- Autres tables

create table act_diplome (
  annee_id                       varchar2(4 char)             not null,
  libelle                        varchar2(120 char)           ,
  libelle_court                  varchar2(25 char),
  z_type_diplome_id            	 varchar2(20 char),
  mention_accredite				 varchar2 (5 char),
  z_structure_id                 varchar2(20 char)            ,
  cmp_apo						 varchar2(3 char),
  source_code                    varchar2(20 char)            not null,
  collegium			             varchar2(6 char) 	  		  ,
  secteur_sise		             varchar2(4 char)             ,
  domaine_formation				 varchar2(4 char),
  tem_exported					 varchar2(1  char),
  libelle_vdi                    varchar2(120 char)           ,
  libelle_court_vdi              varchar2(25 char),
  tem_exported_vdi				 varchar2(1  char),
  constraint  pk_act_dip primary key  ( source_code, annee_id)
  )
;





create table act_resp_diplome (
  annee_id                       varchar2(4 char)             not null,
  libelle                        varchar2(120 char)           ,
  source_code                    varchar2(50 char)            not null,
  usr_nom						 varchar2(120 char),
  usr_prenom                     varchar2(120 char) ,
  usr_login						 varchar2(12 char),
  constraint  pk_act_rdip primary key  ( source_code, annee_id, usr_login)
  )
;



create table act_resp_vdi (
  annee_id                       varchar2(4 char)             not null,
  libelle                        varchar2(120 char)           ,
  source_code                    varchar2(50 char)            not null,
  usr_nom						 varchar2(120 char),
  usr_prenom                     varchar2(120 char) ,
  usr_login						 varchar2(12 char),
  constraint  pk_act_rvdi primary key  ( source_code, annee_id, usr_login)
  )
;



create table act_resp_etp (
  annee_id                       varchar2(4 char)             not null,
  libelle                        varchar2(120 char)           ,
  source_code                    varchar2(50 char)            not null,
  usr_nom						 varchar2(120 char),
  usr_prenom                     varchar2(120 char) ,
  usr_login						 varchar2(12 char),
  constraint  pk_act_retp primary key  ( source_code, annee_id, usr_login)
  )
;




create table act_resp_vet (
  annee_id                       varchar2(4 char)             not null,
  libelle                        varchar2(120 char)           ,
  source_code                    varchar2(50 char)            not null,
  usr_nom						 varchar2(120 char),
  usr_prenom                     varchar2(120 char) ,
  usr_login						 varchar2(12 char),
  constraint  pk_act_rvet primary key  ( source_code, annee_id, usr_login)
  )
;





create table act_vdi_vet (
  annee_id                       varchar2(4 char)             not null,
  cod_vdi				         varchar2(20 char)            not null,
  cod_vet			             varchar2(20 char)            not null,
  constraint pk_act_vdi_vet primary key (annee_id, cod_vdi, cod_vet)

  )
;



create table act_element_commun (
  annee_id                       varchar2(4 char)             not null,
  z_element_pedagogique_id       varchar2(20 char)            not null,
  z_etape_porteuse_id            varchar2(20 char)            not null,
  nel							 varchar2(20 char),
  nb_choix						 number,
  tem_exported					 varchar2(1  char),
  tem_lse_exported				 varchar2(1  char),
  tem_lien_lse_exported			 varchar2(1  char),
  constraint pk_act_elp_comm primary key (annee_id, z_element_pedagogique_id)
  )
;




create table act_arbre_odf (
  annee_id                       varchar2(4  char)    not null,
  etape_sup						 varchar2(20  char)  ,
  etp_sup_libelle				 varchar2(120 char)  ,
  comp_apo_sup				 	 varchar2(3   char)  ,
  structure_sup					 VARCHAR2(10  char)	 ,
  elp_sup_libelle				 varchar2(120 char)  ,
  elp_sup_code					 varchar2(20  char)  ,
  elp_sup_nel					 varchar2(20  char)  ,
  elp_sup_order					 number,
  elp_sup_nb_choix				 number,
  elp_sup_exported				 varchar2(1   char)  ,
  vet_sup_eff					 number,
  etape_inf						 varchar2(20  char)  ,
  etp_inf_libelle				 varchar2(120 char)  ,
  comp_apo_inf					 VARCHAR2(3   char)	 ,
  structure_inf					 VARCHAR2(10  char)	 ,
  elp_inf_libelle				 varchar2(120 char)  ,
  elp_inf_code                   varchar2(20  char)  ,
  elp_inf_nel                    varchar2(20  char)  ,
  elp_inf_order                  number,
  elp_inf_nb_choix               number,
  tem_elp_comm					 varchar2(1   char)  ,
  elp_inf_exported				 varchar2(1   char)  ,
  etape_p						 varchar2(20  char)  ,
  code_elp_ref					 varchar2(20  char)  ,
  lib_elp_ref					 varchar2(120 char)  ,
  eff_calc_inf					 number,
  eff_etp_inf					 number
--  constraint act_arb_uk primary key (id_ext_act, ....),
  )
;





create table act_lcc_apo (
  annee_id    				varchar2(4 char) not null,
  cod_elp                   varchar2( 8 char),
  libelle					varchar2( 160 char ),
  cod_elp_s1_lcc			varchar2( 8 char),
  cod_elp_s2_lcc            varchar2( 8 char),
  daa_deb_val_lcc			varchar2( 4 char),
  daa_fin_val_lcc           varchar2( 4 char),
  com_lien_lcc              varchar2( 200 char),
  constraint act_lcc_apo unique ( annee_id, cod_elp, cod_elp_s1_lcc, cod_elp_s2_lcc )
  )
;
