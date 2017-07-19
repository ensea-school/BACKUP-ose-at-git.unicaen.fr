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