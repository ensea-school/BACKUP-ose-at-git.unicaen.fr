-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

-- depuis DEV
select 'INSERT INTO CATEGORIE_PRIVILEGE (
    ID,
    CODE,
    LIBELLE
  ) VALUES (
    CATEGORIE_PRIVILEGE_ID_SEQ.NEXTVAL,
    ''' || code || ''',
    q''$' || libelle || '$''
  );'
from categorie_privilege;


-- depuis DEV
SELECT 'INSERT INTO PRIVILEGE (
    ID,
    CATEGORIE_ID,
    CODE,
    LIBELLE
  ) VALUES (
    PRIVILEGE_ID_SEQ.NEXTVAL,
    (SELECT id FROM categorie_privilege WHERE code = q''$' || cp.code || '$''),
    ''' || p.code || ''',
    q''$' || p.libelle || '$''
  );'
FROM
  privilege p
  JOIN categorie_privilege cp ON cp.id = p.categorie_id;

-- depuis DEV
SELECT 'INSERT INTO PERIMETRE (
    ID,
    CODE,
    LIBELLE
  ) VALUES (
    PERIMETRE_ID_SEQ.NEXTVAL,
    ''' || code || ''',
    q''$' || libelle || '$''
  );'
FROM perimetre;

-- depuis DEV
select 'INSERT INTO ROLE (
    ID,
    CODE,
    LIBELLE,
    PERIMETRE_ID,
    HISTO_CREATION,
    HISTO_CREATEUR_ID,
    HISTO_MODIFICATION,
    HISTO_MODIFICATEUR_ID
  ) VALUES (
    ' || ROLE_ID_SEQ.NEXTVAL || ',
    ''' || r.CODE || ''',
    q''$' || r.LIBELLE || '$'',
    (SELECT id from perimetre WHERE code=''' || p.code || ''' ),
    SYSDATE, (select id from utilisateur where username=''lecluse''),
    SYSDATE, (select id from utilisateur where username=''lecluse'')
  );'
from
  role r
  JOIN perimetre p ON p.id = r.perimetre_id
where
  histo_destruction is null;

-- depuis DEV
select 'INSERT INTO ROLE_PRIVILEGE (
    ROLE_ID,
    PRIVILEGE_ID
  ) VALUES (
    (SELECT id FROM role WHERE code = ''' || r.code || '''),
    (SELECT p.id FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE cp.code = ''' || cp.code || ''' AND p.code = ''' || p.code || ''')
  );'
from
  role_privilege rp
  JOIN role r ON r.id = rp.role_id
  JOIN privilege p ON p.id = rp.privilege_id
  JOIN categorie_privilege cp ON cp.id = p.categorie_id;


-- depuis DEV
select 'INSERT INTO STATUT_PRIVILEGE (
    STATUT_ID,
    PRIVILEGE_ID
  ) VALUES (
    (SELECT id FROM statut_intervenant WHERE source_code = ''' || s.source_code || '''),
    (SELECT p.id FROM privilege p JOIN categorie_privilege cp ON cp.id = p.categorie_id WHERE cp.code = ''' || cp.code || ''' AND p.code = ''' || p.code || ''')
  );'
from
  STATUT_PRIVILEGE sp
  JOIN statut_intervenant s ON s.id = sp.statut_id
  JOIN privilege p ON p.id = sp.privilege_id
  JOIN categorie_privilege cp ON cp.id = p.categorie_id;


-- depuis le BDD de test ou prod
select 
  'INSERT INTO AFFECTATION(
    ID,
    PERSONNEL_ID,
    ROLE_ID,
    STRUCTURE_ID,
    SOURCE_ID,
    SOURCE_CODE,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
  ) VALUES( 
    AFFECTATION_ID_SEQ.NEXTVAL,
    ' || r.PERSONNEL_ID || ',
    (SELECT id FROM role WHERE code=''' || tr.code || '''),
    ' || NVL(to_char(r.STRUCTURE_ID),'NULL') || ',
    ' || r.SOURCE_ID || ',
    ''' || r.SOURCE_CODE || ''',
    SYSDATE, (select id from utilisateur where username=''lecluse''),
    SYSDATE, (select id from utilisateur where username=''lecluse'')
  );'
FROM
  role r
  JOIN type_role tr ON tr.id = r.type_id;

delete from privilege;
delete from role;

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END;
/

---------------------------
--Nouveau SEQUENCE
--ROLE_PRIVILEGE_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."ROLE_PRIVILEGE_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--PJ_TMP_INTERVENANT_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."PJ_TMP_INTERVENANT_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--PERIMETRE_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."PERIMETRE_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--PAYS_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."PAYS_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 251 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--DEPARTEMENT_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."DEPARTEMENT_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 106 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--CATEGORIE_PRIVILEGE_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."CATEGORIE_PRIVILEGE_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 5 NOCACHE NOORDER NOCYCLE;
---------------------------
--Nouveau SEQUENCE
--AFFECTATION_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."AFFECTATION_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 3 NOCACHE NOORDER NOCYCLE;
---------------------------
--Modifié TABLE
--ELEMENT_MODULATEUR
---------------------------
ALTER TABLE "OSE"."ELEMENT_MODULATEUR" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."ELEMENT_MODULATEUR" DROP CONSTRAINT "ELEMENT_MODULATEUR_ANNEE_FK";

---------------------------
--Modifié TABLE
--INTERVENANT_EXTERIEUR
---------------------------
ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" DROP ("VALIDITE_FIN");
ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" MODIFY ("DOSSIER_ID" NUMBER);
ALTER TABLE "OSE"."INTERVENANT_EXTERIEUR" DROP CONSTRAINT "INTERVENANT_EXTERIEUR_DOSSIER";

---------------------------
--Modifié TABLE
--ADRESSE_INTERVENANT
---------------------------
ALTER TABLE "OSE"."ADRESSE_INTERVENANT" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."ADRESSE_INTERVENANT" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--FORMULE_RESULTAT
---------------------------
ALTER TABLE "OSE"."FORMULE_RESULTAT" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."FORMULE_RESULTAT" DROP CONSTRAINT "FRES_ANNEE_FK";
ALTER TABLE "OSE"."FORMULE_RESULTAT" DROP CONSTRAINT "FORMULE_RESULTAT__UN";
ALTER TABLE "OSE"."FORMULE_RESULTAT" ADD CONSTRAINT "FORMULE_RESULTAT__UN" UNIQUE ("INTERVENANT_ID","TYPE_VOLUME_HORAIRE_ID","ETAT_VOLUME_HORAIRE_ID") ENABLE;

---------------------------
--Modifié TABLE
--TYPE_PIECE_JOINTE_STATUT
---------------------------
ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_PIECE_JOINTE_STATUT" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--DISCIPLINE
---------------------------
ALTER TABLE "OSE"."DISCIPLINE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."DISCIPLINE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--VOLUME_HORAIRE_ENS
---------------------------
ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" DROP CONSTRAINT "VOLUME_HORAIRE_ENS_ANNEE_FK";
ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" DROP CONSTRAINT "VHE_SOURCE_UN";
ALTER TABLE "OSE"."VOLUME_HORAIRE_ENS" DROP CONSTRAINT "VOLUME_HORAIRE_ENS_UN";

---------------------------
--Modifié TABLE
--CORPS
---------------------------
ALTER TABLE "OSE"."CORPS" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."CORPS" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--CONTRAT
---------------------------
ALTER TABLE "OSE"."CONTRAT" ADD ("TOTAL_HETD" FLOAT(126));

---------------------------
--Modifié TABLE
--WF_INTERVENANT_ETAPE
---------------------------
ALTER TABLE "OSE"."WF_INTERVENANT_ETAPE" ADD ("PARENT_ID" NUMBER(*,0));
ALTER TABLE "OSE"."WF_INTERVENANT_ETAPE" ADD CONSTRAINT "WF_INTERVENANT_ETAPE_PFK" FOREIGN KEY ("PARENT_ID") REFERENCES "OSE"."WF_INTERVENANT_ETAPE"("ID") ON DELETE CASCADE ENABLE;

---------------------------
--Modifié TABLE
--TAUX_HORAIRE_HETD
---------------------------
ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TAUX_HORAIRE_HETD" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--MOTIF_MODIFICATION_SERVICE
---------------------------
ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--PARAMETRE
---------------------------
ALTER TABLE "OSE"."PARAMETRE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."PARAMETRE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--TYPE_STRUCTURE
---------------------------
ALTER TABLE "OSE"."TYPE_STRUCTURE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_STRUCTURE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--PAYS
---------------------------
ALTER TABLE "OSE"."PAYS" MODIFY ("ID" NOT NULL ENABLE);
ALTER TABLE "OSE"."PAYS" ADD CONSTRAINT "PAYS_PK" PRIMARY KEY ("ID") ENABLE;

---------------------------
--Modifié TABLE
--FONCTION_REFERENTIEL
---------------------------
ALTER TABLE "OSE"."FONCTION_REFERENTIEL" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."FONCTION_REFERENTIEL" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--EFFECTIFS
---------------------------
ALTER TABLE "OSE"."EFFECTIFS" MODIFY ("ANNEE_ID" NUMBER DEFAULT NULL);
ALTER TABLE "OSE"."EFFECTIFS" MODIFY ("ELEMENT_PEDAGOGIQUE_ID" NULL);
ALTER TABLE "OSE"."EFFECTIFS" DROP CONSTRAINT "EFFECTIFS_ANNEE_FK";
ALTER TABLE "OSE"."EFFECTIFS" DROP CONSTRAINT "EFFECTIFS__UN";
ALTER TABLE "OSE"."EFFECTIFS" ADD CONSTRAINT "EFFECTIFS_FK1" FOREIGN KEY ("ANNEE_ID") REFERENCES "OSE"."ANNEE"("ID") ENABLE;
ALTER TABLE "OSE"."EFFECTIFS" ADD CONSTRAINT "EFFECTIFS__UN" UNIQUE ("SOURCE_CODE","ANNEE_ID") ENABLE;

---------------------------
--Modifié TABLE
--TYPE_MODULATEUR
---------------------------
ALTER TABLE "OSE"."TYPE_MODULATEUR" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_MODULATEUR" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--DOSSIER
---------------------------
ALTER TABLE "OSE"."DOSSIER" ADD ("DATE_NAISSANCE" DATE);
ALTER TABLE "OSE"."DOSSIER" ADD ("DEPT_NAISSANCE_ID" NUMBER(*,0));
ALTER TABLE "OSE"."DOSSIER" ADD ("EMAIL_PERSO" VARCHAR2(128 CHAR));
ALTER TABLE "OSE"."DOSSIER" ADD ("INTERVENANT_ID" NUMBER(*,0));
ALTER TABLE "OSE"."DOSSIER" ADD ("PAYS_NAISSANCE_ID" NUMBER(*,0));
ALTER TABLE "OSE"."DOSSIER" ADD ("VILLE_NAISSANCE" VARCHAR2(128 CHAR));
ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_D_FK" FOREIGN KEY ("DEPT_NAISSANCE_ID") REFERENCES "OSE"."DEPARTEMENT"("ID") ENABLE;
ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_IFK" FOREIGN KEY ("INTERVENANT_ID") REFERENCES "OSE"."INTERVENANT"("ID") ENABLE;
ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_P_FK" FOREIGN KEY ("PAYS_NAISSANCE_ID") REFERENCES "OSE"."PAYS"("ID") ENABLE;
ALTER TABLE "OSE"."DOSSIER" ADD CONSTRAINT "DOSSIER_UK1" UNIQUE ("INTERVENANT_ID","HISTO_DESTRUCTION") ENABLE;

---------------------------
--Modifié TABLE
--EMPLOYEUR
---------------------------
ALTER TABLE "OSE"."EMPLOYEUR" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."EMPLOYEUR" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--SERVICE_REFERENTIEL
---------------------------
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" DROP CONSTRAINT "SERVICES_REFERENTIEL_ANNEES_FK";

---------------------------
--Modifié TABLE
--ELEMENT_PEDAGOGIQUE
---------------------------
ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" DROP ("VALIDITE_FIN");
ALTER TABLE "OSE"."ELEMENT_PEDAGOGIQUE" MODIFY ("ANNEE_ID" NUMBER(*,0) DEFAULT NULL);

---------------------------
--Modifié TABLE
--AFFECTATION_RECHERCHE
---------------------------
ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--CHEMIN_PEDAGOGIQUE
---------------------------
ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."CHEMIN_PEDAGOGIQUE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--REGIME_SECU
---------------------------
ALTER TABLE "OSE"."REGIME_SECU" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."REGIME_SECU" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--FORMULE_RESULTAT_MAJ
---------------------------
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP ("ID");
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP CONSTRAINT "FORMULE_RESULTAT_MAJ_PK";
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP CONSTRAINT "FRM_ANNEE_FK";
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" DROP CONSTRAINT "FORMULE_RESULTAT_MAJ__UN";
ALTER TABLE "OSE"."FORMULE_RESULTAT_MAJ" ADD CONSTRAINT "FORMULE_RESULTAT_MAJ_PK" PRIMARY KEY ("INTERVENANT_ID") ENABLE;

---------------------------
--Modifié TABLE
--TYPE_INTERVENANT
---------------------------
ALTER TABLE "OSE"."TYPE_INTERVENANT" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_INTERVENANT" DROP ("VALIDITE_FIN");

---------------------------
--Nouveau TABLE
--ROLE_PRIVILEGE
---------------------------
  CREATE TABLE "OSE"."ROLE_PRIVILEGE" 
   (	"ROLE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"PRIVILEGE_ID" NUMBER(*,0) NOT NULL ENABLE,
	CONSTRAINT "ROLE_PRIVILEGE_PK" PRIMARY KEY ("PRIVILEGE_ID","ROLE_ID") ENABLE,
	CONSTRAINT "ROLE_PRIVILEGE_PRIVILEGE_FK" FOREIGN KEY ("PRIVILEGE_ID")
	 REFERENCES "OSE"."PRIVILEGE" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "ROLE_PRIVILEGE_ROLE_FK" FOREIGN KEY ("ROLE_ID")
	 REFERENCES "OSE"."ROLE" ("ID") ON DELETE CASCADE ENABLE
   );
---------------------------
--Modifié TABLE
--SERVICE
---------------------------
ALTER TABLE "OSE"."SERVICE" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."SERVICE" DROP ("STRUCTURE_AFF_ID");
ALTER TABLE "OSE"."SERVICE" DROP ("STRUCTURE_ENS_ID");
ALTER TABLE "OSE"."SERVICE" DROP CONSTRAINT "SERVICE_ANNEE_FK";
ALTER TABLE "OSE"."SERVICE" DROP CONSTRAINT "SERVICE_STRUCTURE_AFF_FK";
ALTER TABLE "OSE"."SERVICE" DROP CONSTRAINT "SERVICE_STRUCTURE_ENS_FK";
ALTER TABLE "OSE"."SERVICE" DROP CONSTRAINT "SERVICE__UN";
ALTER TABLE "OSE"."SERVICE" ADD CONSTRAINT "SERVICE__UN" UNIQUE ("INTERVENANT_ID","ELEMENT_PEDAGOGIQUE_ID","ETABLISSEMENT_ID","HISTO_DESTRUCTION") ENABLE;

---------------------------
--Modifié TABLE
--STRUCTURE
---------------------------
ALTER TABLE "OSE"."STRUCTURE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."STRUCTURE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--ADRESSE_STRUCTURE
---------------------------
ALTER TABLE "OSE"."ADRESSE_STRUCTURE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."ADRESSE_STRUCTURE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--TYPE_INTERVENTION
---------------------------
ALTER TABLE "OSE"."TYPE_INTERVENTION" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_INTERVENTION" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--MOTIF_NON_PAIEMENT
---------------------------
ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."MOTIF_NON_PAIEMENT" DROP ("VALIDITE_FIN");

---------------------------
--Nouveau TABLE
--PERIMETRE
---------------------------
  CREATE TABLE "OSE"."PERIMETRE" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"CODE" VARCHAR2(30 CHAR) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(150 CHAR) NOT NULL ENABLE,
	CONSTRAINT "PERIMETRE_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "PERIMETRE_CODE_UN" UNIQUE ("CODE") ENABLE,
	CONSTRAINT "PERIMETRE_LIBELLE_UN" UNIQUE ("LIBELLE") ENABLE
   );
---------------------------
--Nouveau TABLE
--AFFECTATION
---------------------------
  CREATE TABLE "OSE"."AFFECTATION" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"PERSONNEL_ID" NUMBER(*,0) NOT NULL ENABLE,
	"ROLE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"STRUCTURE_ID" NUMBER(*,0),
	"SOURCE_ID" NUMBER(*,0) NOT NULL ENABLE,
	"SOURCE_CODE" VARCHAR2(100 CHAR),
	"HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
	"HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
	"HISTO_DESTRUCTION" DATE,
	"HISTO_DESTRUCTEUR_ID" NUMBER(*,0),
	CONSTRAINT "AFFECTATION_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "AFFECTATION_SOURCE_UN" UNIQUE ("SOURCE_CODE") ENABLE,
	CONSTRAINT "AFFECTATION_HCFK" FOREIGN KEY ("HISTO_CREATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "AFFECTATION_HDFK" FOREIGN KEY ("HISTO_DESTRUCTEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "AFFECTATION_HMFK" FOREIGN KEY ("HISTO_MODIFICATEUR_ID")
	 REFERENCES "OSE"."UTILISATEUR" ("ID") ENABLE,
	CONSTRAINT "AFFECTATION_PERSONNEL_FK" FOREIGN KEY ("PERSONNEL_ID")
	 REFERENCES "OSE"."PERSONNEL" ("ID") ENABLE,
	CONSTRAINT "AFFECTATION_ROLE_FK" FOREIGN KEY ("ROLE_ID")
	 REFERENCES "OSE"."ROLE" ("ID") ON DELETE CASCADE ENABLE,
	CONSTRAINT "AFFECTATION_SOURCE_FK" FOREIGN KEY ("SOURCE_ID")
	 REFERENCES "OSE"."SOURCE" ("ID") ENABLE,
	CONSTRAINT "AFFECTATION_STRUCTURE_FK" FOREIGN KEY ("STRUCTURE_ID")
	 REFERENCES "OSE"."STRUCTURE" ("ID") ENABLE
   );
---------------------------
--Modifié TABLE
--AGREMENT
---------------------------
ALTER TABLE "OSE"."AGREMENT" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."AGREMENT" DROP CONSTRAINT "AGREMENT_ANNEE_FK";
ALTER TABLE "OSE"."AGREMENT" DROP CONSTRAINT "AGREMENT__UN";
ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT__UN" UNIQUE ("TYPE_AGREMENT_ID","INTERVENANT_ID","STRUCTURE_ID") ENABLE;

---------------------------
--Modifié TABLE
--ETABLISSEMENT
---------------------------
ALTER TABLE "OSE"."ETABLISSEMENT" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."ETABLISSEMENT" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--ELEMENT_TAUX_REGIMES
---------------------------
ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" DROP CONSTRAINT "ETR_ANNEE_FK";
ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" DROP CONSTRAINT "ELEMENT_TAUX_REGIMES__UNV1";
ALTER TABLE "OSE"."ELEMENT_TAUX_REGIMES" ADD CONSTRAINT "ELEMENT_TAUX_REGIMES__UNV1" UNIQUE ("ELEMENT_PEDAGOGIQUE_ID","HISTO_DESTRUCTION") ENABLE;

---------------------------
--Modifié TABLE
--ELEMENT_DISCIPLINE
---------------------------
ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."ELEMENT_DISCIPLINE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--ETAPE
---------------------------
ALTER TABLE "OSE"."ETAPE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."ETAPE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--TYPE_MODULATEUR_STRUCTURE
---------------------------
ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_MODULATEUR_STRUCTURE" DROP ("VALIDITE_FIN");

---------------------------
--Nouveau TABLE
--CATEGORIE_PRIVILEGE
---------------------------
  CREATE TABLE "OSE"."CATEGORIE_PRIVILEGE" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"CODE" VARCHAR2(150 CHAR) NOT NULL ENABLE,
	"LIBELLE" VARCHAR2(200 CHAR) NOT NULL ENABLE,
	CONSTRAINT "CATEGORIE_PRIVILEGE_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "CATEGORIE_PRIVILEGE__UN" UNIQUE ("CODE") ENABLE
   );
---------------------------
--Modifié TABLE
--MODIFICATION_SERVICE_DU
---------------------------
ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."MODIFICATION_SERVICE_DU" DROP CONSTRAINT "DS_ANNEE_FK";

---------------------------
--Nouveau TABLE
--PJ_TMP_INTERVENANT
---------------------------
  CREATE GLOBAL TEMPORARY TABLE "OSE"."PJ_TMP_INTERVENANT" 
   (	"INTERVENANT_ID" NUMBER(*,0),
	"TYPE_PIECE_JOINTE_ID" NUMBER(*,0),
	"OBLIGATOIRE" NUMBER(1,0)
   ) ON COMMIT DELETE ROWS;
---------------------------
--Modifié TABLE
--INTERVENANT_PERMANENT
---------------------------
ALTER TABLE "OSE"."INTERVENANT_PERMANENT" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."INTERVENANT_PERMANENT" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--TYPE_PIECE_JOINTE
---------------------------
ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" ADD ("ORDRE" NUMBER DEFAULT 1 NOT NULL ENABLE);
ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_PIECE_JOINTE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--GROUPE
---------------------------
ALTER TABLE "OSE"."GROUPE" DROP ("ANNEE_ID");
ALTER TABLE "OSE"."GROUPE" DROP CONSTRAINT "GROUPE_ANNEE_FK";
ALTER TABLE "OSE"."GROUPE" DROP CONSTRAINT "GROUPE__UN";
ALTER TABLE "OSE"."GROUPE" ADD CONSTRAINT "GROUPE__UN" UNIQUE ("ELEMENT_PEDAGOGIQUE_ID","HISTO_DESTRUCTEUR_ID","TYPE_INTERVENTION_ID") ENABLE;

---------------------------
--Modifié TABLE
--TYPE_FORMATION
---------------------------
ALTER TABLE "OSE"."TYPE_FORMATION" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_FORMATION" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--SITUATION_FAMILIALE
---------------------------
ALTER TABLE "OSE"."SITUATION_FAMILIALE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."SITUATION_FAMILIALE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--TYPE_POSTE
---------------------------
ALTER TABLE "OSE"."TYPE_POSTE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."TYPE_POSTE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--INTERVENANT
---------------------------
ALTER TABLE "OSE"."INTERVENANT" MODIFY ("ANNEE_ID" NUMBER(*,0) DEFAULT NULL);

---------------------------
--Modifié TABLE
--STATUT_INTERVENANT
---------------------------
ALTER TABLE "OSE"."STATUT_INTERVENANT" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."STATUT_INTERVENANT" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--PIECE_JOINTE
---------------------------
ALTER TABLE "OSE"."PIECE_JOINTE" ADD ("FORCE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE);
ALTER TABLE "OSE"."PIECE_JOINTE" ADD ("OBLIGATOIRE" NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE);
ALTER TABLE "OSE"."PIECE_JOINTE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."PIECE_JOINTE" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--ROLE
---------------------------
ALTER TABLE "OSE"."ROLE" ADD ("CODE" VARCHAR2(64 CHAR) NOT NULL ENABLE);
ALTER TABLE "OSE"."ROLE" ADD ("LIBELLE" VARCHAR2(50 CHAR) NOT NULL ENABLE);
ALTER TABLE "OSE"."ROLE" ADD ("PERIMETRE_ID" NUMBER(*,0) NOT NULL ENABLE);
ALTER TABLE "OSE"."ROLE" DROP ("PERSONNEL_ID");
ALTER TABLE "OSE"."ROLE" DROP ("SOURCE_CODE");
ALTER TABLE "OSE"."ROLE" DROP ("SOURCE_ID");
ALTER TABLE "OSE"."ROLE" DROP ("STRUCTURE_ID");
ALTER TABLE "OSE"."ROLE" DROP ("TYPE_ID");
ALTER TABLE "OSE"."ROLE" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."ROLE" DROP ("VALIDITE_FIN");
ALTER TABLE "OSE"."ROLE" DROP CONSTRAINT "ROLE_PERSONNEL_FK";
ALTER TABLE "OSE"."ROLE" DROP CONSTRAINT "ROLE_SOURCE_FK";
ALTER TABLE "OSE"."ROLE" DROP CONSTRAINT "ROLE_STRUCTURE_FK";
ALTER TABLE "OSE"."ROLE" DROP CONSTRAINT "ROLE_TYPE_ROLE_FK";
ALTER TABLE "OSE"."ROLE" DROP CONSTRAINT "ROLE_SOURCE_UN";
ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_PERIMETRE_FK" FOREIGN KEY ("PERIMETRE_ID") REFERENCES "OSE"."PERIMETRE"("ID") ENABLE;
ALTER TABLE "OSE"."ROLE" ADD CONSTRAINT "ROLE_CODE_UN" UNIQUE ("CODE") ENABLE;

---------------------------
--Modifié TABLE
--PERSONNEL
---------------------------
ALTER TABLE "OSE"."PERSONNEL" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."PERSONNEL" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--EMPLOI
---------------------------
ALTER TABLE "OSE"."EMPLOI" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."EMPLOI" DROP ("VALIDITE_FIN");

---------------------------
--Modifié TABLE
--PRIVILEGE
---------------------------
ALTER TABLE "OSE"."PRIVILEGE" ADD ("CATEGORIE_ID" NUMBER(*,0) NOT NULL ENABLE);
ALTER TABLE "OSE"."PRIVILEGE" DROP ("RESSOURCE_ID");
ALTER TABLE "OSE"."PRIVILEGE" DROP CONSTRAINT "PRIVILEGE_RESSOURCE_FK";
ALTER TABLE "OSE"."PRIVILEGE" DROP CONSTRAINT "PRIVILEGE__UN";
ALTER TABLE "OSE"."PRIVILEGE" ADD CONSTRAINT "PRIVILEGE_CATEGORIE_FK" FOREIGN KEY ("CATEGORIE_ID") REFERENCES "OSE"."CATEGORIE_PRIVILEGE"("ID") ON DELETE CASCADE ENABLE;
ALTER TABLE "OSE"."PRIVILEGE" ADD CONSTRAINT "PRIVILEGE__UN" UNIQUE ("CATEGORIE_ID","CODE") ENABLE;

---------------------------
--Modifié TABLE
--MODULATEUR
---------------------------
ALTER TABLE "OSE"."MODULATEUR" DROP ("VALIDITE_DEBUT");
ALTER TABLE "OSE"."MODULATEUR" DROP ("VALIDITE_FIN");

---------------------------
--Nouveau VIEW
--V_TMP_PJ
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TMP_PJ" 
 ( "ID", "SOURCE_CODE", "NB_PJ_OBLIG_ATTENDU", "NB_PJ_OBLIG_FOURNI"
  )  AS 
  WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID, I.SOURCE_CODE, count(pj.id) NB
          FROM INTERVENANT i
          INNER JOIN DOSSIER d ON d.intervenant_ID = i.ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON pj.dossier_ID = d.id and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          and pj.OBLIGATOIRE = 1 
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant, VALIDEES OU NON
          SELECT I.ID, I.SOURCE_CODE, count(pj.ID) NB
          FROM INTERVENANT I
          INNER JOIN DOSSIER d ON d.intervenant_ID = i.ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          and pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          I.ID, 
          I.source_code,
          COALESCE(AO.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(FO.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM intervenant i
      join ATTENDU_OBLIGATOIRE AO on ao.id = i.id
      LEFT JOIN FOURNI_OBLIGATOIRE  FO ON FO.id = i.id
      --WHERE i.ID = p_intervenant_id
      ;
---------------------------
--Modifié VIEW
--V_TBL_SERVICE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE" 
 ( "ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETABLISSEMENT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "PERIODE_ID", "TYPE_INTERVENTION_ID", "FONCTION_REFERENTIEL_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "GROUPE_TYPE_FORMATION_LIBELLE", "TYPE_FORMATION_LIBELLE", "ETAPE_NIVEAU", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "FONCTION_REFERENTIEL_LIBELLE", "ELEMENT_TAUX_FI", "ELEMENT_TAUX_FC", "ELEMENT_TAUX_FA", "COMMENTAIRES", "PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES", "HEURES_REF", "HEURES_NON_PAYEES", "SERVICE_STATUTAIRE", "SERVICE_DU_MODIFIE", "SERVICE_FI", "SERVICE_FA", "SERVICE_FC", "SERVICE_REFERENTIEL", "HEURES_COMPL_FI", "HEURES_COMPL_FA", "HEURES_COMPL_FC", "HEURES_COMPL_FC_MAJOREES", "HEURES_COMPL_REFERENTIEL", "TOTAL", "SOLDE"
  )  AS 
  WITH t AS ( SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  null                              structure_aff_id,
  null                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  null                              fonction_referentiel_id,
  
  vh.heures                         heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
  frvh.service_fi                   service_fi,
  frvh.service_fa                   service_fa,
  frvh.service_fc                   service_fc,
  0                                 service_referentiel,
  frvh.heures_compl_fi              heures_compl_fi,
  frvh.heures_compl_fa              heures_compl_fa,
  frvh.heures_compl_fc              heures_compl_fc,
  frvh.heures_compl_fc_majorees     heures_compl_fc_majorees,
  0                                 heures_compl_referentiel,
  frvh.total                        total,
  fr.solde                          solde,
  null                              commentaires

FROM
  formule_resultat_vh                frvh
  JOIN formule_resultat                fr ON fr.id = frvh.formule_resultat_id
  JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  JOIN service                          s ON s.id = vh.service_id AND s.intervenant_id = fr.intervenant_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION

SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  vhe.etat_volume_horaire_id        etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  null                              structure_aff_id,
  null                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  null                              fonction_referentiel_id,
  
  vh.heures                         heures,
  0                                 heures_ref,
  1                                 heures_non_payees,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  0                                 service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_compl_fc_majorees,
  0                                 heures_compl_referentiel,
  0                                 total,
  fr.solde                          solde,
  null                              commentaires
  
FROM
  volume_horaire                  vh
  JOIN service                     s ON s.id = vh.service_id
  JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
  JOIN formule_resultat           fr ON fr.intervenant_id = s.intervenant_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
WHERE
  vh.motif_non_paiement_id IS NOT NULL
  AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
  AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )

UNION

SELECT
  'vh_ref_' || vhr.id               id,
  sr.id                             service_id,
  sr.intervenant_id                 intervenant_id,
  fr.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  sr.structure_id                   structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  sr.fonction_id                    fonction_referentiel_id,
  
  0                                 heures,
  vhr.heures                        heures_ref,
  0                                 heures_non_payees,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  frvr.service_referentiel          service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_compl_fc_majorees,
  frvr.heures_compl_referentiel     heures_compl_referentiel,
  frvr.total                        total,
  fr.solde                          solde,
  sr.commentaires                   commentaires
  
FROM
  formule_resultat_vh_ref       frvr
  JOIN formule_resultat           fr ON fr.id = frvr.formule_resultat_id
  JOIN volume_horaire_ref        vhr ON vhr.id =  frvr.volume_horaire_ref_id
  JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND 1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction )
)
SELECT
  t.id                            id,
  t.service_id                    service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,  
  i.annee_id                      annee_id,
  t.type_volume_horaire_id        type_volume_horaire_id,
  t.etat_volume_horaire_id        etat_volume_horaire_id,
  etab.id                         etablissement_id,
  saff.id                         structure_aff_id,
  sens.id                         structure_ens_id,
  ose_divers.niveau_formation_id_calc( gtf.id, gtf.pertinence_niveau, etp.niveau ) niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,
  t.periode_id                    periode_id,
  t.type_intervention_id          type_intervention_id,
  t.fonction_referentiel_id       fonction_referentiel_id,
  
  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  gtf.libelle_court               groupe_type_formation_libelle,
  tf.libelle_court                type_formation_libelle,
  etp.niveau                      etape_niveau,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  fr.libelle_long                 fonction_referentiel_libelle,
  ep.taux_fi                      element_taux_fi,
  ep.taux_fc                      element_taux_fc,
  ep.taux_fa                      element_taux_fa,
  null                            commentaires,
  p.libelle_court                 periode_libelle,
  CASE WHEN fs.ponderation_service_compl = 1 THEN NULL ELSE fs.ponderation_service_compl END element_ponderation_compl,
  src.libelle                     element_source_libelle,
  
  t.heures                        heures,
  t.heures_ref                    heures_ref,
  t.heures_non_payees             heures_non_payees,
  si.service_statutaire           service_statutaire,
  fsm.heures                      service_du_modifie,
  t.service_fi                    service_fi,
  t.service_fa                    service_fa,
  t.service_fc                    service_fc,
  t.service_referentiel           service_referentiel,
  t.heures_compl_fi               heures_compl_fi,
  t.heures_compl_fa               heures_compl_fa,
  t.heures_compl_fc               heures_compl_fc,
  t.heures_compl_fc_majorees      heures_compl_fc_majorees,
  t.heures_compl_referentiel      heures_compl_referentiel,
  t.total                         total,
  t.solde                         solde

FROM
  t
  JOIN intervenant                        i ON i.id    = t.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant                si ON si.id   = i.statut_id            
  JOIN type_intervenant                  ti ON ti.id   = si.type_intervenant_id 
  JOIN etablissement                   etab ON etab.id = t.etablissement_id
  LEFT JOIN structure                  saff ON saff.id = i.structure_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique          ep ON ep.id   = t.element_pedagogique_id
  LEFT JOIN structure                  sens ON sens.id = NVL(t.structure_ens_id, ep.structure_id)
  LEFT JOIN periode                       p ON p.id    = t.periode_id
  LEFT JOIN source                      src ON src.id  = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
  LEFT JOIN etape                       etp ON etp.id  = ep.etape_id
  LEFT JOIN type_formation               tf ON tf.id   = etp.type_formation_id AND ose_divers.comprise_entre( tf.histo_creation, tf.histo_destruction ) = 1
  LEFT JOIN groupe_type_formation       gtf ON gtf.id  = tf.groupe_id AND ose_divers.comprise_entre( gtf.histo_creation, gtf.histo_destruction ) = 1
  LEFT JOIN v_formule_service_modifie   fsm ON fsm.intervenant_id = i.id
  LEFT JOIN v_formule_service            fs ON fs.id   = t.service_id
  LEFT JOIN fonction_referentiel         fr ON fr.id   = t.fonction_referentiel_id;
---------------------------
--Modifié VIEW
--V_SYMPA_LISTE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_SYMPA_LISTE" 
 ( "EMAIL"
  )  AS 
  select distinct p.email
  from affectation a
  inner join role r on a.role_id = r.id and 1 = ose_divers.comprise_entre( r.histo_creation, r.histo_destruction)
  inner join personnel p on a.personnel_id = p.id and 1 = ose_divers.comprise_entre( p.histo_creation, p.histo_destruction)
  where r.code in (
     'gestionnaire-composante'
    ,'responsable-composante'
    ,'responsable-drh'
    ,'gestionnaire-drh'
    ,'administrateur'
  )
  and 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction)
  order by p.email;
---------------------------
--Modifié VIEW
--V_RECAP_SERVICE_PREVIS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_RECAP_SERVICE_PREVIS" 
 ( "ID", "INTERVENANT_ID", "NOM_USUEL", "SOURCE_CODE", "ANNEE_ID", "SERVICE_STATUTAIRE", "MODIF_SERVICE", "LIBELLE_STRUCTURE", "CODE_EP", "LIBELLE_EP", "HAS_MODULATEUR", "NON_PAYABLE", "CODE_PERIODE", "ORDRE_PERIODE", "CODE_TI", "ORDRE_TI", "HEURES"
  )  AS 
  select 
  vh.id,
  i.id intervenant_id,
  i.nom_usuel,
  i.source_code,
  i.annee_id,
  si.service_statutaire,
  nvl(fsm.heures, 0) modif_service,
  str.libelle_court libelle_structure,
  ep.source_code code_ep,
  ep.libelle libelle_ep,
  case when fs.id is null then 0 else 1 end has_modulateur,
  case when vh.motif_non_paiement_id is null then 0 else 1 end non_payable,
  p.code code_periode,
  p.ordre ordre_periode,
  ti.code code_ti,
  ti.ordre ordre_ti,
  sum(vh.heures) heures
from volume_horaire vh
join type_volume_horaire tvh on vh.type_volume_horaire_id = tvh.id and tvh.code = 'PREVU'
join service s on vh.service_id = s.id and s.histo_destructeur_id is null
join element_pedagogique ep on s.element_pedagogique_id = ep.id and ep.histo_destructeur_id is null
join periode p on vh.periode_id = p.id and p.histo_destructeur_id is null
join intervenant i on s.intervenant_id = i.id and i.histo_destructeur_id is null
join statut_intervenant si on i.statut_id = si.id
--join validation_vol_horaire vvh on vvh.volume_horaire_id = vh.id
--join validation v on vvh.validation_id = v.id and v.histo_destructeur_id is null
join structure str on ep.structure_id = str.id and str.histo_destructeur_id is null
join type_intervention ti on vh.type_intervention_id = ti.id and ti.histo_destructeur_id is null
left join v_formule_service fs on fs.id = s.id and (fs.ponderation_service_compl <> 1 or fs.ponderation_service_du <> 1) -- NB: fs.id est l'id du service
left join v_formule_service_modifie fsm on fsm.intervenant_id = i.id
where vh.histo_destructeur_id is null
group by 
  vh.id,
  i.id,
  i.nom_usuel,
  i.source_code,
  i.annee_id,
  si.service_statutaire,
  nvl(fsm.heures, 0),
  str.libelle_court,
  ep.source_code,
  ep.libelle,
  case when fs.id is null then 0 else 1 end,
  case when vh.motif_non_paiement_id is null then 0 else 1 end,
  p.code,
  p.ordre,
  ti.code,
  ti.ordre;
---------------------------
--Modifié VIEW
--V_PJ_HEURES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_PJ_HEURES" 
 ( "NOM_USUEL", "PRENOM", "INTERVENANT_ID", "SOURCE_CODE", "ANNEE_ID", "CATEG", "TOTAL_HEURES"
  )  AS 
  SELECT
  i.NOM_USUEL,
  i.PRENOM,
  i.id intervenant_id,
  i.SOURCE_CODE,
  i.annee_id, 'service' categ,
  sum(vh.HEURES) as total_heures
from INTERVENANT i 
  join SERVICE s on s.INTERVENANT_ID = i.id      and ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) = 1
  join VOLUME_HORAIRE vh on vh.SERVICE_ID = s.id and ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction) = 1
  join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID AND (tvh.code = 'PREVU')
  join ELEMENT_PEDAGOGIQUE ep on s.ELEMENT_PEDAGOGIQUE_ID = ep.id        and ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction) = 1
  join ETAPE e on ep.ETAPE_ID = e.id and ose_divers.comprise_entre(e.histo_creation, e.histo_destruction) = 1
where ose_divers.comprise_entre(i.histo_creation, i.histo_destruction) = 1
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, i.annee_id, 'service'
UNION
  SELECT i.NOM_USUEL, i.PRENOM, i.id intervenant_id, i.SOURCE_CODE, i.annee_id, 'referentiel' categ, sum(vh.HEURES) as total_heures
  from INTERVENANT i 
  join service_referentiel s on s.INTERVENANT_ID = i.id                  and ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) = 1
  join volume_horaire_ref vh on vh.service_referentiel_id = s.id         and ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction) = 1
  join type_volume_horaire tvh ON TVH.ID = VH.TYPE_VOLUME_HORAIRE_ID     AND (tvh.code = 'PREVU')
  join fonction_referentiel ep on s.fonction_id = ep.id                  and ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction) = 1
  where ose_divers.comprise_entre(i.histo_creation, i.histo_destruction) = 1
  group by i.NOM_USUEL, i.PRENOM, i.id, i.SOURCE_CODE, i.annee_id, 'referentiel';
---------------------------
--Modifié VIEW
--V_MEP_INTERVENANT_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_MEP_INTERVENANT_STRUCTURE" 
 ( "ID", "MISE_EN_PAIEMENT_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PERIODE_PAIEMENT_ID", "DOMAINE_FONCTIONNEL_ID"
  )  AS 
  SELECT rownum id, t1."MISE_EN_PAIEMENT_ID",t1."INTERVENANT_ID",t1."STRUCTURE_ID", t1.periode_paiement_id, t1.domaine_fonctionnel_id from (

SELECT
  mep.id                   mise_en_paiement_id,
  fr.intervenant_id        intervenant_id,
  cc.structure_id          structure_id,
  mep.periode_paiement_id  periode_paiement_id,
  null                     domaine_fonctionnel_id
FROM
  formule_resultat fr
  JOIN formule_resultat_service_ref frsr ON frsr.formule_resultat_id = fr.id
  JOIN mise_en_paiement              mep ON mep.formule_res_service_ref_id = frsr.id
  JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id

UNION

SELECT
  mep.id                   mise_en_paiement_id,
  fr.intervenant_id        intervenant_id,
  cc.structure_id          structure_id,
  mep.periode_paiement_id  periode_paiement_id,
  e.domaine_fonctionnel_id domaine_fonctionnel_id
FROM
  formule_resultat fr
  JOIN formule_resultat_service        frs ON frs.formule_resultat_id = fr.id
  JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
  JOIN centre_cout                      cc ON cc.id = mep.centre_cout_id
  JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique         ep ON ep.id = s.element_pedagogique_id
  JOIN etape                             e ON e.id = ep.etape_id
) t1;
---------------------------
--Modifié VIEW
--V_INDIC_DIFF_DOSSIER
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDIC_DIFF_DOSSIER" 
 ( "ID", "NOM_USUEL", "ADRESSE_DOSSIER", "ADRESSE_IMPORT", "RIB_DOSSIER", "RIB_IMPORT", "NOM_USUEL_DOSSIER", "NOM_USUEL_IMPORT", "PRENOM_DOSSIER", "PRENOM_IMPORT"
  )  AS 
  select 
    i.id,
    i.nom_usuel,
    case when d.adresse <> a.to_string                                              then d.adresse                            else null end adresse_dossier,
    case when d.adresse <> a.to_string                                              then a.to_string                          else null end adresse_import,
    case when d.rib <> REPLACE(i.BIC || '-' || i.IBAN, ' ')                         then d.rib                                else null end rib_dossier,
    case when d.rib <> REPLACE(i.BIC || '-' || i.IBAN, ' ')                         then REPLACE(i.BIC || '-' || i.IBAN, ' ') else null end rib_import,
    case when UPPER(REPLACE(d.nom_usuel, ' ')) <> UPPER(REPLACE(i.nom_usuel, ' '))  then REPLACE(d.nom_usuel, ' ')            else null end nom_usuel_dossier,
    case when UPPER(REPLACE(d.nom_usuel, ' ')) <> UPPER(REPLACE(i.nom_usuel, ' '))  then REPLACE(i.nom_usuel, ' ')            else null end nom_usuel_import,
    case when UPPER(REPLACE(d.prenom, ' ')) <> UPPER(REPLACE(i.prenom, ' '))        then REPLACE(d.prenom, ' ')               else null end prenom_dossier,
    case when UPPER(REPLACE(d.prenom, ' ')) <> UPPER(REPLACE(i.prenom, ' '))        then REPLACE(i.prenom, ' ')               else null end prenom_import
  from intervenant i
  join intervenant_exterieur ie on i.id = ie.id
  join dossier d on ie.id = d.intervenant_id -- NB: la contrainte d'unicité sur DOSSIER garantit qu'il n'y a qu'un dossier par intervenant
  left join adresse_intervenant_princ a on a.intervenant_id = i.id;
---------------------------
--Modifié VIEW
--V_INDIC_DEPASS_REF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDIC_DEPASS_REF" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "TYPE_VOLUME_HORAIRE_ID", "STRUCTURE_ID", "TOTAL", "PLAFOND"
  )  AS 
  with totaux as (
  select fr.intervenant_id, fr.type_volume_horaire_id, sum(fr.service_referentiel) total
  from formule_resultat fr
  join etat_volume_horaire evh on evh.id = fr.etat_volume_horaire_id and evh.code = 'saisi'
  group by fr.intervenant_id, fr.type_volume_horaire_id
  having sum(fr.service_referentiel) > 0
),
depass as (
  select i.id intervenant_id, t.type_volume_horaire_id, t.total, si.plafond_referentiel plafond
  from intervenant i
  join statut_intervenant si on i.statut_id = si.id and si.plafond_referentiel is not null and si.plafond_referentiel <> 0
  join totaux t on t.intervenant_id = i.id
  where t.total > si.plafond_referentiel
),
str_interv as (
  -- structures d'intervention distinctes par intervenant et type de VH
  select distinct s.intervenant_id, vh.type_volume_horaire_id, s.structure_id
  from service_referentiel s
  join volume_horaire_ref vh on vh.service_referentiel_id = s.id and 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
  join v_vol_horaire_ref_etat_multi vhe on vhe.volume_horaire_ref_id = vh.id
  join etat_volume_horaire evh on vhe.etat_volume_horaire_id = evh.id and evh.code = 'saisi'
  where 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
)
select to_number(d.intervenant_id||d.type_volume_horaire_id||str.structure_id) id, 2014 annee_id, d.intervenant_id, d.type_volume_horaire_id, str.structure_id, d.total, d.plafond
from depass d
join str_interv str on str.intervenant_id = d.intervenant_id and str.type_volume_horaire_id = d.type_volume_horaire_id;
---------------------------
--Modifié VIEW
--V_INDIC_DEPASS_HC_HORS_REMU_FC
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDIC_DEPASS_HC_HORS_REMU_FC" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "TYPE_VOLUME_HORAIRE_ID", "STRUCTURE_ID", "TOTAL", "PLAFOND"
  )  AS 
  with totaux as (
  -- totaux HC FI+FA+FC+Ref par intervenant et type de VH
  select fr.intervenant_id, fr.type_volume_horaire_id, sum(fr.heures_compl_fi + fr.heures_compl_fa + fr.heures_compl_fc + fr.heures_compl_referentiel) total
  from formule_resultat fr
  join etat_volume_horaire evh on evh.id = fr.etat_volume_horaire_id and evh.code = 'saisi'
  group by fr.intervenant_id, fr.type_volume_horaire_id
),
depass as (
  -- totaux HC FI+FA+FC+Ref dépassant le plafond HC par intervenant et type de VH
  select i.id intervenant_id, t.type_volume_horaire_id, t.total, si.plafond_hc_hors_remu_fc plafond
  from intervenant i
  join statut_intervenant si on i.statut_id = si.id and si.plafond_hc_hors_remu_fc is not null
  join totaux t on t.intervenant_id = i.id
  where t.total > si.plafond_hc_hors_remu_fc
),
str_interv as (
  -- structures d'intervention distinctes par intervenant et type de VH
  select distinct s.intervenant_id, vh.type_volume_horaire_id, coalesce(ep.structure_id, i.structure_id) structure_id
  from service s
  left join element_pedagogique ep on s.element_pedagogique_id = ep.id and 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
  join intervenant i on s.intervenant_id = i.id and 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
  join volume_horaire vh on vh.service_id = s.id and 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
  join v_vol_horaire_etat_multi vhe on vhe.volume_horaire_id = vh.id
  join etat_volume_horaire evh on vhe.etat_volume_horaire_id = evh.id and evh.code = 'saisi'
  where 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
)
select to_number(d.intervenant_id||d.type_volume_horaire_id||str.structure_id) id, 2014 annee_id, d.intervenant_id, d.type_volume_horaire_id, str.structure_id, d.total, d.plafond
from depass d
join str_interv str on str.intervenant_id = d.intervenant_id and str.type_volume_horaire_id = d.type_volume_horaire_id;
---------------------------
--Modifié VIEW
--V_INDIC_ATTENTE_MEP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDIC_ATTENTE_MEP" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TOTAL_HEURES_MEP"
  )  AS 
  with 
  -- total des heures comp ayant fait l'objet d'une *demande* de mise en paiement
  mep as (
    select intervenant_id, structure_id, sum(nvl(mep_heures, 0)) total_heures_mep
    from (
      -- enseignements
      select 
        fr.intervenant_id, 
        nvl(ep.structure_id, i.structure_id) structure_id, 
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service frs on mep.formule_res_service_id = frs.id
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service s on frs.service_id = s.id
      left join element_pedagogique ep on s.element_pedagogique_id = ep.id and 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction) and mep.date_mise_en_paiement is null -- si date_mise_en_paiement = null, c'est une demande
      union all
      -- referentiel
      select 
        fr.intervenant_id, 
        s.structure_id,
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service_ref frs on mep.formule_res_service_ref_id = frs.id
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service_referentiel s on frs.service_referentiel_id = s.id
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction) and mep.date_mise_en_paiement is null -- si date_mise_en_paiement = null, c'est une demande
    )
    group by intervenant_id, structure_id
  )
select to_number(intervenant_id||structure_id) id, 2014 annee_id, intervenant_id, structure_id, total_heures_mep from mep;
---------------------------
--Modifié VIEW
--V_INDIC_ATTENTE_DEMANDE_MEP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDIC_ATTENTE_DEMANDE_MEP" 
 ( "ID", "INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID", "TOTAL_HEURES_COMPL", "TOTAL_HEURES_MEP"
  )  AS 
  with 
  -- total des heures comp ayant fait l'objet d'une (demande de) mise en paiement
  mep as (
    select intervenant_id, structure_id, sum(nvl(mep_heures, 0)) total_heures_mep
    from (
      -- enseignements
      select 
        fr.intervenant_id, 
        nvl(ep.structure_id, i.structure_id) structure_id, 
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service frs on mep.formule_res_service_id = frs.id --and mep.date_mise_en_paiement is null -- date_mise_en_paiement is null <=> demande
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service s on frs.service_id = s.id
      left join element_pedagogique ep on s.element_pedagogique_id = ep.id and 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction)
      union all
      -- referentiel
      select 
        fr.intervenant_id, 
        s.structure_id,
        nvl(mep.heures, 0) mep_heures
      from mise_en_paiement mep
      join formule_resultat_service_ref frs on mep.formule_res_service_ref_id = frs.id --and mep.date_mise_en_paiement is null -- date_mise_en_paiement is null <=> demande
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service_referentiel s on frs.service_referentiel_id = s.id
      where 1 = ose_divers.comprise_entre(mep.histo_creation, mep.histo_destruction)
    )
    group by intervenant_id, structure_id
  ),
  -- total des heures comp
  hc as (
    select intervenant_id, structure_id, sum(nvl(total_heures_compl, 0)) total_heures_compl
    from (
      -- enseignements
      select 
        fr.intervenant_id, 
        nvl(ep.structure_id, i.structure_id) structure_id, 
        nvl(frs.heures_compl_fi, 0) + nvl(frs.heures_compl_fa, 0) + nvl(frs.heures_compl_fc, 0) + nvl(frs.heures_compl_fc_majorees, 0) total_heures_compl
      from formule_resultat_service frs
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service s on frs.service_id = s.id
      left join element_pedagogique ep on s.element_pedagogique_id = ep.id and 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      union all
      -- referentiel
      select 
        fr.intervenant_id, 
        s.structure_id,
        nvl(frs.heures_compl_referentiel, 0) total_heures_compl
      from formule_resultat_service_ref frs
      join formule_resultat fr on frs.formule_resultat_id = fr.id
      join intervenant i on fr.intervenant_id = i.id
      join type_volume_horaire tvh on fr.type_volume_horaire_id = tvh.id and tvh.code = 'REALISE'
      join etat_volume_horaire evh on fr.etat_volume_horaire_id = evh.id and evh.code = 'valide'
      join service_referentiel s on frs.service_referentiel_id = s.id
    )
    group by intervenant_id, structure_id
  )
select to_number(i.id||hc.structure_id) id, i.id intervenant_id, 2014 annee_id, hc.structure_id, hc.total_heures_compl, nvl(mep.total_heures_mep, 0) total_heures_mep
from intervenant i
join hc on hc.intervenant_id = i.id
left join mep on mep.intervenant_id = i.id and hc.structure_id = mep.structure_id
where nvl(mep.total_heures_mep, 0) < hc.total_heures_compl
order by id, hc.structure_id;
---------------------------
--Modifié VIEW
--V_FR_SERVICE_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FR_SERVICE_CENTRE_COUT" 
 ( "FORMULE_RESULTAT_SERVICE_ID", "CENTRE_COUT_ID"
  )  AS 
  SELECT
  frs.id formule_resultat_service_id, cc.id centre_cout_id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id
  JOIN element_pedagogique    ep ON ep.id = s.element_pedagogique_id
  JOIN centre_cout            cc ON cc.structure_id = ep.structure_id AND 1 = ose_divers.comprise_entre( cc.histo_creation, cc.histo_destruction )
  JOIN cc_activite             a ON a.id = cc.activite_id AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id AND 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction )
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  )

UNION

SELECT
  frs.id formule_resultat_service_id, cc.id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id AND s.element_pedagogique_id IS NULL
  JOIN intervenant             i ON i.id = s.intervenant_id
  JOIN centre_cout            cc ON cc.structure_id = i.structure_id AND 1 = ose_divers.comprise_entre( cc.histo_creation, cc.histo_destruction )
  JOIN cc_activite             a ON a.id = cc.activite_id AND 1 = ose_divers.comprise_entre( a.histo_creation, a.histo_destruction )
  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id AND 1 = ose_divers.comprise_entre( tr.histo_creation, tr.histo_destruction )
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  );
---------------------------
--Modifié VIEW
--V_FORMULE_VOLUME_HORAIRE_REF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_VOLUME_HORAIRE_REF" 
 ( "ID", "SERVICE_REFERENTIEL_ID", "INTERVENANT_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ORDRE", "HEURES"
  )  AS 
  SELECT
  vhr.id                      id,
  sr.id                       service_referentiel_id,
  sr.intervenant_id           intervenant_id,
  vhr.type_volume_horaire_id  type_volume_horaire_id,
  evh.id                      etat_volume_horaire_id,
  evh.ordre                   etat_volume_horaire_ordre,
  vhr.heures                  heures
FROM
  volume_horaire_ref               vhr
  JOIN service_referentiel          sr ON sr.id     = vhr.service_referentiel_id
  JOIN v_volume_horaire_ref_etat  vher ON vher.volume_horaire_ref_id = vhr.id
  JOIN etat_volume_horaire         evh ON evh.id = vher.etat_volume_horaire_id
WHERE
  1 = ose_divers.comprise_entre( vhr.histo_creation, vhr.histo_destruction, ose_formule.get_date_obs )
  AND 1 = ose_divers.comprise_entre( sr.histo_creation,   sr.histo_destruction,   ose_formule.get_date_obs )
  AND vhr.heures <> 0;
---------------------------
--Modifié VIEW
--V_FORMULE_VOLUME_HORAIRE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_VOLUME_HORAIRE" 
 ( "ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENTION_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ORDRE", "HEURES", "TAUX_SERVICE_DU", "TAUX_SERVICE_COMPL"
  )  AS 
  SELECT
  vh.id                       id,
  s.id                        service_id,
  s.intervenant_id            intervenant_id,
  ti.id                       type_intervention_id,
  vh.type_volume_horaire_id   type_volume_horaire_id,
  evh.id                      etat_volume_horaire_id,
  evh.ordre                   etat_volume_horaire_ordre,
  vh.heures                   heures,
  ti.taux_hetd_service        taux_service_du,
  ti.taux_hetd_complementaire taux_service_compl
FROM
  volume_horaire               vh
  JOIN service                  s ON s.id     = vh.service_id
  JOIN type_intervention       ti ON ti.id    = vh.type_intervention_id
  JOIN v_volume_horaire_etat  vhe ON vhe.volume_horaire_id = vh.id
  JOIN etat_volume_horaire    evh ON evh.id = vhe.etat_volume_horaire_id
WHERE
  1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction, ose_formule.get_date_obs )
  AND 1 = ose_divers.comprise_entre( s.histo_creation,   s.histo_destruction,   ose_formule.get_date_obs )
  AND vh.heures <> 0
  AND vh.motif_non_paiement_id IS NULL;
---------------------------
--Modifié VIEW
--V_FORMULE_SERVICE_REF
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_REF" 
 ( "ID", "INTERVENANT_ID", "STRUCTURE_ID"
  )  AS 
  SELECT
  sr.id             id,
  sr.intervenant_id intervenant_id,
  sr.structure_id   structure_id
FROM
  service_referentiel sr
  JOIN intervenant i ON i.id = sr.intervenant_id
WHERE
  1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs );
---------------------------
--Modifié VIEW
--V_FORMULE_SERVICE_MODIFIE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_MODIFIE" 
 ( "ID", "INTERVENANT_ID", "HEURES"
  )  AS 
  SELECT
  msd.intervenant_id id,
  msd.intervenant_id,
  NVL( SUM( msd.heures * mms.multiplicateur ), 0 ) heures
FROM
  modification_service_du msd
  JOIN MOTIF_MODIFICATION_SERVICE mms ON 
    mms.id = msd.motif_id
    AND 1 = ose_divers.comprise_entre( mms.histo_creation, mms.histo_destruction, ose_formule.get_date_obs )
  JOIN intervenant i ON i.id = msd.intervenant_id
  JOIN type_intervenant ti ON ti.id = i.type_id
WHERE
  1 = ose_divers.comprise_entre( msd.histo_creation, msd.histo_destruction, ose_formule.get_date_obs)
  AND 1 = ose_divers.intervenant_has_privilege(msd.intervenant_id, 'modif-service-du-association')
GROUP BY
  msd.intervenant_id;
---------------------------
--Modifié VIEW
--V_FORMULE_SERVICE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE" 
 ( "ID", "INTERVENANT_ID", "TAUX_FI", "TAUX_FA", "TAUX_FC", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "PONDERATION_SERVICE_DU", "PONDERATION_SERVICE_COMPL"
  )  AS 
  SELECT
  s.id              id,
  s.intervenant_id  intervenant_id,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END taux_fi,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END taux_fa,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END taux_fc,
  i.structure_id,
  ep.structure_id,
  NVL( EXP (SUM (LN (m.ponderation_service_du))), 1) ponderation_service_du,
  NVL( EXP (SUM (LN (m.ponderation_service_compl))), 1) ponderation_service_compl
FROM
  service s
  JOIN intervenant i ON i.id = s.intervenant_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN element_modulateur em ON em.element_id = s.element_pedagogique_id
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction, ose_formule.get_date_obs )
  LEFT JOIN modulateur         m ON m.id = em.modulateur_id
WHERE
  1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
GROUP BY
  s.id,
  s.intervenant_id,
  ep.id,
  ep.taux_fi, ep.taux_fa, ep.taux_fc,
  i.structure_id, ep.structure_id;
---------------------------
--Modifié VIEW
--V_EXPORT_PAIEMENT_WINPAIE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_EXPORT_PAIEMENT_WINPAIE" 
 ( "ANNEE_ID", "STRUCTURE_ID", "PERIODE_PAIEMENT_ID", "INTERVENANT_ID", "INSEE", "NOM", "CARTE", "CODE_ORIGINE", "RETENUE", "SENS", "MC", "NBU", "MONTANT", "LIBELLE"
  )  AS 
  SELECT
  i.annee_id,
  t2.structure_id,
  t2.periode_paiement_id,
  i.id intervenant_id,
  
  NVL(i.numero_insee,'') || TRIM(NVL(TO_CHAR(i.numero_insee_cle,'00'),'')) insee,
  i.nom_usuel || ',' || i.prenom nom,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_carte' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) carte,
  t2.code_origine,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_retenue' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) retenue,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_sens' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) sens,
  to_char((SELECT valeur FROM parametre WHERE nom = 'winpaie_mc' AND 1=ose_divers.comprise_entre(histo_creation,histo_destruction))) mc,
  t2.nbu,
  OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) ) montant,
  s.unite_budgetaire || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id+1) 
  /*  || ' ' || to_char(FLOOR(t2.nbu)) || ' H' || CASE
      WHEN to_char(ROUND( t2.nbu-FLOOR(t2.nbu), 2 )*100,'00') = ' 00' THEN '' 
      ELSE to_char(ROUND( t2.nbu-FLOOR(t2.nbu), 2 )*100,'00') END*/ libelle
FROM (
  SELECT
    structure_id,
    periode_paiement_id,
    intervenant_id,
    code_origine,
    ROUND( SUM(nbu), 2) nbu,
    date_mise_en_paiement
  FROM (
    WITH mep AS (
    SELECT
      -- pour les filtres
      mep.id,
      mis.structure_id,
      mep.periode_paiement_id,
      mis.intervenant_id,
      mep.heures,
      mep.date_mise_en_paiement
    FROM
      v_mep_intervenant_structure  mis
      JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
    WHERE
      mep.date_mise_en_paiement IS NOT NULL
      AND mep.periode_paiement_id IS NOT NULL
    )
    SELECT
      mep.id,
      mep.structure_id,
      mep.periode_paiement_id,
      mep.intervenant_id,
      2 code_origine,
      mep.heures * 4 / 10 nbu,
      mep.date_mise_en_paiement
    FROM
      mep
    WHERE
      mep.heures * 4 / 10 > 0
      
    UNION
    
    SELECT 
      mep.id,
      mep.structure_id,
      mep.periode_paiement_id,
      mep.intervenant_id,
      1 code_origine,
      mep.heures * 6 / 10 nbu,
      mep.date_mise_en_paiement
    FROM
      mep
    WHERE
      mep.heures * 6 / 10 > 0
  ) t1
  GROUP BY
    structure_id,
    periode_paiement_id,
    intervenant_id,
    code_origine,
    date_mise_en_paiement
) t2
JOIN intervenant i ON i.id = t2.intervenant_id
JOIN structure s ON s.id = t2.structure_id;
---------------------------
--Modifié VIEW
--V_ETAT_PAIEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_ETAT_PAIEMENT" 
 ( "PERIODE_PAIEMENT_ID", "STRUCTURE_ID", "INTERVENANT_ID", "ANNEE_ID", "CENTRE_COUT_ID", "DOMAINE_FONCTIONNEL_ID", "ETAT", "STRUCTURE_LIBELLE", "DATE_MISE_EN_PAIEMENT", "PERIODE_PAIEMENT_LIBELLE", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_NUMERO_INSEE", "CENTRE_COUT_CODE", "CENTRE_COUT_LIBELLE", "DOMAINE_FONCTIONNEL_CODE", "DOMAINE_FONCTIONNEL_LIBELLE", "HETD", "HETD_POURC", "HETD_MONTANT", "REM_FC_D714", "EXERCICE_AA", "EXERCICE_AA_MONTANT", "EXERCICE_AC", "EXERCICE_AC_MONTANT"
  )  AS 
  SELECT
  periode_paiement_id,
  structure_id, 
  intervenant_id, 
  annee_id, 
  centre_cout_id, 
  domaine_fonctionnel_id,
  etat,
  structure_libelle,
  date_mise_en_paiement,
  periode_paiement_libelle,
  intervenant_code,
  intervenant_nom,
  intervenant_numero_insee,
  centre_cout_code,
  centre_cout_libelle,
  domaine_fonctionnel_code,
  domaine_fonctionnel_libelle,
  hetd,
  CASE WHEN pourc_ecart >= 0 THEN
    CASE WHEN RANK() OVER (PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END DESC) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
  ELSE
    CASE WHEN RANK() OVER (PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
  END hetd_pourc,
  hetd_montant,
  rem_fc_d714,
  exercice_aa,
  exercice_aa_montant,
  exercice_ac,
  exercice_ac_montant 
FROM
(
SELECT
  dep3.*,
  
  1-CASE WHEN hetd > 0 THEN SUM( hetd_pourc ) OVER ( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END pourc_ecart
  
  
FROM (

SELECT 
  periode_paiement_id,
  structure_id, 
  intervenant_id, 
  annee_id, 
  centre_cout_id, 
  domaine_fonctionnel_id,
  etat,
  structure_libelle,
  date_mise_en_paiement,
  periode_paiement_libelle,
  intervenant_code,
  intervenant_nom,
  intervenant_numero_insee,
  centre_cout_code,
  centre_cout_libelle,
  domaine_fonctionnel_code,
  domaine_fonctionnel_libelle,
  hetd,
  ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) hetd_pourc,
  ROUND( hetd * taux_horaire, 2 ) hetd_montant,
  ROUND( fc_majorees * taux_horaire, 2 ) rem_fc_d714,
  exercice_aa,
  ROUND( exercice_aa * taux_horaire, 2 ) exercice_aa_montant,
  exercice_ac,
  ROUND( exercice_ac * taux_horaire, 2 ) exercice_ac_montant,
  
  
  (CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END)
  -
  ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_paiement_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) pourc_diff

FROM (
  WITH dep AS ( -- détails par état de paiement
  SELECT
    p.id                                                                periode_paiement_id,
    s.id                                                                structure_id,
    i.id                                                                intervenant_id,
    i.annee_id                                                          annee_id,
    cc.id                                                               centre_cout_id,
    df.id                                                               domaine_fonctionnel_id,
    CASE
        WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
        ELSE 'mis-en-paiement'
    END                                                                 etat,

    p.libelle_long                                                      periode_paiement_libelle,
    mep.date_mise_en_paiement                                           date_mise_en_paiement,
    s.libelle_court                                                     structure_libelle,
    i.source_code                                                       intervenant_code,
    i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
    TRIM( NVL(i.numero_insee,'') || NVL(TO_CHAR(i.numero_insee_cle,'00'),'') ) intervenant_numero_insee,
    cc.source_code                                                      centre_cout_code,
    cc.libelle                                                          centre_cout_libelle,
    df.source_code                                                      domaine_fonctionnel_code,
    df.libelle                                                          domaine_fonctionnel_libelle,
    CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
    CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
    mep.heures * 4 / 10                                                 exercice_aa,
    mep.heures * 6 / 10                                                 exercice_ac,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
  FROM
    v_mep_intervenant_structure  mis
    JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
    JOIN type_heures              th ON  th.id = mep.type_heures_id
    JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
    JOIN intervenant               i ON   i.id = mis.intervenant_id      AND 1 = ose_divers.comprise_entre(   i.histo_creation,   i.histo_destruction )
    JOIN structure                 s ON   s.id = mis.structure_id
    LEFT JOIN validation           v ON   v.id = mep.validation_id       AND 1 = ose_divers.comprise_entre(   v.histo_creation,   v.histo_destruction )
    LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
    LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
  )
  SELECT
    periode_paiement_id,
    structure_id, 
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    structure_libelle,
    date_mise_en_paiement,
    intervenant_code,
    intervenant_nom,
    intervenant_numero_insee,
    centre_cout_code,
    centre_cout_libelle,
    domaine_fonctionnel_code,
    domaine_fonctionnel_libelle,
    SUM( hetd ) hetd,
    SUM( fc_majorees ) fc_majorees,
    SUM( exercice_aa ) exercice_aa,
    SUM( exercice_ac ) exercice_ac,
    taux_horaire
  FROM
    dep
  GROUP BY
    periode_paiement_id,
    structure_id, 
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    structure_libelle,
    date_mise_en_paiement,
    intervenant_code,
    intervenant_nom,
    intervenant_numero_insee,
    centre_cout_code,
    centre_cout_libelle,
    domaine_fonctionnel_code,
    domaine_fonctionnel_libelle,
    taux_horaire
) 
dep2
)
dep3
)
dep4;
---------------------------
--Modifié VIEW
--V_DIFF_TYPE_MODULATEUR_EP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_TYPE_MODULATEUR_EP" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "TYPE_MODULATEUR_ID", "U_ELEMENT_PEDAGOGIQUE_ID", "U_TYPE_MODULATEUR_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."TYPE_MODULATEUR_ID",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_TYPE_MODULATEUR_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_MODULATEUR_ID ELSE S.TYPE_MODULATEUR_ID END TYPE_MODULATEUR_ID,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.TYPE_MODULATEUR_ID <> S.TYPE_MODULATEUR_ID OR (D.TYPE_MODULATEUR_ID IS NULL AND S.TYPE_MODULATEUR_ID IS NOT NULL) OR (D.TYPE_MODULATEUR_ID IS NOT NULL AND S.TYPE_MODULATEUR_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_MODULATEUR_ID
FROM
  TYPE_MODULATEUR_EP D
  LEFT JOIN ELEMENT_PEDAGOGIQUE rt ON rt.ID = d.ELEMENT_PEDAGOGIQUE_ID
  FULL JOIN SRC_TYPE_MODULATEUR_EP S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.TYPE_MODULATEUR_ID <> S.TYPE_MODULATEUR_ID OR (D.TYPE_MODULATEUR_ID IS NULL AND S.TYPE_MODULATEUR_ID IS NOT NULL) OR (D.TYPE_MODULATEUR_ID IS NOT NULL AND S.TYPE_MODULATEUR_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_TYPE_INTERVENTION_EP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_TYPE_INTERVENTION_EP" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID", "VISIBLE", "U_ELEMENT_PEDAGOGIQUE_ID", "U_TYPE_INTERVENTION_ID", "U_VISIBLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."TYPE_INTERVENTION_ID",diff."VISIBLE",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_TYPE_INTERVENTION_ID",diff."U_VISIBLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_INTERVENTION_ID ELSE S.TYPE_INTERVENTION_ID END TYPE_INTERVENTION_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VISIBLE ELSE S.VISIBLE END VISIBLE,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_INTERVENTION_ID,
    CASE WHEN D.VISIBLE <> S.VISIBLE OR (D.VISIBLE IS NULL AND S.VISIBLE IS NOT NULL) OR (D.VISIBLE IS NOT NULL AND S.VISIBLE IS NULL) THEN 1 ELSE 0 END U_VISIBLE
FROM
  TYPE_INTERVENTION_EP D
  LEFT JOIN ELEMENT_PEDAGOGIQUE rt ON rt.ID = d.ELEMENT_PEDAGOGIQUE_ID
  FULL JOIN SRC_TYPE_INTERVENTION_EP S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL)
  OR D.VISIBLE <> S.VISIBLE OR (D.VISIBLE IS NULL AND S.VISIBLE IS NOT NULL) OR (D.VISIBLE IS NOT NULL AND S.VISIBLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_TYPE_FORMATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_TYPE_FORMATION" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "GROUPE_ID", "LIBELLE_COURT", "LIBELLE_LONG", "U_GROUPE_ID", "U_LIBELLE_COURT", "U_LIBELLE_LONG"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."GROUPE_ID",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_GROUPE_ID",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.GROUPE_ID ELSE S.GROUPE_ID END GROUPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN D.GROUPE_ID <> S.GROUPE_ID OR (D.GROUPE_ID IS NULL AND S.GROUPE_ID IS NOT NULL) OR (D.GROUPE_ID IS NOT NULL AND S.GROUPE_ID IS NULL) THEN 1 ELSE 0 END U_GROUPE_ID,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG
FROM
  TYPE_FORMATION D
  FULL JOIN SRC_TYPE_FORMATION S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.GROUPE_ID <> S.GROUPE_ID OR (D.GROUPE_ID IS NULL AND S.GROUPE_ID IS NOT NULL) OR (D.GROUPE_ID IS NOT NULL AND S.GROUPE_ID IS NULL)
  OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_STRUCTURE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ETABLISSEMENT_ID", "LIBELLE_COURT", "LIBELLE_LONG", "NIVEAU", "PARENTE_ID", "STRUCTURE_NIV2_ID", "TYPE_ID", "UNITE_BUDGETAIRE", "U_ETABLISSEMENT_ID", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_NIVEAU", "U_PARENTE_ID", "U_STRUCTURE_NIV2_ID", "U_TYPE_ID", "U_UNITE_BUDGETAIRE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ETABLISSEMENT_ID",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."NIVEAU",diff."PARENTE_ID",diff."STRUCTURE_NIV2_ID",diff."TYPE_ID",diff."UNITE_BUDGETAIRE",diff."U_ETABLISSEMENT_ID",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_NIVEAU",diff."U_PARENTE_ID",diff."U_STRUCTURE_NIV2_ID",diff."U_TYPE_ID",diff."U_UNITE_BUDGETAIRE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ETABLISSEMENT_ID ELSE S.ETABLISSEMENT_ID END ETABLISSEMENT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NIVEAU ELSE S.NIVEAU END NIVEAU,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PARENTE_ID ELSE S.PARENTE_ID END PARENTE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_NIV2_ID ELSE S.STRUCTURE_NIV2_ID END STRUCTURE_NIV2_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_ID ELSE S.TYPE_ID END TYPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.UNITE_BUDGETAIRE ELSE S.UNITE_BUDGETAIRE END UNITE_BUDGETAIRE,
    CASE WHEN D.ETABLISSEMENT_ID <> S.ETABLISSEMENT_ID OR (D.ETABLISSEMENT_ID IS NULL AND S.ETABLISSEMENT_ID IS NOT NULL) OR (D.ETABLISSEMENT_ID IS NOT NULL AND S.ETABLISSEMENT_ID IS NULL) THEN 1 ELSE 0 END U_ETABLISSEMENT_ID,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL) THEN 1 ELSE 0 END U_NIVEAU,
    CASE WHEN D.PARENTE_ID <> S.PARENTE_ID OR (D.PARENTE_ID IS NULL AND S.PARENTE_ID IS NOT NULL) OR (D.PARENTE_ID IS NOT NULL AND S.PARENTE_ID IS NULL) THEN 1 ELSE 0 END U_PARENTE_ID,
    CASE WHEN D.STRUCTURE_NIV2_ID <> S.STRUCTURE_NIV2_ID OR (D.STRUCTURE_NIV2_ID IS NULL AND S.STRUCTURE_NIV2_ID IS NOT NULL) OR (D.STRUCTURE_NIV2_ID IS NOT NULL AND S.STRUCTURE_NIV2_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_NIV2_ID,
    CASE WHEN D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_ID,
    CASE WHEN D.UNITE_BUDGETAIRE <> S.UNITE_BUDGETAIRE OR (D.UNITE_BUDGETAIRE IS NULL AND S.UNITE_BUDGETAIRE IS NOT NULL) OR (D.UNITE_BUDGETAIRE IS NOT NULL AND S.UNITE_BUDGETAIRE IS NULL) THEN 1 ELSE 0 END U_UNITE_BUDGETAIRE
FROM
  STRUCTURE D
  FULL JOIN SRC_STRUCTURE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ETABLISSEMENT_ID <> S.ETABLISSEMENT_ID OR (D.ETABLISSEMENT_ID IS NULL AND S.ETABLISSEMENT_ID IS NOT NULL) OR (D.ETABLISSEMENT_ID IS NOT NULL AND S.ETABLISSEMENT_ID IS NULL)
  OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
  OR D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL)
  OR D.PARENTE_ID <> S.PARENTE_ID OR (D.PARENTE_ID IS NULL AND S.PARENTE_ID IS NOT NULL) OR (D.PARENTE_ID IS NOT NULL AND S.PARENTE_ID IS NULL)
  OR D.STRUCTURE_NIV2_ID <> S.STRUCTURE_NIV2_ID OR (D.STRUCTURE_NIV2_ID IS NULL AND S.STRUCTURE_NIV2_ID IS NOT NULL) OR (D.STRUCTURE_NIV2_ID IS NOT NULL AND S.STRUCTURE_NIV2_ID IS NULL)
  OR D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL)
  OR D.UNITE_BUDGETAIRE <> S.UNITE_BUDGETAIRE OR (D.UNITE_BUDGETAIRE IS NULL AND S.UNITE_BUDGETAIRE IS NOT NULL) OR (D.UNITE_BUDGETAIRE IS NOT NULL AND S.UNITE_BUDGETAIRE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_PERSONNEL
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_PERSONNEL" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CIVILITE_ID", "EMAIL", "NOM_PATRONYMIQUE", "NOM_USUEL", "PRENOM", "STRUCTURE_ID", "U_CIVILITE_ID", "U_EMAIL", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_PRENOM", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CIVILITE_ID",diff."EMAIL",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."PRENOM",diff."STRUCTURE_ID",diff."U_CIVILITE_ID",diff."U_EMAIL",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_PRENOM",diff."U_STRUCTURE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CIVILITE_ID ELSE S.CIVILITE_ID END CIVILITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_PATRONYMIQUE ELSE S.NOM_PATRONYMIQUE END NOM_PATRONYMIQUE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_USUEL ELSE S.NOM_USUEL END NOM_USUEL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRENOM ELSE S.PRENOM END PRENOM,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
    CASE WHEN D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL) THEN 1 ELSE 0 END U_NOM_PATRONYMIQUE,
    CASE WHEN D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL) THEN 1 ELSE 0 END U_NOM_USUEL,
    CASE WHEN D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL) THEN 1 ELSE 0 END U_PRENOM,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID
FROM
  PERSONNEL D
  FULL JOIN SRC_PERSONNEL S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
  OR D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL)
  OR D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL)
  OR D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Nouveau VIEW
--V_DIFF_PAYS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_PAYS" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "TEMOIN_UE", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_TEMOIN_UE", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."TEMOIN_UE",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_TEMOIN_UE",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEMOIN_UE ELSE S.TEMOIN_UE END TEMOIN_UE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_DEBUT ELSE S.VALIDITE_DEBUT END VALIDITE_DEBUT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VALIDITE_FIN ELSE S.VALIDITE_FIN END VALIDITE_FIN,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.TEMOIN_UE <> S.TEMOIN_UE OR (D.TEMOIN_UE IS NULL AND S.TEMOIN_UE IS NOT NULL) OR (D.TEMOIN_UE IS NOT NULL AND S.TEMOIN_UE IS NULL) THEN 1 ELSE 0 END U_TEMOIN_UE,
    CASE WHEN D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL) THEN 1 ELSE 0 END U_VALIDITE_DEBUT,
    CASE WHEN D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL) THEN 1 ELSE 0 END U_VALIDITE_FIN
FROM
  PAYS D
  FULL JOIN SRC_PAYS S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
  OR D.TEMOIN_UE <> S.TEMOIN_UE OR (D.TEMOIN_UE IS NULL AND S.TEMOIN_UE IS NOT NULL) OR (D.TEMOIN_UE IS NOT NULL AND S.TEMOIN_UE IS NULL)
  OR D.VALIDITE_DEBUT <> S.VALIDITE_DEBUT OR (D.VALIDITE_DEBUT IS NULL AND S.VALIDITE_DEBUT IS NOT NULL) OR (D.VALIDITE_DEBUT IS NOT NULL AND S.VALIDITE_DEBUT IS NULL)
  OR D.VALIDITE_FIN <> S.VALIDITE_FIN OR (D.VALIDITE_FIN IS NULL AND S.VALIDITE_FIN IS NOT NULL) OR (D.VALIDITE_FIN IS NOT NULL AND S.VALIDITE_FIN IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_INTERVENANT_PERMANENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT_PERMANENT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CORPS_ID", "U_CORPS_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CORPS_ID",diff."U_CORPS_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CORPS_ID ELSE S.CORPS_ID END CORPS_ID,
    CASE WHEN D.CORPS_ID <> S.CORPS_ID OR (D.CORPS_ID IS NULL AND S.CORPS_ID IS NOT NULL) OR (D.CORPS_ID IS NOT NULL AND S.CORPS_ID IS NULL) THEN 1 ELSE 0 END U_CORPS_ID
FROM
  INTERVENANT_PERMANENT D
  LEFT JOIN INTERVENANT rt ON rt.ID = d.ID
  FULL JOIN SRC_INTERVENANT_PERMANENT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CORPS_ID <> S.CORPS_ID OR (D.CORPS_ID IS NULL AND S.CORPS_ID IS NOT NULL) OR (D.CORPS_ID IS NOT NULL AND S.CORPS_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_INTERVENANT_EXTERIEUR
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT_EXTERIEUR" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "SITUATION_FAMILIALE_ID", "U_SITUATION_FAMILIALE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."SITUATION_FAMILIALE_ID",diff."U_SITUATION_FAMILIALE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.SITUATION_FAMILIALE_ID ELSE S.SITUATION_FAMILIALE_ID END SITUATION_FAMILIALE_ID,
    CASE WHEN D.SITUATION_FAMILIALE_ID <> S.SITUATION_FAMILIALE_ID OR (D.SITUATION_FAMILIALE_ID IS NULL AND S.SITUATION_FAMILIALE_ID IS NOT NULL) OR (D.SITUATION_FAMILIALE_ID IS NOT NULL AND S.SITUATION_FAMILIALE_ID IS NULL) THEN 1 ELSE 0 END U_SITUATION_FAMILIALE_ID
FROM
  INTERVENANT_EXTERIEUR D
  LEFT JOIN INTERVENANT rt ON rt.ID = d.ID
  FULL JOIN SRC_INTERVENANT_EXTERIEUR S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.SITUATION_FAMILIALE_ID <> S.SITUATION_FAMILIALE_ID OR (D.SITUATION_FAMILIALE_ID IS NULL AND S.SITUATION_FAMILIALE_ID IS NOT NULL) OR (D.SITUATION_FAMILIALE_ID IS NOT NULL AND S.SITUATION_FAMILIALE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "BIC", "CIVILITE_ID", "DATE_NAISSANCE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "EMAIL", "IBAN", "NOM_PATRONYMIQUE", "NOM_USUEL", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "PRENOM", "STATUT_ID", "STRUCTURE_ID", "TEL_MOBILE", "TEL_PRO", "TYPE_ID", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "U_ANNEE_ID", "U_BIC", "U_CIVILITE_ID", "U_DATE_NAISSANCE", "U_DEP_NAISSANCE_CODE_INSEE", "U_DEP_NAISSANCE_LIBELLE", "U_EMAIL", "U_IBAN", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_NUMERO_INSEE", "U_NUMERO_INSEE_CLE", "U_NUMERO_INSEE_PROVISOIRE", "U_PAYS_NAISSANCE_CODE_INSEE", "U_PAYS_NAISSANCE_LIBELLE", "U_PAYS_NATIONALITE_CODE_INSEE", "U_PAYS_NATIONALITE_LIBELLE", "U_PRENOM", "U_STATUT_ID", "U_STRUCTURE_ID", "U_TEL_MOBILE", "U_TEL_PRO", "U_TYPE_ID", "U_VILLE_NAISSANCE_CODE_INSEE", "U_VILLE_NAISSANCE_LIBELLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."BIC",diff."CIVILITE_ID",diff."DATE_NAISSANCE",diff."DEP_NAISSANCE_CODE_INSEE",diff."DEP_NAISSANCE_LIBELLE",diff."EMAIL",diff."IBAN",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."NUMERO_INSEE",diff."NUMERO_INSEE_CLE",diff."NUMERO_INSEE_PROVISOIRE",diff."PAYS_NAISSANCE_CODE_INSEE",diff."PAYS_NAISSANCE_LIBELLE",diff."PAYS_NATIONALITE_CODE_INSEE",diff."PAYS_NATIONALITE_LIBELLE",diff."PRENOM",diff."STATUT_ID",diff."STRUCTURE_ID",diff."TEL_MOBILE",diff."TEL_PRO",diff."TYPE_ID",diff."VILLE_NAISSANCE_CODE_INSEE",diff."VILLE_NAISSANCE_LIBELLE",diff."U_ANNEE_ID",diff."U_BIC",diff."U_CIVILITE_ID",diff."U_DATE_NAISSANCE",diff."U_DEP_NAISSANCE_CODE_INSEE",diff."U_DEP_NAISSANCE_LIBELLE",diff."U_EMAIL",diff."U_IBAN",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_NUMERO_INSEE",diff."U_NUMERO_INSEE_CLE",diff."U_NUMERO_INSEE_PROVISOIRE",diff."U_PAYS_NAISSANCE_CODE_INSEE",diff."U_PAYS_NAISSANCE_LIBELLE",diff."U_PAYS_NATIONALITE_CODE_INSEE",diff."U_PAYS_NATIONALITE_LIBELLE",diff."U_PRENOM",diff."U_STATUT_ID",diff."U_STRUCTURE_ID",diff."U_TEL_MOBILE",diff."U_TEL_PRO",diff."U_TYPE_ID",diff."U_VILLE_NAISSANCE_CODE_INSEE",diff."U_VILLE_NAISSANCE_LIBELLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.BIC ELSE S.BIC END BIC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CIVILITE_ID ELSE S.CIVILITE_ID END CIVILITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DATE_NAISSANCE ELSE S.DATE_NAISSANCE END DATE_NAISSANCE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_CODE_INSEE ELSE S.DEP_NAISSANCE_CODE_INSEE END DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_LIBELLE ELSE S.DEP_NAISSANCE_LIBELLE END DEP_NAISSANCE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.IBAN ELSE S.IBAN END IBAN,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_PATRONYMIQUE ELSE S.NOM_PATRONYMIQUE END NOM_PATRONYMIQUE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_USUEL ELSE S.NOM_USUEL END NOM_USUEL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE ELSE S.NUMERO_INSEE END NUMERO_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE_CLE ELSE S.NUMERO_INSEE_CLE END NUMERO_INSEE_CLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE_PROVISOIRE ELSE S.NUMERO_INSEE_PROVISOIRE END NUMERO_INSEE_PROVISOIRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NAISSANCE_CODE_INSEE ELSE S.PAYS_NAISSANCE_CODE_INSEE END PAYS_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NAISSANCE_LIBELLE ELSE S.PAYS_NAISSANCE_LIBELLE END PAYS_NAISSANCE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NATIONALITE_CODE_INSEE ELSE S.PAYS_NATIONALITE_CODE_INSEE END PAYS_NATIONALITE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NATIONALITE_LIBELLE ELSE S.PAYS_NATIONALITE_LIBELLE END PAYS_NATIONALITE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRENOM ELSE S.PRENOM END PRENOM,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STATUT_ID ELSE S.STATUT_ID END STATUT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_MOBILE ELSE S.TEL_MOBILE END TEL_MOBILE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_PRO ELSE S.TEL_PRO END TEL_PRO,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_ID ELSE S.TYPE_ID END TYPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_CODE_INSEE ELSE S.VILLE_NAISSANCE_CODE_INSEE END VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_LIBELLE ELSE S.VILLE_NAISSANCE_LIBELLE END VILLE_NAISSANCE_LIBELLE,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL) THEN 1 ELSE 0 END U_BIC,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL) THEN 1 ELSE 0 END U_DATE_NAISSANCE,
    CASE WHEN D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_LIBELLE,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
    CASE WHEN D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL) THEN 1 ELSE 0 END U_IBAN,
    CASE WHEN D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL) THEN 1 ELSE 0 END U_NOM_PATRONYMIQUE,
    CASE WHEN D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL) THEN 1 ELSE 0 END U_NOM_USUEL,
    CASE WHEN D.NUMERO_INSEE <> S.NUMERO_INSEE OR (D.NUMERO_INSEE IS NULL AND S.NUMERO_INSEE IS NOT NULL) OR (D.NUMERO_INSEE IS NOT NULL AND S.NUMERO_INSEE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE,
    CASE WHEN D.NUMERO_INSEE_CLE <> S.NUMERO_INSEE_CLE OR (D.NUMERO_INSEE_CLE IS NULL AND S.NUMERO_INSEE_CLE IS NOT NULL) OR (D.NUMERO_INSEE_CLE IS NOT NULL AND S.NUMERO_INSEE_CLE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE_CLE,
    CASE WHEN D.NUMERO_INSEE_PROVISOIRE <> S.NUMERO_INSEE_PROVISOIRE OR (D.NUMERO_INSEE_PROVISOIRE IS NULL AND S.NUMERO_INSEE_PROVISOIRE IS NOT NULL) OR (D.NUMERO_INSEE_PROVISOIRE IS NOT NULL AND S.NUMERO_INSEE_PROVISOIRE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE_PROVISOIRE,
    CASE WHEN D.PAYS_NAISSANCE_CODE_INSEE <> S.PAYS_NAISSANCE_CODE_INSEE OR (D.PAYS_NAISSANCE_CODE_INSEE IS NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_NAISSANCE_CODE_INSEE,
    CASE WHEN D.PAYS_NAISSANCE_LIBELLE <> S.PAYS_NAISSANCE_LIBELLE OR (D.PAYS_NAISSANCE_LIBELLE IS NULL AND S.PAYS_NAISSANCE_LIBELLE IS NOT NULL) OR (D.PAYS_NAISSANCE_LIBELLE IS NOT NULL AND S.PAYS_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_NAISSANCE_LIBELLE,
    CASE WHEN D.PAYS_NATIONALITE_CODE_INSEE <> S.PAYS_NATIONALITE_CODE_INSEE OR (D.PAYS_NATIONALITE_CODE_INSEE IS NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_NATIONALITE_CODE_INSEE,
    CASE WHEN D.PAYS_NATIONALITE_LIBELLE <> S.PAYS_NATIONALITE_LIBELLE OR (D.PAYS_NATIONALITE_LIBELLE IS NULL AND S.PAYS_NATIONALITE_LIBELLE IS NOT NULL) OR (D.PAYS_NATIONALITE_LIBELLE IS NOT NULL AND S.PAYS_NATIONALITE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_NATIONALITE_LIBELLE,
    CASE WHEN D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL) THEN 1 ELSE 0 END U_PRENOM,
    CASE WHEN D.STATUT_ID <> S.STATUT_ID OR (D.STATUT_ID IS NULL AND S.STATUT_ID IS NOT NULL) OR (D.STATUT_ID IS NOT NULL AND S.STATUT_ID IS NULL) THEN 1 ELSE 0 END U_STATUT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TEL_MOBILE <> S.TEL_MOBILE OR (D.TEL_MOBILE IS NULL AND S.TEL_MOBILE IS NOT NULL) OR (D.TEL_MOBILE IS NOT NULL AND S.TEL_MOBILE IS NULL) THEN 1 ELSE 0 END U_TEL_MOBILE,
    CASE WHEN D.TEL_PRO <> S.TEL_PRO OR (D.TEL_PRO IS NULL AND S.TEL_PRO IS NOT NULL) OR (D.TEL_PRO IS NOT NULL AND S.TEL_PRO IS NULL) THEN 1 ELSE 0 END U_TEL_PRO,
    CASE WHEN D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_ID,
    CASE WHEN D.VILLE_NAISSANCE_CODE_INSEE <> S.VILLE_NAISSANCE_CODE_INSEE OR (D.VILLE_NAISSANCE_CODE_INSEE IS NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN D.VILLE_NAISSANCE_LIBELLE <> S.VILLE_NAISSANCE_LIBELLE OR (D.VILLE_NAISSANCE_LIBELLE IS NULL AND S.VILLE_NAISSANCE_LIBELLE IS NOT NULL) OR (D.VILLE_NAISSANCE_LIBELLE IS NOT NULL AND S.VILLE_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_VILLE_NAISSANCE_LIBELLE
FROM
  INTERVENANT D
  FULL JOIN SRC_INTERVENANT S ON S.source_id = D.source_id AND S.source_code = D.source_code AND S.ANNEE_ID = d.ANNEE_ID
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL)
  OR D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL)
  OR D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL)
  OR D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL)
  OR D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL)
  OR D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
  OR D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL)
  OR D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL)
  OR D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL)
  OR D.NUMERO_INSEE <> S.NUMERO_INSEE OR (D.NUMERO_INSEE IS NULL AND S.NUMERO_INSEE IS NOT NULL) OR (D.NUMERO_INSEE IS NOT NULL AND S.NUMERO_INSEE IS NULL)
  OR D.NUMERO_INSEE_CLE <> S.NUMERO_INSEE_CLE OR (D.NUMERO_INSEE_CLE IS NULL AND S.NUMERO_INSEE_CLE IS NOT NULL) OR (D.NUMERO_INSEE_CLE IS NOT NULL AND S.NUMERO_INSEE_CLE IS NULL)
  OR D.NUMERO_INSEE_PROVISOIRE <> S.NUMERO_INSEE_PROVISOIRE OR (D.NUMERO_INSEE_PROVISOIRE IS NULL AND S.NUMERO_INSEE_PROVISOIRE IS NOT NULL) OR (D.NUMERO_INSEE_PROVISOIRE IS NOT NULL AND S.NUMERO_INSEE_PROVISOIRE IS NULL)
  OR D.PAYS_NAISSANCE_CODE_INSEE <> S.PAYS_NAISSANCE_CODE_INSEE OR (D.PAYS_NAISSANCE_CODE_INSEE IS NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NAISSANCE_CODE_INSEE IS NOT NULL AND S.PAYS_NAISSANCE_CODE_INSEE IS NULL)
  OR D.PAYS_NAISSANCE_LIBELLE <> S.PAYS_NAISSANCE_LIBELLE OR (D.PAYS_NAISSANCE_LIBELLE IS NULL AND S.PAYS_NAISSANCE_LIBELLE IS NOT NULL) OR (D.PAYS_NAISSANCE_LIBELLE IS NOT NULL AND S.PAYS_NAISSANCE_LIBELLE IS NULL)
  OR D.PAYS_NATIONALITE_CODE_INSEE <> S.PAYS_NATIONALITE_CODE_INSEE OR (D.PAYS_NATIONALITE_CODE_INSEE IS NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL) OR (D.PAYS_NATIONALITE_CODE_INSEE IS NOT NULL AND S.PAYS_NATIONALITE_CODE_INSEE IS NULL)
  OR D.PAYS_NATIONALITE_LIBELLE <> S.PAYS_NATIONALITE_LIBELLE OR (D.PAYS_NATIONALITE_LIBELLE IS NULL AND S.PAYS_NATIONALITE_LIBELLE IS NOT NULL) OR (D.PAYS_NATIONALITE_LIBELLE IS NOT NULL AND S.PAYS_NATIONALITE_LIBELLE IS NULL)
  OR D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL)
  OR D.STATUT_ID <> S.STATUT_ID OR (D.STATUT_ID IS NULL AND S.STATUT_ID IS NOT NULL) OR (D.STATUT_ID IS NOT NULL AND S.STATUT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TEL_MOBILE <> S.TEL_MOBILE OR (D.TEL_MOBILE IS NULL AND S.TEL_MOBILE IS NOT NULL) OR (D.TEL_MOBILE IS NOT NULL AND S.TEL_MOBILE IS NULL)
  OR D.TEL_PRO <> S.TEL_PRO OR (D.TEL_PRO IS NULL AND S.TEL_PRO IS NOT NULL) OR (D.TEL_PRO IS NOT NULL AND S.TEL_PRO IS NULL)
  OR D.TYPE_ID <> S.TYPE_ID OR (D.TYPE_ID IS NULL AND S.TYPE_ID IS NOT NULL) OR (D.TYPE_ID IS NOT NULL AND S.TYPE_ID IS NULL)
  OR D.VILLE_NAISSANCE_CODE_INSEE <> S.VILLE_NAISSANCE_CODE_INSEE OR (D.VILLE_NAISSANCE_CODE_INSEE IS NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NULL)
  OR D.VILLE_NAISSANCE_LIBELLE <> S.VILLE_NAISSANCE_LIBELLE OR (D.VILLE_NAISSANCE_LIBELLE IS NULL AND S.VILLE_NAISSANCE_LIBELLE IS NOT NULL) OR (D.VILLE_NAISSANCE_LIBELLE IS NOT NULL AND S.VILLE_NAISSANCE_LIBELLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_GROUPE_TYPE_FORMATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_GROUPE_TYPE_FORMATION" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "PERTINENCE_NIVEAU", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_ORDRE", "U_PERTINENCE_NIVEAU"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."ORDRE",diff."PERTINENCE_NIVEAU",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_ORDRE",diff."U_PERTINENCE_NIVEAU" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ORDRE ELSE S.ORDRE END ORDRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PERTINENCE_NIVEAU ELSE S.PERTINENCE_NIVEAU END PERTINENCE_NIVEAU,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG,
    CASE WHEN D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL) THEN 1 ELSE 0 END U_ORDRE,
    CASE WHEN D.PERTINENCE_NIVEAU <> S.PERTINENCE_NIVEAU OR (D.PERTINENCE_NIVEAU IS NULL AND S.PERTINENCE_NIVEAU IS NOT NULL) OR (D.PERTINENCE_NIVEAU IS NOT NULL AND S.PERTINENCE_NIVEAU IS NULL) THEN 1 ELSE 0 END U_PERTINENCE_NIVEAU
FROM
  GROUPE_TYPE_FORMATION D
  FULL JOIN SRC_GROUPE_TYPE_FORMATION S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
  OR D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL)
  OR D.PERTINENCE_NIVEAU <> S.PERTINENCE_NIVEAU OR (D.PERTINENCE_NIVEAU IS NULL AND S.PERTINENCE_NIVEAU IS NOT NULL) OR (D.PERTINENCE_NIVEAU IS NOT NULL AND S.PERTINENCE_NIVEAU IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ETAPE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ETAPE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "DOMAINE_FONCTIONNEL_ID", "LIBELLE", "NIVEAU", "SPECIFIQUE_ECHANGES", "STRUCTURE_ID", "TYPE_FORMATION_ID", "U_DOMAINE_FONCTIONNEL_ID", "U_LIBELLE", "U_NIVEAU", "U_SPECIFIQUE_ECHANGES", "U_STRUCTURE_ID", "U_TYPE_FORMATION_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."DOMAINE_FONCTIONNEL_ID",diff."LIBELLE",diff."NIVEAU",diff."SPECIFIQUE_ECHANGES",diff."STRUCTURE_ID",diff."TYPE_FORMATION_ID",diff."U_DOMAINE_FONCTIONNEL_ID",diff."U_LIBELLE",diff."U_NIVEAU",diff."U_SPECIFIQUE_ECHANGES",diff."U_STRUCTURE_ID",diff."U_TYPE_FORMATION_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DOMAINE_FONCTIONNEL_ID ELSE S.DOMAINE_FONCTIONNEL_ID END DOMAINE_FONCTIONNEL_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NIVEAU ELSE S.NIVEAU END NIVEAU,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.SPECIFIQUE_ECHANGES ELSE S.SPECIFIQUE_ECHANGES END SPECIFIQUE_ECHANGES,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_FORMATION_ID ELSE S.TYPE_FORMATION_ID END TYPE_FORMATION_ID,
    CASE WHEN D.DOMAINE_FONCTIONNEL_ID <> S.DOMAINE_FONCTIONNEL_ID OR (D.DOMAINE_FONCTIONNEL_ID IS NULL AND S.DOMAINE_FONCTIONNEL_ID IS NOT NULL) OR (D.DOMAINE_FONCTIONNEL_ID IS NOT NULL AND S.DOMAINE_FONCTIONNEL_ID IS NULL) THEN 1 ELSE 0 END U_DOMAINE_FONCTIONNEL_ID,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL) THEN 1 ELSE 0 END U_NIVEAU,
    CASE WHEN D.SPECIFIQUE_ECHANGES <> S.SPECIFIQUE_ECHANGES OR (D.SPECIFIQUE_ECHANGES IS NULL AND S.SPECIFIQUE_ECHANGES IS NOT NULL) OR (D.SPECIFIQUE_ECHANGES IS NOT NULL AND S.SPECIFIQUE_ECHANGES IS NULL) THEN 1 ELSE 0 END U_SPECIFIQUE_ECHANGES,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TYPE_FORMATION_ID <> S.TYPE_FORMATION_ID OR (D.TYPE_FORMATION_ID IS NULL AND S.TYPE_FORMATION_ID IS NOT NULL) OR (D.TYPE_FORMATION_ID IS NOT NULL AND S.TYPE_FORMATION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_FORMATION_ID
FROM
  ETAPE D
  FULL JOIN SRC_ETAPE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.DOMAINE_FONCTIONNEL_ID <> S.DOMAINE_FONCTIONNEL_ID OR (D.DOMAINE_FONCTIONNEL_ID IS NULL AND S.DOMAINE_FONCTIONNEL_ID IS NOT NULL) OR (D.DOMAINE_FONCTIONNEL_ID IS NOT NULL AND S.DOMAINE_FONCTIONNEL_ID IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL)
  OR D.SPECIFIQUE_ECHANGES <> S.SPECIFIQUE_ECHANGES OR (D.SPECIFIQUE_ECHANGES IS NULL AND S.SPECIFIQUE_ECHANGES IS NOT NULL) OR (D.SPECIFIQUE_ECHANGES IS NOT NULL AND S.SPECIFIQUE_ECHANGES IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TYPE_FORMATION_ID <> S.TYPE_FORMATION_ID OR (D.TYPE_FORMATION_ID IS NULL AND S.TYPE_FORMATION_ID IS NOT NULL) OR (D.TYPE_FORMATION_ID IS NOT NULL AND S.TYPE_FORMATION_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ETABLISSEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ETABLISSEMENT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "DEPARTEMENT", "LIBELLE", "LOCALISATION", "U_DEPARTEMENT", "U_LIBELLE", "U_LOCALISATION"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."DEPARTEMENT",diff."LIBELLE",diff."LOCALISATION",diff."U_DEPARTEMENT",diff."U_LIBELLE",diff."U_LOCALISATION" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEPARTEMENT ELSE S.DEPARTEMENT END DEPARTEMENT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LOCALISATION ELSE S.LOCALISATION END LOCALISATION,
    CASE WHEN D.DEPARTEMENT <> S.DEPARTEMENT OR (D.DEPARTEMENT IS NULL AND S.DEPARTEMENT IS NOT NULL) OR (D.DEPARTEMENT IS NOT NULL AND S.DEPARTEMENT IS NULL) THEN 1 ELSE 0 END U_DEPARTEMENT,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.LOCALISATION <> S.LOCALISATION OR (D.LOCALISATION IS NULL AND S.LOCALISATION IS NOT NULL) OR (D.LOCALISATION IS NOT NULL AND S.LOCALISATION IS NULL) THEN 1 ELSE 0 END U_LOCALISATION
FROM
  ETABLISSEMENT D
  FULL JOIN SRC_ETABLISSEMENT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.DEPARTEMENT <> S.DEPARTEMENT OR (D.DEPARTEMENT IS NULL AND S.DEPARTEMENT IS NOT NULL) OR (D.DEPARTEMENT IS NOT NULL AND S.DEPARTEMENT IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.LOCALISATION <> S.LOCALISATION OR (D.LOCALISATION IS NULL AND S.LOCALISATION IS NOT NULL) OR (D.LOCALISATION IS NOT NULL AND S.LOCALISATION IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ELEMENT_TAUX_REGIMES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ELEMENT_TAUX_REGIMES" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "TAUX_FA", "TAUX_FC", "TAUX_FI", "U_ELEMENT_PEDAGOGIQUE_ID", "U_TAUX_FA", "U_TAUX_FC", "U_TAUX_FI"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."TAUX_FA",diff."TAUX_FC",diff."TAUX_FI",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_TAUX_FA",diff."U_TAUX_FC",diff."U_TAUX_FI" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FA ELSE S.TAUX_FA END TAUX_FA,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FC ELSE S.TAUX_FC END TAUX_FC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FI ELSE S.TAUX_FI END TAUX_FI,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.TAUX_FA <> S.TAUX_FA OR (D.TAUX_FA IS NULL AND S.TAUX_FA IS NOT NULL) OR (D.TAUX_FA IS NOT NULL AND S.TAUX_FA IS NULL) THEN 1 ELSE 0 END U_TAUX_FA,
    CASE WHEN D.TAUX_FC <> S.TAUX_FC OR (D.TAUX_FC IS NULL AND S.TAUX_FC IS NOT NULL) OR (D.TAUX_FC IS NOT NULL AND S.TAUX_FC IS NULL) THEN 1 ELSE 0 END U_TAUX_FC,
    CASE WHEN D.TAUX_FI <> S.TAUX_FI OR (D.TAUX_FI IS NULL AND S.TAUX_FI IS NOT NULL) OR (D.TAUX_FI IS NOT NULL AND S.TAUX_FI IS NULL) THEN 1 ELSE 0 END U_TAUX_FI
FROM
  ELEMENT_TAUX_REGIMES D
  LEFT JOIN ELEMENT_PEDAGOGIQUE rt ON rt.ID = d.ELEMENT_PEDAGOGIQUE_ID
  FULL JOIN SRC_ELEMENT_TAUX_REGIMES S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.TAUX_FA <> S.TAUX_FA OR (D.TAUX_FA IS NULL AND S.TAUX_FA IS NOT NULL) OR (D.TAUX_FA IS NOT NULL AND S.TAUX_FA IS NULL)
  OR D.TAUX_FC <> S.TAUX_FC OR (D.TAUX_FC IS NULL AND S.TAUX_FC IS NOT NULL) OR (D.TAUX_FC IS NOT NULL AND S.TAUX_FC IS NULL)
  OR D.TAUX_FI <> S.TAUX_FI OR (D.TAUX_FI IS NULL AND S.TAUX_FI IS NOT NULL) OR (D.TAUX_FI IS NOT NULL AND S.TAUX_FI IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ELEMENT_PEDAGOGIQUE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ELEMENT_PEDAGOGIQUE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "ETAPE_ID", "FA", "FC", "FI", "LIBELLE", "PERIODE_ID", "STRUCTURE_ID", "TAUX_FA", "TAUX_FC", "TAUX_FI", "TAUX_FOAD", "U_ANNEE_ID", "U_ETAPE_ID", "U_FA", "U_FC", "U_FI", "U_LIBELLE", "U_PERIODE_ID", "U_STRUCTURE_ID", "U_TAUX_FA", "U_TAUX_FC", "U_TAUX_FI", "U_TAUX_FOAD"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."ETAPE_ID",diff."FA",diff."FC",diff."FI",diff."LIBELLE",diff."PERIODE_ID",diff."STRUCTURE_ID",diff."TAUX_FA",diff."TAUX_FC",diff."TAUX_FI",diff."TAUX_FOAD",diff."U_ANNEE_ID",diff."U_ETAPE_ID",diff."U_FA",diff."U_FC",diff."U_FI",diff."U_LIBELLE",diff."U_PERIODE_ID",diff."U_STRUCTURE_ID",diff."U_TAUX_FA",diff."U_TAUX_FC",diff."U_TAUX_FI",diff."U_TAUX_FOAD" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ETAPE_ID ELSE S.ETAPE_ID END ETAPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FA ELSE S.FA END FA,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FC ELSE S.FC END FC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FI ELSE S.FI END FI,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PERIODE_ID ELSE S.PERIODE_ID END PERIODE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FA ELSE S.TAUX_FA END TAUX_FA,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FC ELSE S.TAUX_FC END TAUX_FC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FI ELSE S.TAUX_FI END TAUX_FI,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TAUX_FOAD ELSE S.TAUX_FOAD END TAUX_FOAD,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL) THEN 1 ELSE 0 END U_ETAPE_ID,
    CASE WHEN D.FA <> S.FA OR (D.FA IS NULL AND S.FA IS NOT NULL) OR (D.FA IS NOT NULL AND S.FA IS NULL) THEN 1 ELSE 0 END U_FA,
    CASE WHEN D.FC <> S.FC OR (D.FC IS NULL AND S.FC IS NOT NULL) OR (D.FC IS NOT NULL AND S.FC IS NULL) THEN 1 ELSE 0 END U_FC,
    CASE WHEN D.FI <> S.FI OR (D.FI IS NULL AND S.FI IS NOT NULL) OR (D.FI IS NOT NULL AND S.FI IS NULL) THEN 1 ELSE 0 END U_FI,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.PERIODE_ID <> S.PERIODE_ID OR (D.PERIODE_ID IS NULL AND S.PERIODE_ID IS NOT NULL) OR (D.PERIODE_ID IS NOT NULL AND S.PERIODE_ID IS NULL) THEN 1 ELSE 0 END U_PERIODE_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TAUX_FA <> S.TAUX_FA OR (D.TAUX_FA IS NULL AND S.TAUX_FA IS NOT NULL) OR (D.TAUX_FA IS NOT NULL AND S.TAUX_FA IS NULL) THEN 1 ELSE 0 END U_TAUX_FA,
    CASE WHEN D.TAUX_FC <> S.TAUX_FC OR (D.TAUX_FC IS NULL AND S.TAUX_FC IS NOT NULL) OR (D.TAUX_FC IS NOT NULL AND S.TAUX_FC IS NULL) THEN 1 ELSE 0 END U_TAUX_FC,
    CASE WHEN D.TAUX_FI <> S.TAUX_FI OR (D.TAUX_FI IS NULL AND S.TAUX_FI IS NOT NULL) OR (D.TAUX_FI IS NOT NULL AND S.TAUX_FI IS NULL) THEN 1 ELSE 0 END U_TAUX_FI,
    CASE WHEN D.TAUX_FOAD <> S.TAUX_FOAD OR (D.TAUX_FOAD IS NULL AND S.TAUX_FOAD IS NOT NULL) OR (D.TAUX_FOAD IS NOT NULL AND S.TAUX_FOAD IS NULL) THEN 1 ELSE 0 END U_TAUX_FOAD
FROM
  ELEMENT_PEDAGOGIQUE D
  FULL JOIN SRC_ELEMENT_PEDAGOGIQUE S ON S.source_id = D.source_id AND S.source_code = D.source_code AND S.ANNEE_ID = d.ANNEE_ID
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL)
  OR D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL)
  OR D.FA <> S.FA OR (D.FA IS NULL AND S.FA IS NOT NULL) OR (D.FA IS NOT NULL AND S.FA IS NULL)
  OR D.FC <> S.FC OR (D.FC IS NULL AND S.FC IS NOT NULL) OR (D.FC IS NOT NULL AND S.FC IS NULL)
  OR D.FI <> S.FI OR (D.FI IS NULL AND S.FI IS NOT NULL) OR (D.FI IS NOT NULL AND S.FI IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.PERIODE_ID <> S.PERIODE_ID OR (D.PERIODE_ID IS NULL AND S.PERIODE_ID IS NOT NULL) OR (D.PERIODE_ID IS NOT NULL AND S.PERIODE_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TAUX_FA <> S.TAUX_FA OR (D.TAUX_FA IS NULL AND S.TAUX_FA IS NOT NULL) OR (D.TAUX_FA IS NOT NULL AND S.TAUX_FA IS NULL)
  OR D.TAUX_FC <> S.TAUX_FC OR (D.TAUX_FC IS NULL AND S.TAUX_FC IS NOT NULL) OR (D.TAUX_FC IS NOT NULL AND S.TAUX_FC IS NULL)
  OR D.TAUX_FI <> S.TAUX_FI OR (D.TAUX_FI IS NULL AND S.TAUX_FI IS NOT NULL) OR (D.TAUX_FI IS NOT NULL AND S.TAUX_FI IS NULL)
  OR D.TAUX_FOAD <> S.TAUX_FOAD OR (D.TAUX_FOAD IS NULL AND S.TAUX_FOAD IS NOT NULL) OR (D.TAUX_FOAD IS NOT NULL AND S.TAUX_FOAD IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_EFFECTIFS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_EFFECTIFS" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "ELEMENT_PEDAGOGIQUE_ID", "FA", "FC", "FI", "U_ANNEE_ID", "U_ELEMENT_PEDAGOGIQUE_ID", "U_FA", "U_FC", "U_FI"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."ELEMENT_PEDAGOGIQUE_ID",diff."FA",diff."FC",diff."FI",diff."U_ANNEE_ID",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_FA",diff."U_FC",diff."U_FI" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FA ELSE S.FA END FA,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FC ELSE S.FC END FC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.FI ELSE S.FI END FI,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.FA <> S.FA OR (D.FA IS NULL AND S.FA IS NOT NULL) OR (D.FA IS NOT NULL AND S.FA IS NULL) THEN 1 ELSE 0 END U_FA,
    CASE WHEN D.FC <> S.FC OR (D.FC IS NULL AND S.FC IS NOT NULL) OR (D.FC IS NOT NULL AND S.FC IS NULL) THEN 1 ELSE 0 END U_FC,
    CASE WHEN D.FI <> S.FI OR (D.FI IS NULL AND S.FI IS NOT NULL) OR (D.FI IS NOT NULL AND S.FI IS NULL) THEN 1 ELSE 0 END U_FI
FROM
  EFFECTIFS D
  FULL JOIN SRC_EFFECTIFS S ON S.source_id = D.source_id AND S.source_code = D.source_code AND S.ANNEE_ID = d.ANNEE_ID
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL)
  OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.FA <> S.FA OR (D.FA IS NULL AND S.FA IS NOT NULL) OR (D.FA IS NOT NULL AND S.FA IS NULL)
  OR D.FC <> S.FC OR (D.FC IS NULL AND S.FC IS NOT NULL) OR (D.FC IS NOT NULL AND S.FC IS NULL)
  OR D.FI <> S.FI OR (D.FI IS NULL AND S.FI IS NOT NULL) OR (D.FI IS NOT NULL AND S.FI IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_DOMAINE_FONCTIONNEL
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_DOMAINE_FONCTIONNEL" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE", "U_LIBELLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE",diff."U_LIBELLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE
FROM
  DOMAINE_FONCTIONNEL D
  FULL JOIN SRC_DOMAINE_FONCTIONNEL S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Nouveau VIEW
--V_DIFF_DEPARTEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_DEPARTEMENT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "U_LIBELLE_COURT", "U_LIBELLE_LONG"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG
FROM
  DEPARTEMENT D
  FULL JOIN SRC_DEPARTEMENT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_CORPS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CORPS" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "U_LIBELLE_COURT", "U_LIBELLE_LONG"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG
FROM
  CORPS D
  FULL JOIN SRC_CORPS S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_CHEMIN_PEDAGOGIQUE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CHEMIN_PEDAGOGIQUE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "ORDRE", "U_ELEMENT_PEDAGOGIQUE_ID", "U_ETAPE_ID", "U_ORDRE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."ETAPE_ID",diff."ORDRE",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_ETAPE_ID",diff."U_ORDRE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ETAPE_ID ELSE S.ETAPE_ID END ETAPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ORDRE ELSE S.ORDRE END ORDRE,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL) THEN 1 ELSE 0 END U_ETAPE_ID,
    CASE WHEN D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL) THEN 1 ELSE 0 END U_ORDRE
FROM
  CHEMIN_PEDAGOGIQUE D
  LEFT JOIN ELEMENT_PEDAGOGIQUE rt ON rt.ID = d.ELEMENT_PEDAGOGIQUE_ID
  FULL JOIN SRC_CHEMIN_PEDAGOGIQUE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL)
  OR D.ORDRE <> S.ORDRE OR (D.ORDRE IS NULL AND S.ORDRE IS NOT NULL) OR (D.ORDRE IS NOT NULL AND S.ORDRE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CENTRE_COUT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ACTIVITE_ID", "LIBELLE", "PARENT_ID", "STRUCTURE_ID", "TYPE_RESSOURCE_ID", "U_ACTIVITE_ID", "U_LIBELLE", "U_PARENT_ID", "U_STRUCTURE_ID", "U_TYPE_RESSOURCE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ACTIVITE_ID",diff."LIBELLE",diff."PARENT_ID",diff."STRUCTURE_ID",diff."TYPE_RESSOURCE_ID",diff."U_ACTIVITE_ID",diff."U_LIBELLE",diff."U_PARENT_ID",diff."U_STRUCTURE_ID",diff."U_TYPE_RESSOURCE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ACTIVITE_ID ELSE S.ACTIVITE_ID END ACTIVITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PARENT_ID ELSE S.PARENT_ID END PARENT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_RESSOURCE_ID ELSE S.TYPE_RESSOURCE_ID END TYPE_RESSOURCE_ID,
    CASE WHEN D.ACTIVITE_ID <> S.ACTIVITE_ID OR (D.ACTIVITE_ID IS NULL AND S.ACTIVITE_ID IS NOT NULL) OR (D.ACTIVITE_ID IS NOT NULL AND S.ACTIVITE_ID IS NULL) THEN 1 ELSE 0 END U_ACTIVITE_ID,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.PARENT_ID <> S.PARENT_ID OR (D.PARENT_ID IS NULL AND S.PARENT_ID IS NOT NULL) OR (D.PARENT_ID IS NOT NULL AND S.PARENT_ID IS NULL) THEN 1 ELSE 0 END U_PARENT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TYPE_RESSOURCE_ID <> S.TYPE_RESSOURCE_ID OR (D.TYPE_RESSOURCE_ID IS NULL AND S.TYPE_RESSOURCE_ID IS NOT NULL) OR (D.TYPE_RESSOURCE_ID IS NOT NULL AND S.TYPE_RESSOURCE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_RESSOURCE_ID
FROM
  CENTRE_COUT D
  FULL JOIN SRC_CENTRE_COUT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ACTIVITE_ID <> S.ACTIVITE_ID OR (D.ACTIVITE_ID IS NULL AND S.ACTIVITE_ID IS NOT NULL) OR (D.ACTIVITE_ID IS NOT NULL AND S.ACTIVITE_ID IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.PARENT_ID <> S.PARENT_ID OR (D.PARENT_ID IS NULL AND S.PARENT_ID IS NOT NULL) OR (D.PARENT_ID IS NOT NULL AND S.PARENT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TYPE_RESSOURCE_ID <> S.TYPE_RESSOURCE_ID OR (D.TYPE_RESSOURCE_ID IS NULL AND S.TYPE_RESSOURCE_ID IS NOT NULL) OR (D.TYPE_RESSOURCE_ID IS NOT NULL AND S.TYPE_RESSOURCE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_AFFECTATION_RECHERCHE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_AFFECTATION_RECHERCHE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "INTERVENANT_ID", "STRUCTURE_ID", "U_INTERVENANT_ID", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."INTERVENANT_ID",diff."STRUCTURE_ID",diff."U_INTERVENANT_ID",diff."U_STRUCTURE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.INTERVENANT_ID ELSE S.INTERVENANT_ID END INTERVENANT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL) THEN 1 ELSE 0 END U_INTERVENANT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID
FROM
  AFFECTATION_RECHERCHE D
  LEFT JOIN INTERVENANT rt ON rt.ID = d.INTERVENANT_ID
  FULL JOIN SRC_AFFECTATION_RECHERCHE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Nouveau VIEW
--V_DIFF_AFFECTATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_AFFECTATION" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "PERSONNEL_ID", "ROLE_ID", "STRUCTURE_ID", "U_PERSONNEL_ID", "U_ROLE_ID", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."PERSONNEL_ID",diff."ROLE_ID",diff."STRUCTURE_ID",diff."U_PERSONNEL_ID",diff."U_ROLE_ID",diff."U_STRUCTURE_ID" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PERSONNEL_ID ELSE S.PERSONNEL_ID END PERSONNEL_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ROLE_ID ELSE S.ROLE_ID END ROLE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN D.PERSONNEL_ID <> S.PERSONNEL_ID OR (D.PERSONNEL_ID IS NULL AND S.PERSONNEL_ID IS NOT NULL) OR (D.PERSONNEL_ID IS NOT NULL AND S.PERSONNEL_ID IS NULL) THEN 1 ELSE 0 END U_PERSONNEL_ID,
    CASE WHEN D.ROLE_ID <> S.ROLE_ID OR (D.ROLE_ID IS NULL AND S.ROLE_ID IS NOT NULL) OR (D.ROLE_ID IS NOT NULL AND S.ROLE_ID IS NULL) THEN 1 ELSE 0 END U_ROLE_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID
FROM
  AFFECTATION D
  FULL JOIN SRC_AFFECTATION S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.PERSONNEL_ID <> S.PERSONNEL_ID OR (D.PERSONNEL_ID IS NULL AND S.PERSONNEL_ID IS NOT NULL) OR (D.PERSONNEL_ID IS NOT NULL AND S.PERSONNEL_ID IS NULL)
  OR D.ROLE_ID <> S.ROLE_ID OR (D.ROLE_ID IS NULL AND S.ROLE_ID IS NOT NULL) OR (D.ROLE_ID IS NOT NULL AND S.ROLE_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ADRESSE_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ADRESSE_STRUCTURE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE_POSTAL", "LOCALITE", "NOM_VOIE", "NO_VOIE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "PRINCIPALE", "STRUCTURE_ID", "TELEPHONE", "VILLE", "U_CODE_POSTAL", "U_LOCALITE", "U_NOM_VOIE", "U_NO_VOIE", "U_PAYS_CODE_INSEE", "U_PAYS_LIBELLE", "U_PRINCIPALE", "U_STRUCTURE_ID", "U_TELEPHONE", "U_VILLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE_POSTAL",diff."LOCALITE",diff."NOM_VOIE",diff."NO_VOIE",diff."PAYS_CODE_INSEE",diff."PAYS_LIBELLE",diff."PRINCIPALE",diff."STRUCTURE_ID",diff."TELEPHONE",diff."VILLE",diff."U_CODE_POSTAL",diff."U_LOCALITE",diff."U_NOM_VOIE",diff."U_NO_VOIE",diff."U_PAYS_CODE_INSEE",diff."U_PAYS_LIBELLE",diff."U_PRINCIPALE",diff."U_STRUCTURE_ID",diff."U_TELEPHONE",diff."U_VILLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE_POSTAL ELSE S.CODE_POSTAL END CODE_POSTAL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LOCALITE ELSE S.LOCALITE END LOCALITE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_VOIE ELSE S.NOM_VOIE END NOM_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NO_VOIE ELSE S.NO_VOIE END NO_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_CODE_INSEE ELSE S.PAYS_CODE_INSEE END PAYS_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_LIBELLE ELSE S.PAYS_LIBELLE END PAYS_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRINCIPALE ELSE S.PRINCIPALE END PRINCIPALE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TELEPHONE ELSE S.TELEPHONE END TELEPHONE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE ELSE S.VILLE END VILLE,
    CASE WHEN D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL) THEN 1 ELSE 0 END U_CODE_POSTAL,
    CASE WHEN D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL) THEN 1 ELSE 0 END U_LOCALITE,
    CASE WHEN D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL) THEN 1 ELSE 0 END U_NOM_VOIE,
    CASE WHEN D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL) THEN 1 ELSE 0 END U_NO_VOIE,
    CASE WHEN D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_CODE_INSEE,
    CASE WHEN D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_LIBELLE,
    CASE WHEN D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL) THEN 1 ELSE 0 END U_PRINCIPALE,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TELEPHONE <> S.TELEPHONE OR (D.TELEPHONE IS NULL AND S.TELEPHONE IS NOT NULL) OR (D.TELEPHONE IS NOT NULL AND S.TELEPHONE IS NULL) THEN 1 ELSE 0 END U_TELEPHONE,
    CASE WHEN D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL) THEN 1 ELSE 0 END U_VILLE
FROM
  ADRESSE_STRUCTURE D
  FULL JOIN SRC_ADRESSE_STRUCTURE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL)
  OR D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL)
  OR D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL)
  OR D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL)
  OR D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL)
  OR D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL)
  OR D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TELEPHONE <> S.TELEPHONE OR (D.TELEPHONE IS NULL AND S.TELEPHONE IS NOT NULL) OR (D.TELEPHONE IS NOT NULL AND S.TELEPHONE IS NULL)
  OR D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ADRESSE_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ADRESSE_INTERVENANT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE_POSTAL", "INTERVENANT_ID", "LOCALITE", "MENTION_COMPLEMENTAIRE", "NOM_VOIE", "NO_VOIE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "PRINCIPALE", "TEL_DOMICILE", "VILLE", "U_CODE_POSTAL", "U_INTERVENANT_ID", "U_LOCALITE", "U_MENTION_COMPLEMENTAIRE", "U_NOM_VOIE", "U_NO_VOIE", "U_PAYS_CODE_INSEE", "U_PAYS_LIBELLE", "U_PRINCIPALE", "U_TEL_DOMICILE", "U_VILLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE_POSTAL",diff."INTERVENANT_ID",diff."LOCALITE",diff."MENTION_COMPLEMENTAIRE",diff."NOM_VOIE",diff."NO_VOIE",diff."PAYS_CODE_INSEE",diff."PAYS_LIBELLE",diff."PRINCIPALE",diff."TEL_DOMICILE",diff."VILLE",diff."U_CODE_POSTAL",diff."U_INTERVENANT_ID",diff."U_LOCALITE",diff."U_MENTION_COMPLEMENTAIRE",diff."U_NOM_VOIE",diff."U_NO_VOIE",diff."U_PAYS_CODE_INSEE",diff."U_PAYS_LIBELLE",diff."U_PRINCIPALE",diff."U_TEL_DOMICILE",diff."U_VILLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID = ose_import.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE_POSTAL ELSE S.CODE_POSTAL END CODE_POSTAL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.INTERVENANT_ID ELSE S.INTERVENANT_ID END INTERVENANT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LOCALITE ELSE S.LOCALITE END LOCALITE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.MENTION_COMPLEMENTAIRE ELSE S.MENTION_COMPLEMENTAIRE END MENTION_COMPLEMENTAIRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_VOIE ELSE S.NOM_VOIE END NOM_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NO_VOIE ELSE S.NO_VOIE END NO_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_CODE_INSEE ELSE S.PAYS_CODE_INSEE END PAYS_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_LIBELLE ELSE S.PAYS_LIBELLE END PAYS_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRINCIPALE ELSE S.PRINCIPALE END PRINCIPALE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_DOMICILE ELSE S.TEL_DOMICILE END TEL_DOMICILE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE ELSE S.VILLE END VILLE,
    CASE WHEN D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL) THEN 1 ELSE 0 END U_CODE_POSTAL,
    CASE WHEN D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL) THEN 1 ELSE 0 END U_INTERVENANT_ID,
    CASE WHEN D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL) THEN 1 ELSE 0 END U_LOCALITE,
    CASE WHEN D.MENTION_COMPLEMENTAIRE <> S.MENTION_COMPLEMENTAIRE OR (D.MENTION_COMPLEMENTAIRE IS NULL AND S.MENTION_COMPLEMENTAIRE IS NOT NULL) OR (D.MENTION_COMPLEMENTAIRE IS NOT NULL AND S.MENTION_COMPLEMENTAIRE IS NULL) THEN 1 ELSE 0 END U_MENTION_COMPLEMENTAIRE,
    CASE WHEN D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL) THEN 1 ELSE 0 END U_NOM_VOIE,
    CASE WHEN D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL) THEN 1 ELSE 0 END U_NO_VOIE,
    CASE WHEN D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_PAYS_CODE_INSEE,
    CASE WHEN D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL) THEN 1 ELSE 0 END U_PAYS_LIBELLE,
    CASE WHEN D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL) THEN 1 ELSE 0 END U_PRINCIPALE,
    CASE WHEN D.TEL_DOMICILE <> S.TEL_DOMICILE OR (D.TEL_DOMICILE IS NULL AND S.TEL_DOMICILE IS NOT NULL) OR (D.TEL_DOMICILE IS NOT NULL AND S.TEL_DOMICILE IS NULL) THEN 1 ELSE 0 END U_TEL_DOMICILE,
    CASE WHEN D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL) THEN 1 ELSE 0 END U_VILLE
FROM
  ADRESSE_INTERVENANT D
  LEFT JOIN INTERVENANT rt ON rt.ID = d.INTERVENANT_ID
  FULL JOIN SRC_ADRESSE_INTERVENANT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CODE_POSTAL <> S.CODE_POSTAL OR (D.CODE_POSTAL IS NULL AND S.CODE_POSTAL IS NOT NULL) OR (D.CODE_POSTAL IS NOT NULL AND S.CODE_POSTAL IS NULL)
  OR D.INTERVENANT_ID <> S.INTERVENANT_ID OR (D.INTERVENANT_ID IS NULL AND S.INTERVENANT_ID IS NOT NULL) OR (D.INTERVENANT_ID IS NOT NULL AND S.INTERVENANT_ID IS NULL)
  OR D.LOCALITE <> S.LOCALITE OR (D.LOCALITE IS NULL AND S.LOCALITE IS NOT NULL) OR (D.LOCALITE IS NOT NULL AND S.LOCALITE IS NULL)
  OR D.MENTION_COMPLEMENTAIRE <> S.MENTION_COMPLEMENTAIRE OR (D.MENTION_COMPLEMENTAIRE IS NULL AND S.MENTION_COMPLEMENTAIRE IS NOT NULL) OR (D.MENTION_COMPLEMENTAIRE IS NOT NULL AND S.MENTION_COMPLEMENTAIRE IS NULL)
  OR D.NOM_VOIE <> S.NOM_VOIE OR (D.NOM_VOIE IS NULL AND S.NOM_VOIE IS NOT NULL) OR (D.NOM_VOIE IS NOT NULL AND S.NOM_VOIE IS NULL)
  OR D.NO_VOIE <> S.NO_VOIE OR (D.NO_VOIE IS NULL AND S.NO_VOIE IS NOT NULL) OR (D.NO_VOIE IS NOT NULL AND S.NO_VOIE IS NULL)
  OR D.PAYS_CODE_INSEE <> S.PAYS_CODE_INSEE OR (D.PAYS_CODE_INSEE IS NULL AND S.PAYS_CODE_INSEE IS NOT NULL) OR (D.PAYS_CODE_INSEE IS NOT NULL AND S.PAYS_CODE_INSEE IS NULL)
  OR D.PAYS_LIBELLE <> S.PAYS_LIBELLE OR (D.PAYS_LIBELLE IS NULL AND S.PAYS_LIBELLE IS NOT NULL) OR (D.PAYS_LIBELLE IS NOT NULL AND S.PAYS_LIBELLE IS NULL)
  OR D.PRINCIPALE <> S.PRINCIPALE OR (D.PRINCIPALE IS NULL AND S.PRINCIPALE IS NOT NULL) OR (D.PRINCIPALE IS NOT NULL AND S.PRINCIPALE IS NULL)
  OR D.TEL_DOMICILE <> S.TEL_DOMICILE OR (D.TEL_DOMICILE IS NULL AND S.TEL_DOMICILE IS NOT NULL) OR (D.TEL_DOMICILE IS NOT NULL AND S.TEL_DOMICILE IS NULL)
  OR D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--SRC_TYPE_MODULATEUR_EP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_TYPE_MODULATEUR_EP" 
 ( "ID", "TYPE_MODULATEUR_ID", "ELEMENT_PEDAGOGIQUE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null                                id,
  tm.id                               type_modulateur_id,
  ep.id                               element_pedagogique_id,
  src.id                              source_id,
  tm.code || '_' || ep.source_code || '_' || ep.annee_id  source_code
FROM
  element_pedagogique  ep
  JOIN type_modulateur tm ON ose_divers.comprise_entre( tm.histo_creation, tm.histo_destruction ) = 1
  JOIN structure        s ON s.id = ep.structure_id
  JOIN source         src ON src.code = 'Calcul'
WHERE
  ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction ) = 1
  AND ep.taux_fc > 0
  AND (
       (tm.code = 'IAE_FC'      AND s.source_code IN ('U10')) -- IAE
    OR (tm.code = 'DROIT_FC'    AND s.source_code IN ('U01')) -- Droit
    OR (tm.code = 'IUTCAEN_FC'  AND s.source_code IN ('I13')) -- IUT Caen
  );
---------------------------
--Modifié VIEW
--SRC_TYPE_INTERVENTION_EP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_TYPE_INTERVENTION_EP" 
 ( "ID", "TYPE_INTERVENTION_ID", "ELEMENT_PEDAGOGIQUE_ID", "VISIBLE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null id,
  ti.id type_intervention_id,
  ep.id element_pedagogique_id,
  1 visible,
  src.id source_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id source_code
FROM
  element_pedagogique ep
  JOIN type_intervention ti ON ti.code = 'FOAD' AND 1 = ose_divers.comprise_entre( ti.histo_creation, ti.histo_destruction )
  JOIN structure s ON s.id = ep.structure_id
  JOIN source src ON src.code = 'Calcul'
WHERE
  ep.taux_foad > 0
  AND 1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
  AND s.source_code IN ('U07','U08','U04')
  
UNION

SELECT
  null id,
  ti.id type_intervention_id,
  ep.id element_pedagogique_id,
  1 visible,
  src.id source_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id source_code
FROM
  element_pedagogique ep
  JOIN type_intervention ti ON ti.code IN ('FOAD-ECR', 'FOAD-ACTU', 'FOAD-EXPL') AND 1 = ose_divers.comprise_entre( ti.histo_creation, ti.histo_destruction )
  JOIN structure s ON s.id = ep.structure_id
  JOIN source src ON src.code = 'Calcul'
WHERE
  ep.taux_foad > 0
  AND 1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
  AND s.source_code IN ('U10') -- IAE uniquement
  ;
---------------------------
--Modifié VIEW
--SRC_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_STRUCTURE" 
 ( "ID", "LIBELLE_LONG", "LIBELLE_COURT", "PARENTE_ID", "STRUCTURE_NIV2_ID", "TYPE_ID", "ETABLISSEMENT_ID", "NIVEAU", "UNITE_BUDGETAIRE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null id,
  S.LIBELLE_LONG,
  S.LIBELLE_COURT,
  sp.id parente_id,
  S2.id structure_niv2_id,
  ts.id type_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT etablissement_id,
  S.niveau,
  UB.CODE_SIFAC unite_budgetaire,
  S.SOURCE_ID,
  S.SOURCE_CODE
FROM
  mv_structure s
  JOIN type_structure ts on ts.code = S.Z_TYPE_ID
  LEFT JOIN structure sp on (sp.source_code = s.z_parente_id)
  LEFT JOIN structure s2 on (s2.source_code = s.z_structure_niv2_id)
  LEFT JOIN unicaen_corresp_structure_cc ub ON UB.CODE_HARPEGE = s.source_code;
---------------------------
--Modifié VIEW
--SRC_PERSONNEL
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_PERSONNEL" 
 ( "ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "EMAIL", "DATE_NAISSANCE", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  NULL id,
  p.civilite_id,
  p.nom_usuel,
  p.prenom,
  p.nom_patronymique,
  p.email,
  p.date_naissance,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  p.source_id,
  p.source_code
FROM
  mv_personnel p
  JOIN structure s ON s.source_code = p.z_structure_id;
---------------------------
--Modifié VIEW
--SRC_INTERVENANT_PERMANENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT_PERMANENT" 
 ( "ID", "CORPS_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  i.id,
  c.id as corps_id,
  IP.SOURCE_ID,
  to_char(i.id) source_code
FROM
  mv_intervenant ip
  JOIN intervenant i ON i.source_code = ip.source_code AND i.annee_id = ose_import.get_current_annee
  LEFT JOIN corps c ON c.source_code = IP.Z_CORPS_ID
WHERE
  ip.type_code = 'P';
---------------------------
--Modifié VIEW
--SRC_INTERVENANT_EXTERIEUR
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT_EXTERIEUR" 
 ( "ID", "SITUATION_FAMILIALE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  i.id,
  ie."SITUATION_FAMILIALE_ID",
  ie."SOURCE_ID",
  to_char(i.id) source_code
FROM
  mv_intervenant ie
  JOIN intervenant i ON i.source_code = ie.source_code AND i.annee_id = ose_import.get_current_annee
WHERE
  ie.type_code = 'E';
---------------------------
--Modifié VIEW
--SRC_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT" 
 ( "ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "TYPE_ID", "STATUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "ANNEE_ID", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC"
  )  AS 
  SELECT
  null id,
  i."CIVILITE_ID",
  i."NOM_USUEL",
  i."PRENOM",
  i."NOM_PATRONYMIQUE",
  COALESCE(i."DATE_NAISSANCE",TO_DATE('2099-01-01','YYYY-MM-DD')) DATE_NAISSANCE,
  i."PAYS_NAISSANCE_CODE_INSEE",
  i."PAYS_NAISSANCE_LIBELLE",
  i."DEP_NAISSANCE_CODE_INSEE",
  i."DEP_NAISSANCE_LIBELLE",
  i."VILLE_NAISSANCE_CODE_INSEE",
  i."VILLE_NAISSANCE_LIBELLE",
  i."PAYS_NATIONALITE_CODE_INSEE",
  i."PAYS_NATIONALITE_LIBELLE",
  i."TEL_PRO",
  i."TEL_MOBILE",
  i."EMAIL",
  i.type_id,
  CASE WHEN i.statut_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE i.statut_id END statut_id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  i."SOURCE_ID",
  i."SOURCE_CODE",
  ose_import.get_current_annee annee_id,
  i."NUMERO_INSEE",
  i."NUMERO_INSEE_CLE",
  i."NUMERO_INSEE_PROVISOIRE",
  i."IBAN",
  i."BIC"
FROM
  mv_intervenant i
  JOIN structure                  s  ON s.source_code = i.z_structure_id
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = ose_import.get_current_annee
  LEFT JOIN intervenant_exterieur ie ON ie.id = i2.id
  LEFT JOIN dossier               d  ON d.intervenant_id = ie.id -- NB: la contrainte d'unicité sur DOSSIER garantit qu'il n'y a qu'un dossier par intervenant
WHERE
  i.ordre = i.min_ordre
  
UNION

SELECT
  null id,
  i."CIVILITE_ID",
  i."NOM_USUEL",
  i."PRENOM",
  i."NOM_PATRONYMIQUE",
  COALESCE(i."DATE_NAISSANCE",TO_DATE('2099-01-01','YYYY-MM-DD')) DATE_NAISSANCE,
  i."PAYS_NAISSANCE_CODE_INSEE",
  i."PAYS_NAISSANCE_LIBELLE",
  i."DEP_NAISSANCE_CODE_INSEE",
  i."DEP_NAISSANCE_LIBELLE",
  i."VILLE_NAISSANCE_CODE_INSEE",
  i."VILLE_NAISSANCE_LIBELLE",
  i."PAYS_NATIONALITE_CODE_INSEE",
  i."PAYS_NATIONALITE_LIBELLE",
  i."TEL_PRO",
  i."TEL_MOBILE",
  i."EMAIL",
  i.type_id,
  CASE WHEN i.statut_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE i.statut_id END statut_id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  i."SOURCE_ID",
  i."SOURCE_CODE",
  ose_import.get_current_annee - 1 annee_id,
  i."NUMERO_INSEE",
  i."NUMERO_INSEE_CLE",
  i."NUMERO_INSEE_PROVISOIRE",
  i."IBAN",
  i."BIC"
FROM
  mv_intervenant i
  JOIN structure                  s  ON s.source_code = i.z_structure_id
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = ose_import.get_current_annee - 1
  LEFT JOIN intervenant_exterieur ie ON ie.id = i2.id
  LEFT JOIN dossier               d  ON d.intervenant_id = ie.id -- NB: la contrainte d'unicité sur DOSSIER garantit qu'il n'y a qu'un dossier par intervenant
WHERE
  i.ordre = i.min_ordre
  --AND to_number(to_char(date_ref, 'mm')) > 4 -- on importe l'année antérieure à partir du mois de mai uniquement, car le changement d'année sefait théoriquement au 1er mai!!
  ;
---------------------------
--Modifié VIEW
--SRC_ETAPE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ETAPE" 
 ( "ID", "LIBELLE", "TYPE_FORMATION_ID", "NIVEAU", "SPECIFIQUE_ECHANGES", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "DOMAINE_FONCTIONNEL_ID"
  )  AS 
  SELECT
  null id,
  e.libelle,
  tf.id type_formation_id,
  e.niveau,
  e.specifique_echanges,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  e.source_id,
  e.source_code,
  df.id domaine_fonctionnel_id
FROM
  MV_ETAPE e
  LEFT JOIN TYPE_FORMATION tf ON tf.source_code = E.Z_TYPE_FORMATION_ID
  LEFT JOIN STRUCTURE s ON s.source_code = E.Z_STRUCTURE_ID
  LEFT JOIN domaine_fonctionnel df ON df.source_code = e.z_domaine_fonctionnel_id;
---------------------------
--Modifié VIEW
--SRC_ELEMENT_TAUX_REGIMES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_TAUX_REGIMES" 
 ( "ID", "ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "TAUX_FI", "TAUX_FC", "TAUX_FA", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null ID,
  ep.id element_pedagogique_id,
  etr.annee_id,
  OSE_DIVERS.CALCUL_TAUX_FI( etr.eff_taux_fi, etr.eff_taux_fc, etr.eff_taux_fa, ep.fi, ep.fc, ep.fa ) taux_fi,
  OSE_DIVERS.CALCUL_TAUX_FC( etr.eff_taux_fi, etr.eff_taux_fc, etr.eff_taux_fa, ep.fi, ep.fc, ep.fa ) taux_fc,
  OSE_DIVERS.CALCUL_TAUX_FA( etr.eff_taux_fi, etr.eff_taux_fc, etr.eff_taux_fa, ep.fi, ep.fc, ep.fa ) taux_fa,
  etr.source_id,
  etr.source_code || '_' || ose_import.get_current_annee source_code
FROM
  MV_ELEMENT_TAUX_REGIMES etr
  JOIN ELEMENT_PEDAGOGIQUE ep ON ep.source_code = etr.z_element_pedagogique_id AND ep.annee_id = ose_import.get_current_annee
WHERE
  NOT EXISTS(
    SELECT * FROM element_taux_regimes etr_tbl WHERE
      etr_tbl.element_pedagogique_id = ep.id
      AND etr_tbl.source_id <> etr.source_id
  );
---------------------------
--Modifié VIEW
--SRC_ELEMENT_PEDAGOGIQUE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_PEDAGOGIQUE" 
 ( "ID", "LIBELLE", "ETAPE_ID", "STRUCTURE_ID", "PERIODE_ID", "TAUX_FI", "TAUX_FC", "TAUX_FA", "TAUX_FOAD", "FC", "FI", "FA", "SOURCE_ID", "SOURCE_CODE", "ANNEE_ID"
  )  AS 
  SELECT
  null id,
  E.LIBELLE,
  etp.id ETAPE_ID,
  NVL(str.STRUCTURE_NIV2_ID,str.id) structure_id,
  per.id periode_id,
  CASE 
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, e.fi, e.fc, e.fa )
    ELSE ose_divers.calcul_taux_fi( e.fi, e.fc, e.fa, e.fi, e.fc, e.fa )
  END taux_fi,
  CASE 
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fc( etr.taux_fi, etr.taux_fc, etr.taux_fa, e.fi, e.fc, e.fa )
    ELSE ose_divers.calcul_taux_fc( e.fi, e.fc, e.fa, e.fi, e.fc, e.fa )
  END taux_fc,
  CASE 
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fa( etr.taux_fi, etr.taux_fc, etr.taux_fa, e.fi, e.fc, e.fa )
    ELSE ose_divers.calcul_taux_fa( e.fi, e.fc, e.fa, e.fi, e.fc, e.fa )
  END taux_fa,
  e.taux_foad,
  e.fc,
  e.fi,
  e.fa,
  E.SOURCE_ID,
  E.SOURCE_CODE,
  ose_import.get_current_annee annee_id
FROM
  MV_ELEMENT_PEDAGOGIQUE E
  LEFT JOIN etape etp ON etp.source_code = E.Z_ETAPE_ID
  LEFT JOIN structure str ON str.source_code = E.Z_STRUCTURE_ID
  LEFT JOIN periode per ON per.libelle_court = E.Z_PERIODE_ID
  LEFT JOIN element_pedagogique ep ON ep.source_code = e.source_code AND ep.annee_id = ose_import.get_current_annee
  LEFT JOIN element_taux_regimes etr ON
    etr.element_pedagogique_id = ep.id
    AND 1 = OSE_DIVERS.COMPRISE_ENTRE( etr.histo_creation, etr.histo_destruction );
---------------------------
--Modifié VIEW
--SRC_EFFECTIFS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_EFFECTIFS" 
 ( "ID", "ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "FI", "FC", "FA", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null id,
  ep.id element_pedagogique_id,
  e.annee_id annee_id,
  e.fi fi,
  e.fc fc,
  e.fa fa,
  e.source_id source_id,
  e.source_code source_code
from
  mv_effectifs e
  LEFT JOIN element_pedagogique ep ON ep.source_code = e.z_element_pedagogique_id AND ep.annee_id = e.annee_id;
---------------------------
--Modifié VIEW
--SRC_CORPS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CORPS" 
 ( "ID", "LIBELLE_LONG", "LIBELLE_COURT", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null id,
  C.LIBELLE_LONG,
  C.LIBELLE_COURT,
  C.SOURCE_ID,
  C.SOURCE_CODE
FROM
  MV_corps c;
---------------------------
--Modifié VIEW
--SRC_CHEMIN_PEDAGOGIQUE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CHEMIN_PEDAGOGIQUE" 
 ( "ID", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "ORDRE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null id,
  elp.id element_pedagogique_id,
  etp.id ETAPE_ID,
  c.ordre,
  c.source_id,
  C.SOURCE_CODE || '_' || ose_import.get_current_annee SOURCE_CODE
FROM
  MV_CHEMIN_PEDAGOGIQUE C
  LEFT JOIN ELEMENT_PEDAGOGIQUE elp ON elp.source_code = C.Z_ELEMENT_PEDAGOGIQUE_ID AND elp.annee_id = ose_import.get_current_annee
  LEFT JOIN ETAPE etp ON etp.source_code = C.Z_ETAPE_ID;
---------------------------
--Modifié VIEW
--SRC_AFFECTATION_RECHERCHE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_AFFECTATION_RECHERCHE" 
 ( "ID", "INTERVENANT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  NULL id,
  i.id intervenant_id,
  NVL(s.structure_niv2_id,s.id) structure_id,
  aff.source_id,
  aff.source_code || '_' || ose_import.get_current_annee source_code
FROM
  mv_affectation_recherche aff
  LEFT JOIN intervenant i ON i.source_code = aff.z_intervenant_id AND i.annee_id = ose_import.get_current_annee
  LEFT JOIN structure s ON s.source_code = aff.z_structure_id;
---------------------------
--Nouveau VIEW
--SRC_AFFECTATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_AFFECTATION" 
 ( "ID", "STRUCTURE_ID", "PERSONNEL_ID", "ROLE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  NULL id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  p.id personnel_id,
  r.id role_id,
  a.source_id,
  a.source_code
FROM
  mv_AFFECTATION a
  LEFT JOIN personnel p ON p.source_code = a.z_personnel_id
  LEFT JOIN structure s ON s.source_code = a.z_structure_id
  LEFT JOIN role r ON r.code = a.z_role_id
WHERE
  s.id IS NULL -- rôle global
  OR (
    (
      EXISTS (SELECT * FROM element_pedagogique ep WHERE EP.STRUCTURE_ID = NVL(s.STRUCTURE_NIV2_ID,s.id)) -- soit une resp. dans une composante d'enseignement
      OR a.z_role_id IN ('responsable-recherche-labo')                                                    -- soit un responsable de labo
    )
    AND s.niveau <= 2
  );
---------------------------
--Modifié VIEW
--SRC_ADRESSE_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ADRESSE_STRUCTURE" 
 ( "ID", "STRUCTURE_ID", "PRINCIPALE", "TELEPHONE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  NULL id,
  s.ID structure_id,
  astr.PRINCIPALE,
  astr.TELEPHONE,
  astr.NO_VOIE,
  astr.NOM_VOIE,
  astr.LOCALITE,
  astr.CODE_POSTAL,
  astr.VILLE,
  astr.PAYS_CODE_INSEE,
  astr.PAYS_LIBELLE,
  astr.SOURCE_ID,
  astr.SOURCE_CODE
FROM
  mv_adresse_structure astr
  JOIN structure s ON s.source_code = astr.z_structure_id;
---------------------------
--Modifié VIEW
--SRC_ADRESSE_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ADRESSE_INTERVENANT" 
 ( "ID", "INTERVENANT_ID", "PRINCIPALE", "TEL_DOMICILE", "MENTION_COMPLEMENTAIRE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  NULL id,
  i.ID  INTERVENANT_ID,
  AI.PRINCIPALE,
  AI.TEL_DOMICILE,
  AI.MENTION_COMPLEMENTAIRE,
  AI.NO_VOIE,
  AI.NOM_VOIE,
  AI.LOCALITE,
  AI.CODE_POSTAL,
  AI.VILLE,
  AI.PAYS_CODE_INSEE,
  AI.PAYS_LIBELLE,
  AI.SOURCE_ID,
  AI.SOURCE_CODE || '_' || ose_import.get_current_annee SOURCE_CODE
FROM
  MV_ADRESSE_intervenant ai
  LEFT JOIN INTERVENANT i ON i.SOURCE_CODE = AI.Z_INTERVENANT_ID AND i.annee_id = ose_import.get_current_annee;
---------------------------
--Modifié VIEW
--ADRESSE_INTERVENANT_PRINC
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."ADRESSE_INTERVENANT_PRINC" 
 ( "ID", "INTERVENANT_ID", "PRINCIPALE", "TEL_DOMICILE", "MENTION_COMPLEMENTAIRE", "BATIMENT", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE", "HISTO_CREATION", "HISTO_CREATEUR_ID", "HISTO_MODIFICATION", "HISTO_MODIFICATEUR_ID", "HISTO_DESTRUCTION", "HISTO_DESTRUCTEUR_ID", "TO_STRING"
  )  AS 
  select 
    a."ID",a."INTERVENANT_ID",a."PRINCIPALE",a."TEL_DOMICILE",a."MENTION_COMPLEMENTAIRE",a."BATIMENT",a."NO_VOIE",a."NOM_VOIE",a."LOCALITE",a."CODE_POSTAL",a."VILLE",a."PAYS_CODE_INSEE",a."PAYS_LIBELLE",a."SOURCE_ID",a."SOURCE_CODE",a."HISTO_CREATION",a."HISTO_CREATEUR_ID",a."HISTO_MODIFICATION",a."HISTO_MODIFICATEUR_ID",a."HISTO_DESTRUCTION",a."HISTO_DESTRUCTEUR_ID",
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' from replace(', ' || nvl(a.no_voie,'#') || ', ' || nvl(a.nom_voie,'#') || ', ' || nvl(a.batiment,'#') || ', ' || nvl(a.mention_complementaire,'#'), ', #', ''))) ||
    -- saut de ligne complet
    chr(13) || chr(10) || 
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' from replace(', ' || nvl(a.localite,'#') || ', ' || nvl(a.code_postal,'#') || ', ' || nvl(a.ville,'#') || ', ' || nvl(a.pays_libelle,'#'), ', #', ''))) to_string
  from adresse_intervenant a
  where id in (
    -- on ne retient que l'adresse principale si elle existe ou sinon la première adresse trouvée
    select id 
    from (
      -- attribution d'un rang par intervenant aux adresses pour avoir la principale (éventuelle) en n°1
      select id, dense_rank() over(partition by intervenant_id order by principale desc) rang from adresse_intervenant
    ) 
    where rang = 1
  );
---------------------------
--Modifié MATERIALIZED VIEW
--MV_TYPE_FORMATION
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_TYPE_FORMATION";
CREATE MATERIALIZED VIEW "OSE"."MV_TYPE_FORMATION" ("LIBELLE_LONG","LIBELLE_COURT","Z_GROUPE_ID","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  libelle_long,
  libelle_court,
  z_groupe_id,
  src.id source_id,
  source_code
FROM
  ose_type_formation@apoprod
  JOIN source src ON src.code = 'Apogee';
---------------------------
--Modifié MATERIALIZED VIEW
--MV_STRUCTURE
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_STRUCTURE";
CREATE MATERIALIZED VIEW "OSE"."MV_STRUCTURE" ("LIBELLE_LONG","LIBELLE_COURT","Z_PARENTE_ID","Z_STRUCTURE_NIV2_ID","Z_TYPE_ID","NIVEAU","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  ll_structure                                    as libelle_long,
  lc_structure                                    as libelle_court,
  c_structure_pere                                as z_parente_id,
  CASE LEVEL WHEN 2 THEN C_STRUCTURE ELSE STR_LEVEL2_CODE@harpprod(c_structure) END as z_structure_niv2_id,
  CASE c_statut_juridique
    WHEN 'UN' THEN 'UNI'
    WHEN 'C' THEN 'CMP'
    WHEN 'EC' THEN 'AUT'
    WHEN 'SC' THEN 'SCM'
    WHEN 'I' THEN 'STI'
    WHEN 'EI' THEN 'IEI'
    WHEN 'EP' THEN 'AUT'
    WHEN 'SE' THEN 'SIE'
    WHEN 'CH' THEN 'CHU'
    WHEN 'IU' THEN 'IUF'
    WHEN 'PR' THEN 'PRS'
    ELSE 'AUT'
  END                                             as z_type_id,
  LEVEL                                           as niveau,
  s.id                                            as source_id,
  c_structure                                     as source_code
FROM
  structure@harpprod
  JOIN source s ON s.code = 'Harpege'
WHERE
  SYSDATE BETWEEN date_ouverture AND NVL( date_fermeture, SYSDATE )
START WITH c_structure_pere IS NULL
CONNECT BY PRIOR c_structure = c_structure_pere;
---------------------------
--Modifié MATERIALIZED VIEW
--MV_PERSONNEL
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_PERSONNEL";
CREATE MATERIALIZED VIEW "OSE"."MV_PERSONNEL" ("CIVILITE_ID","NOM_USUEL","PRENOM","NOM_PATRONYMIQUE","DATE_NAISSANCE","EMAIL","Z_STRUCTURE_ID","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  WITH aff_ifs (no_dossier_pers, fin) AS (
  SELECT
    no_individu,
    CASE WHEN MAX( fin ) = to_date('12/12/9999','DD/MM/YYYY') THEN NULL ELSE MAX( fin ) END fin
  FROM
    (SELECT
      ifs.no_dossier_pers no_individu,
      COALESCE( ifs.DT_FIN_EXERC_RESP, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      individu_fonct_struct@harpprod ifs
    WHERE
      SYSDATE BETWEEN COALESCE(ifs.DT_DEB_EXERC_RESP, SYSDATE) AND COALESCE(ifs.DT_FIN_EXERC_RESP + 6*31, SYSDATE)
    UNION SELECT
      a.no_dossier_pers no_individu,
      COALESCE( a.d_fin_affectation, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      affectation@harpprod a
    WHERE
      SYSDATE BETWEEN COALESCE(a.d_deb_affectation, SYSDATE) AND COALESCE(a.d_fin_affectation + 6*31, SYSDATE)
  )
  GROUP BY
    no_individu
)
SELECT
  c.id                                     civilite_id,
  initcap(i.nom_usuel)                     nom_usuel,
  initcap(i.prenom)                        prenom,
  initcap(i.nom_patronymique)              nom_patronymique,
  i.d_naissance                            date_naissance,
  im.no_e_mail                             email,
  pbs_divers__cicg.c_structure_globale@harpprod(i.no_individu, TRUNC(ai.fin) ) z_structure_id,
  s.id                                     source_id,
  ltrim(TO_CHAR(i.no_individu,'99999999')) source_code
FROM
  individu@harpprod i
  JOIN source                   s   ON s.code = 'Harpege'
  JOIN civilite                 c   ON c.libelle_court = CASE i.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END
  JOIN aff_ifs                  ai  ON ai.no_dossier_pers = i.no_individu
  JOIN individu_e_mail@harpprod im  ON (im.no_individu = i.no_individu);
---------------------------
--Modifié MATERIALIZED VIEW
--MV_INTERVENANT
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT";
CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT" ("ANNEE_CREATION","CIVILITE_ID","NOM_USUEL","PRENOM","NOM_PATRONYMIQUE","DATE_NAISSANCE","PAYS_NAISSANCE_CODE_INSEE","PAYS_NAISSANCE_LIBELLE","DEP_NAISSANCE_CODE_INSEE","DEP_NAISSANCE_LIBELLE","VILLE_NAISSANCE_CODE_INSEE","VILLE_NAISSANCE_LIBELLE","PAYS_NATIONALITE_CODE_INSEE","PAYS_NATIONALITE_LIBELLE","TEL_PRO","TEL_MOBILE","EMAIL","TYPE_ID","TYPE_CODE","STATUT_ID","STATUT_CODE","Z_STRUCTURE_ID","SOURCE_ID","SOURCE_CODE","NUMERO_INSEE","NUMERO_INSEE_CLE","NUMERO_INSEE_PROVISOIRE","IBAN","BIC","Z_CORPS_ID","SITUATION_FAMILIALE_ID","ORDRE","MIN_ORDRE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  WITH validite ( no_individu, fin ) AS (
  SELECT
    no_individu,
    CASE WHEN MAX( fin ) = to_date('12/12/9999','DD/MM/YYYY') THEN NULL ELSE MAX( fin ) END fin
  FROM
    (SELECT
      ch.no_individu no_individu,
      COALESCE( ch.d_fin_str_trav, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      chercheur@harpprod ch
    WHERE
      SYSDATE BETWEEN COALESCE(ch.d_deb_str_trav, SYSDATE) AND COALESCE(ch.d_fin_str_trav + 6*31, SYSDATE)
    UNION SELECT
      a.no_dossier_pers no_individu,
      COALESCE( a.d_fin_affectation, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      affectation@harpprod a
    WHERE
      SYSDATE BETWEEN COALESCE(a.d_deb_affectation, SYSDATE) AND COALESCE(a.d_fin_affectation + 6*31, SYSDATE)
    UNION SELECT
      ar.no_dossier_pers no_individu,
      COALESCE( ar.d_fin_affe_rech, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      affectation_recherche@harpprod ar
    WHERE
      SYSDATE BETWEEN COALESCE(ar.d_deb_affe_rech, SYSDATE) AND COALESCE(ar.d_fin_affe_rech + 6*31, SYSDATE)
  )
  GROUP BY
    no_individu
),
comptes (no_individu, rank_compte, nombre_comptes, IBAN, BIC) AS (
  SELECT
    i.no_dossier_pers no_individu,
    rank() over(partition by i.no_dossier_pers order by d_creation) rank_compte,
    count(*) over(partition by i.no_dossier_pers) nombre_comptes,
    CASE WHEN i.no_dossier_pers IS NOT NULL THEN
      trim( NVL(i.c_pays_iso || i.cle_controle,'FR00') || ' ' ||
      substr(i.c_banque,0,4) || ' ' ||
      substr(i.c_banque,5,1) || substr(i.c_guichet,0,3) || ' ' ||
      substr(i.c_guichet,4,2) || substr(i.no_compte,0,2) || ' ' ||
      substr(i.no_compte,3,4) || ' ' ||
      substr(i.no_compte,7,4) || ' ' ||
      substr(i.no_compte,11) || i.cle_rib) ELSE NULL END IBAN,
    CASE WHEN i.no_dossier_pers IS NOT NULL THEN i.c_banque_bic || ' ' || i.c_pays_bic || ' ' || i.c_emplacement || ' ' || i.c_branche ELSE NULL END BIC
  from
    individu_banque@harpprod i
)
SELECT DISTINCT
  ose_divers.annee_universitaire(individu.d_creation,5) annee_creation,
  civilite.id                                     civilite_id,
  initcap(individu.nom_usuel)                     nom_usuel,
  initcap(individu.prenom)                        prenom,
  initcap(individu.nom_patronymique)              nom_patronymique,
  individu.d_naissance                            date_naissance,
  pays_naissance.c_pays                           pays_naissance_code_insee,
  pays_naissance.ll_pays                          pays_naissance_libelle,
  departement.c_departement                       dep_naissance_code_insee,
  departement.ll_departement                      dep_naissance_libelle,
  individu.c_commune_naissance                    ville_naissance_code_insee,
  individu.ville_de_naissance                     ville_naissance_libelle,
  pays_nationalite.c_pays                         pays_nationalite_code_insee,
  pays_nationalite.ll_pays                        pays_nationalite_libelle,
  individu_telephone.no_telephone                 tel_pro,
  individu.no_tel_portable                        tel_mobile,
  CASE 
    WHEN INDIVIDU_E_MAIL.NO_E_MAIL IS NULL AND individu.d_creation > SYSDATE -2 THEN 
      UCBN_LDAP.hid2mail(individu.no_individu)
    ELSE
      INDIVIDU_E_MAIL.NO_E_MAIL
  END                                             email,
  ti.id                                           type_id,
  ti.code                                         type_code,
  si.id                                           statut_id,
  si.source_code                                  statut_code,
  pbs_divers__cicg.c_structure_globale@harpprod(individu.no_individu, TRUNC(validite.fin) ) z_structure_id,
  s.id                                            source_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999')) source_code,
  code_insee.no_insee                             numero_insee,
  TO_CHAR(code_insee.cle_insee)                   numero_insee_cle,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END numero_insee_provisoire,
  comptes.iban                                    iban,
  comptes.bic                                     bic,
  pbs_divers__cicg.c_corps@harpprod(individu.no_individu, TRUNC(validite.fin) ) z_corps_id,
  sf.id                                           situation_familiale_id,
  NVL(si.ordre,0)                                 ordre,
  NVL(min(si.ordre) over(partition BY individu.no_individu),0) AS min_ordre
FROM
            individu@harpprod           individu
       JOIN                             validite           ON validite.no_individu           = individu.no_individu
       JOIN source                      s                  ON s.code                         = 'Harpege'
       JOIN                             civilite           ON civilite.libelle_court         = CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END
  LEFT JOIN pays@harpprod               pays_naissance     ON pays_naissance.c_pays          = individu.c_pays_naissance
  LEFT JOIN departement@harpprod        departement        ON departement.c_departement      = individu.c_dept_naissance
  LEFT JOIN pays@harpprod               pays_nationalite   ON pays_nationalite.c_pays        = individu.c_pays_nationnalite
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail    ON individu_e_mail.no_individu    = individu.no_individu
  LEFT JOIN individu_telephone@harpprod individu_telephone ON individu_telephone.no_individu = individu.no_individu AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O'
  LEFT JOIN code_insee@harpprod         code_insee         ON code_insee.no_dossier_pers     = individu.no_individu
  LEFT JOIN                             comptes            ON comptes.no_individu            = individu.no_individu AND comptes.rank_compte = comptes.nombre_comptes
  LEFT JOIN PERSONNEL@harpprod          p                  ON p.no_dossier_pers              = individu.no_individu
  LEFT JOIN SITUATION_FAMILIALE         sf                 ON sf.code                        = p.C_SITUATION_FAMILLE
  LEFT JOIN contrat_travail@harpprod    ct                 ON ct.no_dossier_pers             = individu.no_individu
  LEFT JOIN contrat_avenant@harpprod    ca                 ON ca.no_dossier_pers             = ct.no_dossier_pers AND ca.no_contrat_travail = ct.no_contrat_travail AND 1 = ose_divers.comprise_entre( ca.d_deb_contrat_trav, ca.d_fin_contrat_trav, TRUNC(validite.fin), 1 )
  LEFT JOIN affectation@harpprod        a                  ON a.no_dossier_pers              = individu.no_individu AND 1 = ose_divers.comprise_entre( a.d_deb_affectation, a.d_fin_affectation, TRUNC(validite.fin), 1 )
  LEFT JOIN carriere@harpprod           c                  ON c.no_dossier_pers              = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
  LEFT JOIN statut_intervenant          si                 ON 1 = ose_divers.comprise_entre( si.histo_creation, si.histo_destruction ) AND si.source_code = CASE 
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('MC','MA')                THEN 'ASS_MI_TPS'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('AT')                     THEN 'ATER'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('AX')                     THEN 'ATER_MI_TPS'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('DO')                     THEN 'DOCTOR'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('GI','PN','ED')           THEN 'ENS_CONTRACT'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('MB')                     THEN 'MAITRE_LANG'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('C3','CA','CB','CD','HA','HS','S3','SX','SW','SY','CS','SZ','VA') THEN 'BIATSS'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET','NF') THEN 'NON_AUTORISE'
        
         WHEN c.c_type_population IN ('DA','OA','DC')              THEN 'ENS_2ND_DEG'
         WHEN c.c_type_population IN ('SA')                        THEN 'ENS_CH'
         WHEN c.c_type_population IN ('AA','AC','BA','IA','MA')    THEN 'BIATSS'
         WHEN c.c_type_population IN ('MG','SB')                   THEN 'NON_AUTORISE'
        
                                                                   ELSE 'AUTRES' END
  LEFT JOIN type_intervenant            ti                 ON ti.id = si.type_intervenant_id;
---------------------------
--Modifié MATERIALIZED VIEW
--MV_GROUPE_TYPE_FORMATION
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_GROUPE_TYPE_FORMATION";
CREATE MATERIALIZED VIEW "OSE"."MV_GROUPE_TYPE_FORMATION" ("LIBELLE_COURT","LIBELLE_LONG","ORDRE","PERTINENCE_NIVEAU","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  libelle_court,
  libelle_long,
  ordre,
  pertinence_niveau,
  src.id source_id,
  source_code
FROM
  ose_groupe_type_formation@apoprod
  JOIN source src ON src.code = 'Apogee';
---------------------------
--Modifié MATERIALIZED VIEW
--MV_ETAPE
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_ETAPE";
CREATE MATERIALIZED VIEW "OSE"."MV_ETAPE" ("LIBELLE","Z_TYPE_FORMATION_ID","NIVEAU","SPECIFIQUE_ECHANGES","Z_STRUCTURE_ID","Z_DOMAINE_FONCTIONNEL_ID","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  e.libelle,
  e.z_type_formation_id,
  to_number(e.niveau) niveau,
  e.specifique_echanges,
  e.z_structure_id,
  e.domaine_fonctionnel z_domaine_fonctionnel_id,
  s.id source_id,
  e.source_code
FROM
  ose_etape@apoprod e
  JOIN source s ON s.code = 'Apogee';
---------------------------
--Modifié MATERIALIZED VIEW
--MV_ETABLISSEMENT
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_ETABLISSEMENT";
CREATE MATERIALIZED VIEW "OSE"."MV_ETABLISSEMENT" ("LIBELLE","LOCALISATION","DEPARTEMENT","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  lib_off_etb as libelle,
  lic_etb as localisation,
  cod_dep as departement,
  src.id as source_id,
  cod_etb as source_code
FROM
  etablissement@apoprod e
  JOIN source src ON src.code = 'Apogee';
---------------------------
--Modifié MATERIALIZED VIEW
--MV_ELEMENT_TAUX_REGIMES
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_ELEMENT_TAUX_REGIMES";
CREATE MATERIALIZED VIEW "OSE"."MV_ELEMENT_TAUX_REGIMES" ("Z_ELEMENT_PEDAGOGIQUE_ID","ANNEE_ID","EFF_TAUX_FI","EFF_TAUX_FC","EFF_TAUX_FA","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  e.source_code z_element_pedagogique_id,
  to_number(e.annee_id) + 1 annee_id,
  e.effectif_fi eff_taux_fi,
  e.effectif_fc eff_taux_fc,
  e.effectif_fa eff_taux_fa,
  s.id source_id,
  e.source_code source_code
FROM
  ose_element_effectifs@apoprod e
  JOIN element_pedagogique ep ON ep.source_code = e.source_code
  JOIN source s ON s.code = 'Apogee'
WHERE
  (e.effectif_fi + e.effectif_fc + e.effectif_fa) > 0
  AND NOT EXISTS(
    SELECT * FROM element_taux_regimes etr JOIN element_pedagogique ep2 ON ep2.id = etr.element_pedagogique_id WHERE
      ep2.source_code = e.source_code
      AND ep2.annee_id = to_number(e.annee_id) + 1
      AND etr.source_id <> s.id
  );
---------------------------
--Modifié MATERIALIZED VIEW
--MV_ELEMENT_PEDAGOGIQUE
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_ELEMENT_PEDAGOGIQUE";
CREATE MATERIALIZED VIEW "OSE"."MV_ELEMENT_PEDAGOGIQUE" ("LIBELLE","Z_ETAPE_ID","Z_STRUCTURE_ID","Z_PERIODE_ID","FI","FC","FA","TAUX_FOAD","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  ep.libelle,
  ep.z_etape_id,
  ep.z_structure_id,
  ep.z_periode_id,
  CASE WHEN ep.fi+ep.fa+ep.fc=0 THEN 1 ELSE ep.fi END fi,
  ep.fc,ep.fa,
  ep.taux_foad,
  s.id source_id,
  ep.source_code
FROM
  ose_element_pedagogique@apoprod ep
  JOIN source s ON s.code = 'Apogee';
---------------------------
--Modifié MATERIALIZED VIEW
--MV_EFFECTIFS
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_EFFECTIFS";
CREATE MATERIALIZED VIEW "OSE"."MV_EFFECTIFS" ("Z_ELEMENT_PEDAGOGIQUE_ID","ANNEE_ID","FI","FC","FA","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  e.source_code z_element_pedagogique_id,
  to_number(e.annee_id) annee_id,
  e.effectif_fi fi,
  e.effectif_fc fc,
  e.effectif_fa fa,
  s.id source_id,
  e.source_code source_code
FROM
  ose_element_effectifs@apoprod e
  JOIN source s ON s.code = 'Apogee';
---------------------------
--Modifié MATERIALIZED VIEW
--MV_CORPS
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_CORPS";
CREATE MATERIALIZED VIEW "OSE"."MV_CORPS" ("LIBELLE_LONG","LIBELLE_COURT","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  select  
  ll_corps                            libelle_long,
  lc_corps                            libelle_court,
  s.id                                source_id,
  c_corps                             source_code
FROM
  corps@harpprod c
  JOIN source s ON s.code = 'Harpege'
WHERE
  1 = ose_divers.comprise_entre( NVL(d_ouverture_corps,SYSDATE), d_fermeture_corps+1 );
---------------------------
--Modifié MATERIALIZED VIEW
--MV_CHEMIN_PEDAGOGIQUE
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_CHEMIN_PEDAGOGIQUE";
CREATE MATERIALIZED VIEW "OSE"."MV_CHEMIN_PEDAGOGIQUE" ("Z_ELEMENT_PEDAGOGIQUE_ID","Z_ETAPE_ID","ORDRE","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  z_element_pedagogique_id,
  z_etape_id,
  ROW_NUMBER() OVER (PARTITION BY z_etape_id ORDER BY ROWNUM) ordre,
  s.id source_id,
  source_code
FROM
  ose_chemin_pedagogique@apoprod
  JOIN source s ON s.code = 'Apogee';
---------------------------
--Modifié MATERIALIZED VIEW
--MV_AFFECTATION_RECHERCHE
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_AFFECTATION_RECHERCHE";
CREATE MATERIALIZED VIEW "OSE"."MV_AFFECTATION_RECHERCHE" ("Z_STRUCTURE_ID","Z_INTERVENANT_ID","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
    to_char(AR.C_STRUCTURE)                       Z_STRUCTURE_ID,
    to_char(AR.NO_DOSSIER_PERS)                   Z_INTERVENANT_ID,
    s.id                                          SOURCE_ID,
    to_char(AR.no_seq_affe_rech)                  SOURCE_CODE
FROM
  affectation_recherche@harpprod ar
  JOIN source s ON s.code = 'Harpege'
WHERE
  SYSDATE BETWEEN AR.D_DEB_AFFE_RECH AND COALESCE(AR.D_FIN_AFFE_RECH + 1,SYSDATE);
---------------------------
--Nouveau MATERIALIZED VIEW
--MV_AFFECTATION
---------------------------
CREATE MATERIALIZED VIEW "OSE"."MV_AFFECTATION" ("Z_STRUCTURE_ID","Z_PERSONNEL_ID","Z_ROLE_ID","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT DISTINCT
  z_structure_id,
  z_personnel_id,
  z_role_id,
  source_id,
  MIN( source_code ) source_code
FROM ( SELECT
    CASE WHEN ifs.c_structure = 'UNIV' THEN NULL ELSE ifs.c_structure END z_structure_id,
    ifs.no_dossier_pers z_personnel_id,
    CASE 
      when fs.lc_fonction IN ('_D30a', '_D30b', '_D30c', '_D30d', '_D30e' ) then 'directeur-composante'
      when fs.lc_fonction IN ('_R00', '_R40', '_R40b')                      then 'responsable-composante'
      when fs.lc_fonction IN ('_R00c')                                      then 'responsable-recherche-labo'
      when ifs.c_structure = 'UNIV' AND fs.lc_fonction = '_P00' OR fs.lc_fonction LIKE '_P10%' OR fs.lc_fonction like '_P50%' then 'superviseur-etablissement'
      else NULL
    END z_role_id,
    s.id as source_id,
    to_char(ifs.no_exercice_respons) source_code
  FROM
    individu_fonct_struct@harpprod ifs
    JOIN fonction_structurelle@harpprod fs ON fs.c_fonction = ifs.c_fonction
    JOIN source s ON s.code = 'Harpege'
  WHERE
    SYSDATE BETWEEN ifs.DT_DEB_EXERC_RESP AND NVL(ifs.DT_FIN_EXERC_RESP + 1,SYSDATE)
  ) tmp
WHERE
  tmp.z_role_id IS NOT NULL
GROUP BY
  z_structure_id, z_personnel_id, z_role_id,source_id;
---------------------------
--Modifié MATERIALIZED VIEW
--MV_ADRESSE_STRUCTURE
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_ADRESSE_STRUCTURE";
CREATE MATERIALIZED VIEW "OSE"."MV_ADRESSE_STRUCTURE" ("Z_STRUCTURE_ID","PRINCIPALE","TELEPHONE","NO_VOIE","NOM_VOIE","LOCALITE","CODE_POSTAL","VILLE","PAYS_CODE_INSEE","PAYS_LIBELLE","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  "Z_STRUCTURE_ID", "PRINCIPALE", "TELEPHONE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE"
FROM (

SELECT DISTINCT
  ls.c_structure                                                  z_structure_id,
  CASE ls.tem_local_principal WHEN 'O' THEN 1 ELSE 0 END          principale,
  ls.no_telephone                                                 telephone,
  NO_VOIE_A || CASE BIS_TER_A
    WHEN 'B' THEN ' BIS'
    WHEN 'T' THEN ' TER'
    WHEN 'Q' THEN ' QUATER'
    WHEN 'C' THEN ' QUINQUIES'
    ELSE ''
  END                                                             NO_VOIE,
  UPPER(TRIM(TRIM(V.L_VOIE) || ' ' || TRIM(NOM_VOIE_A)))          NOM_VOIE,
  LOCALITE_A                                                      LOCALITE,
  COALESCE( CP_ETRANGER_ADMIN, CODE_POSTAL_A )                    CODE_POSTAL,
  TRIM(VILLE_A)                                                   VILLE,
  PAYS.C_PAYS                                                     PAYS_CODE_INSEE,
  PAYS.LL_PAYS                                                    PAYS_LIBELLE,
  src.id                                                          source_id,
  to_char(aa.id_adresse_admin) || '_' || ls.c_structure           source_code,
  COUNT(*) over(partition by aa.id_adresse_admin,ls.c_structure)  doublons
FROM
  adresse_administrat@harpprod aa
  JOIN "LOCAL"@harpprod l ON l.id_adresse_admin = aa.id_adresse_admin
  JOIN localisation_structure@harpprod ls ON ls.c_local = l.c_local
  JOIN source src ON src.code = 'Harpege'
  LEFT JOIN PAYS@HARPPROD PAYS ON (PAYS.C_PAYS = aa.C_PAYS)
  LEFT JOIN VOIRIE@HARPPROD V ON (V.C_VOIE = aa.C_VOIE)
WHERE
  SYSDATE BETWEEN COALESCE(aa.d_deb_val, SYSDATE) AND COALESCE(aa.d_fin_val, SYSDATE)
) tmp1

WHERE
  doublons = 1 OR principale = 1;
---------------------------
--Modifié MATERIALIZED VIEW
--MV_ADRESSE_INTERVENANT
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_ADRESSE_INTERVENANT";
CREATE MATERIALIZED VIEW "OSE"."MV_ADRESSE_INTERVENANT" ("Z_INTERVENANT_ID","PRINCIPALE","TEL_DOMICILE","MENTION_COMPLEMENTAIRE","NO_VOIE","NOM_VOIE","LOCALITE","CODE_POSTAL","VILLE","PAYS_CODE_INSEE","PAYS_LIBELLE","SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  
  LTRIM(TO_CHAR(NO_INDIVIDU,'99999999'))                Z_INTERVENANT_ID,
  CASE TEM_ADR_PERS_PRINC WHEN 'O' THEN 1 ELSE 0 END    PRINCIPALE,
  TRIM(TELEPHONE_DOMICILE)                              TEL_DOMICILE,
  TRIM(UPPER(HABITANT_CHEZ))                            MENTION_COMPLEMENTAIRE,
  NO_VOIE || CASE BIS_TER
    WHEN 'B' THEN ' BIS'
    WHEN 'T' THEN ' TER'
    WHEN 'Q' THEN ' QUATER'
    WHEN 'C' THEN ' QUINQUIES'
    ELSE ''
  END                                                   NO_VOIE,
  UPPER(TRIM(TRIM(V.L_VOIE) || ' ' || TRIM(NOM_VOIE)))  NOM_VOIE,
  LOCALITE                                              LOCALITE,
  COALESCE( CP_ETRANGER, CODE_POSTAL )                  CODE_POSTAL,
  TRIM(VILLE)                                           VILLE,
  PAYS.C_PAYS                                           PAYS_CODE_INSEE,
  PAYS.LL_PAYS                                          PAYS_LIBELLE,
  src.id                                                SOURCE_ID,
  to_char(ID_ADRESSE_PERSO)                             SOURCE_CODE
FROM
  ADRESSE_PERSONNELLE@HARPPROD ADRESSE
  JOIN source src ON src.code = 'Harpege'
  LEFT JOIN PAYS@HARPPROD PAYS ON (PAYS.C_PAYS = ADRESSE.C_PAYS)
  LEFT JOIN VOIRIE@HARPPROD V ON (V.C_VOIE = ADRESSE.C_VOIE)
WHERE
  ADRESSE.D_CREATION <= SYSDATE;
---------------------------
--Nouveau INDEX
--MV_PAYS_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_PAYS_PK" ON "OSE"."MV_PAYS" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--CATEGORIE_PRIVILEGE__UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."CATEGORIE_PRIVILEGE__UN" ON "OSE"."CATEGORIE_PRIVILEGE" ("CODE");
---------------------------
--Modifié INDEX
--AGREMENT__UN
---------------------------
DROP INDEX "OSE"."AGREMENT__UN";
  CREATE UNIQUE INDEX "OSE"."AGREMENT__UN" ON "OSE"."AGREMENT" ("TYPE_AGREMENT_ID","INTERVENANT_ID","STRUCTURE_ID");
---------------------------
--Modifié INDEX
--PRIVILEGE__UN
---------------------------
DROP INDEX "OSE"."PRIVILEGE__UN";
  CREATE UNIQUE INDEX "OSE"."PRIVILEGE__UN" ON "OSE"."PRIVILEGE" ("CATEGORIE_ID","CODE");
---------------------------
--Nouveau INDEX
--ROLE_PRIVILEGE_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."ROLE_PRIVILEGE_PK" ON "OSE"."ROLE_PRIVILEGE" ("PRIVILEGE_ID","ROLE_ID");
---------------------------
--Modifié INDEX
--SERVICE__UN
---------------------------
DROP INDEX "OSE"."SERVICE__UN";
  CREATE UNIQUE INDEX "OSE"."SERVICE__UN" ON "OSE"."SERVICE" ("INTERVENANT_ID","ELEMENT_PEDAGOGIQUE_ID","ETABLISSEMENT_ID","HISTO_DESTRUCTION");
---------------------------
--Modifié INDEX
--EFFECTIFS__UN
---------------------------
DROP INDEX "OSE"."EFFECTIFS__UN";
  CREATE UNIQUE INDEX "OSE"."EFFECTIFS__UN" ON "OSE"."EFFECTIFS" ("SOURCE_CODE","ANNEE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_SRC_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."AFFECTATION_R_SRC_UN" ON "OSE"."AFFECTATION_RECHERCHE" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--MV_DEPARTEMENT_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_DEPARTEMENT_PK" ON "OSE"."MV_DEPARTEMENT" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--MV_PERSONNEL_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_PERSONNEL_PK" ON "OSE"."MV_PERSONNEL" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."AFFECTATION_R_PK" ON "OSE"."AFFECTATION_RECHERCHE" ("ID");
---------------------------
--Modifié INDEX
--AFFECTATION_PK
---------------------------
DROP INDEX "OSE"."AFFECTATION_PK";
  CREATE UNIQUE INDEX "OSE"."AFFECTATION_PK" ON "OSE"."AFFECTATION" ("ID");
---------------------------
--Nouveau INDEX
--PERIMETRE_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."PERIMETRE_PK" ON "OSE"."PERIMETRE" ("ID");
---------------------------
--Nouveau INDEX
--DOSSIER_UK1
---------------------------
  CREATE UNIQUE INDEX "OSE"."DOSSIER_UK1" ON "OSE"."DOSSIER" ("INTERVENANT_ID","HISTO_DESTRUCTION");
---------------------------
--Modifié INDEX
--ELEMENT_TAUX_REGIMES__UNV1
---------------------------
DROP INDEX "OSE"."ELEMENT_TAUX_REGIMES__UNV1";
  CREATE UNIQUE INDEX "OSE"."ELEMENT_TAUX_REGIMES__UNV1" ON "OSE"."ELEMENT_TAUX_REGIMES" ("ELEMENT_PEDAGOGIQUE_ID","HISTO_DESTRUCTION");
---------------------------
--Nouveau INDEX
--CATEGORIE_PRIVILEGE_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."CATEGORIE_PRIVILEGE_PK" ON "OSE"."CATEGORIE_PRIVILEGE" ("ID");
---------------------------
--Nouveau INDEX
--MV_ADRESSE_INTERVENANT_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_ADRESSE_INTERVENANT_PK" ON "OSE"."MV_ADRESSE_INTERVENANT" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--PAYS_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."PAYS_PK" ON "OSE"."PAYS" ("ID");
---------------------------
--Nouveau INDEX
--ROLE_CODE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."ROLE_CODE_UN" ON "OSE"."ROLE" ("CODE");
---------------------------
--Nouveau INDEX
--PERIMETRE_LIBELLE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."PERIMETRE_LIBELLE_UN" ON "OSE"."PERIMETRE" ("LIBELLE");
---------------------------
--Modifié INDEX
--GROUPE__UN
---------------------------
DROP INDEX "OSE"."GROUPE__UN";
  CREATE UNIQUE INDEX "OSE"."GROUPE__UN" ON "OSE"."GROUPE" ("ELEMENT_PEDAGOGIQUE_ID","HISTO_DESTRUCTEUR_ID","TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--PERIMETRE_CODE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."PERIMETRE_CODE_UN" ON "OSE"."PERIMETRE" ("CODE");
---------------------------
--Nouveau INDEX
--MV_AFFECTATION_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MV_AFFECTATION_PK" ON "OSE"."MV_AFFECTATION" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--AFFECTATION_SOURCE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."AFFECTATION_SOURCE_UN" ON "OSE"."AFFECTATION" ("SOURCE_CODE");
---------------------------
--Modifié INDEX
--FORMULE_RESULTAT__UN
---------------------------
DROP INDEX "OSE"."FORMULE_RESULTAT__UN";
  CREATE UNIQUE INDEX "OSE"."FORMULE_RESULTAT__UN" ON "OSE"."FORMULE_RESULTAT" ("INTERVENANT_ID","TYPE_VOLUME_HORAIRE_ID","ETAT_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--FORMULE_RESULTAT_MAJ__PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."FORMULE_RESULTAT_MAJ__PK" ON "OSE"."FORMULE_RESULTAT_MAJ" ("INTERVENANT_ID");
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  ose_workflow.add_intervenant_to_update (CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END); 
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_PJ_VALIDATION_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_PJ_VALIDATION_S"
  AFTER UPDATE ON "OSE"."PIECE_JOINTE"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_PJ_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_PJ_VALIDATION"
  AFTER UPDATE OF VALIDATION_ID ON "OSE"."PIECE_JOINTE"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenantId NUMERIC;
BEGIN
  SELECT d.intervenant_id INTO intervenantId FROM dossier d WHERE d.id = :NEW.dossier_id;
  ose_workflow.add_intervenant_to_update (intervenantId); 
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_PJ
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_PJ"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTEUR_ID, OBLIGATOIRE ON "OSE"."PIECE_JOINTE"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenantId NUMERIC;
  dossierId NUMERIC;
BEGIN
  dossierId := CASE WHEN deleting THEN :OLD.dossier_id ELSE :NEW.dossier_id END;
  SELECT d.intervenant_id INTO intervenantId FROM dossier d WHERE d.id = dossierId;
  ose_workflow.add_intervenant_to_update (intervenantId); 
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_DOSSIER_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_DOSSIER_VALIDATION"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  DECLARE
  type_validation_id NUMERIC;
  code VARCHAR2(128);
  intervenant_id NUMERIC;
BEGIN
  type_validation_id := CASE WHEN deleting THEN :OLD.type_validation_id ELSE :NEW.type_validation_id END;
  SELECT code INTO code FROM type_validation WHERE id = type_validation_id;
  
  IF code = 'DONNEES_PERSO_PAR_COMP' THEN
    intervenant_id := CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END;
    ose_workflow.add_intervenant_to_update (intervenant_id); 
  END IF;
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_DOSSIER_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_DOSSIER_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."DOSSIER"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Modifié TRIGGER
--WF_TRG_DOSSIER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_DOSSIER"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."DOSSIER"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenantId NUMERIC;
BEGIN
  intervenantId := case when inserting or updating then :NEW.intervenant_id else :OLD.intervenant_id end;
  /*if intervenantId is null then
    return;
  end if;*/
  
  ose_workflow.add_intervenant_to_update (intervenantId); 
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_CLOTURE_REALISE_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_CLOTURE_REALISE_S"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  BEGIN
  ose_workflow.update_intervenants_etapes();
END;
/
---------------------------
--Nouveau TRIGGER
--WF_TRG_CLOTURE_REALISE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."WF_TRG_CLOTURE_REALISE"
  AFTER INSERT OR DELETE OR UPDATE OF HISTO_DESTRUCTION ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  DECLARE
  type_validation_id NUMERIC;
  code VARCHAR2(128);
  intervenant_id NUMERIC;
BEGIN
  type_validation_id := CASE WHEN deleting THEN :OLD.type_validation_id ELSE :NEW.type_validation_id END;
  SELECT tv.code INTO code FROM type_validation tv WHERE tv.id = type_validation_id;
  IF code = 'CLOTURE_REALISE' THEN
    intervenant_id := CASE WHEN deleting THEN :OLD.intervenant_id ELSE :NEW.intervenant_id END;
    ose_workflow.add_intervenant_to_update (intervenant_id); 
  END IF;
END;
/
---------------------------
--Modifié TRIGGER
--SERVICE_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_CK"
  BEFORE INSERT OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  DECLARE 
  etablissement integer;
  res integer;
BEGIN
  
  etablissement := OSE_PARAMETRE.GET_ETABLISSEMENT();
  
  IF :NEW.etablissement_id = etablissement AND :NEW.element_pedagogique_id IS NULL THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être renseigné si le service est réalisé en interne.');
  END IF;

  IF OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(:NEW.intervenant_id, 'saisie_service') = 0 THEN
    raise_application_error(-20101, 'Il est impossible de saisir des services pour cet intervenant.');
  END IF;

  IF :NEW.etablissement_id <> etablissement AND OSE_DIVERS.INTERVENANT_HAS_PRIVILEGE(:NEW.intervenant_id, 'saisie_service_exterieur') = 0 THEN
    raise_application_error(-20101, 'Les intervenants vacataires n''ont pas la possibilité de renseigner des enseignements pris à l''extérieur.');
  END IF;

  IF :NEW.intervenant_id IS NOT NULL AND :NEW.element_pedagogique_id IS NOT NULL THEN
    SELECT
      count(*) INTO res
    FROM
      intervenant i,
      element_pedagogique ep
    WHERE
          i.id        = :NEW.intervenant_id
      AND ep.id       = :NEW.element_pedagogique_id
      AND ep.annee_id = i.annee_id
    ;
    
    IF 0 = res THEN -- années non concomitantes
      raise_application_error(-20101, 'L''année de l''intervenant ne correspond pas à l''année de l''élément pédagogique.');
    END IF;
  END IF;

  --IF :OLD.id IS NOT NULL AND ( :NEW.etablissement_id <> :OLD.etablissement_id OR :NEW.element_pedagogique_id <> :OLD.element_pedagogique_id ) THEN
    --UPDATE volume_horaire SET histo_destruction = SYSDATE, histo_destructeur_id = :NEW.histo_modificateur_id WHERE service_id = :NEW.id;
  --END IF;

END;
/
---------------------------
--Nouveau TRIGGER
--PJ_TRG_TYPE_PJ_STATUT_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PJ_TRG_TYPE_PJ_STATUT_S"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."TYPE_PIECE_JOINTE_STATUT"
  BEGIN
  ose_pj.update_intervenants_pj();
END;
/
---------------------------
--Nouveau TRIGGER
--PJ_TRG_TYPE_PJ_STATUT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PJ_TRG_TYPE_PJ_STATUT"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."TYPE_PIECE_JOINTE_STATUT"
  REFERENCING FOR EACH ROW
  DECLARE
  tpjId numeric;
BEGIN
  tpjId := case when inserting or updating then :NEW.type_piece_jointe_id else :OLD.type_piece_jointe_id end;

  -- parcours de tous les intervenants ayant un dossier
  for r in (
    select d.intervenant_id 
    from dossier d 
    join intervenant_exterieur ie on d.intervenant_id = ie.id and ie.histo_destruction is null 
    where d.histo_destruction is null
  ) 
  loop
    ose_pj.add_intervenant_to_update (r.intervenant_id, tpjId); 
  end loop;
END;
/
---------------------------
--Nouveau TRIGGER
--PJ_TRG_DOSSIER_S
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PJ_TRG_DOSSIER_S"
  AFTER INSERT OR UPDATE OF STATUT_ID, PREMIER_RECRUTEMENT, RIB ON "OSE"."DOSSIER"
  BEGIN
  ose_pj.update_intervenants_pj();
END;
/
---------------------------
--Nouveau TRIGGER
--PJ_TRG_DOSSIER
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PJ_TRG_DOSSIER"
  AFTER INSERT OR UPDATE OF STATUT_ID, PREMIER_RECRUTEMENT, RIB ON "OSE"."DOSSIER"
  REFERENCING FOR EACH ROW
  DECLARE
  intervenantId numeric;
  tpjId numeric;
  TPJ_CODE_RIB varchar2(64) := 'RIB';
  ribImport varchar2(128);
BEGIN
  intervenantId := :NEW.intervenant_id;
  
  --dbms_output.put_line('PJ_TRG_DOSSIER');
  
  /**
   * Dans le cas d'une création de dossier ou si "Statut" ou "1er Recrutement" a changé, 
   * la liste des PJ attendues pour l'intervenant doit être mise à jour.
   */
  if inserting or :OLD.statut_id <> :NEW.statut_id or :OLD.premier_recrutement <> :NEW.premier_recrutement then
    --dbms_output.put_line('Statut ou 1er Recrut du dossier ' || :OLD.id || ' modifié...');
    --dbms_output.put_line('Statut     : ' || :OLD.statut_id           || ' -> ' || :NEW.statut_id);
    --dbms_output.put_line('1er Recrut : ' || :OLD.premier_recrutement || ' -> ' || :NEW.premier_recrutement);
    for t in (
      select id tpj_id from type_piece_jointe tpj where 1 = ose_divers.comprise_entre(tpj.histo_creation, tpj.histo_destruction)
    ) loop
      ose_pj.add_intervenant_to_update(intervenantId, t.tpj_id); 
    end loop;
  end if;
  
  /**
   * Si le RIB saisi diffère de celui importé, la PJ sera obligatoire.
   */
  select id into tpjId from type_piece_jointe where code = TPJ_CODE_RIB;
  select regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '') into ribImport from intervenant where id = intervenantId;
  if trim(:NEW.rib) <> ribImport then
    --dbms_output.put_line('RIB du dossier ' || :OLD.id || ' différent de celui importé : ' || trim(:NEW.rib) || ' <> ' || ribImport);
    ose_pj.add_intervenant_to_update(intervenantId, tpjId, 1); -- forcé à 1, i.e. obligatoire
  else
  /**
   * Si le RIB saisi égale celui importé, la PJ n'est plus requise.
   */
    --dbms_output.put_line('RIB du dossier ' || :OLD.id || ' identique à celui importé : ' || trim(:NEW.rib) || ' = ' || ribImport);
    ose_pj.add_intervenant_to_update(intervenantId, tpjId, 2); -- forcé à 2, i.e. non attendu
  end if;
END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service_referentiel s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_referentiel_id OR s.id = :OLD.service_referentiel_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      AND (s.id = :NEW.service_id OR s.id = :OLD.service_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
  
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE_REF
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE_REF"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE_REF"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire_ref vh
      JOIN service_referentiel s ON s.id = vh.service_referentiel_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_ref_id OR vh.id = :OLD.volume_horaire_ref_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
  
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION_VOL_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION_VOL_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."VALIDATION_VOL_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.id = :NEW.volume_horaire_id OR vh.id = :OLD.volume_horaire_id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
  
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_VALIDATION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VALIDATION"
  AFTER UPDATE ON "OSE"."VALIDATION"
  REFERENCING FOR EACH ROW
  BEGIN

  FOR p IN ( -- validations de volume horaire

    SELECT DISTINCT
      s.intervenant_id
    FROM
      validation_vol_horaire vvh
      JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (vvh.validation_id = :OLD.ID OR vvh.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );

  END LOOP;

  FOR p IN ( -- validations de contrat

    SELECT DISTINCT
      s.intervenant_id
    FROM
      contrat c
      JOIN volume_horaire vh ON vh.contrat_id = c.id AND 1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    WHERE
      (c.validation_id = :OLD.ID OR c.validation_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_TYPE_INTERVENTION
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_TYPE_INTERVENTION"
  AFTER UPDATE ON "OSE"."TYPE_INTERVENTION"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.type_intervention_id = :NEW.id OR vh.type_intervention_id = :OLD.id)
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
  
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_STATUT_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_STATUT_INTERVENANT"
  AFTER UPDATE ON "OSE"."STATUT_INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      fr.intervenant_id
    FROM
      intervenant i
      JOIN formule_resultat fr ON fr.intervenant_id = i.id
    WHERE
      (i.statut_id = :NEW.id OR i.statut_id = :OLD.id)
      AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
  
  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE_REFERENTIEL
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE_REFERENTIEL"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE_REFERENTIEL"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--F_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_SERVICE"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id );
  END IF;
END;
/
---------------------------
--Modifié TRIGGER
--F_MOTIF_MODIFICATION_SERVICE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MOTIF_MODIFICATION_SERVICE"
  AFTER DELETE OR UPDATE ON "OSE"."MOTIF_MODIFICATION_SERVICE"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      intervenant_id
    FROM
      modification_service_du msd
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( msd.histo_creation, msd.histo_destruction )
      AND (msd.motif_id = :NEW.id OR msd.motif_id = :OLD.id)
      
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
  
  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODULATEUR"
  AFTER DELETE OR UPDATE ON "OSE"."MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
      JOIN element_modulateur em ON 
        em.element_id   = s.element_pedagogique_id 
        AND 1 = ose_divers.comprise_entre( em.histo_creation, em.histo_destruction )
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (em.modulateur_id = :OLD.id OR em.modulateur_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );

  END LOOP;
END;
/
---------------------------
--Modifié TRIGGER
--F_MODIF_SERVICE_DU
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_MODIF_SERVICE_DU"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."MODIFICATION_SERVICE_DU"
  REFERENCING FOR EACH ROW
  BEGIN

  IF DELETING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :OLD.intervenant_id );
  END IF;
  IF INSERTING OR UPDATING THEN
    OSE_FORMULE.DEMANDE_CALCUL( :NEW.intervenant_id );
  END IF;

END;
/
---------------------------
--Modifié TRIGGER
--F_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT"
  AFTER UPDATE ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN

  FOR p IN (
      
    SELECT DISTINCT
      fr.intervenant_id
    FROM
      formule_resultat fr
    WHERE
      fr.intervenant_id = :NEW.id OR fr.intervenant_id = :OLD.id
  
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );

  END LOOP;
  
END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE"
  AFTER DELETE OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN FOR p IN
    ( SELECT DISTINCT s.intervenant_id
    FROM service s
    WHERE (s.element_pedagogique_id = :NEW.id
    OR s.element_pedagogique_id     = :OLD.id)
    AND 1                           = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
    ) LOOP OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
END LOOP;
END;
/
  ALTER TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE" DISABLE;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_MODULATEUR
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_MODULATEUR"
  AFTER INSERT OR DELETE OR UPDATE ON "OSE"."ELEMENT_MODULATEUR"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (
  
    SELECT DISTINCT
      s.intervenant_id
    FROM
      service s
    WHERE
      1 = OSE_DIVERS.COMPRISE_ENTRE( s.histo_creation, s.histo_destruction )
      AND (s.element_pedagogique_id = :OLD.element_id OR s.element_pedagogique_id = :NEW.element_id)
      
  ) LOOP
  
    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );
    
  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--F_CONTRAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT"
  AFTER DELETE OR UPDATE ON "OSE"."CONTRAT"
  REFERENCING FOR EACH ROW
  BEGIN
  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    WHERE
      1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    OSE_FORMULE.DEMANDE_CALCUL( p.intervenant_id );

  END LOOP;

END;
/
---------------------------
--Modifié TRIGGER
--ELEMENT_PEDAGOGIQUE_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."ELEMENT_PEDAGOGIQUE_CK"
  BEFORE INSERT OR UPDATE ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  DECLARE
  enseignement INTEGER;
  source_id INTEGER;
BEGIN
  SELECT id INTO source_id FROM source WHERE code = 'OSE';

  IF :NEW.source_id <> source_id THEN RETURN; END IF; -- impossible de checker car l'UPD par import se fait champ par champ...
  
  IF :NEW.fi = 0 AND :NEW.fc = 0 AND :NEW.fa = 0 THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être au moins en FI, FC ou FA');
  END IF;

  IF 1 <> ROUND(:NEW.taux_fi + :NEW.taux_fc + :NEW.taux_fa, 2) THEN
    raise_application_error( -20101, 'Le total des taux FI, FC et FA n''est pas égal à 1');
  END IF;

  IF :NEW.fi = 0 AND :NEW.taux_fi > 0 THEN
    raise_application_error( -20101, 'Le taux FI doit être à 0 puisque la formation n''est pas dispensée en FI');
  END IF;

  IF :NEW.fa = 0 AND :NEW.taux_fa > 0 THEN
    raise_application_error( -20101, 'Le taux FA doit être à 0 puisque la formation n''est pas dispensée en FA');
  END IF;
  
  IF :NEW.fc = 0 AND :NEW.taux_fc > 0 THEN
    raise_application_error( -20101, 'Le taux FC doit être à 0 puisque la formation n''est pas dispensée en FC');
  END IF;  

  IF :NEW.periode_id IS NOT NULL THEN
    SELECT p.enseignement
    INTO enseignement
    FROM periode p
    WHERE p.id	     = :NEW.periode_id;
    IF enseignement <> 1 THEN
      raise_application_error(-20101, 'Cette période n''est pas appliquable à cet élément pédagogique.');
    END IF;
  END IF;

END;
/
---------------------------
--Modifié PACKAGE
--UNICAEN_OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."UNICAEN_OSE_FORMULE" AS 

  PROCEDURE CALCUL_RESULTAT_V2( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC );

  PROCEDURE PURGE_EM_NON_FC;

END UNICAEN_OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_WORKFLOW" AS 

  PROCEDURE add_intervenant_to_update         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenant_etapes         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenants_etapes;
  PROCEDURE update_all_intervenants_etapes    (p_annee_id NUMERIC DEFAULT 2014);
  PROCEDURE Process_Intervenant_Etape (p_intervenant_id NUMERIC) ;
  
  TYPE T_LIST_STRUCTURE_ID IS TABLE OF NUMBER INDEX BY PLS_INTEGER;

  -- liste d'ids de structures
  l_structures_ids T_LIST_STRUCTURE_ID;
  
  --
  -- Fetch des ids des structures d'intervention (enseignement)
  --
  PROCEDURE fetch_struct_ens_ids_tvh          (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ens_ids              (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ens_realise_ids      (p_intervenant_id NUMERIC);
  
  --
  -- Fetch des ids des structures d'intervention (référentiel)
  --
  PROCEDURE fetch_struct_ref_ids_tvh          (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ref_ids              (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ref_realise_ids      (p_intervenant_id NUMERIC);
  
  --
  -- Fetch des ids des structures d'intervention (enseignement + référentiel)
  --
  PROCEDURE fetch_struct_ensref_ids          (p_intervenant_id NUMERIC);
  PROCEDURE fetch_struct_ensref_realis_ids   (p_intervenant_id NUMERIC);
  
    
  
  
  --------------------------------------------------------------------------------------------------------------------------
  -- Règles de pertinence et de franchissement des étapes
  --------------------------------------------------------------------------------------------------------------------------
  --
  -- Données personnelles
  --
  FUNCTION peut_saisir_dossier                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_dossier                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION dossier_valide                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Enseignements
  --  
  FUNCTION peut_saisir_service                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION possede_services_tvh               (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services                   (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_services_realises          (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION service_valide_tvh                 (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_valide                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION service_realise_valide             (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION peut_cloturer_realise              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION realise_cloture                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Référentiel
  --
  FUNCTION peut_saisir_referentiel            (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION possede_referentiel_tvh            (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_referentiel                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_referentiel_realise        (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION referentiel_valide_tvh             (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_valide                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION referentiel_realise_valide         (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Pièces justificatives
  --
  FUNCTION peut_saisir_pj                     (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_valider_pj                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION pj_oblig_fournies                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION pj_oblig_validees                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Agréments
  --
  FUNCTION necessite_agrement_cr              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION necessite_agrement_ca              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  FUNCTION agrement_cr_fourni                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION agrement_ca_fourni                 (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Contrat / avenant
  --
  FUNCTION necessite_contrat                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_contrat                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  
  --
  -- Paiement
  --
  FUNCTION peut_demander_mep                  (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_demande_mep                (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION peut_saisir_mep                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;
  FUNCTION possede_mep                        (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC;

END OSE_WORKFLOW;
/
---------------------------
--Nouveau PACKAGE
--OSE_PJ
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_PJ" AS 

  /**
   * Inscription de l'intervenant dont il faudra regénérer la liste des PJ à fournir.
   */
  PROCEDURE add_intervenant_to_update (intervenantId IN NUMERIC, tpjId IN NUMERIC, forceObligatoire IN NUMERIC default null);
  
  /**
   * Parcours des intervenants dont il faut regénérer la liste des PJ à fournir.
   */
  PROCEDURE update_intervenants_pj;
  
  /**
   * Recherche du caractère obligatoire d'un type de PJ pour un dossier.
   */
  function is_tpj_obligatoire(tpjId IN numeric, dossierId IN numeric) return numeric;
  
  /**
   * Mise à jour des PJ attendues pour le type de PJ et le dossier spécifiés.
   */
  procedure update_pj(tpjId IN numeric, dossierId IN numeric, forceObligatoire IN numeric default null);
  
END OSE_PJ;
/
---------------------------
--Modifié PACKAGE
--OSE_PARAMETRE
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_PARAMETRE" AS 

  function get_etablissement return Numeric;
  function get_annee return Numeric;
  function get_annee_import return Numeric;
  function get_ose_user return Numeric;
  function get_drh_structure_id return Numeric;
  function get_date_fin_saisie_permanents RETURN DATE;
  function get_ddeb_saisie_serv_real RETURN DATE;
  function get_dfin_saisie_serv_real RETURN DATE;
  function get_formule_package_name RETURN VARCHAR2;
  function get_formule_function_name RETURN VARCHAR2;

END OSE_PARAMETRE;
/
---------------------------
--Modifié PACKAGE
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_IMPORT" IS
 
  PROCEDURE set_current_user(p_current_user IN INTEGER);
  FUNCTION get_current_user return INTEGER;

  FUNCTION get_current_annee RETURN INTEGER;
  PROCEDURE set_current_annee (p_current_annee INTEGER);

  FUNCTION get_sql_criterion( table_name varchar2, sql_criterion VARCHAR2 ) RETURN CLOB;

  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL );
  PROCEDURE REFRESH_MVS;
  PROCEDURE SYNC_TABLES;
  PROCEDURE SYNCHRONISATION;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DEPARTEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PAYS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DOMAINE_FONCTIONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CENTRE_COUT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/
---------------------------
--Modifié PACKAGE
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_FORMULE" AS 

  TYPE t_intervenant IS RECORD (
    structure_id                   NUMERIC,
    heures_service_statutaire      FLOAT   DEFAULT 0,
    heures_service_modifie         FLOAT   DEFAULT 0,
    depassement_service_du_sans_hc FLOAT   DEFAULT 0
  );
  
  TYPE t_type_etat_vh IS RECORD (
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC
  );
  TYPE t_lst_type_etat_vh   IS TABLE OF t_type_etat_vh INDEX BY PLS_INTEGER;
  
  TYPE t_service_ref IS RECORD (
    id                        NUMERIC,
    structure_id              NUMERIC
  );
  TYPE t_lst_service_ref      IS TABLE OF t_service_ref INDEX BY PLS_INTEGER;
  
  TYPE t_service IS RECORD (
    id                        NUMERIC,
    taux_fi                   FLOAT   DEFAULT 1,
    taux_fa                   FLOAT   DEFAULT 0,
    taux_fc                   FLOAT   DEFAULT 0,
    ponderation_service_du    FLOAT   DEFAULT 1,
    ponderation_service_compl FLOAT   DEFAULT 1,
    structure_aff_id          NUMERIC,
    structure_ens_id          NUMERIC
  );
  TYPE t_lst_service          IS TABLE OF t_service INDEX BY PLS_INTEGER;
  
  TYPE t_volume_horaire_ref IS RECORD (
    id                        NUMERIC,
    service_referentiel_id    NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0
  );
  TYPE t_lst_volume_horaire_ref   IS TABLE OF t_volume_horaire_ref INDEX BY PLS_INTEGER;
  
  TYPE t_volume_horaire IS RECORD (
    id                        NUMERIC,
    service_id                NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    etat_volume_horaire_ordre NUMERIC,
    heures                    FLOAT   DEFAULT 0,
    taux_service_du           FLOAT   DEFAULT 1,
    taux_service_compl        FLOAT   DEFAULT 1
  );
  TYPE t_lst_volume_horaire   IS TABLE OF t_volume_horaire INDEX BY PLS_INTEGER;



  TYPE t_resultat_hetd IS RECORD (
    service_fi                FLOAT DEFAULT 0,
    service_fa                FLOAT DEFAULT 0,
    service_fc                FLOAT DEFAULT 0,
    heures_compl_fi           FLOAT DEFAULT 0,
    heures_compl_fa           FLOAT DEFAULT 0,
    heures_compl_fc           FLOAT DEFAULT 0,
    heures_compl_fc_majorees  FLOAT DEFAULT 0
  );
  TYPE t_lst_resultat_hetd   IS TABLE OF t_resultat_hetd INDEX BY PLS_INTEGER;

  TYPE t_resultat_hetd_ref IS RECORD (
    service_referentiel       FLOAT DEFAULT 0,
    heures_compl_referentiel  FLOAT DEFAULT 0
  );
  TYPE t_lst_resultat_hetd_ref   IS TABLE OF t_resultat_hetd_ref INDEX BY PLS_INTEGER;

  TYPE t_resultat IS RECORD (
    intervenant_id            NUMERIC,
    type_volume_horaire_id    NUMERIC,
    etat_volume_horaire_id    NUMERIC,
    service_du                FLOAT DEFAULT 0,
    solde                     FLOAT DEFAULT 0,
    sous_service              FLOAT DEFAULT 0,
    heures_compl              FLOAT DEFAULT 0,
    volume_horaire            t_lst_resultat_hetd,
    volume_horaire_ref        t_lst_resultat_hetd_ref
  );

  d_intervenant         t_intervenant;
  d_type_etat_vh        t_lst_type_etat_vh;
  d_service_ref         t_lst_service_ref;
  d_service             t_lst_service;
  d_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_volume_horaire      t_lst_volume_horaire;
  d_resultat            t_resultat;

  FUNCTION  GET_DATE_OBS RETURN DATE;
  FUNCTION  SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE;

  PROCEDURE SET_DEBUG_LEVEL( DEBUG_LEVEL NUMERIC );
  FUNCTION GET_DEBUG_LEVEL RETURN NUMERIC;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT;

  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC );
  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC );
  PROCEDURE CALCULER_SUR_DEMANDE; -- mise à jour de tous les items identifiés
  PROCEDURE CALCULER_TOUT;        -- mise à jour de TOUTES les données ! ! ! !
END OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_DIVERS" AS 

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC;

  FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC;

  FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB;
  
  FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC;
  
  FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC;

  FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE, date_obs DATE DEFAULT NULL, inclusif NUMERIC DEFAULT 0 ) RETURN NUMERIC;

  PROCEDURE DO_NOTHING;

  FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC;

  FUNCTION ANNEE_UNIVERSITAIRE( date_ref DATE DEFAULT SYSDATE, mois_deb_au NUMERIC DEFAULT 9, jour_deb_au NUMERIC DEFAULT 1 ) RETURN NUMERIC;

  PROCEDURE SYNC_LOG( msg CLOB );

END OSE_DIVERS;
/
---------------------------
--Modifié PACKAGE BODY
--UNICAEN_OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_OSE_FORMULE" AS

  /* Stockage des valeurs intermédiaires */
  TYPE t_valeurs IS TABLE OF FLOAT INDEX BY PLS_INTEGER;
  TYPE t_tableau IS RECORD (
    valeurs t_valeurs,
    total   FLOAT DEFAULT 0
  );  
  TYPE t_tableaux       IS TABLE OF t_tableau INDEX BY PLS_INTEGER;
  t                     t_tableaux;
  current_id            PLS_INTEGER;

  /* Accès au stockage des valeurs intermédiaires */
  -- Initialisation des tableaux de valeurs intermédiaires
  PROCEDURE V_INIT IS
  BEGIN
    t.delete;
  END;

  -- Setter d'une valeur intermédiaire au niveau case
  PROCEDURE SV( tab_index PLS_INTEGER, id PLS_INTEGER, val FLOAT ) IS
  BEGIN
    t(tab_index).valeurs(id) := val;
    t(tab_index).total       := t(tab_index).total + val;
  END;

  -- Setter d'une valeur intermédiaire au niveau tableau
  PROCEDURE SV( tab_index PLS_INTEGER, val FLOAT ) IS
  BEGIN
    t(tab_index).total      := val;
  END;

  -- Getter d'une valeur intermédiaire, au niveau case
  FUNCTION GV( tab_index PLS_INTEGER, id PLS_INTEGER DEFAULT NULL ) RETURN FLOAT IS
  BEGIN
    IF NOT t.exists(tab_index) THEN RETURN 0; END IF;
    IF NOT t(tab_index).valeurs.exists( NVL(id,current_id) ) THEN RETURN 0; END IF;
    RETURN t(tab_index).valeurs( NVL(id,current_id) );
  END;

  -- Getter d'une valeur intermédiaire, au niveau tableau
  FUNCTION GT( tab_index PLS_INTEGER ) RETURN FLOAT IS
  BEGIN 
    IF NOT t.exists(tab_index) THEN RETURN 0; END IF;
    RETURN t(tab_index).total;
  END;


  /* Débogage des valeurs intermédiaires */
  PROCEDURE DEBUG_TAB( tab_index PLS_INTEGER ) IS
    id PLS_INTEGER;
  BEGIN
    ose_test.echo( 'Tableau numéro ' || tab_index );
    
    id := ose_formule.d_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      dbms_output.put( 'Service id=' || lpad(id,6,' ') || ', data = ' );

      current_id := ose_formule.d_volume_horaire.FIRST;
      LOOP EXIT WHEN current_id IS NULL;
        dbms_output.put( lpad(gv(tab_index),10,' ') || ' | ' );
        current_id := ose_formule.d_volume_horaire.NEXT(current_id);
      END LOOP;
      dbms_output.new_line;
      id := ose_formule.d_service.NEXT(id);
    END LOOP;

    ose_test.echo( 'TOTAL = ' || LPAD(gt(tab_index), 10, ' ') );
  END;



  /* Calcul des valeurs intermédiaires */
  FUNCTION C_11( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_12( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc < 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_13( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) = NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_14( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
  
    IF NVL(s.structure_ens_id,0) <> NVL(s.structure_aff_id,0) AND s.taux_fc = 1 THEN
      RETURN vh.heures;
    ELSE
      RETURN 0;
    END IF;
  END;  

  FUNCTION C_15( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
    f ose_formule.t_service_ref;
  BEGIN
    f := ose_formule.d_service_ref( fr.service_referentiel_id );
  
    IF NVL(ose_formule.d_intervenant.structure_id,0) = NVL(f.structure_id,0) THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_16( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
    f ose_formule.t_service_ref;
  BEGIN
    f := ose_formule.d_service_ref( fr.service_referentiel_id );
    
    IF NVL(ose_formule.d_intervenant.structure_id,0) <> NVL(f.structure_id,0) AND NVL(f.structure_id,0) <> ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_17( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
    f ose_formule.t_service_ref;
  BEGIN
    f := ose_formule.d_service_ref( fr.service_referentiel_id );
    
    IF NVL(f.structure_id,0) = ose_divers.STRUCTURE_UNIV_GET_ID THEN
      RETURN fr.heures;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_21( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(11) * vh.taux_service_du;
  END;

  FUNCTION C_22( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(12) * vh.taux_service_du;
  END;
  
  FUNCTION C_23( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(13) * vh.taux_service_du;
  END;
  
  FUNCTION C_24( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(14) * vh.taux_service_du;
  END;

  FUNCTION C_25( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN gv(15);
  END;
  
  FUNCTION C_26( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN gv(16);
  END;
  
  FUNCTION C_27( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN gv(17);
  END;

  FUNCTION C_31 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( ose_formule.d_resultat.service_du - gt(21), 0 );
  END;

  FUNCTION C_32 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( gt(31) - gt(22), 0 );
  END;

  FUNCTION C_33 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( gt(32) - gt(23), 0 );
  END;

  FUNCTION C_34 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( gt(33) - gt(24), 0 );
  END;
  
  FUNCTION C_35 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( gt(34) - gt(25), 0 );
  END;

  FUNCTION C_36 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( gt(35) - gt(26), 0 );
  END;

  FUNCTION C_37 RETURN FLOAT IS
  BEGIN
    RETURN GREATEST( gt(36) - gt(27), 0 );
  END;

  FUNCTION C_41( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(21) <> 0 THEN
      RETURN gv(21) / gt(21);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_42( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(22) <> 0 THEN
      RETURN gv(22) / gt(22);
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_43( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(23) <> 0 THEN
      RETURN gv(23) / gt(23);
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_44( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(24) <> 0 THEN
      RETURN gv(24) / gt(24);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_45( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gt(25) <> 0 THEN
      RETURN gv(25) / gt(25);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_46( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gt(26) <> 0 THEN
      RETURN gv(26) / gt(26);
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_47( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gt(27) <> 0 THEN
      RETURN gv(27) / gt(27);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_51( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( ose_formule.d_resultat.service_du, gt(21) ) * gv(41);
  END;

  FUNCTION C_52( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( gt(31), gt(22) ) * gv(42);
  END;

  FUNCTION C_53( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( gt(32), gt(23) ) * gv(43);
  END;

  FUNCTION C_54( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( gt(33), gt(24) ) * gv(44);
  END;

  FUNCTION C_55( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( gt(34), gt(25) ) * gv(45);
  END;

  FUNCTION C_56( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( gt(35), gt(26) ) * gv(46);
  END;
  
  FUNCTION C_57( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN LEAST( gt(36), gt(27) ) * gv(47);
  END;  

  FUNCTION C_61( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(51) * s.taux_fi;
  END;

  FUNCTION C_62( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(52) * s.taux_fi;
  END;
  
  FUNCTION C_71( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(51) * s.taux_fa;
  END;

  FUNCTION C_72( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(52) * s.taux_fa;
  END;
  
  FUNCTION C_81( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(51) * s.taux_fc;
  END;

  FUNCTION C_82( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(52) * s.taux_fc;
  END;
  
  FUNCTION C_83( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(53) * s.taux_fc;
  END;

  FUNCTION C_84( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(54) * s.taux_fc;
  END;
  
  FUNCTION C_91( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gv(21) <> 0 THEN
      RETURN gv(51) / gv(21);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_92( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gv(22) <> 0 THEN
      RETURN gv(52) / gv(22);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_93( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gv(23) <> 0 THEN
      RETURN gv(53) / gv(23);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_94( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gv(24) <> 0 THEN
      RETURN gv(54) / gv(24);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_95( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gv(25) <> 0 THEN
      RETURN gv(55) / gv(25);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_96( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gv(26) <> 0 THEN
      RETURN gv(56) / gv(26);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_97( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gv(27) <> 0 THEN
      RETURN gv(57) / gv(27);
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_101( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(37) <> 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - gv(91);
    END IF;
  END;

  FUNCTION C_102( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(37) <> 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - gv(92);
    END IF;
  END;

  FUNCTION C_103( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(37) <> 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - gv(93);
    END IF;
  END;

  FUNCTION C_104( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    IF gt(37) <> 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - gv(94);
    END IF;
  END;

  FUNCTION C_105( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gt(37) <> 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - gv(95);
    END IF;
  END;

  FUNCTION C_106( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gt(37) <> 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - gv(96);
    END IF;
  END;
  
  FUNCTION C_107( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    IF gt(37) <> 0 THEN
      RETURN 0;
    ELSE
      RETURN 1 - gv(97);
    END IF;
  END;
  
  FUNCTION C_111( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(11) * vh.taux_service_compl * gv(101);
  END;

  FUNCTION C_112( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(12) * vh.taux_service_compl * gv(102);
  END;

  FUNCTION C_113( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(13) * vh.taux_service_compl * gv(103);
  END;
  
  FUNCTION C_114( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
  BEGIN
    RETURN gv(14) * vh.taux_service_compl * gv(104);
  END;

  FUNCTION C_115( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN gv(15) * gv(105);
  END;

  FUNCTION C_116( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN gv(16) * gv(106);
  END;

  FUNCTION C_117( fr ose_formule.t_volume_horaire_ref ) RETURN FLOAT IS
  BEGIN
    RETURN gv(17) * gv(107);
  END;

  FUNCTION C_123( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN gv(113) * s.ponderation_service_compl;
    ELSE
      RETURN gv(113);
    END IF;
  END;
  
  FUNCTION C_124( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );

    IF s.taux_fc = 1 THEN
      RETURN gv(114) * s.ponderation_service_compl;
    ELSE
      RETURN gv(114);
    END IF;    
  END;

  FUNCTION C_131( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(111) * s.taux_fi;
  END;

  FUNCTION C_132( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(112) * s.taux_fi;
  END;

  FUNCTION C_141( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(111) * s.taux_fa;
  END;

  FUNCTION C_142( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(112) * s.taux_fa;
  END;
  
  FUNCTION C_151( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(111) * s.taux_fc;
  END;

  FUNCTION C_152( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    RETURN gv(112) * s.taux_fc;
  END;
  
  FUNCTION C_153( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF gv(123) = gv(113) THEN
      RETURN gv(113) * s.taux_fc;
    ELSE
      RETURN 0;
    END IF;
  END;
  
  FUNCTION C_154( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF gv(124) = gv(114) THEN
      RETURN gv(114) * s.taux_fc;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_163( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF gv(123) <> gv(113) THEN
      RETURN gv(123) * s.taux_fc;
    ELSE
      RETURN 0;
    END IF;
  END;

  FUNCTION C_164( vh ose_formule.t_volume_horaire ) RETURN FLOAT IS
    s  ose_formule.t_service;
  BEGIN
    s  := ose_formule.d_service( vh.service_id );
    
    IF gv(124) <> gv(114) THEN
      RETURN gv(124) * s.taux_fc;
    ELSE
      RETURN 0;
    END IF;
  END;

  PROCEDURE CALCUL_RESULTAT_V2( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    current_tableau           PLS_INTEGER;
    id                        PLS_INTEGER;
    val                       FLOAT;
    TYPE t_liste_tableaux   IS VARRAY (100) OF PLS_INTEGER;
    liste_tableaux            t_liste_tableaux;
    resultat_total            FLOAT;
    res                       FLOAT;
    vh                        ose_formule.t_volume_horaire;
    vhr                       ose_formule.t_volume_horaire_ref;
  BEGIN
    V_INIT;

    ose_formule.d_resultat.service_du := CASE
      WHEN ose_formule.d_intervenant.depassement_service_du_sans_hc = 1 THEN 9999
      ELSE ose_formule.d_intervenant.heures_service_statutaire + ose_formule.d_intervenant.heures_service_modifie
    END;

    liste_tableaux := t_liste_tableaux(
       11,  12,  13,  14,  15,  16,  17,
       21,  22,  23,  24,  25,  26,  27,
       31,  32,  33,  34,  35,  36,  37,
       41,  42,  43,  44,  45,  46,  47,
       51,  52,  53,  54,  55,  56,  57,
       61,  62,
       71,  72,
       81,  82,  83,  84,       
       91,  92,  93,  94,  95,  96,  97,
      101, 102, 103, 104, 105, 106, 107,
      111, 112, 113, 114, 115, 116, 117,
                123, 124,
      131, 132,
      141, 142,
      151, 152, 153, 154,
                163, 164
    );

    FOR i IN liste_tableaux.FIRST .. liste_tableaux.LAST
    LOOP
      current_tableau := liste_tableaux(i);

      IF current_tableau IN ( -- calcul pour les volumes horaires des services
         11,  12,  13,  14,
         21,  22,  23,  24,
         41,  42,  43,  44,
         51,  52,  53,  54,
         61,  62,
         71,  72,
         81,  82,  83,  84,
         91,  92,  93,  94,
        101, 102, 103, 104,
        111, 112, 113, 114,
                  123, 124,
        131, 132,
        141, 142,
        151, 152, 153, 154,
                  163, 164
      ) THEN
      
        current_id := ose_formule.d_volume_horaire.FIRST;
        LOOP EXIT WHEN current_id IS NULL;
          vh := ose_formule.d_volume_horaire(current_id);
          res := CASE current_tableau
            WHEN  11 THEN  C_11 (vh) WHEN  12 THEN  C_12 (vh) WHEN  13 THEN  C_13 (vh) WHEN  14 THEN  C_14 (vh)
            WHEN  21 THEN  C_21 (vh) WHEN  22 THEN  C_22 (vh) WHEN  23 THEN  C_23 (vh) WHEN  24 THEN  C_24 (vh)
            WHEN  41 THEN  C_41 (vh) WHEN  42 THEN  C_42 (vh) WHEN  43 THEN  C_43 (vh) WHEN  44 THEN  C_44 (vh)
            WHEN  51 THEN  C_51 (vh) WHEN  52 THEN  C_52 (vh) WHEN  53 THEN  C_53 (vh) WHEN  54 THEN  C_54 (vh)
            WHEN  61 THEN  C_61 (vh) WHEN  62 THEN  C_62 (vh)
            WHEN  71 THEN  C_71 (vh) WHEN  72 THEN  C_72 (vh)
            WHEN  81 THEN  C_81 (vh) WHEN  82 THEN  C_82 (vh) WHEN  83 THEN  C_83 (vh) WHEN  84 THEN  C_84 (vh)
            WHEN  91 THEN  C_91 (vh) WHEN  92 THEN  C_92 (vh) WHEN  93 THEN  C_93 (vh) WHEN  94 THEN  C_94 (vh)
            WHEN 101 THEN C_101 (vh) WHEN 102 THEN C_102 (vh) WHEN 103 THEN C_103 (vh) WHEN 104 THEN C_104 (vh)
            WHEN 111 THEN C_111 (vh) WHEN 112 THEN C_112 (vh) WHEN 113 THEN C_113 (vh) WHEN 114 THEN C_114 (vh)
                                                              WHEN 123 THEN C_123 (vh) WHEN 124 THEN C_124 (vh)
            WHEN 131 THEN C_131 (vh) WHEN 132 THEN C_132 (vh)
            WHEN 141 THEN C_141 (vh) WHEN 142 THEN C_142 (vh)
            WHEN 151 THEN C_151 (vh) WHEN 152 THEN C_152 (vh) WHEN 153 THEN C_153 (vh) WHEN 154 THEN C_154 (vh)
                                                              WHEN 163 THEN C_163 (vh) WHEN 164 THEN C_164 (vh)
          END;
          SV( current_tableau, current_id, res );
          current_id := ose_formule.d_volume_horaire.NEXT(current_id);
        END LOOP;
      
      ELSIF current_tableau IN ( -- calcul des services restants dus
        31, 32, 33, 34, 35, 36, 37
      ) THEN
      
        res := CASE current_tableau
          WHEN 31 THEN C_31  WHEN 32 THEN C_32  WHEN 33 THEN C_33
          WHEN 34 THEN C_34  WHEN 35 THEN C_35  WHEN 36 THEN C_36
          WHEN 37 THEN C_37
        END;
        SV( current_tableau, res );
  
      ELSIF current_tableau IN ( -- tableaux de calcul des volumes horaires référentiels
         15,  16,  17,
         25,  26,  27,
         45,  46,  47,
         55,  56,  57,     
         95,  96,  97,
        105, 106, 107,
        115, 116, 117
      ) THEN  

        current_id := ose_formule.d_volume_horaire_ref.FIRST;
        LOOP EXIT WHEN current_id IS NULL;
          vhr := ose_formule.d_volume_horaire_ref(current_id);
          res := CASE current_tableau
            WHEN  15 THEN  C_15 (vhr)  WHEN  16 THEN  C_16 (vhr)  WHEN  17 THEN  C_17 (vhr)
            WHEN  25 THEN  C_25 (vhr)  WHEN  26 THEN  C_26 (vhr)  WHEN  27 THEN  C_27 (vhr)
            WHEN  45 THEN  C_45 (vhr)  WHEN  46 THEN  C_46 (vhr)  WHEN  47 THEN  C_47 (vhr)
            WHEN  55 THEN  C_55 (vhr)  WHEN  56 THEN  C_56 (vhr)  WHEN  57 THEN  C_57 (vhr)
            WHEN  95 THEN  C_95 (vhr)  WHEN  96 THEN  C_96 (vhr)  WHEN  97 THEN  C_97 (vhr)
            WHEN 105 THEN C_105 (vhr)  WHEN 106 THEN C_106 (vhr)  WHEN 107 THEN C_107 (vhr)
            WHEN 115 THEN C_115 (vhr)  WHEN 116 THEN C_116 (vhr)  WHEN 117 THEN C_117 (vhr)
          END;
          SV(current_tableau, current_id, res);
          current_id := ose_formule.d_volume_horaire_ref.NEXT(current_id);
        END LOOP;

      END IF;
    END LOOP;

    resultat_total :=                                         gt( 55) + gt( 56) + gt( 57)
                    + gt( 61) + gt( 62)
                    + gt( 71) + gt( 72)
                    + gt( 81) + gt( 82) + gt( 83) + gt( 84)
                                                            + gt(115) + gt(116) + gt(117)                                       
                    + gt(131) + gt(132)
                    + gt(141) + gt(142)
                    + gt(151) + gt(152) + gt(153) + gt(154)
                                        + gt(163) + gt(164);

    ose_formule.d_resultat.service_du := CASE
      WHEN ose_formule.d_intervenant.depassement_service_du_sans_hc = 1
      THEN GREATEST(resultat_total, ose_formule.d_intervenant.heures_service_statutaire + ose_formule.d_intervenant.heures_service_modifie)
      ELSE ose_formule.d_intervenant.heures_service_statutaire + ose_formule.d_intervenant.heures_service_modifie
    END;
    ose_formule.d_resultat.solde                    := resultat_total - ose_formule.d_resultat.service_du;
    IF ose_formule.d_resultat.solde >= 0 THEN
      ose_formule.d_resultat.sous_service           := 0;
      ose_formule.d_resultat.heures_compl           := ose_formule.d_resultat.solde;
    ELSE
      ose_formule.d_resultat.sous_service           := ose_formule.d_resultat.solde * -1;
      ose_formule.d_resultat.heures_compl           := 0;
    END IF;

     -- répartition des résultats par volumes horaires
    current_id := ose_formule.d_volume_horaire.FIRST;
    LOOP EXIT WHEN current_id IS NULL;
      ose_formule.d_resultat.volume_horaire(current_id).service_fi               := gv( 61) + gv( 62);
      ose_formule.d_resultat.volume_horaire(current_id).service_fa               := gv( 71) + gv( 72);
      ose_formule.d_resultat.volume_horaire(current_id).service_fc               := gv( 81) + gv( 82) + gv( 83) + gv( 84);
      ose_formule.d_resultat.volume_horaire(current_id).heures_compl_fi          := gv(131) + gv(132);
      ose_formule.d_resultat.volume_horaire(current_id).heures_compl_fa          := gv(141) + gv(142);
      ose_formule.d_resultat.volume_horaire(current_id).heures_compl_fc          := gv(151) + gv(152) + gv(153) + gv(154);
      ose_formule.d_resultat.volume_horaire(current_id).heures_compl_fc_majorees :=                     gv(163) + gv(164);
      current_id := ose_formule.d_volume_horaire.NEXT(current_id); 
    END LOOP;

    -- répartition des résultats par volumes horaires référentiel
    current_id := ose_formule.d_volume_horaire_ref.FIRST;
    LOOP EXIT WHEN current_id IS NULL;
      ose_formule.d_resultat.volume_horaire_ref(current_id).service_referentiel      := gv(55) + gv(56) + gv(57);
      ose_formule.d_resultat.volume_horaire_ref(current_id).heures_compl_referentiel := gv(115) + gv(116) + gv(117);
      current_id := ose_formule.d_volume_horaire_ref.NEXT(current_id); 
    END LOOP;

  END;


  PROCEDURE PURGE_EM_NON_FC IS
  BEGIN
    FOR em IN (
      SELECT
        em.id
      FROM 
        ELEMENT_MODULATEUR em
        JOIN element_pedagogique ep ON ep.id = em.element_id AND 1 = ose_divers.comprise_entre(ep.histo_creation,ep.histo_destruction)
      WHERE
        1 = ose_divers.comprise_entre(em.histo_creation,em.histo_destruction)
        AND ep.taux_fc < 1
    ) LOOP
      UPDATE
        element_modulateur
      SET
        histo_destruction = SYSDATE,
        histo_destructeur_id = ose_parametre.get_ose_user 
      WHERE
        id = em.id
      ;
    END LOOP;
  END;

END UNICAEN_OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_WORKFLOW" AS

  --------------------------------------------------------------------------------------------------------------------------
  -- Moteur du workflow.
  --------------------------------------------------------------------------------------------------------------------------
  
  /**
   * Inscription de l'intervenant dont il faudra regénérer la progression dans le workflow.
   */
  PROCEDURE Add_Intervenant_To_Update (p_intervenant_id NUMERIC)
  IS
  BEGIN 
    MERGE INTO wf_tmp_intervenant t USING dual ON (t.intervenant_id = p_intervenant_id) WHEN NOT MATCHED THEN INSERT (INTERVENANT_ID) VALUES (p_intervenant_id);
  END;
  
  /**
   * Parcours des intervenants dont il faut regénérer la progression dans le workflow.
   */
  PROCEDURE Update_Intervenants_Etapes 
  IS
  BEGIN
    FOR ti IN (SELECT distinct * FROM wf_tmp_intervenant) LOOP
      --DBMS_OUTPUT.put_line ('wf_tmp_intervenant.intervenant_id = '||ti.intervenant_id);
      ose_workflow.Update_Intervenant_Etapes(ti.intervenant_id);
    END LOOP;
    DELETE FROM wf_tmp_intervenant;
  END;
  
  /**
   * Regénère la progression dans le workflow de tous les intervenants dont le statut autorise la saisie de service.
   */
  PROCEDURE Update_All_Intervenants_Etapes (p_annee_id NUMERIC DEFAULT 2014)
  IS
    CURSOR intervenant_cur IS 
      SELECT i.* FROM intervenant i 
      JOIN statut_intervenant si ON si.id = i.statut_id AND 1 = ose_divers.comprise_entre(si.histo_creation, si.histo_destruction) AND si.peut_saisir_service = 1
      WHERE i.annee_id = p_annee_id AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction);
  BEGIN
    FOR intervenant_rec IN intervenant_cur
    LOOP
      --DBMS_OUTPUT.put_line (intervenant_rec.nom_usuel || '(' || intervenant_rec.source_code || ')');
      ose_workflow.Update_Intervenant_Etapes(intervenant_rec.id);
    END LOOP;
  END;
  
  /**
   * Test
   */
  PROCEDURE Process_Intervenant_Etape (p_intervenant_id NUMERIC/*, p_structure_dependant NUMERIC*/) 
  IS
    structure_id NUMERIC;
    pertinente NUMERIC;
    franchie NUMERIC;
    atteignable NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
    parentId NUMERIC;
    intervenantEtapeIdPrec NUMERIC := 0;
  BEGIN    
    --
    -- Parcours des étapes.
    --
    FOR etape_rec IN (       
      select e.* from wf_etape e
      where e.code <> 'DEBUT' and e.code <> 'FIN' and e.annee_id = ( select annee_id from intervenant where id = p_intervenant_id ) 
      order by e.ordre
    )
    LOOP
      --
      -- Si l'étape n'est pas pertinente, on passe à la suivante.
      --
      pertinente := 0;
      IF etape_rec.PERTIN_FUNC IS NULL THEN
        pertinente := 1;
      ELSE
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.PERTIN_FUNC || '(:1); END;' USING OUT pertinente, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' --> ' || etape_rec.PERTIN_FUNC || ' returned ' || pertinente);
      END IF;
      IF pertinente = 0 THEN 
        CONTINUE;
      END IF;
      
      --
      -- La règle (fonction) de franchissement prend 2 arguments : l'id de l'intervenant (null interdit) et l'id de la structure (null accepté).
      -- Cette règle sera exécutée une fois avec un id de structure null (ce qui se traduit par "peu importe la structure"), puis
      -- autant de fois qu'il existe de structures d'enseignement dans le cas où l'étape est déclinable par structure.
      -- L'id null et les ids des structures sont stockés dans une liste qui sera parcourue plus loin.
      --
      l_structures_ids.DELETE;
      -- id structure null
      l_structures_ids(l_structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 AND etape_rec.STRUCTURES_IDS_FUNC IS NOT NULL THEN
        EXECUTE IMMEDIATE 'BEGIN ' || etape_rec.STRUCTURES_IDS_FUNC || '(:1); END;' USING p_intervenant_id;
      END IF;
      
      parentId := null;
        
      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null 
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où 
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. l_structures_ids.COUNT - 1
      LOOP
        structure_id := l_structures_ids(i);
        DBMS_OUTPUT.put_line (etape_rec.libelle || ' : structures_ids('||i||') := ' || structure_id);
        
        --
        -- Interrogation de la règle de franchissement de l'étape.
        --
        IF etape_rec.FRANCH_FUNC IS NULL THEN
          franchie := 1;
        ELSE
          EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.FRANCH_FUNC || '(:1, :2); END;' USING OUT franchie, p_intervenant_id, structure_id;
          --DBMS_OUTPUT.put_line (etape_rec.FRANCH_FUNC || ' returned ' || franchie);
        END IF;
        
        courante := 0;
        atteignable := 0;
        
        --
        -- Ecriture dans la table.
        --
        INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, structure_id, courante, franchie, atteignable, ordre, parent_id) SELECT 
          wf_intervenant_etape_id_seq.nextval, 
          p_intervenant_id, 
          etape_rec.id, 
          structure_id, 
          courante, 
          franchie, 
          atteignable, 
          ordre, 
          parentId
        FROM DUAL;
        
        -- mémorisation de l'id parent : c'est celui pour lequel aucune structure n'est spécifié
        if structure_id is null then
          parentId := wf_intervenant_etape_id_seq.currval;
        end if;
        
      END LOOP;
        
      ordre := ordre + 1;
      
    END LOOP;
  END;
  
  
  /**
   * Regénère la progression complète dans le workflow d'un intervenant.
   */
  PROCEDURE Update_Intervenant_Etapes (p_intervenant_id NUMERIC) 
  IS
    v_annee_id NUMERIC;
    structures_ids T_LIST_STRUCTURE_ID;
    structure_id NUMERIC;
    pertinente NUMERIC;
    franchie NUMERIC;
    atteignable NUMERIC;
    courante NUMERIC;
    courante_trouvee NUMERIC := 0;
    ordre NUMERIC := 1;
  BEGIN
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;
    
    --
    -- Année concernée.
    --
    select i.annee_id into v_annee_id from intervenant i where i.id = p_intervenant_id;
    
    --
    -- Parcours des étapes de l'année concernée.
    --
    FOR etape_rec IN ( select * from wf_etape where annee_id = v_annee_id and code <> 'DEBUT' and code <> 'FIN' order by ordre )
    LOOP
      --
      -- Si l'étape n'est pas pertinente, on passe à la suivante.
      --
      pertinente := 0;
      IF etape_rec.PERTIN_FUNC IS NULL THEN
        pertinente := 1;
      ELSE
        EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.PERTIN_FUNC || '(:1); END;' USING OUT pertinente, p_intervenant_id;
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' --> ' || etape_rec.PERTIN_FUNC || ' returned ' || pertinente);
      END IF;
      IF pertinente = 0 THEN 
        CONTINUE;
      END IF;
      
      --
      -- La règle (fonction) de franchissement prend 2 arguments : l'id de l'intervenant (null interdit) et l'id de la structure (null accepté).
      -- Cette règle sera exécutée une fois avec un id de structure null (ce qui se traduit par "peu importe la structure"), puis
      -- autant de fois qu'il existe de structures d'enseignement dans le cas où l'étape est déclinable par structure.
      -- L'id null et les ids des structures sont stockés dans une liste qui sera parcourue plus loin.
      --
      l_structures_ids.DELETE;
      -- id structure null
      l_structures_ids(l_structures_ids.COUNT) := NULL;
      -- pour les étapes pouvant être déclinées par structure, collecte des structures d'enseignement
      IF etape_rec.STRUCTURE_DEPENDANT = 1 AND etape_rec.STRUCTURES_IDS_FUNC IS NOT NULL THEN
        --ose_workflow.fetch_struct_ens_ids(p_intervenant_id, structures_ids);
        EXECUTE IMMEDIATE 'BEGIN ' || etape_rec.STRUCTURES_IDS_FUNC || '(:1); END;' USING p_intervenant_id;
      END IF;
      
      --
      -- Dans la progression de l'intervenant, une même étape peut figurer plusieurs fois : une fois avec un id de structure null 
      -- (ce qui se traduit par "peu importe la structure") + autant de fois qu'il existe de structures d'enseignement dans le cas où 
      -- l'étape est déclinable par structure.
      --
      FOR i IN 0 .. l_structures_ids.COUNT - 1
      LOOP
        structure_id := l_structures_ids(i);
        --DBMS_OUTPUT.put_line (etape_rec.libelle || ' : structures_ids('||i||') := ' || structure_id);
        
        --
        -- Interrogation de la règle de franchissement de l'étape.
        --
        IF etape_rec.FRANCH_FUNC IS NULL THEN
          franchie := 1;
        ELSE
          EXECUTE IMMEDIATE 'BEGIN :res := ' || etape_rec.FRANCH_FUNC || '(:1, :2); END;' USING OUT franchie, p_intervenant_id, structure_id;
          --DBMS_OUTPUT.put_line (etape_rec.FRANCH_FUNC || ' returned ' || franchie);
        END IF;
                        
        atteignable := 1;
        
        --
        -- Si l'étape courante n'a pas encore été trouvée.
        --
        IF courante_trouvee = 0 THEN 
          IF franchie = 1 THEN 
            courante := 0;
          ELSE
            -- l'étape marquée "courante" est la 1ère étape non franchie
            courante := 1;
            courante_trouvee := etape_rec.id;
          END IF;
        --
        -- Si l'étape courante a été trouvée et que l'on se situe dessus.
        --
        ELSIF courante_trouvee = etape_rec.id THEN
          IF franchie = 1 THEN 
            courante := 0;
          ELSE
            courante := 1;
          END IF;
        --
        -- Une étape située après l'étape courante est forcément "non courante".
        --
        ELSE
          courante := 0;
          atteignable := 0;
        END IF;
                        
        --
        -- Ecriture dans la table.
        --
        INSERT INTO wf_intervenant_etape (id, intervenant_id, etape_id, structure_id, courante, franchie, atteignable, ordre) 
          SELECT wf_intervenant_etape_id_seq.nextval, p_intervenant_id, etape_rec.id, structure_id, courante, franchie, atteignable, ordre FROM DUAL;
        
        ordre := ordre + 1;
      END LOOP;
      
    END LOOP;
  END;
  
  /**
   * Fetch les ids des structures d'enseignement PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ens_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_ids_tvh('PREVU', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ens_realise_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_ids_tvh('REALISE', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement de l'intervenant spécifié, 
   * pour le type de volume horaire spécifié.
   */
  PROCEDURE fetch_struct_ens_ids_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    i := l_structures_ids.COUNT;
    FOR d IN (
      SELECT distinct ep.structure_id 
      FROM element_pedagogique ep
      JOIN service s on s.element_pedagogique_id = ep.id /*AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE()*/ AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id
    ) LOOP
      l_structures_ids(i) := d.structure_id;
      i := i + 1;
    END LOOP;
  END;
  
  /**
   * Fetch les ids des structures du référentiel PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ref_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ref_ids_tvh('PREVU', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures du référentiel REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ref_realise_ids (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ref_ids_tvh('REALISE', p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures du référentiel de l'intervenant spécifié, 
   * pour le seul type de volume horaire spécifié.
   */
  PROCEDURE fetch_struct_ref_ids_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    i := l_structures_ids.COUNT;
    FOR d IN (
      SELECT distinct structure_id FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND S.ANNEE_ID = OSE_PARAMETRE.GET_ANNEE()*/ AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
    ) LOOP
      l_structures_ids(i) := d.structure_id;
      i := i + 1;
    END LOOP;
  END;
  
  
  /**
   * Fetch les ids des structures d'enseignement + les ids des structures du référentiel PREVU de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ensref_ids          (p_intervenant_id NUMERIC)
  IS
    i PLS_INTEGER;
  BEGIN
    fetch_struct_ens_ids (p_intervenant_id);
    fetch_struct_ref_ids (p_intervenant_id);
  END;
  
  /**
   * Fetch les ids des structures d'enseignement + les ids des structures du référentiel REALISE de l'intervenant spécifié.
   */
  PROCEDURE fetch_struct_ensref_realis_ids  (p_intervenant_id NUMERIC)
  IS
  BEGIN
    fetch_struct_ens_realise_ids (p_intervenant_id);
    fetch_struct_ref_realise_ids (p_intervenant_id);
  END;
  
  
  
  
  
  
  
  --------------------------------------------------------------------------------------------------------------------------
  -- Règles de pertinence et de franchissement des étapes.
  --------------------------------------------------------------------------------------------------------------------------
  
  /**
   *
   */
  FUNCTION peut_saisir_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_dossier INTO res FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_dossier (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM dossier d where d.intervenant_id = p_intervenant_id and 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction);
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION dossier_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM validation v 
    JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'DONNEES_PERSO_PAR_COMP' 
    WHERE 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    AND v.intervenant_id = p_intervenant_id;
    RETURN res;
  END;
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_service (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_service INTO res FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION possede_services (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_services_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_services_realises (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_services_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_services_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) /*AND s.annee_id = ose_parametre.get_annee()*/;
    ELSE
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id 
      JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/
      AND ep.structure_id = p_structure_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;
  
  /**
   *
   */
  FUNCTION service_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
  BEGIN
    RETURN service_valide_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION service_realise_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
  BEGIN
    RETURN service_valide_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION service_valide_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR service_cur IS 
      SELECT s.*, ep.structure_id
      FROM service s 
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON vh.type_volume_horaire_id = tvh.id AND tvh.code = p_type_volume_horaire_code
      JOIN v_volume_horaire_etat vhe ON vhe.volume_horaire_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id AND 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
      JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) /*AND s.annee_id = ose_parametre.get_annee()*/;
    service_rec service_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    FOR service_rec IN service_cur
    LOOP
      IF p_structure_id IS NULL THEN
        -- si aucune structure n'est spécifiée, on se contente du moindre service trouvé
        return 1;
      END IF;
      -- si une structure précise est spécifiée, on se contente du moindre service trouvé concernant cette structure d'enseignement
      IF service_rec.structure_id = p_structure_id THEN
        return 1;
      END IF;
    END LOOP;
    
    RETURN 0;
  END;
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_cloturer_realise              (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    estPerm numeric;
  BEGIN
    select count(*) into estPerm
    from type_intervenant ti 
    join statut_intervenant si on si.TYPE_INTERVENANT_ID = ti.id 
    join intervenant i on i.STATUT_ID = si.id and i.id = p_intervenant_id
    where ti.code = 'P';
    
    return estPerm;
  END;
  
  /**
   *
   */
  FUNCTION realise_cloture                    (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    found numeric;
  BEGIN
    select count(*) into found 
    from validation v 
    join type_validation tv on tv.id = v.type_validation_id and tv.code = 'CLOTURE_REALISE'
    where 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    and v.intervenant_id = p_intervenant_id;
    
    return case when found > 0 then 1 else 0 end;
  END;
  
  
  
  
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_saisir_referentiel INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_referentiel_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel_realise (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_referentiel_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;
  
  /**
   *
   */
  FUNCTION possede_referentiel_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/;
    ELSE
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/
      AND s.structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION referentiel_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN referentiel_valide_tvh('PREVU', p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION referentiel_realise_valide (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN referentiel_valide_tvh('REALISE', p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION referentiel_valide_tvh (p_type_volume_horaire_code VARCHAR2, p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    CURSOR ref_cur IS 
      SELECT s.* FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON vh.type_volume_horaire_id = tvh.id AND tvh.code = p_type_volume_horaire_code
      JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id
      JOIN etat_volume_horaire evh ON evh.id = vhe.etat_volume_horaire_id AND evh.ordre >= ( SELECT min(ordre) FROM etat_volume_horaire WHERE code = 'valide' )
      WHERE s.intervenant_id = p_intervenant_id /*AND s.annee_id = ose_parametre.get_annee()*/;
    ref_rec ref_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    IF p_structure_id IS NULL THEN
      -- si aucune structure n'est spécifiée, on se contente du moindre référentiel trouvé
      OPEN ref_cur;
      FETCH ref_cur INTO ref_rec;
      IF ref_cur%FOUND = TRUE THEN
        res := 1;
      END IF;
      CLOSE ref_cur;
    ELSE
      -- si une structure précise est spécifiée, on se contente du moindre référentiel trouvé concernant cette structure d'enseignement
      FOR ref_rec IN ref_cur
      LOOP
        IF ref_rec.structure_id = p_structure_id THEN
          res := 1;
          EXIT;
        END IF;
      END LOOP;
    END IF;
    RETURN res;
  END;
  
  
  
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION peut_saisir_pj (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_piece_jointe_statut tpjs 
    JOIN statut_intervenant si on tpjs.statut_intervenant_id = si.id 
    JOIN intervenant i ON i.statut_id = si.id
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION peut_valider_pj (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    res := peut_saisir_pj(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
  
    -- nombre de pj fournies (avec fichier)
    select count(*) into res
    from PIECE_JOINTE_FICHIER pjf
    join PIECE_JOINTE pj ON pjf.piece_jointe_id = pj.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
    join dossier d on pj.dossier_id = d.id and d.intervenant_id = p_intervenant_id and 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction);
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pj_oblig_fournies (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- verif existence de données perso
    res := possede_dossier(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
  
    SELECT count(*) INTO res FROM (WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.id) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON I.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          inner join piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant, VALIDEES OU NON
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.ID) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON IE.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          I.ID, 
          I.source_code,
          I.nom_usuel,
          COALESCE(A.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(F.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM intervenant i
      left join ATTENDU_OBLIGATOIRE A on a.intervenant_id = i.id
      LEFT JOIN FOURNI_OBLIGATOIRE  F ON F.INTERVENANT_ID = i.id
      WHERE i.ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU = 0 OR NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION pj_oblig_validees (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    -- verif existence de données perso
    res := possede_dossier(p_intervenant_id, p_structure_id);
    if (res = 0) then
      return 0;
    end if;
    
    SELECT count(*) INTO res FROM (
      WITH 
      ATTENDU_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES pour chaque intervenant
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.id) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON I.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          inner join piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant et VALIDEES 
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(pj.ID) NB
          FROM INTERVENANT_EXTERIEUR IE
          INNER JOIN INTERVENANT I ON IE.ID = I.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
          INNER JOIN DOSSIER d ON IE.ID = d.intervenant_ID AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE pj.OBLIGATOIRE = 1
          and pj.validation_id is not null
          GROUP BY I.ID, I.SOURCE_CODE
      )
      SELECT 
          I.ID, 
          I.source_code,
          I.nom_usuel,
          COALESCE(A.NB, 0) NB_PJ_OBLIG_ATTENDU, 
          COALESCE(F.NB, 0) NB_PJ_OBLIG_FOURNI
      FROM intervenant i
      left join ATTENDU_OBLIGATOIRE A on a.intervenant_id = i.id
      LEFT JOIN FOURNI_OBLIGATOIRE  F ON F.INTERVENANT_ID = i.id
      WHERE i.ID = p_intervenant_id
    )
    WHERE NB_PJ_OBLIG_ATTENDU = 0 OR NB_PJ_OBLIG_ATTENDU <= NB_PJ_OBLIG_FOURNI;
    
    RETURN res;
  END;
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION necessite_agrement_cr (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas 
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_RESTREINT'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 
    AND i.id = p_intervenant_id;
    RETURN res;
  END;
  
  /**
   *
   */
  FUNCTION necessite_agrement_ca (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res FROM type_agrement_statut tas 
    JOIN type_agrement ta ON ta.id = tas.type_agrement_id AND ta.code = 'CONSEIL_ACADEMIQUE'
    JOIN statut_intervenant si on tas.statut_intervenant_id = si.id
    JOIN intervenant i ON i.statut_id = si.id
    WHERE tas.PREMIER_RECRUTEMENT = i.PREMIER_RECRUTEMENT AND tas.OBLIGATOIRE = 1 
    AND i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_cr_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    code VARCHAR2(64) := 'CONSEIL_RESTREINT';
  BEGIN
    WITH 
    composantes_enseign AS (
        -- composantes d'enseignement par intervenant
        SELECT DISTINCT i.ID, i.source_code, ep.structure_id
        FROM element_pedagogique ep
        INNER JOIN service s on s.element_pedagogique_id = ep.id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
        INNER JOIN intervenant i ON i.ID = s.intervenant_id AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
        INNER JOIN STRUCTURE comp ON comp.ID = ep.structure_id AND 1 = ose_divers.comprise_entre(comp.histo_creation, comp.histo_destruction)
        WHERE 1 = ose_divers.comprise_entre(ep.histo_creation, ep.histo_destruction)
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND ep.structure_id = p_structure_id)
    ),
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND 1 = ose_divers.comprise_entre(ta.histo_creation, ta.histo_destruction)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND 1 = ose_divers.comprise_entre(tas.histo_creation, tas.histo_destruction)
        WHERE 1 = ose_divers.comprise_entre(a.histo_creation, a.histo_destruction)
        AND ta.code = code
        AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND A.structure_id = p_structure_id)
    ), 
    v_agrement AS (
      -- nombres de composantes d'enseignement et d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from COMPOSANTES_ENSEIGN ce where ce.id = i.id ) nb_comp, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE (
      -- si aucune structure précise n'est spécifiée, on ne retient que les intervenants qui ont au moins un d'agrément CR
      p_structure_id IS NULL AND nb_agrem > 0
      OR 
      -- si une structure précise est spécifiée, on ne retient que les intervenants qui ont (au moins) autant d'agréments CR que de composantes d'enseignement
      p_structure_id IS NOT NULL AND v.nb_comp <= nb_agrem 
    ) 
    AND v.id = p_intervenant_id ;
    
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION agrement_ca_fourni (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
    v_code VARCHAR2(64) := 'CONSEIL_ACADEMIQUE';
  BEGIN
    WITH 
    agrements_oblig_exist AS (
        -- agréments obligatoires obtenus par intervenant et structure
        SELECT i.ID, i.source_code, A.type_agrement_id, A.ID agrement_id, A.structure_id
        FROM agrement A
        INNER JOIN type_agrement ta ON A.type_agrement_id = ta.ID AND 1 = ose_divers.comprise_entre(ta.histo_creation, ta.histo_destruction)
        INNER JOIN intervenant i ON A.intervenant_id = i.ID AND 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
        INNER JOIN type_agrement_statut tas ON i.statut_id = tas.statut_intervenant_id AND ta.ID = tas.type_agrement_id 
            AND i.premier_recrutement = tas.premier_recrutement AND tas.obligatoire = 1 AND 1 = ose_divers.comprise_entre(tas.histo_creation, tas.histo_destruction)
        WHERE 1 = ose_divers.comprise_entre(a.histo_creation, a.histo_destruction)
        AND ta.code = v_code
    ), 
    v_agrement AS (
      -- nombres d'agrément obligatoires fournis par intervenant
      SELECT DISTINCT i.ID, i.source_code, 
        ( select count(*) from AGREMENTS_OBLIG_EXIST ao where ao.id = i.id ) nb_agrem
      FROM intervenant i 
      WHERE 1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
    )
    SELECT COUNT(*) INTO res
    FROM v_agrement v
    WHERE nb_agrem > 0
    AND v.id = p_intervenant_id ;
    
    RETURN res;
  END;
  
  
   
  
  
  
  
  
  
  
  /**
   *
   */
  FUNCTION necessite_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT si.peut_avoir_contrat INTO res FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    RETURN res;
  END;

  /**
   *
   */
  FUNCTION possede_contrat (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    SELECT count(*) INTO res 
    FROM contrat c
    JOIN validation v ON c.validation_id = v.id AND 1 = ose_divers.comprise_entre(v.histo_creation, v.histo_destruction)
    WHERE 1 = ose_divers.comprise_entre(c.histo_creation, c.histo_destruction)
    AND c.intervenant_id = p_intervenant_id
    AND (p_structure_id IS NULL OR p_structure_id IS NOT NULL AND c.STRUCTURE_ID = p_structure_id) 
    AND ROWNUM = 1;
    
    RETURN res;
  END;






  /**
   *
   */
  FUNCTION peut_demander_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      select count(*) into res from v_indic_attente_demande_mep where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_indic_attente_demande_mep where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION possede_demande_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      select count(*) into res from v_indic_attente_mep where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_indic_attente_mep where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;

  /**
   *
   */
  FUNCTION peut_saisir_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    RETURN possede_demande_mep(p_intervenant_id, p_structure_id);
  END;

  /**
   *
   */
  FUNCTION possede_mep (p_intervenant_id NUMERIC, p_structure_id NUMERIC DEFAULT NULL) RETURN NUMERIC
  IS
    res NUMERIC;
  BEGIN
    IF p_structure_id IS NULL THEN
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
    END IF;
    RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
  END;


END OSE_WORKFLOW;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_TEST
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_TEST" AS
  TYPE OUT_LIST IS TABLE OF CLOB;

  SUCCES_SHOWN BOOLEAN DEFAULT TRUE;
  T_SUCCES_COUNT NUMERIC DEFAULT 0;
  T_ECHECS_COUNT NUMERIC DEFAULT 0;
  A_SUCCES_COUNT NUMERIC DEFAULT 0;
  A_ECHECS_COUNT NUMERIC DEFAULT 0;
  CURRENT_TEST CLOB;
  CURRENT_TEST_OUTPUT_BUFFER OUT_LIST := OUT_LIST();
  CURRENT_TEST_OUTPUT_BUFFER_ERR BOOLEAN;
  
  PROCEDURE SHOW_SUCCES IS
  BEGIN
    SUCCES_SHOWN := true;
  END SHOW_SUCCES;

  PROCEDURE HIDE_SUCCES IS
  BEGIN
    SUCCES_SHOWN := false;
  END HIDE_SUCCES;

  PROCEDURE DEBUT( TEST_NAME CLOB ) IS
  BEGIN
    CURRENT_TEST := TEST_NAME;
    CURRENT_TEST_OUTPUT_BUFFER_ERR := FALSE;
    echo (' '); echo('TEST ' || TEST_NAME || ' >>>>>>>>>>' );
  END;

  PROCEDURE FIN IS
    TEST_NAME CLOB;
  BEGIN
    IF CURRENT_TEST_OUTPUT_BUFFER_ERR THEN
      T_ECHECS_COUNT := T_ECHECS_COUNT + 1;
      echo('>>>>>>>>>> FIN DU TEST ' || CURRENT_TEST ); echo (' ');
      CURRENT_TEST := NULL;

      FOR i IN 1 .. CURRENT_TEST_OUTPUT_BUFFER.COUNT LOOP
        echo( CURRENT_TEST_OUTPUT_BUFFER(i) );
      END LOOP;
    ELSE
      T_SUCCES_COUNT := T_SUCCES_COUNT + 1;
      TEST_NAME := CURRENT_TEST;
      CURRENT_TEST := NULL;
      echo('SUCCÈS DU TEST : ' || TEST_NAME );
    END IF;
    CURRENT_TEST_OUTPUT_BUFFER.DELETE; -- clear buffer
  END;

  PROCEDURE ECHO( MSG CLOB ) IS
  BEGIN
    IF CURRENT_TEST IS NULL THEN
      dbms_output.put_line(MSG);
    ELSE
      CURRENT_TEST_OUTPUT_BUFFER.EXTEND;
      CURRENT_TEST_OUTPUT_BUFFER (CURRENT_TEST_OUTPUT_BUFFER.LAST) := MSG;
    END IF;
  END;

  PROCEDURE INIT IS
  BEGIN
    T_SUCCES_COUNT  := 0;
    T_ECHECS_COUNT  := 0;
    A_SUCCES_COUNT  := 0;
    A_ECHECS_COUNT  := 0;
    CURRENT_TEST    := NULL;
  END INIT;

  PROCEDURE SHOW_STATS IS
  BEGIN
    echo ( ' ' );
    echo ( '********************************* STATISTIQUES *********************************' );
    echo ( ' ' );
    echo ( '   - nombre de tests passés avec succès :       ' || T_SUCCES_COUNT );
    echo ( '   - nombre de tests ayant échoué :             ' || T_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '   - nombre d''assertions passés avec succès :   ' || A_SUCCES_COUNT );
    echo ( '   - nombre d''assertions ayant échoué :         ' || A_ECHECS_COUNT );
    echo ( ' ' );
    echo ( '********************************************************************************' );
    echo ( ' ' );
  END;

  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB ) IS
  BEGIN
    IF condition THEN
      A_SUCCES_COUNT := A_SUCCES_COUNT + 1;
      IF SUCCES_SHOWN THEN
        ECHO('        SUCCÈS : ' || MSG );
      END IF;
    ELSE
      A_ECHECS_COUNT := A_ECHECS_COUNT + 1;
      CURRENT_TEST_OUTPUT_BUFFER_ERR := TRUE;
      ECHO('        ** ECHEC ** : ' || MSG );
    END IF;
  END;
  
  PROCEDURE ADD_BUFFER( table_name VARCHAR2, id NUMERIC ) IS
  BEGIN
    INSERT INTO TEST_BUFFER( ID, TABLE_NAME, DATA_ID ) 
                    VALUES ( TEST_BUFFER_ID_SEQ.NEXTVAL, table_name, id );
  END;
  
  PROCEDURE DELETE_TEST_DATA IS
  BEGIN
    FOR tb IN (SELECT * FROM TEST_BUFFER)
    LOOP
      EXECUTE IMMEDIATE 'DELETE FROM ' || tb.table_name || ' WHERE ID = ' || tb.data_id;
    END LOOP;
    DELETE FROM TEST_BUFFER;
  END;
  
  FUNCTION GET_USER RETURN NUMERIC IS
  BEGIN
    RETURN 1; -- utilisateur réservé aux tests... (à revoir!!)
  END;
 
  FUNCTION GET_SOURCE RETURN NUMERIC IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.source s WHERE s.code = 'TEST';
    RETURN res_id;
  END;
  
  
  FUNCTION GET_CIVILITE( libelle_court VARCHAR2 DEFAULT NULL ) RETURN civilite%rowtype IS
    res civilite%rowtype;
  BEGIN
    SELECT * INTO res FROM civilite WHERE
      (OSE_DIVERS.LIKED( libelle_court, GET_CIVILITE.libelle_court ) = 1 OR GET_CIVILITE.libelle_court IS NULL) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENANT( code VARCHAR2 DEFAULT NULL ) RETURN type_intervenant%rowtype IS
    res type_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervenant WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_INTERVENANT.code ) = 1 OR GET_TYPE_INTERVENANT.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENANT_BY_ID( id NUMERIC ) RETURN type_intervenant%rowtype IS
    res type_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervenant WHERE
      id = GET_TYPE_INTERVENANT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_STATUT_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN statut_intervenant%rowtype IS
    res statut_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM statut_intervenant WHERE
      (OSE_DIVERS.LIKED( source_code, GET_STATUT_INTERVENANT.source_code ) = 1 OR GET_STATUT_INTERVENANT.source_code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STATUT_INTERVENANT_BY_ID( id NUMERIC ) RETURN statut_intervenant%rowtype IS
    res statut_intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM statut_intervenant WHERE id = GET_STATUT_INTERVENANT_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_STRUCTURE( code VARCHAR2 DEFAULT NULL ) RETURN type_structure%rowtype IS
    res type_structure%rowtype;
  BEGIN
    SELECT * INTO res FROM type_structure WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_STRUCTURE.code ) = 1 OR GET_TYPE_STRUCTURE.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STRUCTURE( source_code VARCHAR2 DEFAULT NULL ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE
      (OSE_DIVERS.LIKED( source_code, GET_STRUCTURE.source_code ) = 1 OR GET_STRUCTURE.source_code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_STRUCTURE_BY_ID( id NUMERIC ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE id = GET_STRUCTURE_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION GET_STRUCTURE_ENS_BY_NIVEAU( niveau NUMERIC ) RETURN structure%rowtype IS
    res structure%rowtype;
  BEGIN
    SELECT * INTO res FROM structure WHERE
      niveau = GET_STRUCTURE_ENS_BY_NIVEAU.niveau AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_STRUCTURE_UNIV RETURN "STRUCTURE"%rowtype IS
    res "STRUCTURE"%rowtype;
  BEGIN
    SELECT * INTO res FROM "STRUCTURE" WHERE source_code = 'UNIV' AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction );
    RETURN res;  
  END;

  FUNCTION ADD_STRUCTURE(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    parente_id    NUMERIC,
    type_id       NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    parente  structure%rowtype;
    niv2_id  NUMERIC;
  BEGIN
    entity_id := STRUCTURE_ID_SEQ.NEXTVAL;
    IF parente_id IS NOT NULL THEN
      parente := GET_STRUCTURE_BY_ID( parente_id );
      niv2_id := CASE
        WHEN parente.niveau = 1 THEN entity_id
        WHEN parente.niveau = 2 THEN parente_id
        WHEN parente.niveau = 3 THEN parente.parente_id
        WHEN parente.niveau = 4 THEN GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id
        WHEN parente.niveau = 5 THEN GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id ).parente_id
        WHEN parente.niveau = 6 THEN GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( GET_STRUCTURE_BY_ID( parente.parente_id ).parente_id ).parente_id ).parente_id
      END;
    END IF;
    INSERT INTO STRUCTURE (
      ID,
      LIBELLE_LONG,
      LIBELLE_COURT,
      PARENTE_ID,
      STRUCTURE_NIV2_ID,
      TYPE_ID,
      ETABLISSEMENT_ID,
      NIVEAU,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle_long,
      libelle_court,
      parente_id,
      niv2_id,
      type_id,
      OSE_PARAMETRE.GET_ETABLISSEMENT,
      NVL( parente.niveau, 1),
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'structure', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      (OSE_DIVERS.LIKED( source_code, GET_INTERVENANT.source_code ) = 1 OR GET_INTERVENANT.source_code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_ID( id NUMERIC DEFAULT NULL ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE id = GET_INTERVENANT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_STATUT( statut_id NUMERIC ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      statut_id = GET_INTERVENANT_BY_STATUT.statut_id AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_INTERVENANT_BY_TYPE( type_id NUMERIC ) RETURN intervenant%rowtype IS
    res intervenant%rowtype;
  BEGIN
    SELECT * INTO res FROM intervenant WHERE
      type_id = GET_INTERVENANT_BY_TYPE.type_id AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;  
  END;

  FUNCTION ADD_INTERVENANT(
    civilite_id     NUMERIC,
    nom_usuel       VARCHAR2,
    prenom          VARCHAR2,
    date_naissance  DATE,
    email           VARCHAR2,
    statut_id       NUMERIC,
    structure_id    NUMERIC,
    source_code     VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    statut statut_intervenant%rowtype;
    type_interv type_intervenant%rowtype;
  BEGIN
    entity_id := INTERVENANT_ID_SEQ.NEXTVAL;
    statut := GET_STATUT_INTERVENANT_BY_ID( statut_id );
    type_interv := GET_TYPE_INTERVENANT_BY_ID( statut.type_intervenant_id );
    INSERT INTO INTERVENANT (
      ID,
      CIVILITE_ID,
      NOM_USUEL,
      PRENOM,
      NOM_PATRONYMIQUE,
      DATE_NAISSANCE,
      PAYS_NAISSANCE_CODE_INSEE,
      PAYS_NAISSANCE_LIBELLE,
      EMAIL,
      TYPE_ID,
      STATUT_ID,
      STRUCTURE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      civilite_id,
      nom_usuel,
      prenom,
      nom_usuel,
      date_naissance,
      100,
      'FRANCE',
      email,
      type_interv.id,
      statut_id,
      structure_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant', entity_id);
    IF type_interv.code = 'P' THEN
      INSERT INTO INTERVENANT_PERMANENT(
        ID,
        SOURCE_ID,
        SOURCE_CODE,
        HISTO_CREATEUR_ID,
        HISTO_MODIFICATEUR_ID
      )VALUES(
        entity_id,
        GET_SOURCE,
        source_code,
        GET_USER,
        GET_USER
      );
      INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant_permanent', entity_id);
    END IF;
    IF type_interv.code = 'E' THEN
      INSERT INTO INTERVENANT_EXTERIEUR(
        ID,
        SOURCE_ID,
        SOURCE_CODE,
        HISTO_CREATEUR_ID,
        HISTO_MODIFICATEUR_ID
      )VALUES(
        entity_id,
        GET_SOURCE,
        source_code,
        GET_USER,
        GET_USER
      );
      INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant_exterieur', entity_id);
    END IF;
    RETURN entity_id;
  END;

  FUNCTION GET_GROUPE_TYPE_FORMATION( source_code VARCHAR2 DEFAULT NULL ) RETURN groupe_type_formation%rowtype IS
    res groupe_type_formation%rowtype;
  BEGIN
    SELECT * INTO res FROM groupe_type_formation WHERE
      (OSE_DIVERS.LIKED( source_code, GET_GROUPE_TYPE_FORMATION.source_code ) = 1 OR GET_GROUPE_TYPE_FORMATION.source_code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_GROUPE_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL;
    INSERT INTO GROUPE_TYPE_FORMATION (
      ID,
      LIBELLE_COURT,
      LIBELLE_LONG,
      ORDRE,
      PERTINENCE_NIVEAU,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      entity_id,
      libelle_court,
      libelle_long,
      999,
      0,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'groupe_type_formation', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_TYPE_FORMATION( source_code VARCHAR2 ) RETURN type_formation%rowtype IS
    res type_formation%rowtype;
  BEGIN
    SELECT * INTO res FROM type_formation WHERE
      (OSE_DIVERS.LIKED( source_code, GET_TYPE_FORMATION.source_code ) = 1 OR GET_TYPE_FORMATION.source_code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    groupe_id     NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := TYPE_FORMATION_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_FORMATION(
      ID,
      LIBELLE_LONG,
      LIBELLE_COURT,
      GROUPE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      entity_id,
      libelle_long,
      libelle_court,
      groupe_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_formation', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_ETAPE( source_code VARCHAR2 DEFAULT NULL ) RETURN etape%rowtype IS
    res etape%rowtype;
  BEGIN
    SELECT * INTO res FROM etape WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ETAPE.source_code ) = 1 OR GET_ETAPE.source_code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_ETAPE(
    libelle           VARCHAR2,
    type_formation_id NUMERIC,
    niveau            NUMERIC,
    structure_id      NUMERIC,
    source_code       VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := ETAPE_ID_SEQ.NEXTVAL;
    INSERT INTO ETAPE (
      ID,
      LIBELLE,
      TYPE_FORMATION_ID,
      NIVEAU,
      SPECIFIQUE_ECHANGES,
      STRUCTURE_ID,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle,
      type_formation_id,
      niveau,
      0,
      structure_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'etape', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_PERIODE( code VARCHAR2 DEFAULT NULL ) RETURN periode%rowtype IS
    res periode%rowtype;
  BEGIN
    SELECT * INTO res FROM periode WHERE
      (OSE_DIVERS.LIKED( code, GET_PERIODE.code ) = 1 OR GET_PERIODE.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_ELEMENT_PEDAGOGIQUE( source_code VARCHAR2 DEFAULT NULL ) RETURN element_pedagogique%rowtype IS
    res element_pedagogique%rowtype;
  BEGIN
    SELECT * INTO res FROM element_pedagogique WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ELEMENT_PEDAGOGIQUE.source_code ) = 1 OR GET_ELEMENT_PEDAGOGIQUE.source_code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_ELEMENT_PEDAGOGIQUE_BY_ID( ID NUMERIC ) RETURN element_pedagogique%rowtype IS
    res element_pedagogique%rowtype;
  BEGIN
    SELECT * INTO res FROM element_pedagogique WHERE id = GET_ELEMENT_PEDAGOGIQUE_BY_ID.id;
    RETURN res;
  END;
  
  FUNCTION ADD_ELEMENT_PEDAGOGIQUE(
    libelle       VARCHAR2,
    etape_id      NUMERIC,
    structure_id  NUMERIC,
    periode_id    NUMERIC,
    taux_foad     FLOAT,
    taux_fi       FLOAT,
    taux_fc       FLOAT,
    taux_fa       FLOAT,
    source_code   VARCHAR2
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ch_id NUMERIC;
  BEGIN
    entity_id := ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL;
    INSERT INTO ELEMENT_PEDAGOGIQUE (
      ID,
      LIBELLE,
      ETAPE_ID,
      STRUCTURE_ID,
      PERIODE_ID,
      TAUX_FOAD,
      TAUX_FI,
      TAUX_FC,
      TAUX_FA,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      libelle,
      etape_id,
      structure_id,
      periode_id,
      taux_foad,
      taux_fi,
      taux_fc,
      taux_fa,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    ch_id := CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL;
    INSERT INTO CHEMIN_PEDAGOGIQUE (
      ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ETAPE_ID,
      ORDRE,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      ch_id,
      entity_id,
      etape_id,
      9999999,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'element_pedagogique', entity_id);
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'chemin_pedagogique', ch_id);
    RETURN entity_id;
  END;

  FUNCTION GET_TYPE_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN type_modulateur%rowtype IS
    res type_modulateur%rowtype;
  BEGIN
    SELECT * INTO res FROM type_modulateur WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_MODULATEUR.code ) = 1 OR GET_TYPE_MODULATEUR.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_TYPE_MODULATEUR(
    code        VARCHAR2,
    libelle     VARCHAR2,
    publique    NUMERIC,
    obligatoire NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
    tms_id    NUMERIC;
    structure_id NUMERIC;
  BEGIN
    entity_id := TYPE_MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_MODULATEUR (
      ID,
      CODE,
      LIBELLE,
      PUBLIQUE,
      OBLIGATOIRE,
      SAISIE_PAR_ENSEIGNANT,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle,
      publique,
      obligatoire,
      0,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_modulateur', entity_id);
    structure_id := ose_test.get_structure_univ().id;
    tms_id := TYPE_MODULATEUR_STRUCTU_ID_SEQ.NEXTVAL;
    INSERT INTO TYPE_MODULATEUR_STRUCTURE(
      ID,
      TYPE_MODULATEUR_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      tms_id,
      entity_id,
      structure_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'type_modulateur_structure', tms_id);
    RETURN entity_id;
  END;

  FUNCTION GET_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN modulateur%rowtype IS
    res modulateur%rowtype;
  BEGIN
    SELECT * INTO res FROM modulateur WHERE
      (OSE_DIVERS.LIKED( code, GET_MODULATEUR.code ) = 1 OR GET_MODULATEUR.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_MODULATEUR(
    code                      VARCHAR2,
    libelle                   VARCHAR2,
    type_modulateur_id        NUMERIC,
    ponderation_service_du    FLOAT,
    ponderation_service_compl FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO MODULATEUR (
      ID,
      CODE,
      LIBELLE,
      TYPE_MODULATEUR_ID,
      PONDERATION_SERVICE_DU,
      PONDERATION_SERVICE_COMPL,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle,
      type_modulateur_id,
      ponderation_service_du,
      ponderation_service_compl,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'modulateur', entity_id);
    RETURN entity_id;
  END;

  FUNCTION ADD_ELEMENT_MODULATEUR(
    element_id    NUMERIC,
    modulateur_id NUMERIC,
    annee_id      NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := ELEMENT_MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO ELEMENT_MODULATEUR (
      ID,
      ELEMENT_ID,
      MODULATEUR_ID,
      ANNEE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      element_id,
      modulateur_id,
      annee_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'element_modulateur', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_FONCTION_REFERENTIEL( code VARCHAR2 DEFAULT NULL ) RETURN fonction_referentiel%rowtype IS
    res fonction_referentiel%rowtype;
  BEGIN
    SELECT * INTO res FROM fonction_referentiel WHERE
      (OSE_DIVERS.LIKED( code, GET_FONCTION_REFERENTIEL.code ) = 1 OR GET_FONCTION_REFERENTIEL.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION ADD_FONCTION_REFERENTIEL(
    code          VARCHAR2,
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    plafond       FLOAT
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := FONCTION_REFERENTIEL_ID_SEQ.NEXTVAL;
    INSERT INTO FONCTION_REFERENTIEL (
      ID,
      CODE,
      LIBELLE_LONG,
      LIBELLE_COURT,
      PLAFOND,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      code,
      libelle_long,
      libelle_court,
      plafond,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'fonction_referentiel', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION ADD_SERVICE_REFERENTIEL(
    fonction_id     NUMERIC,
    intervenant_id  NUMERIC,
    structure_id    NUMERIC,
    annee_id        NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL;
    INSERT INTO SERVICE_REFERENTIEL (
      ID,
      FONCTION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      ANNEE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      fonction_id,
      intervenant_id,
      structure_id,
      annee_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'service_referentiel', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION ADD_MODIFICATION_SERVICE_DU(
    intervenant_id  NUMERIC,
    annee_id        NUMERIC,
    heures          FLOAT,
    motif_id        NUMERIC,
    commentaires    CLOB DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := MODIFICATION_SERVICE_DU_ID_SEQ.NEXTVAL;
    INSERT INTO MODIFICATION_SERVICE_DU (
      ID,
      INTERVENANT_ID,
      ANNEE_ID,
      HEURES,
      MOTIF_ID,
      COMMENTAIRES,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
      annee_id,
      heures,
      motif_id,
      commentaires,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'modification_service_du', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_MOTIF_MODIFICATION_SERVICE( code VARCHAR2 DEFAULT NULL, multiplicateur FLOAT DEFAULT NULL ) RETURN motif_modification_service%rowtype IS
    res motif_modification_service%rowtype;
  BEGIN
    SELECT * INTO res FROM motif_modification_service WHERE
      (OSE_DIVERS.LIKED( code, GET_MOTIF_MODIFICATION_SERVICE.code ) = 1 OR GET_MOTIF_MODIFICATION_SERVICE.code IS NULL)
      AND (multiplicateur = GET_MOTIF_MODIFICATION_SERVICE.multiplicateur OR GET_MOTIF_MODIFICATION_SERVICE.multiplicateur IS NULL)
      AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_ETABLISSEMENT( source_code VARCHAR2 DEFAULT NULL ) RETURN etablissement%rowtype IS
    res etablissement%rowtype;
  BEGIN
    SELECT * INTO res FROM etablissement WHERE
      (OSE_DIVERS.LIKED( source_code, GET_ETABLISSEMENT.source_code ) = 1 OR (GET_ETABLISSEMENT.source_code IS NULL AND id <> OSE_PARAMETRE.GET_ETABLISSEMENT))
      AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction )
      AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_SERVICE_BY_ID( id NUMERIC ) RETURN service%rowtype IS
    res service%rowtype;
  BEGIN
    SELECT * INTO res FROM service WHERE id = GET_SERVICE_BY_ID.id;
    RETURN res;
  END;

  FUNCTION ADD_SERVICE(
    intervenant_id          NUMERIC,
    annee_id                NUMERIC,
    element_pedagogique_id  NUMERIC,
    etablissement_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := SERVICE_ID_SEQ.NEXTVAL;
    INSERT INTO SERVICE (
      ID,
      INTERVENANT_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ANNEE_ID,
      ETABLISSEMENT_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
      element_pedagogique_id,
      annee_id,
      COALESCE( ADD_SERVICE.etablissement_id, OSE_PARAMETRE.GET_ETABLISSEMENT),
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'service', entity_id);
    RETURN entity_id;
  END;

  FUNCTION GET_ETAT_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN etat_volume_horaire%rowtype IS
    res etat_volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM etat_volume_horaire WHERE
      (OSE_DIVERS.LIKED( code, GET_ETAT_VOLUME_HORAIRE.code ) = 1 OR GET_ETAT_VOLUME_HORAIRE.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN type_volume_horaire%rowtype IS
    res type_volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM type_volume_horaire WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_VOLUME_HORAIRE.code ) = 1 OR GET_TYPE_VOLUME_HORAIRE.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
  FUNCTION GET_TYPE_INTERVENTION( code VARCHAR2 DEFAULT NULL ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervention WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_INTERVENTION.code ) = 1 OR GET_TYPE_INTERVENTION.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENTION_BY_ID( id NUMERIC ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT * INTO res FROM type_intervention WHERE id = GET_TYPE_INTERVENTION_BY_ID.id;
    RETURN res;
  END;

  FUNCTION GET_TYPE_INTERVENTION_BY_ELEMT( ELEMENT_ID NUMERIC ) RETURN type_intervention%rowtype IS
    res type_intervention%rowtype;
  BEGIN
    SELECT
      ti.*
    INTO
      res
    FROM
      type_intervention ti
      JOIN v_element_type_intervention eti ON eti.type_intervention_id = ti.id AND eti.element_pedagogique_id = ELEMENT_ID
    WHERE
      1 = ose_divers.comprise_entre( ti.histo_creation, ti.histo_destruction )
      AND rownum = 1;
    RETURN res;
  END;

  FUNCTION GET_MOTIF_NON_PAIEMENT( code VARCHAR2 DEFAULT NULL ) RETURN motif_non_paiement%rowtype IS
    res motif_non_paiement%rowtype;
  BEGIN
    SELECT * INTO res FROM motif_non_paiement WHERE
      (OSE_DIVERS.LIKED( code, GET_MOTIF_NON_PAIEMENT.code ) = 1 OR GET_MOTIF_NON_PAIEMENT.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;

  FUNCTION GET_VOLUME_HORAIRE( id NUMERIC DEFAULT NULL ) RETURN volume_horaire%rowtype IS
    res volume_horaire%rowtype;
  BEGIN
    SELECT * INTO res FROM volume_horaire WHERE
      id = GET_VOLUME_HORAIRE.id OR (GET_VOLUME_HORAIRE.id IS NULL AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1);
    RETURN res;    
  END;

  FUNCTION ADD_VOLUME_HORAIRE(
    type_volume_horaire_id  NUMERIC,
    service_id              NUMERIC,
    periode_id              NUMERIC,
    type_intervention_id    NUMERIC,
    heures                  FLOAT,
    motif_non_paiement_id   NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := VOLUME_HORAIRE_ID_SEQ.NEXTVAL;
    INSERT INTO VOLUME_HORAIRE (
      ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_ID,
      PERIODE_ID,
      TYPE_INTERVENTION_ID,
      HEURES,
      MOTIF_NON_PAIEMENT_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      type_volume_horaire_id,
      service_id,
      periode_id,
      type_intervention_id,
      heures,
      motif_non_paiement_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'volume_horaire', entity_id);
    RETURN entity_id;
  END;

  FUNCTION ADD_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC,
    intervenant_id    NUMERIC,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := VALIDATION_ID_SEQ.NEXTVAL;
    INSERT INTO VALIDATION (
      ID,
      TYPE_VALIDATION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_validation WHERE code = 'SERVICES_PAR_COMP'),
      intervenant_id,
      structure_id,
      GET_USER,
      GET_USER
    );
    FOR vh IN (
      SELECT 
        vh.id
      FROM
        volume_horaire vh
        JOIN service s ON s.id = vh.service_id
        JOIN intervenant i ON i.id = s.intervenant_id
        LEFT JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id
      WHERE
        vh.histo_destruction IS NULL AND
        s.histo_destruction IS NULL
        AND (NVL(ep.structure_id,0) = ADD_VALIDATION_VOLUME_HORAIRE.structure_id OR i.structure_id = ADD_VALIDATION_VOLUME_HORAIRE.structure_id)
        AND (s.intervenant_id = ADD_VALIDATION_VOLUME_HORAIRE.intervenant_id)
        AND (vh.id = ADD_VALIDATION_VOLUME_HORAIRE.volume_horaire_id OR ADD_VALIDATION_VOLUME_HORAIRE.volume_horaire_id IS NULL)
        AND (s.id = ADD_VALIDATION_VOLUME_HORAIRE.service_id OR ADD_VALIDATION_VOLUME_HORAIRE.service_id IS NULL)
    ) LOOP
      INSERT INTO VALIDATION_VOL_HORAIRE(
        VALIDATION_ID,
        VOLUME_HORAIRE_ID
      )VALUES(
        entity_id,
        vh.id
      );
    END LOOP;
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'validation', entity_id);
    RETURN entity_id;
  END;

  PROCEDURE DEL_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC,
    intervenant_id    NUMERIC,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL,
    validation_id     NUMERIC DEFAULT NULL
  ) IS
    vvh_count NUMERIC;
  BEGIN
    FOR vh IN (
      SELECT
        vh.id
      FROM
        volume_horaire vh
        JOIN service s ON s.id = vh.service_id
        JOIN intervenant i ON i.id = s.intervenant_id
        LEFT JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id
      WHERE
        1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction ) AND
        1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
        AND (NVL(ep.structure_id,0) = DEL_VALIDATION_VOLUME_HORAIRE.structure_id OR i.structure_id = DEL_VALIDATION_VOLUME_HORAIRE.structure_id)
        AND (s.intervenant_id = DEL_VALIDATION_VOLUME_HORAIRE.intervenant_id)
        AND (vh.id = DEL_VALIDATION_VOLUME_HORAIRE.volume_horaire_id OR DEL_VALIDATION_VOLUME_HORAIRE.volume_horaire_id IS NULL)
        AND (s.id = DEL_VALIDATION_VOLUME_HORAIRE.service_id OR DEL_VALIDATION_VOLUME_HORAIRE.service_id IS NULL)
    ) LOOP
      DELETE FROM VALIDATION_VOL_HORAIRE WHERE 
        VOLUME_HORAIRE_ID = vh.id 
        AND (VALIDATION_ID = DEL_VALIDATION_VOLUME_HORAIRE.validation_id OR DEL_VALIDATION_VOLUME_HORAIRE.validation_id IS NULL);
    END LOOP;
    IF VALIDATION_ID IS NOT NULL THEN
      SELECT count(*) INTO vvh_count FROM VALIDATION_VOL_HORAIRE WHERE VALIDATION_ID = DEL_VALIDATION_VOLUME_HORAIRE.validation_id;
      IF 0 = vvh_count THEN
        DELETE FROM validation WHERE id = VALIDATION_ID;
      END IF;
    END IF;
  END;

  FUNCTION GET_CONTRAT_BY_ID( ID NUMERIC ) RETURN contrat%rowtype IS
    res contrat%rowtype;
  BEGIN
    SELECT * INTO res FROM contrat WHERE id = GET_CONTRAT_BY_ID.id;
    RETURN res;
  END;

  FUNCTION ADD_CONTRAT(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL    
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := CONTRAT_ID_SEQ.NEXTVAL;
    INSERT INTO CONTRAT (
      ID,
      TYPE_CONTRAT_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      NUMERO_AVENANT,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_contrat WHERE code = 'CONTRAT'),
      intervenant_id,
      structure_id,
      (SELECT MAX(numero_avenant) FROM contrat) + 1,
      GET_USER,
      GET_USER
    );
    FOR vh IN (
      SELECT vh.id FROM volume_horaire vh JOIN service s ON s.id = vh.service_id
      WHERE
        1 = ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction )
        AND 1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction )
        AND (s.intervenant_id = ADD_CONTRAT.intervenant_id OR ADD_CONTRAT.intervenant_id IS NULL)
        AND (vh.id = ADD_CONTRAT.volume_horaire_id OR ADD_CONTRAT.volume_horaire_id IS NULL)
        AND (s.id = ADD_CONTRAT.service_id OR ADD_CONTRAT.service_id IS NULL)
        AND vh.contrat_id IS NULL
    ) LOOP
      UPDATE volume_horaire SET contrat_id = entity_id WHERE volume_horaire.id = vh.id;
    END LOOP;

    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'contrat', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION SIGNATURE_CONTRAT(
    contrat_id        NUMERIC
  ) RETURN NUMERIC IS
  BEGIN
    UPDATE contrat SET date_retour_signe = SYSDATE WHERE id = SIGNATURE_CONTRAT.contrat_id;
    RETURN contrat_id;
  END;
  
  FUNCTION ADD_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC IS
    entity_id NUMERIC;
    ctr contrat%rowtype;
  BEGIN
    ctr := GET_CONTRAT_BY_ID( contrat_id );

    IF ctr.validation_id IS NOT NULL THEN RETURN NULL; END IF;

    entity_id := VALIDATION_ID_SEQ.NEXTVAL;
    INSERT INTO VALIDATION (
      ID,
      TYPE_VALIDATION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      (SELECT id FROM type_validation WHERE code = 'CONTRAT_PAR_COMP'),
      ctr.intervenant_id,
      ctr.structure_id,
      GET_USER,
      GET_USER
    );
    UPDATE contrat SET validation_id = entity_id WHERE id = ADD_CONTRAT_VALIDATION.contrat_id;
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'validation', entity_id);
    RETURN entity_id;
  END;  
  
  FUNCTION DEL_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC IS
    ctr contrat%rowtype;
  BEGIN
    ctr := GET_CONTRAT_BY_ID( contrat_id );
    
    IF ctr.validation_id IS NOT NULL THEN
      UPDATE contrat SET validation_id = NULL WHERE contrat_id = DEL_CONTRAT_VALIDATION.contrat_id;
      DELETE FROM validation WHERE id = ctr.validation_id;
    END IF;
    RETURN contrat_id;
  END;
  
  FUNCTION GET_TYPE_VALIDATION( code VARCHAR2 DEFAULT NULL ) RETURN type_validation%rowtype IS
    res type_validation%rowtype;
  BEGIN
    SELECT * INTO res FROM type_validation WHERE
      (OSE_DIVERS.LIKED( code, GET_TYPE_VALIDATION.code ) = 1 OR GET_TYPE_VALIDATION.code IS NULL) AND 1 = ose_divers.comprise_entre( histo_creation, histo_destruction ) AND ROWNUM = 1;
    RETURN res;
  END;
  
END OSE_TEST;
/
---------------------------
--Nouveau PACKAGE BODY
--OSE_PJ
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_PJ" AS
  
  /**
   * Inscription des infos utiles à la mise à jour de la liste des PJ attendues.
   */
  PROCEDURE add_intervenant_to_update (intervenantId IN NUMERIC, tpjId IN NUMERIC, forceObligatoire IN NUMERIC default null)
  IS
  BEGIN 
    MERGE INTO pj_tmp_intervenant t USING dual ON (t.intervenant_id = intervenantId and t.type_piece_jointe_id = tpjId and t.obligatoire = forceObligatoire) 
      WHEN NOT MATCHED THEN INSERT (INTERVENANT_ID, type_piece_jointe_id, obligatoire) VALUES (intervenantId, tpjId, forceObligatoire);
  END;
  
  /**
   * Parcours des intervenants dont il faut regénérer la liste des PJ attendues.
   */
  PROCEDURE update_intervenants_pj
  IS
    dossierId numeric;
  BEGIN
    FOR ti IN (SELECT distinct * FROM pj_tmp_intervenant order by intervenant_id, type_piece_jointe_id) LOOP
      -- recherche du dossier de l'intervenant spécifié dans la table temporaire
      select id into dossierId from dossier where intervenant_id = ti.intervenant_id and 1 = ose_divers.comprise_entre(histo_creation, histo_destruction);
      -- mise à jour
      ose_pj.update_pj(ti.type_piece_jointe_id, dossierId, ti.obligatoire);
    END LOOP;
    --DELETE FROM pj_tmp_intervenant;
  END;

  /**
   * Mise à jour de la liste des PJ attendues pour le type de PJ et le dossier spécifiés.
   */
  procedure update_pj(tpjId IN numeric, dossierId IN numeric, forceObligatoire IN numeric default null)
  is
    oblig numeric;
    found numeric;
  begin 
    --dbms_output.put_line('update_pj('||tpjId||', '||dossierId||', '||forceObligatoire||')');
    
    
    if forceObligatoire is null then
    -- pas de forçage : recherche du caractère obligatoire du type de PJ pour le dossier.
    
      select is_tpj_obligatoire(tpjId, dossierId) into oblig from dual;
      --dbms_output.put_line('is_tpj_obligatoire : '||oblig);
      
      -- La fonction is_tpj_obligatoire() renvoie null lorsque le type de PJ ne figure pas dans TYPE_PIECE_JOINTE_STATUT (i.e. n'est pas attendu).
      -- Dans ce cas, on supprime/historise la PJ ssi son caractère obligatoire n'est pas forcé ET il n'existe pas de fichier déposé.
      if oblig is null then
        /*update piece_jointe pj set histo_destructeur_id = ose_parametre.get_ose_user(), histo_destruction = sysdate 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
        and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
        and pj.force = 0;*/
        delete from piece_jointe pj 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
        and not exists (
          select * from piece_jointe_fichier pjf 
          join fichier f on f.id = pjf.fichier_id and 1 = ose_divers.comprise_entre(f.histo_creation, f.histo_destruction)
          where pjf.piece_jointe_id = pj.id 
        )
        and pj.force = 0;
        
        return; -- terminé
      end if;
      
    elsif forceObligatoire = 2 then
    -- forçage à 2 (type de PJ non attendu) : on supprime, à condition qu'il n'existe pas de fichier déposé.
    
        /*update piece_jointe pj set histo_destructeur_id = ose_parametre.get_ose_user(), histo_destruction = sysdate 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
        and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
        and pj.force = 1;*/
        delete from piece_jointe pj 
        where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId  
        and not exists (
          select * from piece_jointe_fichier pjf 
          join fichier f on f.id = pjf.fichier_id and 1 = ose_divers.comprise_entre(f.histo_creation, f.histo_destruction)
          where pjf.piece_jointe_id = pj.id 
        );
        
        return; -- terminé
        
    else
    -- forçage à 0 (facultatif) ou 1 (obligatoire)
    
      oblig := forceObligatoire;
      
    end if;
    
    -- Recherche dans PIECE_JOINTE s'il existe un enregistrement pour le type de PJ et le dossier spécifiés
    select count(*) into found from piece_jointe pj where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
      and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction);
    -- Si oui, mise à jour
    if found > 0 then
      dbms_output.put_line('update_pj() : update : dossier '||dossierId||' tpj '||tpjId||' : '||oblig||')');
      update piece_jointe pj 
      set pj.obligatoire = oblig, 
          pj.force = case when forceObligatoire is not null then 1 else 0 end,
          histo_modificateur_id = ose_parametre.get_ose_user(), 
          histo_modification = sysdate 
      where pj.dossier_id = dossierId and pj.type_piece_jointe_id = tpjId 
      and pj.obligatoire <> oblig
      and 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
      and 1 = case when pj.force = 0 or pj.force = 1 and forceObligatoire is not null then 1 else 0 end; -- un caractère forçé ne peut être modifié que par forçage!
    -- Si non, insertion
    else
      dbms_output.put_line('update_pj() : insert : dossier '||dossierId||' tpj '||tpjId||' : '||oblig||')');
      insert into piece_jointe pj (id, dossier_id, type_piece_jointe_id, obligatoire, force, histo_createur_id, histo_modificateur_id) 
      values (
        piece_jointe_id_seq.nextval, 
        dossierId, tpjId, oblig, 
        case when forceObligatoire is not null then 1 else 0 end, 
        ose_parametre.get_ose_user(), 
        ose_parametre.get_ose_user()); 
    end if;
  end;
  
  /**
   * Recherche du caractère obligatoire d'un type de PJ pour un dossier.
   */
  function is_tpj_obligatoire(tpjId IN numeric, dossierId IN numeric) return numeric 
  is 
    intervenantId numeric;
    statutId numeric;
    premierRecrutement numeric;
    obligatoire numeric;
  begin
    -- recherche de l'intervenant extérieur correspondant au dossier, du statut et du témoin "1er recrutement" dans le dossier
    select intervenant_id, statut_id, PREMIER_RECRUTEMENT into intervenantId, statutId, premierRecrutement from dossier d where d.id = dossierId;
    
    -- recherche du caractère obligatoire du type de PJ spécifié
    select tpjs.OBLIGATOIRE into obligatoire
    from type_piece_jointe_statut   tpjs
    join type_piece_jointe          tpj       on tpj.id = tpjs.type_piece_jointe_id and tpj.id = tpjId
    join statut_intervenant         si        on tpjs.statut_intervenant_id = si.id and si.id = statutId
    LEFT JOIN V_PJ_HEURES           vheures   ON vheures.INTERVENANT_ID = intervenantId
    where 
      tpjs.PREMIER_RECRUTEMENT = premierRecrutement AND 
      (tpjs.SEUIL_HETD IS NULL OR COALESCE(vheures.TOTAL_HEURES, 0) > tpjs.SEUIL_HETD);
    
    return obligatoire;
  end;

END OSE_PJ;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_PARAMETRE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_PARAMETRE" AS

  cache_ose_user NUMERIC;
  cache_annee_id NUMERIC;

  function get_etablissement return Numeric AS
    etab_id numeric;
  BEGIN
    select to_number(valeur) into etab_id from parametre where nom = 'etablissement';
    RETURN etab_id;
  END get_etablissement;

  function get_annee return Numeric AS
    annee_id numeric;
  BEGIN
    IF cache_annee_id IS NOT NULL THEN RETURN cache_annee_id; END IF;
    select to_number(valeur) into annee_id from parametre where nom = 'annee';
    cache_annee_id := annee_id;
    RETURN cache_annee_id;
  END get_annee;

  FUNCTION get_annee_import RETURN NUMERIC AS
    annee_id NUMERIC;
  BEGIN
    SELECT to_number(valeur) INTO annee_id FROM parametre WHERE nom = 'annee_import';
    RETURN annee_id;
  END get_annee_import;

  function get_ose_user return Numeric AS
    ose_user_id numeric;
  BEGIN
    IF cache_ose_user IS NOT NULL THEN RETURN cache_ose_user; END IF;
    select to_number(valeur) into ose_user_id from parametre where nom = 'oseuser';
    cache_ose_user := ose_user_id;
    RETURN cache_ose_user;
  END get_ose_user;

  function get_drh_structure_id return Numeric AS
    drh_structure_id numeric;
  BEGIN
    select to_number(valeur) into drh_structure_id from parametre where nom = 'drh_structure_id';
    RETURN drh_structure_id;
  END get_drh_structure_id;

  FUNCTION get_date_fin_saisie_permanents RETURN DATE IS
    date_fin_saisie_permanents date;
  BEGIN
    select TO_DATE(valeur, 'dd/mm/yyyy') into date_fin_saisie_permanents from parametre where nom = 'date_fin_saisie_permanents';
    RETURN date_fin_saisie_permanents;
  END;

  FUNCTION get_ddeb_saisie_serv_real RETURN DATE IS
    val date;
  BEGIN
    select TO_DATE(valeur, 'dd/mm/yyyy') into val from parametre where nom = 'date_debut_saisie_services_realises';
    RETURN val;
  END;
  
  FUNCTION get_dfin_saisie_serv_real RETURN DATE IS
    val date;
  BEGIN
    select TO_DATE(valeur, 'dd/mm/yyyy') into val from parametre where nom = 'date_fin_saisie_services_realises';
    RETURN val;
  END;

  FUNCTION get_formule_package_name RETURN VARCHAR2 IS
    formule_package_name VARCHAR2(30);
  BEGIN
    SELECT valeur INTO formule_package_name FROM parametre WHERE nom = 'formule_package_name';
    RETURN formule_package_name;
  END;
  
  FUNCTION get_formule_function_name RETURN VARCHAR2 IS
    formule_function_name VARCHAR2(30);
  BEGIN
    SELECT valeur INTO formule_function_name FROM parametre WHERE nom = 'formule_function_name';
    RETURN formule_function_name;
  END;

END OSE_PARAMETRE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_IMPORT" IS
  
  v_current_user INTEGER;
  v_current_annee INTEGER;

  FUNCTION get_current_user RETURN INTEGER IS
  BEGIN
    IF v_current_user IS NULL THEN
      v_current_user := OSE_PARAMETRE.GET_OSE_USER();
    END IF;
    RETURN v_current_user;
  END get_current_user;
 
  PROCEDURE set_current_user (p_current_user INTEGER) is
  BEGIN
    v_current_user := p_current_user;
  END set_current_user;


  FUNCTION get_current_annee RETURN INTEGER IS
  BEGIN
    IF v_current_annee IS NULL THEN
      v_current_annee := OSE_PARAMETRE.GET_ANNEE_IMPORT();
    END IF;
    RETURN v_current_annee;
  END get_current_annee;
 
  PROCEDURE set_current_annee (p_current_annee INTEGER) IS
  BEGIN
    v_current_annee := p_current_annee;
  END set_current_annee;


  FUNCTION get_sql_criterion( table_name varchar2, sql_criterion VARCHAR2 ) RETURN CLOB IS
  BEGIN
    IF sql_criterion <> '' OR sql_criterion IS NOT NULL THEN
      RETURN sql_criterion;
    END IF;
    RETURN CASE table_name
      WHEN 'INTERVENANT' THEN -- Met à jour toutes les données sauf le statut, qui sera traité à part
        'WHERE IMPORT_ACTION IN (''delete'',''update'',''undelete'')'
        
      WHEN 'INTERVENANT_EXTERIEUR' THEN
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
        
      WHEN 'INTERVENANT_PERMANENT' THEN
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
        
      WHEN 'AFFECTATION_RECHERCHE' THEN
        'WHERE INTERVENANT_ID IS NOT NULL'
        
      WHEN 'ADRESSE_INTERVENANT' THEN
        'WHERE INTERVENANT_ID IS NOT NULL'
        
      WHEN 'ELEMENT_TAUX_REGIMES' THEN
        'WHERE IMPORT_ACTION IN (''delete'',''insert'',''undelete'')'

      ELSE
        ''
    END;
  END;


  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL ) IS
  BEGIN
    INSERT INTO OSE.SYNC_LOG("ID","DATE_SYNC","MESSAGE","TABLE_NAME","SOURCE_CODE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message,table_name,source_code);
  END SYNC_LOG;


  PROCEDURE REFRESH_MV( mview_name varchar2 ) IS
  BEGIN
    DBMS_MVIEW.REFRESH(mview_name, 'C');
  EXCEPTION WHEN OTHERS THEN
    OSE_IMPORT.SYNC_LOG( SQLERRM, mview_name );
  END;

  PROCEDURE REFRESH_MVS IS
  BEGIN
    -- Mise à jour des vues matérialisées
    REFRESH_MV('MV_PAYS');
    REFRESH_MV('MV_DEPARTEMENT');
    REFRESH_MV('MV_ETABLISSEMENT');
    REFRESH_MV('MV_STRUCTURE');
    REFRESH_MV('MV_ADRESSE_STRUCTURE');
    
    REFRESH_MV('MV_PERSONNEL');
    REFRESH_MV('MV_AFFECTATION');
    
    REFRESH_MV('MV_CORPS');
    
    REFRESH_MV('MV_INTERVENANT');
    REFRESH_MV('MV_AFFECTATION_RECHERCHE');
    REFRESH_MV('MV_ADRESSE_INTERVENANT');
    
    REFRESH_MV('MV_GROUPE_TYPE_FORMATION');
    REFRESH_MV('MV_TYPE_FORMATION');
    REFRESH_MV('MV_ETAPE');
    REFRESH_MV('MV_ELEMENT_PEDAGOGIQUE');
    REFRESH_MV('MV_EFFECTIFS');
    REFRESH_MV('MV_ELEMENT_TAUX_REGIMES');
    REFRESH_MV('MV_CHEMIN_PEDAGOGIQUE');
    REFRESH_MV('MV_ELEMENT_PORTEUR_PORTE');
    
    REFRESH_MV('MV_CENTRE_COUT');
    REFRESH_MV('MV_DOMAINE_FONCTIONNEL');
  END;

  PROCEDURE SYNC_TABLES IS
  BEGIN
    MAJ_PAYS();
    MAJ_DEPARTEMENT();
  
    MAJ_ETABLISSEMENT();
    MAJ_STRUCTURE();
    MAJ_ADRESSE_STRUCTURE();
    
    MAJ_DOMAINE_FONCTIONNEL();
    MAJ_CENTRE_COUT();

    MAJ_PERSONNEL();
    MAJ_AFFECTATION();

    MAJ_CORPS();

    MAJ_INTERVENANT();
    MAJ_INTERVENANT_EXTERIEUR();
    MAJ_INTERVENANT_PERMANENT();
    MAJ_AFFECTATION_RECHERCHE();
    MAJ_ADRESSE_INTERVENANT();

    MAJ_GROUPE_TYPE_FORMATION();
    MAJ_TYPE_FORMATION();
    MAJ_ETAPE();
    MAJ_ELEMENT_PEDAGOGIQUE();
    MAJ_EFFECTIFS();
    MAJ_ELEMENT_TAUX_REGIMES();
    MAJ_CHEMIN_PEDAGOGIQUE();
    
    -- Mise à jour des sources calculées en dernier
    MAJ_TYPE_INTERVENTION_EP();
    MAJ_TYPE_MODULATEUR_EP();
  END;

  PROCEDURE SYNCHRONISATION IS
  BEGIN
    REFRESH_MVS;
    SYNC_TABLES;
  END SYNCHRONISATION;



  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;





  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_GROUPE_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_GROUPE_TYPE_FORMATION.* FROM V_DIFF_GROUPE_TYPE_FORMATION ' || get_sql_criterion('GROUPE_TYPE_FORMATION',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.GROUPE_TYPE_FORMATION
              ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE,PERTINENCE_NIVEAU, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE,diff_row.PERTINENCE_NIVEAU, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;
            UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'GROUPE_TYPE_FORMATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_GROUPE_TYPE_FORMATION;



  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_FORMATION.* FROM V_DIFF_TYPE_FORMATION ' || get_sql_criterion('TYPE_FORMATION',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.TYPE_FORMATION
              ( id, GROUPE_ID,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.GROUPE_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_FORMATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_FORMATION;



  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_PERSONNEL%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_PERSONNEL.* FROM V_DIFF_PERSONNEL ' || get_sql_criterion('PERSONNEL',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.PERSONNEL
              ( id, CIVILITE_ID,EMAIL,NOM_PATRONYMIQUE,NOM_USUEL,PRENOM,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,PERSONNEL_ID_SEQ.NEXTVAL), diff_row.CIVILITE_ID,diff_row.EMAIL,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.PRENOM,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.PERSONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.PERSONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'PERSONNEL', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_PERSONNEL;



  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_STRUCTURE.* FROM V_DIFF_ADRESSE_STRUCTURE ' || get_sql_criterion('ADRESSE_STRUCTURE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ADRESSE_STRUCTURE
              ( id, CODE_POSTAL,LOCALITE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,STRUCTURE_ID,TELEPHONE,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ADRESSE_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.LOCALITE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.STRUCTURE_ID,diff_row.TELEPHONE,diff_row.VILLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ADRESSE_STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_STRUCTURE;



  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_AFFECTATION_RECHERCHE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_AFFECTATION_RECHERCHE.* FROM V_DIFF_AFFECTATION_RECHERCHE ' || get_sql_criterion('AFFECTATION_RECHERCHE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.AFFECTATION_RECHERCHE
              ( id, INTERVENANT_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,AFFECTATION_RECHERCHE_ID_SEQ.NEXTVAL), diff_row.INTERVENANT_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'AFFECTATION_RECHERCHE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_AFFECTATION_RECHERCHE;



  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_EXTERIEUR%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_EXTERIEUR.* FROM V_DIFF_INTERVENANT_EXTERIEUR ' || get_sql_criterion('INTERVENANT_EXTERIEUR',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.INTERVENANT_EXTERIEUR
              ( id, SITUATION_FAMILIALE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,INTERVENANT_EXTERIEUR_ID_SEQ.NEXTVAL), diff_row.SITUATION_FAMILIALE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'INTERVENANT_EXTERIEUR', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_EXTERIEUR;



  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_PERMANENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_PERMANENT.* FROM V_DIFF_INTERVENANT_PERMANENT ' || get_sql_criterion('INTERVENANT_PERMANENT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.INTERVENANT_PERMANENT
              ( id, CORPS_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,INTERVENANT_PERMANENT_ID_SEQ.NEXTVAL), diff_row.CORPS_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'INTERVENANT_PERMANENT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_PERMANENT;



  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CORPS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CORPS.* FROM V_DIFF_CORPS ' || get_sql_criterion('CORPS',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.CORPS
              ( id, LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CORPS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.CORPS SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.CORPS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'CORPS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_CORPS;



  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_INTERVENANT.* FROM V_DIFF_ADRESSE_INTERVENANT ' || get_sql_criterion('ADRESSE_INTERVENANT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ADRESSE_INTERVENANT
              ( id, CODE_POSTAL,INTERVENANT_ID,LOCALITE,MENTION_COMPLEMENTAIRE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,TEL_DOMICILE,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ADRESSE_INTERVENANT_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.INTERVENANT_ID,diff_row.LOCALITE,diff_row.MENTION_COMPLEMENTAIRE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.TEL_DOMICILE,diff_row.VILLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ADRESSE_INTERVENANT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_INTERVENANT;



  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CHEMIN_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CHEMIN_PEDAGOGIQUE.* FROM V_DIFF_CHEMIN_PEDAGOGIQUE ' || get_sql_criterion('CHEMIN_PEDAGOGIQUE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.CHEMIN_PEDAGOGIQUE
              ( id, ELEMENT_PEDAGOGIQUE_ID,ETAPE_ID,ORDRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.ETAPE_ID,diff_row.ORDRE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'CHEMIN_PEDAGOGIQUE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_CHEMIN_PEDAGOGIQUE;



  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETABLISSEMENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETABLISSEMENT.* FROM V_DIFF_ETABLISSEMENT ' || get_sql_criterion('ETABLISSEMENT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ETABLISSEMENT
              ( id, DEPARTEMENT,LIBELLE,LOCALISATION, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ETABLISSEMENT_ID_SEQ.NEXTVAL), diff_row.DEPARTEMENT,diff_row.LIBELLE,diff_row.LOCALISATION, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ETABLISSEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ETABLISSEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ETABLISSEMENT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETABLISSEMENT;



  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT.* FROM V_DIFF_INTERVENANT ' || get_sql_criterion('INTERVENANT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.INTERVENANT
              ( id, ANNEE_ID,BIC,CIVILITE_ID,DATE_NAISSANCE,DEP_NAISSANCE_CODE_INSEE,DEP_NAISSANCE_LIBELLE,EMAIL,IBAN,NOM_PATRONYMIQUE,NOM_USUEL,NUMERO_INSEE,NUMERO_INSEE_CLE,NUMERO_INSEE_PROVISOIRE,PAYS_NAISSANCE_CODE_INSEE,PAYS_NAISSANCE_LIBELLE,PAYS_NATIONALITE_CODE_INSEE,PAYS_NATIONALITE_LIBELLE,PRENOM,STATUT_ID,STRUCTURE_ID,TEL_MOBILE,TEL_PRO,TYPE_ID,VILLE_NAISSANCE_CODE_INSEE,VILLE_NAISSANCE_LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,INTERVENANT_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.BIC,diff_row.CIVILITE_ID,diff_row.DATE_NAISSANCE,diff_row.DEP_NAISSANCE_CODE_INSEE,diff_row.DEP_NAISSANCE_LIBELLE,diff_row.EMAIL,diff_row.IBAN,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.NUMERO_INSEE,diff_row.NUMERO_INSEE_CLE,diff_row.NUMERO_INSEE_PROVISOIRE,diff_row.PAYS_NAISSANCE_CODE_INSEE,diff_row.PAYS_NAISSANCE_LIBELLE,diff_row.PAYS_NATIONALITE_CODE_INSEE,diff_row.PAYS_NATIONALITE_LIBELLE,diff_row.PRENOM,diff_row.STATUT_ID,diff_row.STRUCTURE_ID,diff_row.TEL_MOBILE,diff_row.TEL_PRO,diff_row.TYPE_ID,diff_row.VILLE_NAISSANCE_CODE_INSEE,diff_row.VILLE_NAISSANCE_LIBELLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_CODE_INSEE = diff_row.PAYS_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_LIBELLE = diff_row.PAYS_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_CODE_INSEE = diff_row.PAYS_NATIONALITE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_LIBELLE = diff_row.PAYS_NATIONALITE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_CODE_INSEE = diff_row.PAYS_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NAISSANCE_LIBELLE = diff_row.PAYS_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_CODE_INSEE = diff_row.PAYS_NATIONALITE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PAYS_NATIONALITE_LIBELLE = diff_row.PAYS_NATIONALITE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'INTERVENANT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT;



  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_PEDAGOGIQUE.* FROM V_DIFF_ELEMENT_PEDAGOGIQUE ' || get_sql_criterion('ELEMENT_PEDAGOGIQUE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ELEMENT_PEDAGOGIQUE
              ( id, ANNEE_ID,ETAPE_ID,FA,FC,FI,LIBELLE,PERIODE_ID,STRUCTURE_ID,TAUX_FA,TAUX_FC,TAUX_FI,TAUX_FOAD, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ETAPE_ID,diff_row.FA,diff_row.FC,diff_row.FI,diff_row.LIBELLE,diff_row.PERIODE_ID,diff_row.STRUCTURE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI,diff_row.TAUX_FOAD, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ELEMENT_PEDAGOGIQUE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_PEDAGOGIQUE;



  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_STRUCTURE.* FROM V_DIFF_STRUCTURE ' || get_sql_criterion('STRUCTURE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.STRUCTURE
              ( id, ETABLISSEMENT_ID,LIBELLE_COURT,LIBELLE_LONG,NIVEAU,PARENTE_ID,STRUCTURE_NIV2_ID,TYPE_ID,UNITE_BUDGETAIRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,STRUCTURE_ID_SEQ.NEXTVAL), diff_row.ETABLISSEMENT_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.NIVEAU,diff_row.PARENTE_ID,diff_row.STRUCTURE_NIV2_ID,diff_row.TYPE_ID,diff_row.UNITE_BUDGETAIRE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UNITE_BUDGETAIRE = 1 AND IN_COLUMN_LIST('UNITE_BUDGETAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET UNITE_BUDGETAIRE = diff_row.UNITE_BUDGETAIRE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UNITE_BUDGETAIRE = 1 AND IN_COLUMN_LIST('UNITE_BUDGETAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET UNITE_BUDGETAIRE = diff_row.UNITE_BUDGETAIRE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_STRUCTURE;



  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_INTERVENTION_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_INTERVENTION_EP.* FROM V_DIFF_TYPE_INTERVENTION_EP ' || get_sql_criterion('TYPE_INTERVENTION_EP',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.TYPE_INTERVENTION_EP
              ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_INTERVENTION_ID,VISIBLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_INTERVENTION_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_INTERVENTION_ID,diff_row.VISIBLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VISIBLE = 1 AND IN_COLUMN_LIST('VISIBLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_INTERVENTION_EP SET VISIBLE = diff_row.VISIBLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.TYPE_INTERVENTION_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_INTERVENTION_EP', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_INTERVENTION_EP;



  PROCEDURE MAJ_DEPARTEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_DEPARTEMENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_DEPARTEMENT.* FROM V_DIFF_DEPARTEMENT ' || get_sql_criterion('DEPARTEMENT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.DEPARTEMENT
              ( id, LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,DEPARTEMENT_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.DEPARTEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DEPARTEMENT SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.DEPARTEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'DEPARTEMENT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_DEPARTEMENT;



  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETAPE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETAPE.* FROM V_DIFF_ETAPE ' || get_sql_criterion('ETAPE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ETAPE
              ( id, DOMAINE_FONCTIONNEL_ID,LIBELLE,NIVEAU,SPECIFIQUE_ECHANGES,STRUCTURE_ID,TYPE_FORMATION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ETAPE_ID_SEQ.NEXTVAL), diff_row.DOMAINE_FONCTIONNEL_ID,diff_row.LIBELLE,diff_row.NIVEAU,diff_row.SPECIFIQUE_ECHANGES,diff_row.STRUCTURE_ID,diff_row.TYPE_FORMATION_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_DOMAINE_FONCTIONNEL_ID = 1 AND IN_COLUMN_LIST('DOMAINE_FONCTIONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET DOMAINE_FONCTIONNEL_ID = diff_row.DOMAINE_FONCTIONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ETAPE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_DOMAINE_FONCTIONNEL_ID = 1 AND IN_COLUMN_LIST('DOMAINE_FONCTIONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET DOMAINE_FONCTIONNEL_ID = diff_row.DOMAINE_FONCTIONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ETAPE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ETAPE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETAPE;



  PROCEDURE MAJ_PAYS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_PAYS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_PAYS.* FROM V_DIFF_PAYS ' || get_sql_criterion('PAYS',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.PAYS
              ( id, LIBELLE_COURT,LIBELLE_LONG,TEMOIN_UE,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,PAYS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.TEMOIN_UE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEMOIN_UE = 1 AND IN_COLUMN_LIST('TEMOIN_UE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET TEMOIN_UE = diff_row.TEMOIN_UE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.PAYS SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEMOIN_UE = 1 AND IN_COLUMN_LIST('TEMOIN_UE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET TEMOIN_UE = diff_row.TEMOIN_UE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PAYS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
            UPDATE OSE.PAYS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'PAYS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_PAYS;



  PROCEDURE MAJ_AFFECTATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_AFFECTATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_AFFECTATION.* FROM V_DIFF_AFFECTATION ' || get_sql_criterion('AFFECTATION',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.AFFECTATION
              ( id, PERSONNEL_ID,ROLE_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,AFFECTATION_ID_SEQ.NEXTVAL), diff_row.PERSONNEL_ID,diff_row.ROLE_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ROLE_ID = 1 AND IN_COLUMN_LIST('ROLE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET ROLE_ID = diff_row.ROLE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.AFFECTATION SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ROLE_ID = 1 AND IN_COLUMN_LIST('ROLE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET ROLE_ID = diff_row.ROLE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.AFFECTATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'AFFECTATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_AFFECTATION;



  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_MODULATEUR_EP%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_MODULATEUR_EP.* FROM V_DIFF_TYPE_MODULATEUR_EP ' || get_sql_criterion('TYPE_MODULATEUR_EP',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.TYPE_MODULATEUR_EP
              ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_MODULATEUR_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_MODULATEUR_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_MODULATEUR_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.TYPE_MODULATEUR_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_MODULATEUR_EP', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_MODULATEUR_EP;



  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_TAUX_REGIMES%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_TAUX_REGIMES.* FROM V_DIFF_ELEMENT_TAUX_REGIMES ' || get_sql_criterion('ELEMENT_TAUX_REGIMES',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.ELEMENT_TAUX_REGIMES
              ( id, ELEMENT_PEDAGOGIQUE_ID,TAUX_FA,TAUX_FC,TAUX_FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ELEMENT_TAUX_REGIMES_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
            UPDATE OSE.ELEMENT_TAUX_REGIMES SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'ELEMENT_TAUX_REGIMES', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_TAUX_REGIMES;



  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_EFFECTIFS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_EFFECTIFS.* FROM V_DIFF_EFFECTIFS ' || get_sql_criterion('EFFECTIFS',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.EFFECTIFS
              ( id, ANNEE_ID,ELEMENT_PEDAGOGIQUE_ID,FA,FC,FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,EFFECTIFS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.FA,diff_row.FC,diff_row.FI, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.EFFECTIFS SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
            UPDATE OSE.EFFECTIFS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'EFFECTIFS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_EFFECTIFS;



  PROCEDURE MAJ_DOMAINE_FONCTIONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_DOMAINE_FONCTIONNEL%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_DOMAINE_FONCTIONNEL.* FROM V_DIFF_DOMAINE_FONCTIONNEL ' || get_sql_criterion('DOMAINE_FONCTIONNEL',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.DOMAINE_FONCTIONNEL
              ( id, LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,DOMAINE_FONCTIONNEL_ID_SEQ.NEXTVAL), diff_row.LIBELLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DOMAINE_FONCTIONNEL SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.DOMAINE_FONCTIONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DOMAINE_FONCTIONNEL SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
            UPDATE OSE.DOMAINE_FONCTIONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'DOMAINE_FONCTIONNEL', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_DOMAINE_FONCTIONNEL;



  PROCEDURE MAJ_CENTRE_COUT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CENTRE_COUT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CENTRE_COUT.* FROM V_DIFF_CENTRE_COUT ' || get_sql_criterion('CENTRE_COUT',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.CENTRE_COUT
              ( id, ACTIVITE_ID,LIBELLE,PARENT_ID,STRUCTURE_ID,TYPE_RESSOURCE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CENTRE_COUT_ID_SEQ.NEXTVAL), diff_row.ACTIVITE_ID,diff_row.LIBELLE,diff_row.PARENT_ID,diff_row.STRUCTURE_ID,diff_row.TYPE_RESSOURCE_ID, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ACTIVITE_ID = 1 AND IN_COLUMN_LIST('ACTIVITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET ACTIVITE_ID = diff_row.ACTIVITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENT_ID = 1 AND IN_COLUMN_LIST('PARENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET PARENT_ID = diff_row.PARENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_RESSOURCE_ID = 1 AND IN_COLUMN_LIST('TYPE_RESSOURCE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET TYPE_RESSOURCE_ID = diff_row.TYPE_RESSOURCE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.CENTRE_COUT SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ACTIVITE_ID = 1 AND IN_COLUMN_LIST('ACTIVITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET ACTIVITE_ID = diff_row.ACTIVITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENT_ID = 1 AND IN_COLUMN_LIST('PARENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET PARENT_ID = diff_row.PARENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_RESSOURCE_ID = 1 AND IN_COLUMN_LIST('TYPE_RESSOURCE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CENTRE_COUT SET TYPE_RESSOURCE_ID = diff_row.TYPE_RESSOURCE_ID WHERE ID = diff_row.id; END IF;
            UPDATE OSE.CENTRE_COUT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'CENTRE_COUT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_CENTRE_COUT;

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_FORMULE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_FORMULE" AS

  v_date_obs DATE;
  debug_level NUMERIC DEFAULT 0;
  d_all_volume_horaire_ref  t_lst_volume_horaire_ref;
  d_all_volume_horaire      t_lst_volume_horaire;
  arrondi NUMERIC DEFAULT 2;



  FUNCTION GET_DATE_OBS RETURN DATE AS
  BEGIN
    RETURN COALESCE( v_date_obs, SYSDATE );
  END;

  FUNCTION SET_DATE_OBS( DATE_OBS DATE DEFAULT NULL ) RETURN DATE IS
  BEGIN
    v_date_obs := DATE_OBS;
    RETURN v_date_obs;
  END;

  PROCEDURE SET_DEBUG_LEVEL( DEBUG_LEVEL NUMERIC ) IS
  BEGIN
    ose_formule.debug_level := SET_DEBUG_LEVEL.DEBUG_LEVEL;
  END;
  
  FUNCTION GET_DEBUG_LEVEL RETURN NUMERIC IS
  BEGIN
    RETURN ose_formule.debug_level;
  END;

  FUNCTION GET_TAUX_HORAIRE_HETD( DATE_OBS DATE DEFAULT NULL ) RETURN FLOAT IS
    taux_hetd FLOAT;
  BEGIN
    SELECT valeur INTO taux_hetd FROM taux_horaire_hetd t WHERE 1 = OSE_DIVERS.COMPRISE_ENTRE( t.histo_creation, t.histo_destruction, DATE_OBS );
    RETURN taux_hetd;
  END;

  PROCEDURE DEMANDE_CALCUL( INTERVENANT_ID NUMERIC ) IS
  BEGIN
    MERGE INTO formule_resultat_maj frm USING dual ON (
      frm.INTERVENANT_ID = DEMANDE_CALCUL.INTERVENANT_ID
    )
    WHEN NOT MATCHED THEN INSERT ( 
      INTERVENANT_ID
    ) VALUES (
      DEMANDE_CALCUL.INTERVENANT_ID
    );
  END;



  PROCEDURE CALCULER_TOUT IS
    a_id NUMERIC;
  BEGIN
    a_id := OSE_PARAMETRE.GET_ANNEE;
    FOR mp IN (
      SELECT DISTINCT
        intervenant_id 
      FROM 
        service s
        JOIN intervenant i ON i.id = s.intervenant_id
      WHERE
        1 = ose_divers.comprise_entre( s.histo_creation, s.histo_destruction, ose_formule.get_date_obs )
        AND i.annee_id = a_id
        
      UNION
      
      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
        JOIN intervenant i ON i.id = sr.intervenant_id
      WHERE
        1 = ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction, ose_formule.get_date_obs )
        AND i.annee_id = a_id

    )
    LOOP
      CALCULER( mp.intervenant_id );
    END LOOP;
  END;


  PROCEDURE CALCULER_SUR_DEMANDE IS
  BEGIN
    FOR mp IN (SELECT intervenant_id FROM formule_resultat_maj)
    LOOP
      CALCULER( mp.intervenant_id );
    END LOOP;
    DELETE FROM formule_resultat_maj;
  END;


  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat tfr USING dual ON (

          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id
      
    ) WHEN MATCHED THEN UPDATE SET
    
      service_du                     = ROUND( fr.service_du, arrondi ),
      service_fi                     = ROUND( fr.service_fi, arrondi ),
      service_fa                     = ROUND( fr.service_fa, arrondi ),
      service_fc                     = ROUND( fr.service_fc, arrondi ),
      service_referentiel            = ROUND( fr.service_referentiel, arrondi ),
      heures_compl_fi                = ROUND( fr.heures_compl_fi, arrondi ),
      heures_compl_fa                = ROUND( fr.heures_compl_fa, arrondi ),
      heures_compl_fc                = ROUND( fr.heures_compl_fc, arrondi ),
      heures_compl_fc_majorees       = ROUND( fr.heures_compl_fc_majorees, arrondi ),
      heures_compl_referentiel       = ROUND( fr.heures_compl_referentiel, arrondi ),
      total                          = ROUND( fr.total, arrondi ),
      solde                          = ROUND( fr.solde, arrondi ),
      sous_service                   = ROUND( fr.sous_service, arrondi ),
      heures_compl                   = ROUND( fr.heures_compl, arrondi ),
      to_delete                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      INTERVENANT_ID,
      TYPE_VOLUME_HORAIRE_ID,
      ETAT_VOLUME_HORAIRE_ID,
      SERVICE_DU,
      SERVICE_FI,
      SERVICE_FA,
      SERVICE_FC,
      SERVICE_REFERENTIEL,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_majorees,
      HEURES_COMPL_REFERENTIEL,
      TOTAL,
      SOLDE,
      SOUS_SERVICE,
      HEURES_COMPL,
      TO_DELETE
      
    ) VALUES (
    
      FORMULE_RESULTAT_ID_SEQ.NEXTVAL,
      fr.intervenant_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      ROUND( fr.service_du, arrondi ),
      ROUND( fr.service_fi, arrondi ),
      ROUND( fr.service_fa, arrondi ),
      ROUND( fr.service_fc, arrondi ),
      ROUND( fr.service_referentiel, arrondi ),
      ROUND( fr.heures_compl_fi, arrondi ),
      ROUND( fr.heures_compl_fa, arrondi ),
      ROUND( fr.heures_compl_fc, arrondi ),
      ROUND( fr.heures_compl_fc_majorees, arrondi ),
      ROUND( fr.heures_compl_referentiel, arrondi ),
      ROUND( fr.total, arrondi ),
      ROUND( fr.solde, arrondi ),
      ROUND( fr.sous_service, arrondi ),
      ROUND( fr.heures_compl, arrondi ),
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat tfr WHERE
          tfr.intervenant_id         = fr.intervenant_id
      AND tfr.type_volume_horaire_id = fr.type_volume_horaire_id
      AND tfr.etat_volume_horaire_id = fr.etat_volume_horaire_id;
    RETURN id;
  END;


  FUNCTION ENREGISTRER_RESULTAT_SERVICE( fs formule_resultat_service%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service tfs USING dual ON (
    
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id

    ) WHEN MATCHED THEN UPDATE SET
    
      service_fi                     = ROUND( fs.service_fi, arrondi ),
      service_fa                     = ROUND( fs.service_fa, arrondi ),
      service_fc                     = ROUND( fs.service_fc, arrondi ),
      heures_compl_fi                = ROUND( fs.heures_compl_fi, arrondi ),
      heures_compl_fa                = ROUND( fs.heures_compl_fa, arrondi ),
      heures_compl_fc                = ROUND( fs.heures_compl_fc, arrondi ),
      heures_compl_fc_majorees       = ROUND( fs.heures_compl_fc_majorees, arrondi ),
      total                          = ROUND( fs.total, arrondi ),
      TO_DELETE                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_ID,
      SERVICE_FI,
      SERVICE_FA,
      SERVICE_FC,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_majorees,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fs.formule_resultat_id,
      fs.service_id,
      ROUND( fs.service_fi, arrondi ),
      ROUND( fs.service_fa, arrondi ),
      ROUND( fs.service_fc, arrondi ),
      ROUND( fs.heures_compl_fi, arrondi ),
      ROUND( fs.heures_compl_fa, arrondi ),
      ROUND( fs.heures_compl_fc, arrondi ),
      ROUND( fs.heures_compl_fc_majorees, arrondi ),
      ROUND( fs.total, arrondi ),
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat_service tfs WHERE
          tfs.formule_resultat_id    = fs.formule_resultat_id
      AND tfs.service_id             = fs.service_id;
    RETURN id;
  END;


  FUNCTION ENREGISTRER_RESULTAT_VH( fvh formule_resultat_vh%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_vh tfvh USING dual ON (
    
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id

    ) WHEN MATCHED THEN UPDATE SET
    
      service_fi                     = ROUND( fvh.service_fi, arrondi ),
      service_fa                     = ROUND( fvh.service_fa, arrondi ),
      service_fc                     = ROUND( fvh.service_fc, arrondi ),
      heures_compl_fi                = ROUND( fvh.heures_compl_fi, arrondi ),
      heures_compl_fa                = ROUND( fvh.heures_compl_fa, arrondi ),
      heures_compl_fc                = ROUND( fvh.heures_compl_fc, arrondi ),
      heures_compl_fc_majorees       = ROUND( fvh.heures_compl_fc_majorees, arrondi ),
      total                          = ROUND( fvh.total, arrondi ),
      TO_DELETE                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      FORMULE_RESULTAT_ID,
      VOLUME_HORAIRE_ID,
      SERVICE_FI,
      SERVICE_FA,
      SERVICE_FC,
      HEURES_COMPL_FI,
      HEURES_COMPL_FA,
      HEURES_COMPL_FC,
      HEURES_COMPL_FC_MAJOREES,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
      fvh.formule_resultat_id,
      fvh.volume_horaire_id,
      ROUND( fvh.service_fi, arrondi ),
      ROUND( fvh.service_fa, arrondi ),
      ROUND( fvh.service_fc, arrondi ),
      ROUND( fvh.heures_compl_fi, arrondi ),
      ROUND( fvh.heures_compl_fa, arrondi ),
      ROUND( fvh.heures_compl_fc, arrondi ),
      ROUND( fvh.heures_compl_fc_majorees, arrondi ),
      ROUND( fvh.total, arrondi ),
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat_vh tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_id      = fvh.volume_horaire_id;
    RETURN id;
  END;
  
  
  FUNCTION ENREGISTRER_RESULTAT_SERV_REF( fr formule_resultat_service_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_service_ref tfr USING dual ON (

          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id

    ) WHEN MATCHED THEN UPDATE SET

      service_referentiel            = ROUND( fr.service_referentiel, arrondi ),
      heures_compl_referentiel       = ROUND( fr.heures_compl_referentiel, arrondi ),
      TO_DELETE                      = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      FORMULE_RESULTAT_ID,
      SERVICE_REFERENTIEL_ID,
      SERVICE_REFERENTIEL,
      HEURES_COMPL_REFERENTIEL,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_SERVIC_ID_SEQ.NEXTVAL,
      fr.formule_resultat_id,
      fr.service_referentiel_id,
      ROUND( fr.service_referentiel, arrondi ),
      ROUND( fr.heures_compl_referentiel, arrondi ),
      fr.total,
      0

    );

    SELECT id INTO id FROM formule_resultat_service_ref tfr WHERE
          tfr.formule_resultat_id    = fr.formule_resultat_id
      AND tfr.service_referentiel_id = fr.service_referentiel_id;
      
    RETURN id;
  END;
  

  FUNCTION ENREGISTRER_RESULTAT_VH_REF( fvh formule_resultat_vh_ref%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
  BEGIN
    MERGE INTO formule_resultat_vh_ref tfvh USING dual ON (
    
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_ref_id      = fvh.volume_horaire_ref_id

    ) WHEN MATCHED THEN UPDATE SET
    
      service_referentiel            = ROUND( fvh.service_referentiel, arrondi ),
      heures_compl_referentiel       = ROUND( fvh.heures_compl_referentiel, arrondi ),
      total                          = fvh.total,
      TO_DELETE                      = 0
      
    WHEN NOT MATCHED THEN INSERT (
    
      ID,
      FORMULE_RESULTAT_ID,
      VOLUME_HORAIRE_REF_ID,
      SERVICE_REFERENTIEL,
      HEURES_COMPL_REFERENTIEL,
      TOTAL,
      TO_DELETE

    ) VALUES (

      FORMULE_RESULTAT_VH_ID_SEQ.NEXTVAL,
      fvh.formule_resultat_id,
      fvh.volume_horaire_ref_id,
      ROUND( fvh.service_referentiel, arrondi ),
      ROUND( fvh.heures_compl_referentiel, arrondi ),
      fvh.total,
      0
      
    );
    
    SELECT id INTO id FROM formule_resultat_vh_ref tfvh WHERE
          tfvh.formule_resultat_id    = fvh.formule_resultat_id
      AND tfvh.volume_horaire_ref_id  = fvh.volume_horaire_ref_id;
    RETURN id;
  END;
  
  
  PROCEDURE POPULATE_INTERVENANT( INTERVENANT_ID NUMERIC, d_intervenant OUT t_intervenant ) IS
  BEGIN

    SELECT
      structure_id,
      heures_service_statutaire,
      depassement_service_du_sans_hc
    INTO
      d_intervenant.structure_id,
      d_intervenant.heures_service_statutaire,
      d_intervenant.depassement_service_du_sans_hc
    FROM
      v_formule_intervenant fi
    WHERE
      fi.id = POPULATE_INTERVENANT.INTERVENANT_ID;

    SELECT
      NVL( SUM(heures), 0)
    INTO
      d_intervenant.heures_service_modifie
    FROM
      v_formule_service_modifie fsm
    WHERE
      fsm.intervenant_id = POPULATE_INTERVENANT.INTERVENANT_ID;
  
  EXCEPTION WHEN NO_DATA_FOUND THEN
    d_intervenant.structure_id := null;
    d_intervenant.heures_service_statutaire := null;
  END;
  

  PROCEDURE POPULATE_SERVICE_REF( INTERVENANT_ID NUMERIC, d_service_ref OUT t_lst_service_ref ) IS
    i PLS_INTEGER;
  BEGIN
    d_service_ref.delete;

    FOR d IN (
      SELECT
        fr.id,
        fr.structure_id
      FROM
        v_formule_service_ref fr
      WHERE
        fr.intervenant_id = POPULATE_SERVICE_REF.INTERVENANT_ID
    ) LOOP
      d_service_ref( d.id ).id           := d.id;
      d_service_ref( d.id ).structure_id := d.structure_id;
    END LOOP;
  END;


  PROCEDURE POPULATE_SERVICE( INTERVENANT_ID NUMERIC, d_service OUT t_lst_service ) IS
  BEGIN
    d_service.delete;

    FOR d IN (
      SELECT
        id,
        taux_fi,
        taux_fa,
        taux_fc,
        structure_aff_id,
        structure_ens_id,
        ponderation_service_du,
        ponderation_service_compl
      FROM
        v_formule_service fs
      WHERE
        fs.intervenant_id = POPULATE_SERVICE.INTERVENANT_ID
    ) LOOP
      d_service( d.id ).id                        := d.id;
      d_service( d.id ).taux_fi                   := d.taux_fi;
      d_service( d.id ).taux_fa                   := d.taux_fa;
      d_service( d.id ).taux_fc                   := d.taux_fc;
      d_service( d.id ).ponderation_service_du    := d.ponderation_service_du;
      d_service( d.id ).ponderation_service_compl := d.ponderation_service_compl;
      d_service( d.id ).structure_aff_id          := d.structure_aff_id;
      d_service( d.id ).structure_ens_id          := d.structure_ens_id;
    END LOOP;
  END;

  PROCEDURE POPULATE_VOLUME_HORAIRE_REF( INTERVENANT_ID NUMERIC, d_volume_horaire_ref OUT t_lst_volume_horaire_ref ) IS
  BEGIN
    d_volume_horaire_ref.delete;

    FOR d IN (
      SELECT
        id,
        service_referentiel_id,
        heures,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire_ref fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE_REF.INTERVENANT_ID
    ) LOOP
      d_volume_horaire_ref( d.id ).id                        := d.id;
      d_volume_horaire_ref( d.id ).service_referentiel_id    := d.service_referentiel_id;
      d_volume_horaire_ref( d.id ).heures                    := d.heures;
      d_volume_horaire_ref( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire_ref( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire_ref( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;

  PROCEDURE POPULATE_VOLUME_HORAIRE( INTERVENANT_ID NUMERIC, d_volume_horaire OUT t_lst_volume_horaire ) IS
  BEGIN
    d_volume_horaire.delete;

    FOR d IN (
      SELECT
        id,
        service_id,
        heures,
        taux_service_du,
        taux_service_compl,
        fvh.type_volume_horaire_id,
        fvh.etat_volume_horaire_id,
        fvh.etat_volume_horaire_ordre
      FROM
        v_formule_volume_horaire fvh
      WHERE
        fvh.intervenant_id                = POPULATE_VOLUME_HORAIRE.INTERVENANT_ID
    ) LOOP
      d_volume_horaire( d.id ).id                        := d.id;
      d_volume_horaire( d.id ).service_id                := d.service_id;
      d_volume_horaire( d.id ).heures                    := d.heures;
      d_volume_horaire( d.id ).taux_service_du           := d.taux_service_du;
      d_volume_horaire( d.id ).taux_service_compl        := d.taux_service_compl;
      d_volume_horaire( d.id ).type_volume_horaire_id    := d.type_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_id    := d.etat_volume_horaire_id;
      d_volume_horaire( d.id ).etat_volume_horaire_ordre := d.etat_volume_horaire_ordre;
    END LOOP;
  END;


  PROCEDURE POPULATE_TYPE_ETAT_VH( d_volume_horaire t_lst_volume_horaire, d_type_etat_vh OUT t_lst_type_etat_vh ) IS
    TYPE t_ordres IS TABLE OF NUMERIC INDEX BY PLS_INTEGER;

    ordres_found t_ordres;
    ordres_exists t_ordres;
    type_volume_horaire_id PLS_INTEGER;
    etat_volume_horaire_ordre PLS_INTEGER;
    id PLS_INTEGER;
  BEGIN
    d_type_etat_vh.delete;

    -- récupération des ID et ordres de volumes horaires
    FOR evh IN (
      SELECT   id, ordre
      FROM     etat_volume_horaire evh
      WHERE    OSE_DIVERS.COMPRISE_ENTRE( evh.histo_creation, evh.histo_destruction ) = 1
      ORDER BY ordre
    ) LOOP
      ordres_exists( evh.ordre ) := evh.id;
    END LOOP;

    -- récupération des ordres maximum par type d'intervention
    id := d_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF NOT ordres_found.EXISTS(d_volume_horaire(id).type_volume_horaire_id) THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      ELSIF ordres_found( d_volume_horaire(id).type_volume_horaire_id ) < d_volume_horaire(id).etat_volume_horaire_ordre THEN
        ordres_found( d_volume_horaire(id).type_volume_horaire_id ) := d_volume_horaire(id).etat_volume_horaire_ordre;
      END IF;
      id := d_volume_horaire.NEXT(id);
    END LOOP;
    
    -- peuplement des t_lst_type_etat_vh
    type_volume_horaire_id := ordres_found.FIRST;
    LOOP EXIT WHEN type_volume_horaire_id IS NULL;
      etat_volume_horaire_ordre := ordres_exists.FIRST;
      LOOP EXIT WHEN etat_volume_horaire_ordre IS NULL;
        IF etat_volume_horaire_ordre <= ordres_found(type_volume_horaire_id) THEN
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).type_volume_horaire_id := type_volume_horaire_id;
          d_type_etat_vh( type_volume_horaire_id + 100000 * etat_volume_horaire_ordre ).etat_volume_horaire_id := ordres_exists( etat_volume_horaire_ordre );
        END IF;
        etat_volume_horaire_ordre := ordres_exists.NEXT(etat_volume_horaire_ordre);
      END LOOP;
      
      type_volume_horaire_id := ordres_found.NEXT(type_volume_horaire_id);
    END LOOP;

  END;


  PROCEDURE POPULATE( INTERVENANT_ID NUMERIC ) IS
  BEGIN
    POPULATE_INTERVENANT    ( INTERVENANT_ID, d_intervenant );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
      POPULATE_SERVICE_REF        ( INTERVENANT_ID, d_service_ref         );
      POPULATE_SERVICE            ( INTERVENANT_ID, d_service             );
      POPULATE_VOLUME_HORAIRE_REF ( INTERVENANT_ID, d_all_volume_horaire_ref  );
      POPULATE_VOLUME_HORAIRE     ( INTERVENANT_ID, d_all_volume_horaire      );
      POPULATE_TYPE_ETAT_VH       ( d_all_volume_horaire, d_type_etat_vh );
    END IF;
  END;

  
  PROCEDURE POPULATE_FILTER( TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    EVH_ORDRE NUMERIC;
    id PLS_INTEGER;
  BEGIN
    d_volume_horaire.delete;
    d_volume_horaire_ref.delete;

    SELECT ordre INTO EVH_ORDRE FROM etat_volume_horaire WHERE ID = ETAT_VOLUME_HORAIRE_ID;

    id := d_all_volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        d_all_volume_horaire(id).type_volume_horaire_id = TYPE_VOLUME_HORAIRE_ID
        AND d_all_volume_horaire(id).etat_volume_horaire_ordre >= EVH_ORDRE 
      THEN
        d_volume_horaire(id) := d_all_volume_horaire(id);
      END IF;
      id := d_all_volume_horaire.NEXT(id);
    END LOOP;
    
    id := d_all_volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF
        d_all_volume_horaire_ref(id).type_volume_horaire_id = TYPE_VOLUME_HORAIRE_ID
        AND d_all_volume_horaire_ref(id).etat_volume_horaire_ordre >= EVH_ORDRE 
      THEN
        d_volume_horaire_ref(id) := d_all_volume_horaire_ref(id);
      END IF;
      id := d_all_volume_horaire_ref.NEXT(id);
    END LOOP;
  END;


  PROCEDURE INIT_RESULTAT ( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
  BEGIN
    d_resultat.intervenant_id         := INTERVENANT_ID;
    d_resultat.type_volume_horaire_id := TYPE_VOLUME_HORAIRE_ID;
    d_resultat.etat_volume_horaire_id := ETAT_VOLUME_HORAIRE_ID;
    d_resultat.service_du             := 0;
    d_resultat.solde                  := 0;
    d_resultat.sous_service           := 0;
    d_resultat.heures_compl           := 0;
    d_resultat.volume_horaire.delete;
    d_resultat.volume_horaire_ref.delete;
  END;


  PROCEDURE CALC_RESULTAT IS
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
    function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

    EXECUTE IMMEDIATE 
      'BEGIN ' || package_name || '.' || function_name || '( :1, :2, :3 ); END;'
    USING
      d_resultat.intervenant_id, d_resultat.type_volume_horaire_id, d_resultat.etat_volume_horaire_id;

  END;
  
  
  PROCEDURE SAVE_RESULTAT IS
    res             t_resultat_hetd;
    res_ref         t_resultat_hetd_ref;
    res_service     t_lst_resultat_hetd;
    res_service_ref t_lst_resultat_hetd_ref;
    id              PLS_INTEGER;
    sid             PLS_INTEGER;
    fr              formule_resultat%rowtype;
    frs             formule_resultat_service%rowtype;
    frsr            formule_resultat_service_ref%rowtype;
    frvh            formule_resultat_vh%rowtype;
    frvhr           formule_resultat_vh_ref%rowtype;
    dev_null        PLS_INTEGER;
  BEGIN
    -- Calcul des données pour les services et le résultat global
    fr.service_fi := 0;
    fr.service_fa := 0;
    fr.service_fc := 0;
    fr.service_referentiel := 0;
    fr.heures_compl_fi := 0;
    fr.heures_compl_fa := 0;
    fr.heures_compl_fc := 0;
    fr.heures_compl_fc_majorees := 0;
    fr.heures_compl_referentiel := 0;

    id := d_resultat.volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      sid := d_volume_horaire(id).service_id;
      IF NOT res_service.exists(sid) THEN res_service(sid).service_fi := 0; END IF;

      res_service(sid).service_fi               := res_service(sid).service_fi               + d_resultat.volume_horaire(id).service_fi;
      res_service(sid).service_fa               := res_service(sid).service_fa               + d_resultat.volume_horaire(id).service_fa;
      res_service(sid).service_fc               := res_service(sid).service_fc               + d_resultat.volume_horaire(id).service_fc;
      res_service(sid).heures_compl_fi          := res_service(sid).heures_compl_fi          + d_resultat.volume_horaire(id).heures_compl_fi;
      res_service(sid).heures_compl_fa          := res_service(sid).heures_compl_fa          + d_resultat.volume_horaire(id).heures_compl_fa;
      res_service(sid).heures_compl_fc          := res_service(sid).heures_compl_fc          + d_resultat.volume_horaire(id).heures_compl_fc;
      res_service(sid).heures_compl_fc_majorees := res_service(sid).heures_compl_fc_majorees + d_resultat.volume_horaire(id).heures_compl_fc_majorees;

      fr.service_fi                             := fr.service_fi                             + d_resultat.volume_horaire(id).service_fi;
      fr.service_fa                             := fr.service_fa                             + d_resultat.volume_horaire(id).service_fa;
      fr.service_fc                             := fr.service_fc                             + d_resultat.volume_horaire(id).service_fc;
      fr.heures_compl_fi                        := fr.heures_compl_fi                        + d_resultat.volume_horaire(id).heures_compl_fi;
      fr.heures_compl_fa                        := fr.heures_compl_fa                        + d_resultat.volume_horaire(id).heures_compl_fa;
      fr.heures_compl_fc                        := fr.heures_compl_fc                        + d_resultat.volume_horaire(id).heures_compl_fc;
      fr.heures_compl_fc_majorees               := fr.heures_compl_fc_majorees               + d_resultat.volume_horaire(id).heures_compl_fc_majorees;
      id := d_resultat.volume_horaire.NEXT(id);
    END LOOP;

    id := d_resultat.volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      sid := d_volume_horaire_ref(id).service_referentiel_id;
      IF NOT res_service_ref.exists(sid) THEN res_service_ref(sid).service_referentiel := 0; END IF;

      res_service_ref(sid).service_referentiel      := res_service_ref(sid).service_referentiel      + d_resultat.volume_horaire_ref(id).service_referentiel;
      res_service_ref(sid).heures_compl_referentiel := res_service_ref(sid).heures_compl_referentiel + d_resultat.volume_horaire_ref(id).heures_compl_referentiel;

      fr.service_referentiel                        := fr.service_referentiel                        + d_resultat.volume_horaire_ref(id).service_referentiel;
      fr.heures_compl_referentiel                   := fr.heures_compl_referentiel                   + d_resultat.volume_horaire_ref(id).heures_compl_referentiel;
      id := d_resultat.volume_horaire_ref.NEXT(id);
    END LOOP;

    -- Sauvegarde du résultat global
    fr.id                       := NULL;
    fr.intervenant_id           := d_resultat.intervenant_id;
    fr.type_volume_horaire_id   := d_resultat.type_volume_horaire_id;
    fr.etat_volume_horaire_id   := d_resultat.etat_volume_horaire_id;
    fr.service_du               := d_resultat.service_du;
    fr.total                    := fr.service_fi
                                 + fr.service_fa
                                 + fr.service_fc
                                 + fr.service_referentiel
                                 + fr.heures_compl_fi
                                 + fr.heures_compl_fa
                                 + fr.heures_compl_fc
                                 + fr.heures_compl_fc_majorees
                                 + fr.heures_compl_referentiel;
    fr.solde                    := d_resultat.solde;
    fr.sous_service             := d_resultat.sous_service;
    fr.heures_compl             := d_resultat.heures_compl;
    fr.id := OSE_FORMULE.ENREGISTRER_RESULTAT( fr );

    -- sauvegarde des services
    id := res_service.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frs.id                       := NULL;
      frs.formule_resultat_id      := fr.id;
      frs.service_id               := id;
      frs.service_fi               := res_service(id).service_fi;
      frs.service_fa               := res_service(id).service_fa;
      frs.service_fc               := res_service(id).service_fc;
      frs.heures_compl_fi          := res_service(id).heures_compl_fi;
      frs.heures_compl_fa          := res_service(id).heures_compl_fa;
      frs.heures_compl_fc          := res_service(id).heures_compl_fc;
      frs.heures_compl_fc_majorees := res_service(id).heures_compl_fc_majorees;
      frs.total                    := frs.service_fi
                                    + frs.service_fa
                                    + frs.service_fc
                                    + frs.heures_compl_fi
                                    + frs.heures_compl_fa
                                    + frs.heures_compl_fc
                                    + frs.heures_compl_fc_majorees;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_SERVICE( frs );
      id := res_service.NEXT(id);
    END LOOP;
     
    -- sauvegarde des services référentiels
    id := res_service_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frsr.id                       := NULL;
      frsr.formule_resultat_id      := fr.id;
      frsr.service_referentiel_id   := id;
      frsr.service_referentiel      := res_service_ref(id).service_referentiel;
      frsr.heures_compl_referentiel := res_service_ref(id).heures_compl_referentiel;
      frsr.total                    := res_service_ref(id).service_referentiel
                                     + res_service_ref(id).heures_compl_referentiel;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_SERV_REF( frsr );
      id := res_service_ref.NEXT(id);
    END LOOP;

    -- sauvegarde des volumes horaires
    id := d_resultat.volume_horaire.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frvh.id                        := NULL;
      frvh.formule_resultat_id       := fr.id;
      frvh.volume_horaire_id         := id;
      frvh.service_fi                := d_resultat.volume_horaire(id).service_fi;
      frvh.service_fa                := d_resultat.volume_horaire(id).service_fa;
      frvh.service_fc                := d_resultat.volume_horaire(id).service_fc;
      frvh.heures_compl_fi           := d_resultat.volume_horaire(id).heures_compl_fi;
      frvh.heures_compl_fa           := d_resultat.volume_horaire(id).heures_compl_fa;
      frvh.heures_compl_fc           := d_resultat.volume_horaire(id).heures_compl_fc;
      frvh.heures_compl_fc_majorees  := d_resultat.volume_horaire(id).heures_compl_fc_majorees;
      frvh.total                     := frvh.service_fi
                                      + frvh.service_fa
                                      + frvh.service_fc
                                      + frvh.heures_compl_fi
                                      + frvh.heures_compl_fa
                                      + frvh.heures_compl_fc
                                      + frvh.heures_compl_fc_majorees;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_VH( frvh );
      id := d_resultat.volume_horaire.NEXT(id);
    END LOOP;

    -- sauvegarde des volumes horaires référentiels
    id := d_resultat.volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      frvhr.id                       := NULL;
      frvhr.formule_resultat_id      := fr.id;
      frvhr.volume_horaire_ref_id    := id;
      frvhr.service_referentiel      := d_resultat.volume_horaire_ref(id).service_referentiel;
      frvhr.heures_compl_referentiel := d_resultat.volume_horaire_ref(id).heures_compl_referentiel;
      frvhr.total                    := frvhr.service_referentiel
                                      + frvhr.heures_compl_referentiel;
      dev_null := OSE_FORMULE.ENREGISTRER_RESULTAT_VH_REF( frvhr );
      id := d_resultat.volume_horaire_ref.NEXT(id);
    END LOOP;
  END;

  PROCEDURE DEBUG_INTERVENANT IS
  BEGIN
    ose_test.echo('d_intervenant');
    ose_test.echo('      .structure_id                   = ' || d_intervenant.structure_id || ' (' || ose_test.get_structure_by_id(d_intervenant.structure_id).libelle_court || ')' );
    ose_test.echo('      .heures_service_statutaire      = ' || d_intervenant.heures_service_statutaire );
    ose_test.echo('      .heures_service_modifie         = ' || d_intervenant.heures_service_modifie );
    ose_test.echo('      .depassement_service_du_sans_hc = ' || d_intervenant.depassement_service_du_sans_hc );
    ose_test.echo('');
  END;
  
  PROCEDURE DEBUG_SERVICE( SERVICE_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_service(' || SERVICE_ID || ')' );
    ose_test.echo('      .taux_fi                   = ' || d_service(SERVICE_ID).taux_fi );
    ose_test.echo('      .taux_fa                   = ' || d_service(SERVICE_ID).taux_fa );
    ose_test.echo('      .taux_fc                   = ' || d_service(SERVICE_ID).taux_fc );
    ose_test.echo('      .ponderation_service_du    = ' || d_service(SERVICE_ID).ponderation_service_du );
    ose_test.echo('      .ponderation_service_compl = ' || d_service(SERVICE_ID).ponderation_service_compl );
    ose_test.echo('      .structure_aff_id          = ' || d_service(SERVICE_ID).structure_aff_id || ' (' || ose_test.get_structure_by_id(d_service(SERVICE_ID).structure_aff_id).libelle_court || ')' );
    ose_test.echo('      .structure_ens_id          = ' || d_service(SERVICE_ID).structure_ens_id || ' (' || CASE WHEN d_service(SERVICE_ID).structure_ens_id IS NOT NULL THEN ose_test.get_structure_by_id(d_service(SERVICE_ID).structure_ens_id).libelle_court ELSE 'null' END || ')' );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_SERVICE_REF( SERVICE_REF_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_service_ref(' || SERVICE_REF_ID || ')' );
    ose_test.echo('      .structure_id          = ' || d_service_ref(SERVICE_REF_ID).structure_id || ' (' || ose_test.get_structure_by_id(d_service_ref(SERVICE_REF_ID).structure_id).libelle_court || ')' );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_VOLUME_HORAIRE( VH_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_volume_horaire(' || VH_ID || ')' );
    ose_test.echo('      .service_id                = ' || d_volume_horaire(VH_ID).service_id );
    ose_test.echo('      .type_volume_horaire_id    = ' || d_volume_horaire(VH_ID).type_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_id    = ' || d_volume_horaire(VH_ID).etat_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_ordre = ' || d_volume_horaire(VH_ID).etat_volume_horaire_ordre );
    ose_test.echo('      .heures                    = ' || d_volume_horaire(VH_ID).heures );
    ose_test.echo('      .taux_service_du           = ' || d_volume_horaire(VH_ID).taux_service_du );
    ose_test.echo('      .taux_service_compl        = ' || d_volume_horaire(VH_ID).taux_service_compl );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_VOLUME_HORAIRE_REF( VH_REF_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_volume_horaire_ref(' || VH_REF_ID || ')' );
    ose_test.echo('      .service_referentiel_id    = ' || d_volume_horaire_ref(VH_REF_ID).service_referentiel_id );
    ose_test.echo('      .type_volume_horaire_id    = ' || d_volume_horaire_ref(VH_REF_ID).type_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_id    = ' || d_volume_horaire_ref(VH_REF_ID).etat_volume_horaire_id );
    ose_test.echo('      .etat_volume_horaire_ordre = ' || d_volume_horaire_ref(VH_REF_ID).etat_volume_horaire_ordre );
    ose_test.echo('      .heures                    = ' || d_volume_horaire_ref(VH_REF_ID).heures );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_RESULTAT IS
  BEGIN
    ose_test.echo('d_resultat' );
    ose_test.echo('      .service_du   = ' || d_resultat.service_du );
    ose_test.echo('      .solde        = ' || d_resultat.solde );
    ose_test.echo('      .sous_service = ' || d_resultat.sous_service );
    ose_test.echo('      .heures_compl = ' || d_resultat.heures_compl );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_RESULTAT_VH( VH_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_resultat.volume_horaire(' || VH_ID || ')' );
    ose_test.echo('      .service_fi                = ' || d_resultat.volume_horaire(VH_ID).service_fi );
    ose_test.echo('      .service_fa                = ' || d_resultat.volume_horaire(VH_ID).service_fa );
    ose_test.echo('      .service_fc                = ' || d_resultat.volume_horaire(VH_ID).service_fc );
    ose_test.echo('      .heures_compl_fi           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fi );
    ose_test.echo('      .heures_compl_fa           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fa );
    ose_test.echo('      .heures_compl_fc           = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fc );
    ose_test.echo('      .heures_compl_fc_majorees  = ' || d_resultat.volume_horaire(VH_ID).heures_compl_fc_majorees );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_RESULTAT_VH_REF( VH_REF_ID PLS_INTEGER ) IS
  BEGIN
    ose_test.echo('d_resultat.volume_horaire_ref(' || VH_REF_ID || ')' );
    ose_test.echo('      .service_referentiel                = ' || d_resultat.volume_horaire_ref(VH_REF_ID).service_referentiel );
    ose_test.echo('      .heures_compl_referentiel           = ' || d_resultat.volume_horaire_ref(VH_REF_ID).heures_compl_referentiel );
    ose_test.echo('');
  END;

  PROCEDURE DEBUG_ALL( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, ETAT_VOLUME_HORAIRE_ID NUMERIC ) IS
    id  PLS_INTEGER;
    i   intervenant%rowtype;
    a   annee%rowtype;
    tvh type_volume_horaire%rowtype;
    evh etat_volume_horaire%rowtype;
  BEGIN
    IF GET_DEBUG_LEVEL >= 1 THEN
      SELECT * INTO   i FROM intervenant         WHERE id = INTERVENANT_ID;
      SELECT * INTO   a FROM annee               WHERE id = i.annee_id;
      SELECT * INTO tvh FROM type_volume_horaire WHERE id = TYPE_VOLUME_HORAIRE_ID;
      SELECT * INTO evh FROM etat_volume_horaire WHERE id = ETAT_VOLUME_HORAIRE_ID;
          
      ose_test.echo('');
      ose_test.echo('---------------------------------------------------------------------');
      ose_test.echo('Intervenant: ' || INTERVENANT_ID || ' : ' || i.prenom || ' ' || i.nom_usuel || ' (n° harp. ' || i.source_code || ')' );
      ose_test.echo(
                  'Année: ' || a.libelle
               || ', type ' || tvh.libelle
               || ', état ' || evh.libelle
      );
      ose_test.echo('');
    END IF;
    IF GET_DEBUG_LEVEL >= 2 THEN
      DEBUG_INTERVENANT;
    END IF;
    
    IF GET_DEBUG_LEVEL >= 5 THEN     
      id := d_service.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_SERVICE( id ); 
        id := d_service.NEXT(id);
      END LOOP;

      id := d_service_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_SERVICE_REF( id ); 
        id := d_service_ref.NEXT(id);
      END LOOP;
    END IF;
    
    IF GET_DEBUG_LEVEL >= 6 THEN     
      id := d_volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_VOLUME_HORAIRE( id ); 
        id := d_volume_horaire.NEXT(id);
      END LOOP;

      id := d_volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_VOLUME_HORAIRE_REF( id ); 
        id := d_volume_horaire_ref.NEXT(id);
      END LOOP;
    END IF;

    IF GET_DEBUG_LEVEL >= 3 THEN
      DEBUG_RESULTAT;
    END IF;
    
    IF GET_DEBUG_LEVEL >= 4 THEN
      id := d_resultat.volume_horaire.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_RESULTAT_VH( id ); 
        id := d_resultat.volume_horaire.NEXT(id);
      END LOOP;
      
      id := d_resultat.volume_horaire_ref.FIRST;
      LOOP EXIT WHEN id IS NULL;
        DEBUG_RESULTAT_VH_REF( id ); 
        id := d_resultat.volume_horaire_ref.NEXT(id);
      END LOOP;
    END IF;
  END;


  PROCEDURE CALCULER( INTERVENANT_ID NUMERIC ) IS
    id PLS_INTEGER;
    found BOOLEAN;
    function_name VARCHAR2(30);
    package_name VARCHAR2(30);
  BEGIN
    package_name  := OSE_PARAMETRE.GET_FORMULE_PACKAGE_NAME;
    function_name := OSE_PARAMETRE.GET_FORMULE_FUNCTION_NAME;

    -- détection de suppression des lignes de résultat obsolètes
    UPDATE formule_resultat SET TO_DELETE = 1 WHERE intervenant_id = CALCULER.INTERVENANT_ID;
    UPDATE FORMULE_RESULTAT_SERVICE_REF SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    UPDATE FORMULE_RESULTAT_SERVICE     SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    UPDATE FORMULE_RESULTAT_VH_REF      SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    UPDATE FORMULE_RESULTAT_VH          SET TO_DELETE = 1 WHERE formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);

    POPULATE( INTERVENANT_ID );
    IF d_intervenant.heures_service_statutaire IS NOT NULL THEN -- sinon rien n'est à faire!!
      -- lancement du calcul sur les nouvelles lignes ou sur les lignes existantes
      id := d_type_etat_vh.FIRST;
      LOOP EXIT WHEN id IS NULL;
        POPULATE_FILTER( d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
        DEBUG_ALL( INTERVENANT_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
        OSE_FORMULE.INIT_RESULTAT( INTERVENANT_ID, d_type_etat_vh(id).type_volume_horaire_id, d_type_etat_vh(id).etat_volume_horaire_id );
        OSE_FORMULE.CALC_RESULTAT;
        OSE_FORMULE.SAVE_RESULTAT;  
        id := d_type_etat_vh.NEXT(id);
      END LOOP;
    END IF;

    -- suppression des données devenues obsolètes
    DELETE FROM FORMULE_RESULTAT_SERVICE_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM formule_resultat WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID;

  END;

END OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_DIVERS" AS

FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
  statut statut_intervenant%rowtype;
  itype  type_intervenant%rowtype;
  res NUMERIC;
BEGIN
  res := 1;
  SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
  
  /* DEPRECATED */
  SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;
  IF 'saisie_service' = privilege_name THEN
    res := statut.peut_saisir_service;
    RETURN res;
  ELSIF 'saisie_service_exterieur' = privilege_name THEN
    --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
    IF itype.code = 'E' THEN
      res := 0;
      RETURN res;
    END IF;
  ELSIF 'saisie_service_referentiel' = privilege_name THEN
    IF itype.code = 'E' THEN
      res := 0;
      RETURN res;
    END IF;
  ELSIF 'saisie_service_referentiel_autre_structure' = privilege_name THEN
    res := 1;
    RETURN res;
  ELSIF 'saisie_motif_non_paiement' = privilege_name THEN
    res := statut.peut_saisir_motif_non_paiement;
    RETURN res;
  END IF;
  /* FIN DE DEPRECATED */
  
  SELECT
    count(*)
  INTO
    res
  FROM
    intervenant i
    JOIN statut_privilege sp ON sp.statut_id = i.statut_id
    JOIN privilege p ON p.id = sp.privilege_id
    JOIN categorie_privilege cp ON cp.id = p.categorie_id
  WHERE
    i.id = INTERVENANT_HAS_PRIVILEGE.intervenant_id
    AND cp.code || '-' || p.code = privilege_name;
    
  RETURN res;
END;

FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2 AS
  l_return CLOB:='';
  l_temp CLOB;
  TYPE r_cursor is REF CURSOR;
  rc r_cursor;
BEGIN
  OPEN rc FOR i_query;
  LOOP
    FETCH rc INTO L_TEMP;
    EXIT WHEN RC%NOTFOUND;
    l_return:=l_return||L_TEMP||i_seperator;
  END LOOP;
  RETURN RTRIM(l_return,i_seperator);
END;

FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant_permanent WHERE id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.non_autorise = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC AS
  resultat NUMERIC;
BEGIN
  SELECT COUNT(*) INTO resultat FROM intervenant i JOIN statut_intervenant si ON si.id = i.statut_id AND si.peut_saisir_service = 1 WHERE i.id = INTERVENANT_ID;
  RETURN resultat;
END;

FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC AS
BEGIN
  IF 1 <> gtf_pertinence_niveau OR niveau IS NULL OR niveau < 1 OR gtf_id < 1 THEN RETURN NULL; END IF;
  RETURN gtf_id * 256 + niveau;
END;

FUNCTION STRUCTURE_DANS_STRUCTURE( structure_testee NUMERIC, structure_cible NUMERIC ) RETURN NUMERIC AS
  RESULTAT NUMERIC;
BEGIN
  IF structure_testee = structure_cible THEN RETURN 1; END IF;
  
  select count(*) into resultat
  from structure
  WHERE structure.id = structure_testee
  start with parente_id = structure_cible
  connect by parente_id = prior id;

  RETURN RESULTAT;
END;

FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB IS
BEGIN
  RETURN utl_raw.cast_to_varchar2((nlssort(str, 'nls_sort=binary_ai')));
END;

FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC IS
BEGIN
  IF STR_REDUCE( haystack ) LIKE STR_REDUCE( '%' || needle || '%' ) THEN RETURN 1; END IF;
  RETURN 0;
END;

FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC IS
BEGIN
  RETURN CASE WHEN STR_REDUCE(haystack) LIKE STR_REDUCE(needle) THEN 1 ELSE 0 END;
END;

FUNCTION COMPRISE_ENTRE( date_debut DATE, date_fin DATE, date_obs DATE DEFAULT NULL, inclusif NUMERIC DEFAULT 0 ) RETURN NUMERIC IS
  d_deb DATE;
  d_fin DATE;
  d_obs DATE;
  res NUMERIC;
BEGIN
  IF inclusif = 1 THEN
    d_obs := TRUNC( COALESCE( d_obs     , SYSDATE ) );
    d_deb := TRUNC( COALESCE( date_debut, d_obs   ) );
    d_fin := TRUNC( COALESCE( date_fin  , d_obs   ) );
    IF d_obs BETWEEN d_deb AND d_fin THEN
      RETURN 1;
    ELSE
      RETURN 0;
    END IF;
  ELSE
    d_obs := TRUNC( COALESCE( d_obs, SYSDATE ) );
    d_deb := TRUNC( date_debut );
    d_fin := TRUNC( date_fin   );
    
    IF d_deb IS NOT NULL AND NOT d_deb <= d_obs THEN
      RETURN 0;
    END IF;
    IF d_fin IS NOT NULL AND NOT d_obs < d_fin THEN
      RETURN 0;
    END IF;
    RETURN 1;
  END IF;
END;

PROCEDURE DO_NOTHING IS
BEGIN
  RETURN;
END;

FUNCTION VOLUME_HORAIRE_VALIDE( volume_horaire_id NUMERIC ) RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT count(*) INTO res FROM
    validation v
    JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
  WHERE
    1 = ose_divers.comprise_entre( v.histo_creation, v.histo_destruction );
  RETURN CASE WHEN res > 0 THEN 1 ELSE 0 END;
END;


PROCEDURE CALCUL_TAUX( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, r_fi OUT FLOAT, r_fc OUT FLOAT, r_fa OUT FLOAT, arrondi NUMERIC DEFAULT 15 ) IS
  nt FLOAT;
  bi FLOAT;
  bc FLOAT;
  ba FLOAT;
  reste FLOAT;
BEGIN
  bi := eff_fi * fi;
  bc := eff_fc * fc;
  ba := eff_fa * fa;
  nt := bi + bc + ba;

  IF nt = 0 THEN -- au cas ou, alors on ne prend plus en compte les effectifs!!
    bi := fi;
    bc := fc;
    ba := fa;
    nt := bi + bc + ba;
  END IF;
  
  IF nt = 0 THEN -- toujours au cas ou...
    bi := 1;
    bc := 0;
    ba := 0;
    nt := bi + bc + ba;
  END IF;

  -- Calcul
  r_fi := bi / nt;
  r_fc := bc / nt;
  r_fa := ba / nt;

  -- Arrondis
  r_fi := ROUND( r_fi, arrondi );
  r_fc := ROUND( r_fc, arrondi );
  r_fa := ROUND( r_fa, arrondi );

  -- détermination du reste
  reste := 1 - r_fi - r_fc - r_fa;

  -- répartition éventuelle du reste
  IF reste <> 0 THEN
    IF r_fi > 0 THEN r_fi := r_fi + reste;
    ELSIF r_fc > 0 THEN r_fc := r_fc + reste;
    ELSE r_fa := r_fa + reste; END IF;
  END IF;

END;


FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ri;
END;
  
FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN rc;
END;
  
FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT IS
  ri FLOAT;
  rc FLOAT;
  ra FLOAT;
BEGIN
  CALCUL_TAUX( eff_fi, eff_fc, eff_fa, fi, fc, fa, ri, rc, ra, arrondi );
  RETURN ra;
END;

FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC IS
  res NUMERIC;
BEGIN
  SELECT id INTO res FROM structure WHERE niveau = 1 AND ROWNUM = 1;
  RETURN res;
END;

PROCEDURE SYNC_LOG( msg CLOB ) IS
BEGIN
  INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
END;

FUNCTION ANNEE_UNIVERSITAIRE( date_ref DATE DEFAULT SYSDATE, mois_deb_au NUMERIC DEFAULT 9, jour_deb_au NUMERIC DEFAULT 1 ) RETURN NUMERIC IS
  annee_ref NUMERIC;
  mois_ref NUMERIC;
  jour_ref NUMERIC;
BEGIN
  annee_ref := to_number(to_char(date_ref, 'yyyy'));
  mois_ref  := to_number(to_char(date_ref, 'mm'));
  jour_ref  := to_number(to_char(date_ref, 'dd'));
  
  IF jour_ref < jour_deb_au THEN mois_ref := mois_ref - 1; END IF;
  IF mois_ref < mois_deb_au THEN annee_ref := annee_ref - 1; END IF;
  
  RETURN annee_ref;
END;

END OSE_DIVERS;
/
---------------------------
--Nouveau PROCEDURE
--UPGRADE_PIECE_JOINTE_V15
---------------------------
CREATE OR REPLACE PROCEDURE "OSE"."UPGRADE_PIECE_JOINTE_V15" as
  found numeric;
begin 
  for r in (
    -- parcours : produit_cartesien(tous les dossiers existants, tous les types de PJ existants)
    select d.id dossier_id, tpj.id tpj_id
    from dossier d, type_piece_jointe tpj
    where d.histo_destruction is null and tpj.histo_destruction is null
  ) 
  loop
    -- Mise à jour des PJ attendues pour le type de PJ et le dossier spécifiés.
    ose_pj.update_pj(r.tpj_id, r.dossier_id);
    
--    -- Le témoin "PJ fournie" est mis à 1 si des fichiers sont trouvés
--    update piece_jointe pj set pj.fournie = (
--      select case when count(*)>0 then 1 else 0 end 
--      from piece_jointe_fichier pjf 
--      join fichier f on f.id = pjf.fichier_id and f.histo_destruction is null 
--      where pjf.piece_jointe_id = pj.id
--    );
--    -- et forcé à 1 si la PJ a été validée
--    update piece_jointe pj set pj.fournie = 1 where pj.validation_id is not null;
    
  end loop;
end;
/





UPDATE adresse_intervenant SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE chemin_pedagogique SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE affectation_recherche SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE element_discipline SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE element_taux_regimes SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE TYPE_INTERVENTION_EP SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE TYPE_MODULATEUR_EP SET source_code = source_code || '_2014' WHERE source_code IS NOT NULL;
UPDATE intervenant_exterieur SET source_code = id;
UPDATE intervenant_permanent SET source_code = id;


update effectifs set annee_id = to_number( substr( source_code, 1, 4) ), source_code = substr( source_code, 6 );

/
BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END;
/

DROP SEQUENCE RESSOURCE_ID_SEQ;
DROP SEQUENCE ROLE_UTILISATEUR_ID_SEQ;
DROP SEQUENCE ROLE_UTILISATEUR_LINKER_ID_SEQ;
DROP SEQUENCE TYPE_ROLE_ID_SEQ;
DROP SEQUENCE TYPE_ROLE_PRIVILEGE_ID_SEQ;
DROP SEQUENCE TYPE_ROLE_STRUCTURE_ID_SEQ;
DROP SEQUENCE UNICAEN_EFFECTIFS_POUR__ID_SEQ;
DROP SEQUENCE WF_ETAPE_TO_ETAPE_ID_SEQ;

DROP INDEX ROLE_UTILISATEUR_ID_UN;
DROP INDEX MV_DISCIPLINE_PK;
DROP INDEX MV_INTERVENANT_EXTERIEUR_PK;
DROP INDEX TYPE_ROLE_STRUCTURE_PK;
DROP INDEX MV_VOLUME_HORAIRE_ENS_PK;
DROP INDEX MV_INTERVENANT_PERMANENT_PK;
DROP INDEX WF_ETAPE_TO_ETAPE_PK;
DROP INDEX MV_HARP_IND_DER_STRUCT_PK;
DROP INDEX MV_HARP_INDIVIDU_STATUT_PK;
DROP INDEX MV_ELEMENT_DISCIPLINE_PK;
DROP INDEX TYPE_ROLE_CODE_UN;
DROP INDEX VHE_SOURCE_UN;
DROP INDEX DROIT_PK;
DROP INDEX FORMULE_RESULTAT_MAJ_PK;
DROP INDEX ROLE_UTILISATEUR_PK;
DROP INDEX MV_ROLE_PK;
DROP INDEX MV_ELEMENT_PORTEUR_PORTE_PK;
DROP INDEX TYPE_ROLE_PK;
DROP INDEX RESSOURCE__UN;
DROP INDEX ROLE_UTILISATEUR_LINKER_PK;
DROP INDEX RESSOURCE_PK;
DROP INDEX MV_HARP_INDIVIDU_BANQUE_PK;

DROP table ressource;
DROP table ROLE_UTILISATEUR;
DROP table ROLE_UTILISATEUR_LINKER;
DROP table TYPE_ROLE;
DROP table TYPE_ROLE_PRIVILEGE;
DROP table TYPE_ROLE_STRUCTURE;
DROP table WF_ETAPE_TO_ETAPE;

DROP VIEW SRC_DISCIPLINE;
DROP VIEW SRC_ELEMENT_DISCIPLINE;
DROP VIEW SRC_ELEMENT_PORTEUR_PORTE;
DROP VIEW SRC_ROLE;
DROP VIEW SRC_VOLUME_HORAIRE_ENS;
DROP VIEW V_BERTRAND;
DROP VIEW V_DIFF_DISCIPLINE;
DROP VIEW V_DIFF_ELEMENT_DISCIPLINE;
DROP VIEW V_DIFF_ROLE;
DROP VIEW V_DIFF_VOLUME_HORAIRE_ENS;
DROP VIEW V_TMP_WF;

DROP MATERIALIZED VIEW MV_DISCIPLINE;
DROP MATERIALIZED VIEW MV_ELEMENT_DISCIPLINE;
DROP MATERIALIZED VIEW MV_ELEMENT_PORTEUR_PORTE;
DROP MATERIALIZED VIEW MV_ELEMENT_TAUX_REGIMES_X;
DROP MATERIALIZED VIEW MV_HARP_IND_DER_STRUCT;
DROP MATERIALIZED VIEW MV_HARP_INDIVIDU_BANQUE;
DROP MATERIALIZED VIEW MV_HARP_INDIVIDU_STATUT;
DROP MATERIALIZED VIEW MV_INTERVENANT_EXTERIEUR;
DROP MATERIALIZED VIEW MV_INTERVENANT_PERMANENT;
DROP MATERIALIZED VIEW MV_ROLE;
DROP MATERIALIZED VIEW MV_VOLUME_HORAIRE_ENS;

DROP TRIGGER F_RESULTAT_R;
DROP TRIGGER F_RESULTAT_SERVICE_R;
DROP TRIGGER F_RESULTAT_SERVICE_REF_R;
DROP TRIGGER F_RESULTAT_VH_R;
DROP TRIGGER F_RESULTAT_VH_REF_R;
DROP TRIGGER WF_TRG_INTERV_DOSSIER;
DROP TRIGGER WF_TRG_INTERV_DOSSIER_S;


DROP procedure PURGER_INTERVENANT;






BEGIN OSE_FORMULE.CALCULER_TOUT; END;
/