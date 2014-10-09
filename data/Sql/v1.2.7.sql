-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/


CREATE TABLE ETAT_VOLUME_HORAIRE 
(
  ID NUMBER(*, 0) NOT NULL 
, CODE VARCHAR2(30 CHAR) NOT NULL 
, LIBELLE VARCHAR2(80 CHAR) NOT NULL 
, HISTO_CREATION DATE DEFAULT SYSDATE NOT NULL 
, HISTO_CREATEUR_ID NUMBER(*, 0) NOT NULL 
, HISTO_MODIFICATION DATE DEFAULT SYSDATE NOT NULL 
, HISTO_MODIFICATEUR_ID NUMBER(*, 0) NOT NULL 
, HISTO_DESTRUCTION DATE 
, HISTO_DESTRUCTEUR_ID NUMBER(*, 0) 
, CONSTRAINT ETAT_VOLUME_HORAIRE_PK PRIMARY KEY (ID)
);

ALTER TABLE ETAT_VOLUME_HORAIRE ADD CONSTRAINT ETAT_VOLUME_HORAIRE__UN UNIQUE ( CODE , HISTO_DESTRUCTION ) ENABLE;
ALTER TABLE ETAT_VOLUME_HORAIRE ADD CONSTRAINT ETAT_VOLUME_HORAIRE_HCFK FOREIGN KEY ( HISTO_CREATEUR_ID ) REFERENCES UTILISATEUR (ID) ENABLE;
ALTER TABLE ETAT_VOLUME_HORAIRE ADD CONSTRAINT ETAT_VOLUME_HORAIRE_HDFK FOREIGN KEY ( HISTO_DESTRUCTEUR_ID ) REFERENCES UTILISATEUR (ID) ENABLE;
ALTER TABLE ETAT_VOLUME_HORAIRE ADD CONSTRAINT ETAT_VOLUME_HORAIRE_HMFK FOREIGN KEY ( HISTO_MODIFICATEUR_ID ) REFERENCES UTILISATEUR (ID) ENABLE;
CREATE SEQUENCE ETAT_VOLUME_HORAIRE_ID_SEQ INCREMENT BY 1 MAXVALUE 9999999999999999999999999999 MINVALUE 1 NOCACHE;     



CREATE MATERIALIZED VIEW OSE.MV_HARP_IND_DER_STRUCT 
BUILD IMMEDIATE USING INDEX REFRESH COMPLETE ON DEMAND AS
SELECT
  no_individu,
  CASE date_depart WHEN to_date('01/01/1000','DD/MM/YYYY') THEN NULL else date_depart END date_depart,
  pbs_divers__cicg.c_structure_globale@harpprod(no_individu, CASE date_depart WHEN to_date('01/01/1000','DD/MM/YYYY') THEN SYSDATE else date_depart END ) c_structure
FROM
(
SELECT DISTINCT
  i.no_individu,
  MAX(greatest(
    CASE WHEN aa.no_seq_affectation IS NOT NULL THEN nvl(aa.d_fin_affectation,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
    CASE WHEN ar.no_seq_affe_rech IS NOT NULL THEN nvl(ar.d_fin_affe_rech,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
    CASE WHEN ufe.no_individu IS NOT NULL THEN nvl(ufe.fin,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END,
    CASE WHEN cc.no_seq_chercheur IS NOT NULL THEN nvl(cc.d_fin_str_trav,SYSDATE) ELSE to_date('01/01/1000','DD/MM/YYYY') END
  )) over(partition by i.no_individu)
   date_depart
FROM
  individu@harpprod i
  LEFT JOIN ucbn_flag_enseignant@harpprod ufe ON ufe.no_individu = i.no_individu
  LEFT JOIN chercheur@harpprod cc ON cc.no_individu = i.no_individu
  LEFT JOIN affectation@harpprod aa ON aa.no_dossier_pers = i.no_individu
  LEFT JOIN affectation_recherche@harpprod ar ON ar.no_dossier_pers = i.no_individu

) tmp1;


CREATE MATERIALIZED VIEW OSE.MV_HARP_INDIVIDU_BANQUE 
BUILD IMMEDIATE USING INDEX REFRESH COMPLETE ON DEMAND AS
WITH comptes AS (SELECT
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
SELECT no_individu, iban, bic FROM comptes WHERE rank_compte = nombre_comptes;


CREATE MATERIALIZED VIEW OSE.MV_HARP_INDIVIDU_STATUT
BUILD IMMEDIATE USING INDEX REFRESH COMPLETE ON DEMAND AS
SELECT
  no_individu, 
  statut,
  ti.code type_intervenant
FROM
(
SELECT
  i.no_individu,
  CASE
    WHEN NVL(c.ordre,99999) > NVL(tp.ordre,99999) THEN COALESCE(tp.statut, c.statut, 'AUTRES')
    WHEN NVL(c.ordre,99999) <= NVL(tp.ordre,99999) THEN COALESCE(c.statut, tp.statut, 'AUTRES')
  END statut
FROM
  individu@harpprod i
  LEFT JOIN (SELECT DISTINCT
      ct.no_dossier_pers no_dossier_pers,
      si.source_code statut,
      si.ordre,
      min(si.ordre) over(partition BY ct.no_dossier_pers) AS min_ordre
    FROM
      contrat_travail@harpprod ct
      JOIN contrat_avenant@harpprod ca ON ca.no_dossier_pers = ct.no_dossier_pers AND ca.no_contrat_travail = ct.no_contrat_travail
      JOIN statut_intervenant si ON si.source_code = CASE 
        WHEN ct.c_type_contrat_trav IN ('MC','MA')                THEN 'ASS_MI_TPS'
        WHEN ct.c_type_contrat_trav IN ('AT')                     THEN 'ATER'
        WHEN ct.c_type_contrat_trav IN ('AX')                     THEN 'ATER_MI_TPS'
        WHEN ct.c_type_contrat_trav IN ('DO')                     THEN 'DOCTOR'
        WHEN ct.c_type_contrat_trav IN ('GI','PN')                THEN 'ENS_CONTRACT'
        WHEN ct.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
        WHEN ct.c_type_contrat_trav IN ('MB')                     THEN 'MAITRE_LANG'
        WHEN ct.c_type_contrat_trav IN ('C3','CA','CB','CD','HA','HS','S3','SX','SW','SY','CS','SZ','VA') THEN 'BIATSS'
        WHEN ct.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET','NF') THEN 'NON_AUTORISE'
                                                                  ELSE 'AUTRES'
      END
    WHERE
      SYSDATE BETWEEN ca.d_deb_contrat_trav AND NVL(ca.d_fin_contrat_trav,SYSDATE)
  ) c ON c.no_dossier_pers = i.no_individu AND c.ordre = c.min_ordre
  LEFT JOIN (SELECT DISTINCT
      a.no_dossier_pers,
      si.source_code statut,
      si.ordre,
      min(si.ordre) over(partition BY a.no_dossier_pers) AS min_ordre
    FROM
      affectation@harpprod a
      JOIN carriere@harpprod c ON  c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
      JOIN statut_intervenant si ON si.source_code = CASE 
        WHEN c.c_type_population IN ('DA','OA')                     THEN 'ENS_2ND_DEG'
        WHEN c.c_type_population IN ('SA')                          THEN 'ENS_CH'
        WHEN c.c_type_population IN ('AA','AC','BA','IA','MA')      THEN 'BIATSS'
        WHEN c.c_type_population IN ('MG','SB','DC')                THEN 'NON_AUTORISE'
                                                                    ELSE 'AUTRES'
      END
    WHERE
      (SYSDATE BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,SYSDATE))
  ) tp ON tp.no_dossier_pers = i.no_individu AND tp.ordre = tp.min_ordre
  
) tmp
JOIN statut_intervenant si ON si.source_code = tmp.statut
JOIN type_intervenant ti ON ti.id = si.type_intervenant_id;


DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT";


  CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT" ("CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "Z_TYPE_ID", "Z_STATUT_ID", "Z_STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING TRUSTED CONSTRAINTS DISABLE QUERY REWRITE
  AS SELECT 
  ose_import.get_civilite_id(CASE individu.c_civilite 
    WHEN 'M.' THEN 'M.' ELSE 'Mme'
  END)                                            civilite_id,
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
  NVL(INDIVIDU_E_MAIL.NO_E_MAIL,UCBN_LDAP.hid2mail(individu.no_individu)) EMAIL,
  his.type_intervenant                            z_type_id,
  his.statut                                      z_statut_id,
  NVL(istr.c_structure, 'UNIV')                   z_structure_id,
  ose_import.get_source_id('Harpege')             source_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999')) source_code,
--  null                                            prime_excellence_scientifique,
  code_insee.no_insee                             numero_insee,
  TO_CHAR(code_insee.cle_insee)                   numero_insee_cle,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END numero_insee_provisoire,
  ib.iban iban,
  ib.bic bic
FROM
  individu@harpprod                     individu
  JOIN MV_HARP_IND_DER_STRUCT istr ON (istr.no_individu = individu.no_individu)
  JOIN mv_harp_individu_statut his ON his.no_individu = individu.no_individu
  LEFT JOIN pays@harpprod               pays_naissance ON (pays_naissance.c_pays = individu.c_pays_naissance)
  LEFT JOIN departement@harpprod        departement ON (departement.c_departement = individu.c_dept_naissance)
  LEFT JOIN pays@harpprod               pays_nationalite ON (pays_nationalite.c_pays = individu.c_pays_nationnalite)
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail ON (individu_e_mail.no_individu = individu.no_individu)
  LEFT JOIN individu_telephone@harpprod individu_telephone ON (individu_telephone.no_individu = individu.no_individu AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O')
  LEFT JOIN code_insee@harpprod         code_insee ON (code_insee.no_dossier_pers = individu.no_individu)
  LEFT JOIN mv_harp_individu_banque      ib ON (ib.no_individu = individu.no_individu);


DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT_EXTERIEUR";


  CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT_EXTERIEUR" ("SITUATION_FAMILIALE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING ENFORCED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH validite ( no_individu, debut, fin ) AS (
  SELECT
    no_individu, MIN( date_debut ) date_debut, MAX( date_fin ) date_fin
  FROM (
      SELECT no_individu, ch.d_deb_str_trav date_debut, ch.d_fin_str_trav date_fin FROM chercheur@harpprod ch
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN ch.d_deb_str_trav AND COALESCE(ch.d_fin_str_trav,ose_import.get_date_obs)
    UNION
      SELECT a.no_dossier_pers, a.d_deb_affectation date_debut, a.d_fin_affectation date_fin FROM affectation@harpprod a
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,ose_import.get_date_obs)
    UNION
      SELECT fe.NO_INDIVIDU, fe.DEBUT date_debut, fe.FIN date_fin FROM ucbn_flag_enseignant@harpprod fe
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN fe.DEBUT AND COALESCE(fe.FIN,ose_import.get_date_obs)
  )
  GROUP BY
    no_individu
) 
SELECT
--  null                                       type_intervenant_exterieur_id,
  s.id                                       situation_familiale_id,
--  null                                       regime_secu_id,
--  null                                       type_poste_id,
  ose_import.get_source_id('Harpege')        source_id,
  ltrim(TO_CHAR(i.no_individu,'99999999'))   source_code,
  nvl(validite.debut,TRUNC(SYSDATE))         validite_debut,
  validite.fin                               validite_fin
FROM
  individu@harpprod i
  JOIN MV_HARP_INDIVIDU_STATUT his ON his.no_individu = ltrim(TO_CHAR(i.no_individu,'99999999'))
  LEFT JOIN PERSONNEL@harpprod p ON (p.no_dossier_pers = i.no_individu)
  LEFT JOIN SITUATION_FAMILIALE s on (s.code = p.C_SITUATION_FAMILLE)
  LEFT JOIN validite ON (validite.no_individu = i.no_individu)
WHERE
  'E' = his.type_intervenant;


DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT_PERMANENT";


  CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT_PERMANENT" ("Z_CORPS_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN")
  ORGANIZATION HEAP PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "OSE_TS" 
  BUILD IMMEDIATE
  USING INDEX 
  REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT
  USING TRUSTED CONSTRAINTS DISABLE QUERY REWRITE
  AS WITH validite ( no_individu, debut, fin ) AS (
  SELECT
    no_individu, MIN( date_debut ) date_debut, MAX( date_fin ) date_fin
  FROM (
      SELECT no_individu, ch.d_deb_str_trav date_debut, ch.d_fin_str_trav date_fin FROM chercheur@harpprod ch
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN ch.d_deb_str_trav AND COALESCE(ch.d_fin_str_trav,ose_import.get_date_obs)
    UNION
      SELECT a.no_dossier_pers, a.d_deb_affectation date_debut, a.d_fin_affectation date_fin FROM affectation@harpprod a
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,ose_import.get_date_obs)
    UNION
      SELECT fe.NO_INDIVIDU, fe.DEBUT date_debut, fe.FIN date_fin FROM ucbn_flag_enseignant@harpprod fe
      WHERE ose_import.get_date_obs IS NULL OR ose_import.get_date_obs BETWEEN fe.DEBUT AND COALESCE(fe.FIN,ose_import.get_date_obs)
  )
  GROUP BY
    no_individu
)
SELECT  
  pbs_divers__cicg.c_corps@harpprod(i.no_individu, ose_import.get_date_obs) z_corps_id,
  --null section_cnu_id
  ose_import.get_source_id('Harpege')           source_id,
  ltrim(TO_CHAR(i.no_individu,'99999999'))      source_code,
  NVL(validite.debut,to_date('01/01/1950','DD/MM/YYYY'))              validite_debut,
  validite.fin                                  validite_fin
FROM
  individu@harpprod i
  JOIN MV_HARP_INDIVIDU_STATUT his ON his.no_individu = ltrim(TO_CHAR(i.no_individu,'99999999'))
  LEFT JOIN validite ON (validite.no_individu = i.no_individu)
WHERE
  'P' = his.type_intervenant;

-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

DROP VIEW OSE.V_HARP_IND_DER_STRUCT;
DROP VIEW OSE.V_HARP_INDIVIDU_STATUT;
DROP VIEW OSE.V_HARP_INDIVIDU_BANQUE;
DROP VIEW OSE.V_CONCAT_ELEMENT_INFOS;
DROP VIEW OSE.V_SERVICE_HEURES;
DROP VIEW OSE.V_STRUCTURE_ENSEIGNEMENT;
DROP VIEW OSE.V_DROP_BCP_TABLES;
DROP VIEW OSE.V_STRUCTURES_HIERARCHIQUES;
DROP VIEW OSE.V_ROLE_PERSONNEL;
drop function "OSE"."CHEMIN_STRUCTURE";
drop function "OSE"."NIVEAU_STRUCTURE";
DROP SEQUENCE OSE.TYPE_ROLE_PHP_ROLE_ID_SEQ;

BEGIN DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"'); END; 
/