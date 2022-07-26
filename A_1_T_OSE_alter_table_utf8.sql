/* ====================================================================================================

	Detail du connecteur PARTIE A/ SIHAM_REF : des tables de référentiel - Avec user OSE
                           --------------------
						   
	PHASE1 : EXTRACTION_SIHAM CREATION SCHEMA TABLES INTERMEDIAIRES POUR OSE : nommage UM_<nom table>

	A_1_T_OSE_alter_tables_utf8.sql : à executer POUR LES UNIVERSITES AYANT DEJA INSTALLE le Connecteur_SIHAM_OSE_v1.1_2019-12
	
	-- v1.8 - 02/12/19 MYP : aucune modif pour ces tables de referentiel
	-- v2.0 - 11/20-01/21 MYP : V15 ajout VOIRIE
	-- v2.1 - 28/05/21 MYP : retaillage zones adresse
	-- v2.2 - 20/07/22 MYP : param C_ORG_RATTACH passage de valeur unique à multiple codes UAI => variable + test avec in (v_org_rattach)
=====================================================================================================*/


/* === V2.2 20/07/2021  ================================================================================*/
-- !! update à passer si vous avez une seule valeur de code UAI
update OSE.UM_PARAM_ETABL set valeur = ''''||valeur||'''' where CODE = 'C_ORG_RATTACH';

-- sinon exemple d'insert multi valeurs :
--insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('C_ORG_RATTACH','''0342321N'',''0342490X''','Liste des codes UAI de l''établissement dans Siham - utilisé dans proc UM_SYNCHRO_STRUCTURE');  -- v2.2 - 20/07/22


/* === V2.1 16/07/2021  ================================================================================*/

-- UM_ADRESSE_STRUCTURE.localite  varchar2(26) =>  varchar2(40) -- le champs était tronqué par rapport à Siham
ALTER TABLE OSE.UM_ADRESSE_STRUCTURE MODIFY LOCALITE VARCHAR2(40 CHAR);		 
-- OSEPREP2 le 28/05/21



/* === V2.0 03/2021  ===================================================================================*/
--- A FAIRE DANS CET ORDRE et pas à pas en adaptant si besoin ---------------------------------------------

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

--- pour les structures -----------------------------
ALTER TABLE OSE.UM_ADRESSE_STRUCTURE ADD NUMERO_COMPL_CODE	VARCHAR2(5 CHAR);
ALTER TABLE OSE.UM_ADRESSE_STRUCTURE ADD VOIRIE_CODE		VARCHAR2(5 CHAR);

update OSE.UM_ADRESSE_STRUCTURE set NUMERO_COMPL_CODE='',VOIRIE_CODE='';

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
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('C_ORG_RATTACH','0342321N','Code RNE de l''établissement dans Siham - utilisé dans proc UM_SYNCHRO_STRUCTURE');
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('C_UO_A_EXCLURE','''0000000000'',''UM1REP'',''UO_REP'',''UO_UM1'',''HZD0000003''','Liste des structures UO Siham à exclure des traitements : création structures et affectations agents - UO générique, UO de reprise des donnees Harpege, UO hors établissement');

insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('OSE_STATUT_DOC_MCE','DOC_MCE','Code statut dans OSE créé pour distinguer les doctorants avec mission d enseignement');
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('PREFIXE_MATRICULE','UDM','Préfixe établissement utilisé pour les matricules SIHAM');
insert into OSE.UM_PARAM_ETABL (CODE, VALEUR, COMMENTAIRE) values ('GESTION_STATUT','UNIQUE_MANUEL','Multi-statuts sur une année: 4 choix :  UNIQUE_MANUEL/UNIQUE_AUTO/MULTI_MANUEL/MULTI_AUTO (UNIQUE_MANUEL : unique à valider manuellement (maj) dans UM_SYNCHRO_A_VALIDER - comme V14 
																	/ UNIQUE_AUTO : unique et écrasé automatiquement/ MULTI_MANUEL :  à valider manuellement (ajout ou maj) dans UM_SYNCHRO_A_VALIDER / MULTI_AUTO : inséré automatiquement en plus');


