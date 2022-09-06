/* ====================================================================================================
	A_1_T_OSE_create_table_utf8.sql
	# Detail du connecteur PARTIE A/ SIHAM_REF : des tables de référentiel - Avec user OSE
   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>
	
	CREATE TABLES : pour une 1ere installation

	--- tables ------------------ 
	OSE.UM_PAYS
	OSE.UM_DEPARTEMENT
	OSE.UM_STRUCTURE
	OSE.UM_ADRESSE_STRUCTURE
	OSE.UM_CORPS
	OSE.UM_GRADE
	OSE.UM_VOIRIE
	OSE.UM_PARAM_ETABL
	----------------------------
	
	-- v1.8 - 02/12/19 MYP : UM_STRUCTURE renommage tem_manu en eotp_DU_defaut + eotp_DN_defaut	
	-- v2.0 - 11/20-01/21 - MYP - V15 : + table UM_VOIRIE + UM_PARAM_ETABL + ajout champs ID dans adresses
	-- v2.1 - 28/05/21 MYP : retaillage zones adresse
	-- v2.2 - 20/07/22 MYP : passage champs C_ORG_RATTACH de la table UM_PARAM_ETABL en valeurs muliples
=====================================================================================================*/

/*------------- UM_PAYS -------------------------------------*/
CREATE TABLE OSE.UM_PAYS
(
  ID						NUMBER(9) default 0,
  LIBELLE_LONG				VARCHAR2(120  CHAR),
  LIBELLE_COURT				VARCHAR2(60  CHAR),
  TEMOIN_UE					NUMBER(1),	
  VALIDITE_DEBUT			DATE,	
  VALIDITE_FIN				DATE,	
  SOURCE_ID					NUMBER(9),	
  SOURCE_CODE				VARCHAR2(100  CHAR),
  CONSTRAINT PK_ID_UM_PAYS PRIMARY KEY (ID),
  CONSTRAINT UK_UM_PAYS_SOURCE_CODE UNIQUE (SOURCE_CODE)
);

CREATE SEQUENCE OSE.SEQ_ID_UM_PAYS START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_UM_PAYS 
BEFORE INSERT ON OSE.UM_PAYS
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_UM_PAYS.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*------------- UM_DEPARTEMENT ------------------------------*/
/* Maura ajout table um_departement  */

create table OSE.UM_DEPARTEMENT
   (	code varchar2(5 char), 
		libelle_long varchar2(120 char) not null enable, 
		libelle_court varchar2(60 char) not null enable, 
		source_id char(5 char), 
		source_code varchar2(100 char),
		constraint pk_id_dpt primary key (code)
   ) 
  tablespace data_ose ;


/*------------- UM_STRUCTURE --------------------------------*/
CREATE TABLE OSE.UM_STRUCTURE
(
  ID						NUMBER(9),	
  LIBELLE_LONG				VARCHAR2(60  CHAR),
  LIBELLE_COURT				VARCHAR2(25  CHAR),
  PARENTE_ID				NUMBER(9),	
  STRUCTURE_NIV2_ID			NUMBER(9),	
  TYPE_ID					NUMBER(1),	
  ETABLISSEMENT_ID			NUMBER(9),	
  NIVEAU					NUMBER(1),	
  SOURCE_ID					NUMBER(9),	
  SOURCE_CODE				VARCHAR2(100  CHAR),
  CONTACT_PJ				VARCHAR2(255  CHAR),
  AFF_ADRESSE_CONTRAT		NUMBER(1),	
  ENSEIGNEMENT				NUMBER(1),
  TEM_STRUCT_MANU			VARCHAR2(1	CHAR), 		-- v1.7
  EOTP_DU_DEFAUT			VARCHAR2(100	CHAR), 	-- v1.8
  EOTP_DN_DEFAUT			VARCHAR2(100	CHAR), 	-- v1.8
  CONSTRAINT PK_ID_UM_STRUCT PRIMARY KEY (ID),
  CONSTRAINT UK_UM_STRUCT_SOURCE_CODE UNIQUE (SOURCE_CODE)
);

CREATE SEQUENCE OSE.SEQ_ID_UM_STRUCT START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_UM_STRUCT
BEFORE INSERT ON OSE.UM_STRUCTURE
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_UM_STRUCT.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*------------- UM_ADRESSE_STRUCTURE ------------------------*/
CREATE TABLE OSE.UM_ADRESSE_STRUCTURE
(
  ID						NUMBER(9),	
  STRUCTURE_ID				NUMBER(9),
  PRINCIPALE				NUMBER(1),	
  TELEPHONE					VARCHAR2(20  CHAR),
  NO_VOIE					VARCHAR2(10  CHAR),
  NOM_VOIE					VARCHAR2(60  CHAR),
  LOCALITE					VARCHAR2(40  CHAR),			-- v2.1 - 28/05/21
  CODE_POSTAL				VARCHAR2(15  CHAR),
  VILLE						VARCHAR2(26  CHAR),
  PAYS_CODE_INSEE			VARCHAR2(3  CHAR),
  PAYS_LIBELLE				VARCHAR2(30  CHAR),
  SOURCE_ID					NUMBER(9),
  SOURCE_CODE				VARCHAR2(100  CHAR),
  NUMERO_COMPL_CODE			VARCHAR2(5  CHAR),			-- v2.0
  VOIRIE_CODE				VARCHAR2(5  CHAR),			-- v2.0
);

/*------------- UM_CORPS ------------------------------------*/
CREATE TABLE OSE.UM_CORPS
(
  ID						NUMBER(9) default  0,	
  LIBELLE_LONG				VARCHAR2(40  CHAR),
  LIBELLE_COURT				VARCHAR2(20  CHAR),
  SOURCE_ID					NUMBER(9),	
  SOURCE_CODE				VARCHAR2(100  CHAR),
  CONSTRAINT PK_ID_UM_CORPS PRIMARY KEY (ID),
  CONSTRAINT UK_UM_CORPS_SOURCE_CODE UNIQUE (SOURCE_CODE)
);

CREATE SEQUENCE OSE.SEQ_ID_UM_CORPS START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_UM_CORPS 
BEFORE INSERT ON OSE.UM_CORPS
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_UM_CORPS.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*------------- UM_GRADE ------------------------------------*/
CREATE TABLE OSE.UM_GRADE
(
  ID						NUMBER(9) default  0,
  LIBELLE_LONG				VARCHAR2(40  CHAR),
  LIBELLE_COURT				VARCHAR2(20  CHAR),
  ECHELLE					VARCHAR2(10  CHAR),
  CORPS_ID					NUMBER(9),
  SOURCE_ID					NUMBER(9),
  SOURCE_CODE				VARCHAR2(100  CHAR),
  CONSTRAINT PK_ID_UM_GRADE PRIMARY KEY (ID),
  CONSTRAINT UK_UM_GRADE_SOURCE_CODE UNIQUE (SOURCE_CODE)
);

CREATE SEQUENCE OSE.SEQ_ID_UM_GRADE START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_UM_GRADE
BEFORE INSERT ON OSE.UM_GRADE
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_UM_GRADE.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*------------- UM_VOIRIE ------------------------------------*/
CREATE TABLE OSE.UM_VOIRIE  -- v2.0
(
  ID						NUMBER(9) 			not null,
  CODE						VARCHAR2(5  CHAR)	not null,
  LIBELLE					VARCHAR2(120  CHAR) not null,
  SOURCE_ID					NUMBER(9)			not null,
  SOURCE_CODE				VARCHAR2(100  CHAR) not null,
  CONSTRAINT PK_ID_UM_VOIRIE PRIMARY KEY (ID),
  CONSTRAINT UK_UM_VOIRIE_SOURCE_CODE UNIQUE (SOURCE_CODE)
);

CREATE SEQUENCE OSE.SEQ_ID_UM_VOIRIE START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_UM_VOIRIE
BEFORE INSERT ON OSE.UM_VOIRIE
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_UM_VOIRIE.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

/*------------- UM_PARAM_ETABL ------------------------------------*/
CREATE TABLE OSE.UM_PARAM_ETABL  -- v2.0
(
  ID						NUMBER(9) 			not null,
  CODE						VARCHAR2(20  CHAR)	not null,
  VALEUR					VARCHAR2(100  CHAR),
  COMMENTAIRE				VARCHAR2(1000  CHAR),
  CONSTRAINT PK_ID_UM_PARAM_ETABL PRIMARY KEY (ID),
  CONSTRAINT UK_UM_PARAM_ETABL_CODE UNIQUE (CODE)
);

CREATE SEQUENCE OSE.SEQ_ID_UM_PARAM_ETABL START WITH 1;

CREATE OR REPLACE TRIGGER OSE.TRG_ID_UM_PARAM_ETABL
BEFORE INSERT ON OSE.UM_PARAM_ETABL
FOR EACH ROW
BEGIN
  SELECT OSE.SEQ_ID_UM_PARAM_ETABL.NEXTVAL
  INTO   :new.id
  FROM   dual;
END;

-- ##A_PERSONNALISER_CHOIX_SIHAM## ##A_PERSONNALISER_CHOIX_OSE##
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('C_STRUCTURE_MERE','HU00000001','Code UO mère de l''établissement dans Siham - utilisé dans proc UM_SYNCHRO_STRUCTURE');
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('C_ORG_RATTACH','''0342321N'',''0342490X''','Liste des codes UAI de l''établissement dans Siham - utilisé dans proc UM_SYNCHRO_STRUCTURE');  -- v2.2 - 20/07/22
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('C_UO_A_EXCLURE','''0000000000'',''UM1REP'',''UO_REP'',''UO_UM1'',''HZD0000003''','Liste des structures UO Siham à exclure des traitements : création structures et affectations agents - UO générique, UO de reprise des donnees Harpege, UO hors établissement');
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('OSE_STATUT_DOC_MCE','DOC_MCE','Code statut dans OSE créé pour distinguer les doctorants avec mission d enseignement');
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('PREFIXE_MATRICULE','UDM','Préfixe établissement utilisé pour les matricules SIHAM');
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('GESTION_STATUT','UNIQUE_MANUEL','Multi-statuts sur une année: 4 choix :  UNIQUE_MANUEL/UNIQUE_AUTO/MULTI_MANUEL/MULTI_AUTO (UNIQUE_MANUEL : unique à valider manuellement (maj) dans UM_SYNCHRO_A_VALIDER - comme V14 
																	/ UNIQUE_AUTO : unique et écrasé automatiquement/ MULTI_MANUEL :  à valider manuellement (ajout ou maj) dans UM_SYNCHRO_A_VALIDER / MULTI_AUTO : inséré automatiquement en plus');


/*------------- OSE.ADRESSE_NUMERO_COMPL ------------------------------------*/
-- pour l'instant V15 : table livrée par Caen, pas synchronisable dans Ose 
-- donc j'aoute simplement les codes manquants / SIHAM

select distinct trim(reg.cdcode) as code_adr_num_compl
            , trim(l_reg.liblon) as ll_adr_num_compl
         from zd00@SIHAM_TEST reg    -- reglementation
            , zd01@SIHAM_TEST l_reg  -- libelle reglementation
        where cdstco = 'WAN'    
        and reg.nudoss = l_reg.nudoss
        and trim(reg.cdcode) in 
		(	-- code pas deja ixistant dans table livree
		    select distinct trim(reg.cdcode) 
			from zd00@SIHAM_TEST reg    -- reglementation
				,zd01@SIHAM_TEST l_reg  -- libelle reglementation
			where cdstco = 'WAN'    
			and reg.nudoss = l_reg.nudoss
			minus 
			select code from adresse_numero_compl
		) 
