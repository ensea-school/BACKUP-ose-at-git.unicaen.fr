--------------------------------------------------------
--  Fichier créé - vendredi-août-29-2014   
--------------------------------------------------------


ALTER TABLE "OSE"."TYPE_AGREMENT_STATUT" MODIFY ("PREMIER_RECRUTEMENT" NULL);
ALTER TABLE "OSE"."INTERVENANT_PERMANENT" MODIFY ("CORPS_ID" NULL);
ALTER TABLE "OSE"."AGREMENT" DROP CONSTRAINT "AGREMENT__UN";
ALTER TABLE "OSE"."AGREMENT" ADD CONSTRAINT "AGREMENT__UN" UNIQUE ("TYPE_AGREMENT_ID","INTERVENANT_ID","ANNEE_ID","STRUCTURE_ID") ENABLE;
ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" DROP CONSTRAINT "AFFECTATION_IS_UN";
ALTER TABLE "OSE"."AFFECTATION_RECHERCHE" ADD CONSTRAINT "AFFECTATION_IS_UN" UNIQUE ("INTERVENANT_ID","STRUCTURE_ID","HISTO_DESTRUCTION") ENABLE;

DROP INDEX "OSE"."AGREMENT__UN";
  CREATE UNIQUE INDEX "OSE"."AGREMENT__UN" ON "OSE"."AGREMENT" ("TYPE_AGREMENT_ID","INTERVENANT_ID","ANNEE_ID","STRUCTURE_ID");
---------------------------
--Modifié INDEX
--AFFECTATION_IS_UN
---------------------------
DROP INDEX "OSE"."AFFECTATION_IS_UN";
  CREATE UNIQUE INDEX "OSE"."AFFECTATION_IS_UN" ON "OSE"."AFFECTATION_RECHERCHE" ("INTERVENANT_ID","STRUCTURE_ID","HISTO_DESTRUCTION");

---------------------------
--Modifié VIEW
--V_HARP_INDIVIDU_STATUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_HARP_INDIVIDU_STATUT" 
 ( "NO_INDIVIDU", "STATUT", "TYPE_INTERVENANT"
  )  AS 
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
      JOIN statut_intervenant si ON si.source_code = ose_harpege.get_intervenant_statut('CT_' || ct.c_type_contrat_trav)
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
      JOIN statut_intervenant si ON si.source_code = ose_harpege.get_intervenant_statut('TP_' || c.c_type_population)
    WHERE
      (SYSDATE BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,SYSDATE))
  ) tp ON tp.no_dossier_pers = i.no_individu AND tp.ordre = tp.min_ordre
  
) tmp
JOIN statut_intervenant si ON si.source_code = tmp.statut
JOIN type_intervenant ti ON ti.id = si.type_intervenant_id;


/

---------------------------
--Modifié PACKAGE BODY
--OSE_HARPEGE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_HARPEGE" AS

  FUNCTION get_intervenant_type_code(id Numeric) RETURN varchar2 IS
    ok Number;
  BEGIN
  SELECT
    count(*) INTO ok
  FROM
    affectation@harpprod a
    JOIN carriere@harpprod c ON  c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
    JOIN type_population@harpprod tp ON tp.c_type_population = c.c_type_population
  WHERE
    c.no_dossier_pers = id
    AND tp.tem_enseignant    = 'O' 
    AND (OSE_IMPORT.GET_DATE_OBS BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,OSE_IMPORT.GET_DATE_OBS));

    if 1 = ok THEN  return 'P'; END IF;
    return 'E'; 
  END get_intervenant_type_code;


  FUNCTION get_intervenant_type_popu( no_individu Varchar2) RETURN varchar2 IS
    resultat Varchar2(50);
  BEGIN
    SELECT c.c_type_population INTO resultat
    FROM
      affectation@harpprod a
      JOIN carriere@harpprod c ON  c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
    WHERE
      (SYSDATE BETWEEN a.d_deb_affectation AND COALESCE(a.d_fin_affectation,SYSDATE))
      AND c.no_dossier_pers = no_individu
      AND ROWNUM = 1;
    RETURN resultat;
  END get_intervenant_type_popu;


  FUNCTION get_intervenant_statut( type_population Varchar2) RETURN varchar2 IS
  BEGIN
    RETURN CASE 
      WHEN type_population IN ('TP_DA')                         THEN 'ENS_2ND_DEG'
      WHEN type_population IN ('TP_SA')                         THEN 'ENS_CH'
      WHEN type_population IN ('CT_MC', 'CT_MA')                THEN 'ASS_MI_TPS'
      WHEN type_population IN ('CT_AT')                         THEN 'ATER'
      WHEN type_population IN ('CT_AX')                         THEN 'ATER_MI_TPS'
      WHEN type_population IN ('CT_DO')                         THEN 'DOCTOR'
      WHEN type_population IN ('CT_GI','PN')                    THEN 'ENS_CONTRACT'
      WHEN type_population IN ('CT_LT','CT_LB')                 THEN 'LECTEUR'
      WHEN type_population IN ('CT_MB')                         THEN 'MAITRE_LANG'
      WHEN type_population IN ('TP_AA','TP_AC','TP_BA','CT_C3','CT_CA','CT_CB','CT_CD','CT_HA','CT_HS','TP_IA','TP_MA','TP_OA','CT_S3','CT_SX') THEN 'BIATSS'
      WHEN type_population IN ('TP_MG','TP_SB','TP_DC','CT_CU','CT_PN','CT_AH','CT_CG','CT_MM','CT_PM','CT_IN','CT_DN','CT_ET','CT_NF') THEN 'NON_AUTORISE'
                                                                ELSE 'AUTRES'
    END;
  END get_intervenant_statut;

  FUNCTION get_intervenant_type( statut Varchar2) RETURN varchar2 IS
  BEGIN
    RETURN CASE
      WHEN statut IN ('ENS_2ND_DEG','ENS_CH','ASS_MI_TPS','ATER','ATER_MI_TPS','DOCTOR','ENS_CONTRACT','LECTEUR','MAITRE_LANG') THEN 'P'
      ELSE 'E'
    END;
  END get_intervenant_type;

END OSE_HARPEGE;
/




---------------------------
--Modifié MATERIALIZED VIEW
--MV_INTERVENANT_PERMANENT
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT_PERMANENT";
CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT_PERMANENT" ("Z_CORPS_ID","SOURCE_ID","SOURCE_CODE","VALIDITE_DEBUT","VALIDITE_FIN") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING TRUSTED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  WITH validite ( no_individu, debut, fin ) AS (
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
  NVL(validite.debut,'01/01/1950')              validite_debut,
  validite.fin                                  validite_fin
FROM
  individu@harpprod i
  JOIN V_HARP_INDIVIDU_STATUT his ON his.no_individu = ltrim(TO_CHAR(i.no_individu,'99999999'))
  LEFT JOIN validite ON (validite.no_individu = i.no_individu)
WHERE
  'P' = his.type_intervenant;
  
---------------------------
--Modifié MATERIALIZED VIEW
--MV_INTERVENANT_EXTERIEUR
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT_EXTERIEUR";
CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT_EXTERIEUR" ("SITUATION_FAMILIALE_ID","SOURCE_ID","SOURCE_CODE","VALIDITE_DEBUT","VALIDITE_FIN") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  WITH validite ( no_individu, debut, fin ) AS (
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
  JOIN V_HARP_INDIVIDU_STATUT his ON his.no_individu = ltrim(TO_CHAR(i.no_individu,'99999999'))
  LEFT JOIN PERSONNEL@harpprod p ON (p.no_dossier_pers = i.no_individu)
  LEFT JOIN SITUATION_FAMILIALE s on (s.code = p.C_SITUATION_FAMILLE)
  LEFT JOIN validite ON (validite.no_individu = i.no_individu)
WHERE
  'E' = his.type_intervenant;
  
  
---------------------------
--Modifié MATERIALIZED VIEW
--MV_INTERVENANT
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT";
CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT" ("CIVILITE_ID","NOM_USUEL","PRENOM","NOM_PATRONYMIQUE","DATE_NAISSANCE","PAYS_NAISSANCE_CODE_INSEE","PAYS_NAISSANCE_LIBELLE","DEP_NAISSANCE_CODE_INSEE","DEP_NAISSANCE_LIBELLE","VILLE_NAISSANCE_CODE_INSEE","VILLE_NAISSANCE_LIBELLE","PAYS_NATIONALITE_CODE_INSEE","PAYS_NATIONALITE_LIBELLE","TEL_PRO","TEL_MOBILE","EMAIL","Z_TYPE_ID","Z_STATUT_ID","Z_STRUCTURE_ID","SOURCE_ID","SOURCE_CODE","NUMERO_INSEE","NUMERO_INSEE_CLE","NUMERO_INSEE_PROVISOIRE","IBAN","BIC") 
  BUILD IMMEDIATE
  USING INDEX REFRESH COMPLETE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING TRUSTED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT 
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
  INDIVIDU_E_MAIL.NO_E_MAIL                       EMAIL,
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
  JOIN V_HARP_IND_DER_STRUCT istr ON (istr.no_individu = individu.no_individu)
  JOIN v_harp_individu_statut his ON his.no_individu = individu.no_individu
  LEFT JOIN pays@harpprod               pays_naissance ON (pays_naissance.c_pays = individu.c_pays_naissance)
  LEFT JOIN departement@harpprod        departement ON (departement.c_departement = individu.c_dept_naissance)
  LEFT JOIN pays@harpprod               pays_nationalite ON (pays_nationalite.c_pays = individu.c_pays_nationnalite)
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail ON (individu_e_mail.no_individu = individu.no_individu)
  LEFT JOIN individu_telephone@harpprod individu_telephone ON (individu_telephone.no_individu = individu.no_individu AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O')
  LEFT JOIN code_insee@harpprod         code_insee ON (code_insee.no_dossier_pers = individu.no_individu)
  LEFT JOIN v_harp_individu_banque      ib ON (ib.no_individu = individu.no_individu);




---------------------------
--Modifié VIEW
--SRC_INTERVENANT_PERMANENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT_PERMANENT" 
 ( "ID", "CORPS_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN"
  )  AS 
  select
  i.id,
  c.id as corps_id,
  IP.SOURCE_ID,
  IP.SOURCE_CODE,
  IP.VALIDITE_DEBUT,
  IP.VALIDITE_FIN
from
  mv_intervenant_permanent ip
  JOIN intervenant i ON (i.source_code = ip.source_code)
  LEFT JOIN corps c ON (c.source_code = IP.Z_CORPS_ID);
---------------------------
--Modifié VIEW
--SRC_INTERVENANT_EXTERIEUR
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT_EXTERIEUR" 
 ( "ID", "SITUATION_FAMILIALE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN"
  )  AS 
  select
  i.id,
  ie."SITUATION_FAMILIALE_ID",ie."SOURCE_ID",ie."SOURCE_CODE",ie."VALIDITE_DEBUT",ie."VALIDITE_FIN"
from
  mv_intervenant_exterieur ie
  JOIN intervenant i ON (i.source_code = ie.source_code);
---------------------------
--Modifié VIEW
--SRC_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT" 
 ( "ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "TYPE_ID", "STATUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC", "LAST_SYNC_STATUT_ID"
  )  AS 
  SELECT
  null id,
  i."CIVILITE_ID",
  i."NOM_USUEL",
  i."PRENOM",
  i."NOM_PATRONYMIQUE",
  i."DATE_NAISSANCE",
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
  TI.ID type_id,
  si.id statut_id,
  s.id structure_id,
  i."SOURCE_ID",
  i."SOURCE_CODE",
  i."NUMERO_INSEE",
  i."NUMERO_INSEE_CLE",
  i."NUMERO_INSEE_PROVISOIRE",
  i."IBAN",
  i."BIC",
  si.id last_sync_statut_id
FROM
  mv_intervenant i
  LEFT JOIN statut_intervenant si ON si.source_code = i.z_statut_id
  LEFT JOIN type_intervenant ti ON ti.code = I.Z_TYPE_ID
  LEFT JOIN structure s ON s.source_code = i.z_structure_id;



---------------------------
--Modifié VIEW
--V_DIFF_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "BIC", "CIVILITE_ID", "DATE_NAISSANCE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "EMAIL", "IBAN", "LAST_SYNC_STATUT_ID", "NOM_PATRONYMIQUE", "NOM_USUEL", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "PRENOM", "STATUT_ID", "STRUCTURE_ID", "TEL_MOBILE", "TEL_PRO", "TYPE_ID", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "U_BIC", "U_CIVILITE_ID", "U_DATE_NAISSANCE", "U_DEP_NAISSANCE_CODE_INSEE", "U_DEP_NAISSANCE_LIBELLE", "U_EMAIL", "U_IBAN", "U_LAST_SYNC_STATUT_ID", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_NUMERO_INSEE", "U_NUMERO_INSEE_CLE", "U_NUMERO_INSEE_PROVISOIRE", "U_PAYS_NAISSANCE_CODE_INSEE", "U_PAYS_NAISSANCE_LIBELLE", "U_PAYS_NATIONALITE_CODE_INSEE", "U_PAYS_NATIONALITE_LIBELLE", "U_PRENOM", "U_STATUT_ID", "U_STRUCTURE_ID", "U_TEL_MOBILE", "U_TEL_PRO", "U_TYPE_ID", "U_VILLE_NAISSANCE_CODE_INSEE", "U_VILLE_NAISSANCE_LIBELLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."BIC",diff."CIVILITE_ID",diff."DATE_NAISSANCE",diff."DEP_NAISSANCE_CODE_INSEE",diff."DEP_NAISSANCE_LIBELLE",diff."EMAIL",diff."IBAN",diff."LAST_SYNC_STATUT_ID",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."NUMERO_INSEE",diff."NUMERO_INSEE_CLE",diff."NUMERO_INSEE_PROVISOIRE",diff."PAYS_NAISSANCE_CODE_INSEE",diff."PAYS_NAISSANCE_LIBELLE",diff."PAYS_NATIONALITE_CODE_INSEE",diff."PAYS_NATIONALITE_LIBELLE",diff."PRENOM",diff."STATUT_ID",diff."STRUCTURE_ID",diff."TEL_MOBILE",diff."TEL_PRO",diff."TYPE_ID",diff."VILLE_NAISSANCE_CODE_INSEE",diff."VILLE_NAISSANCE_LIBELLE",diff."U_BIC",diff."U_CIVILITE_ID",diff."U_DATE_NAISSANCE",diff."U_DEP_NAISSANCE_CODE_INSEE",diff."U_DEP_NAISSANCE_LIBELLE",diff."U_EMAIL",diff."U_IBAN",diff."U_LAST_SYNC_STATUT_ID",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_NUMERO_INSEE",diff."U_NUMERO_INSEE_CLE",diff."U_NUMERO_INSEE_PROVISOIRE",diff."U_PAYS_NAISSANCE_CODE_INSEE",diff."U_PAYS_NAISSANCE_LIBELLE",diff."U_PAYS_NATIONALITE_CODE_INSEE",diff."U_PAYS_NATIONALITE_LIBELLE",diff."U_PRENOM",diff."U_STATUT_ID",diff."U_STRUCTURE_ID",diff."U_TEL_MOBILE",diff."U_TEL_PRO",diff."U_TYPE_ID",diff."U_VILLE_NAISSANCE_CODE_INSEE",diff."U_VILLE_NAISSANCE_LIBELLE" from (SELECT
  COALESCE( D.id, S.id ) id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.BIC ELSE S.BIC END BIC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CIVILITE_ID ELSE S.CIVILITE_ID END CIVILITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DATE_NAISSANCE ELSE S.DATE_NAISSANCE END DATE_NAISSANCE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_CODE_INSEE ELSE S.DEP_NAISSANCE_CODE_INSEE END DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_LIBELLE ELSE S.DEP_NAISSANCE_LIBELLE END DEP_NAISSANCE_LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.IBAN ELSE S.IBAN END IBAN,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LAST_SYNC_STATUT_ID ELSE S.LAST_SYNC_STATUT_ID END LAST_SYNC_STATUT_ID,
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
    CASE WHEN D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL) THEN 1 ELSE 0 END U_BIC,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL) THEN 1 ELSE 0 END U_DATE_NAISSANCE,
    CASE WHEN D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_LIBELLE,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
    CASE WHEN D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL) THEN 1 ELSE 0 END U_IBAN,
    CASE WHEN D.LAST_SYNC_STATUT_ID <> S.LAST_SYNC_STATUT_ID OR (D.LAST_SYNC_STATUT_ID IS NULL AND S.LAST_SYNC_STATUT_ID IS NOT NULL) OR (D.LAST_SYNC_STATUT_ID IS NOT NULL AND S.LAST_SYNC_STATUT_ID IS NULL) THEN 1 ELSE 0 END U_LAST_SYNC_STATUT_ID,
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
  FULL JOIN SRC_INTERVENANT S ON (S.source_id = D.source_id AND S.source_code = D.source_code)
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL)
  OR D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL)
  OR D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL)
  OR D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL)
  OR D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
  OR D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL)
  OR D.LAST_SYNC_STATUT_ID <> S.LAST_SYNC_STATUT_ID OR (D.LAST_SYNC_STATUT_ID IS NULL AND S.LAST_SYNC_STATUT_ID IS NOT NULL) OR (D.LAST_SYNC_STATUT_ID IS NOT NULL AND S.LAST_SYNC_STATUT_ID IS NULL)
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
--Modifié TRIGGER
--SERVICE_HISTO_CK
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."SERVICE_HISTO_CK"
  BEFORE UPDATE ON "OSE"."SERVICE"
  REFERENCING FOR EACH ROW
  DECLARE
  has_validation integer;
BEGIN
  SELECT COUNT(*) INTO has_validation
  FROM
    VALIDATION_VOL_HORAIRE vvh
    JOIN validation v ON v.id = VVH.VALIDATION_ID
    JOIN volume_horaire vh ON vh.id = VVH.VOLUME_HORAIRE_ID
  WHERE
    V.HISTO_DESTRUCTION IS NULL
    AND vh.service_ID = :NEW.ID;

  IF 0 <> has_validation THEN
    raise_application_error(-20101, 'Il est impossible de modifier ou supprimer un service dont des heures ont déjà été validées.');
  END IF;

  -- En cas de mise en historique d'un service
  IF :NEW.histo_destruction IS NOT NULL AND :OLD.histo_destruction IS NULL THEN
    UPDATE VOLUME_HORAIRE SET histo_destruction = :NEW.histo_destruction, histo_destructeur_id = :NEW.histo_destructeur_id WHERE service_id = :NEW.id;
  END IF;
  -- En cas de restauration d'un service, on ne restaure pas les historiques de volumes horaires pour ne pas récussiter d'éventuels volume horaires indésirables car préalablement supprimés
    
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
  structure_ens_id NUMERIC;  
BEGIN
  
  etablissement := OSE_PARAMETRE.GET_ETABLISSEMENT();
  
  IF :NEW.etablissement_id = etablissement AND :NEW.element_pedagogique_id IS NULL THEN
    raise_application_error(-20101, 'Un enseignement doit obligatoirement être renseigné si le service est réalisé en interne.');
  END IF;

  IF OSE_DIVERS.INTERVENANT_EST_PERMANENT(:NEW.intervenant_id) = 0 THEN
    IF :NEW.etablissement_id <> etablissement THEN
      raise_application_error(-20101, 'Les intervenants vacataires n''ont pas la possibilité de renseigner des enseignements pris à l''extérieur.');
    END IF;
  END IF;

  IF :NEW.element_pedagogique_id <> :OLD.element_pedagogique_id THEN
    SELECT structure_id INTO structure_ens_id FROM element_pedagogique WHERE id = :NEW.element_pedagogique_id;
    :NEW.structure_ens_id := structure_ens_id;
  END IF;

  --IF :OLD.id IS NOT NULL AND ( :NEW.etablissement_id <> :OLD.etablissement_id OR :NEW.element_pedagogique_id <> :OLD.element_pedagogique_id ) THEN
    --UPDATE volume_horaire SET histo_destruction = SYSDATE, histo_destructeur_id = :NEW.histo_modificateur_id WHERE service_id = :NEW.id;
  --END IF;

END;
/
---------------------------
--Modifié PACKAGE
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_IMPORT" IS

  v_current_user INTEGER := 1;
  v_date_obs Date := SYSDATE;
 
  FUNCTION get_date_obs RETURN Date;
 
  PROCEDURE set_current_user(p_current_user IN INTEGER);
  FUNCTION get_current_user return INTEGER;

  FUNCTION get_type_intervenant_id( src_code varchar2 ) RETURN Numeric;
  FUNCTION get_civilite_id( src_libelle_court varchar2 ) RETURN Numeric;
  FUNCTION get_source_id( src_code Varchar2 ) return Numeric;

  PROCEDURE SYNC_LOG( message CLOB );
  PROCEDURE SYNC_MVS;
  PROCEDURE SYNC_VOLUME_HORAIRE_ENS;
  PROCEDURE SYNC_TYPE_FORMATION;
  PROCEDURE SYNC_STRUCTURE;
  PROCEDURE SYNC_ROLE;
  PROCEDURE SYNC_PERSONNEL;
  PROCEDURE SYNC_INTERVENANT_PERMANENT;
  PROCEDURE SYNC_INTERVENANT_EXTERIEUR;
  PROCEDURE SYNC_INTERVENANT;
  PROCEDURE SYNC_GROUPE_TYPE_FORMATION;
  PROCEDURE SYNC_ETAPE;
  PROCEDURE SYNC_ETABLISSEMENT;
  PROCEDURE SYNC_ELEMENT_PORTEUR_PORTE;
  PROCEDURE SYNC_ELEMENT_PEDAGOGIQUE;
  PROCEDURE SYNC_ELEMENT_DISCIPLINE;
  PROCEDURE SYNC_DISCIPLINE;
  PROCEDURE SYNC_CORPS;
  PROCEDURE SYNC_CHEMIN_PEDAGOGIQUE;
  PROCEDURE SYNC_AFFECTATION_RECHERCHE;
  PROCEDURE SYNC_ADRESSE_STRUCTURE;
  PROCEDURE SYNC_ADRESSE_INTERVENANT;
  PROCEDURE SYNC_TABLES;
  PROCEDURE SYNCHRONISATION;
  
  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ROLE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_PORTEUR_PORTE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_VOLUME_HORAIRE_ENS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_IMPORT" IS

  FUNCTION get_date_obs RETURN Date IS
  BEGIN
    RETURN v_date_obs;
  END get_date_obs;

  FUNCTION get_current_user RETURN INTEGER IS
  BEGIN
    RETURN v_current_user;
  END get_current_user;
 
 
  PROCEDURE set_current_user (p_current_user INTEGER) is
  BEGIN
    v_current_user := p_current_user;
  END set_current_user;


  FUNCTION get_type_intervenant_id( src_code varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.type_intervenant s WHERE s.code = src_code;
    RETURN res_id;
  END get_type_intervenant_id;


  FUNCTION get_civilite_id( src_libelle_court varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.civilite s WHERE s.libelle_court = src_libelle_court;
    RETURN res_id;
  END get_civilite_id;


  FUNCTION get_source_id( src_code Varchar2 ) RETURN Numeric IS
    res_id Numeric;
  BEGIN
    SELECT s.id INTO res_id FROM ose.source s WHERE s.code = src_code;
    RETURN res_id;
  END get_source_id;

  PROCEDURE SYNC_LOG( message CLOB ) IS
  BEGIN
    INSERT INTO OSE.SYNC_LOG("ID","DATE_SYNC","MESSAGE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message);
  END SYNC_LOG;

  PROCEDURE SYNC_MVS IS
  BEGIN
    -- Mise à jour des vues matérialisées
    DBMS_MVIEW.REFRESH('MV_ETABLISSEMENT', 'C');
    DBMS_MVIEW.REFRESH('MV_STRUCTURE', 'C');
    DBMS_MVIEW.REFRESH('MV_ADRESSE_STRUCTURE', 'C');
    
    DBMS_MVIEW.REFRESH('MV_PERSONNEL', 'C');
    DBMS_MVIEW.REFRESH('MV_ROLE', 'C');
    
    DBMS_MVIEW.REFRESH('MV_CORPS', 'C');
    
    DBMS_MVIEW.REFRESH('MV_INTERVENANT', 'C');
    DBMS_MVIEW.REFRESH('MV_INTERVENANT_EXTERIEUR', 'C');
    DBMS_MVIEW.REFRESH('MV_INTERVENANT_PERMANENT', 'C');
    DBMS_MVIEW.REFRESH('MV_AFFECTATION_RECHERCHE', 'C');
    DBMS_MVIEW.REFRESH('MV_ADRESSE_INTERVENANT', 'C');
    
    DBMS_MVIEW.REFRESH('MV_GROUPE_TYPE_FORMATION', 'C');
    DBMS_MVIEW.REFRESH('MV_TYPE_FORMATION', 'C');
    DBMS_MVIEW.REFRESH('MV_ETAPE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_PEDAGOGIQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_CHEMIN_PEDAGOGIQUE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_PORTEUR_PORTE', 'C');
    DBMS_MVIEW.REFRESH('MV_DISCIPLINE', 'C');
    DBMS_MVIEW.REFRESH('MV_ELEMENT_DISCIPLINE', 'C');
    DBMS_MVIEW.REFRESH('MV_VOLUME_HORAIRE_ENS', 'C');  
  END;

  PROCEDURE SYNC_VOLUME_HORAIRE_ENS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_VOLUME_HORAIRE_ENS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_VOLUME_HORAIRE_ENS;

  PROCEDURE SYNC_ELEMENT_DISCIPLINE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_DISCIPLINE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_DISCIPLINE;

  PROCEDURE SYNC_DISCIPLINE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_DISCIPLINE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_DISCIPLINE;

  PROCEDURE SYNC_ELEMENT_PORTEUR_PORTE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_PORTEUR_PORTE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_PORTEUR_PORTE;

  PROCEDURE SYNC_CHEMIN_PEDAGOGIQUE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_CHEMIN_PEDAGOGIQUE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_CHEMIN_PEDAGOGIQUE;

  PROCEDURE SYNC_ELEMENT_PEDAGOGIQUE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ELEMENT_PEDAGOGIQUE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ELEMENT_PEDAGOGIQUE;

  PROCEDURE SYNC_ETAPE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ETAPE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ETAPE;

  PROCEDURE SYNC_TYPE_FORMATION IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_TYPE_FORMATION();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_TYPE_FORMATION;

  PROCEDURE SYNC_GROUPE_TYPE_FORMATION IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_GROUPE_TYPE_FORMATION();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_GROUPE_TYPE_FORMATION;

  PROCEDURE SYNC_ADRESSE_INTERVENANT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ADRESSE_INTERVENANT('WHERE INTERVENANT_ID IS NOT NULL');
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ADRESSE_INTERVENANT;

  PROCEDURE SYNC_AFFECTATION_RECHERCHE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_AFFECTATION_RECHERCHE('WHERE INTERVENANT_ID IS NOT NULL');
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_AFFECTATION_RECHERCHE;

  PROCEDURE SYNC_ETABLISSEMENT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ETABLISSEMENT();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ETABLISSEMENT;

  PROCEDURE SYNC_STRUCTURE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_STRUCTURE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_STRUCTURE;

  PROCEDURE SYNC_ADRESSE_STRUCTURE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ADRESSE_STRUCTURE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ADRESSE_STRUCTURE;

  PROCEDURE SYNC_PERSONNEL IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_PERSONNEL();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_PERSONNEL;
  
  PROCEDURE SYNC_ROLE IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_ROLE();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_ROLE;  

  PROCEDURE SYNC_CORPS IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_CORPS();
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_CORPS; 

  PROCEDURE SYNC_INTERVENANT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT( -- Met à jour toutes les données sauf le statut, qui sera traité à part
        'WHERE IMPORT_ACTION IN (''delete'',''update'',''undelete'')',
        'STATUT_ID,TYPE_ID,LAST_SYNC_STATUT_ID'
      );
      OSE_IMPORT.MAJ_INTERVENANT( -- importe les statuts synchronisables tout de même, mais qui ne changent pas le type pour autant
        'JOIN intervenant i ON i.source_code = V_DIFF_INTERVENANT.source_code ' 
        || 'JOIN statut_intervenant si ON si.id = V_DIFF_INTERVENANT.statut_id '
        || 'WHERE IMPORT_ACTION IN (''delete'',''update'',''undelete'') AND ( i.statut_id = i.last_sync_statut_id OR si.synchronisable = 0 )'
      );
      
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT; 
  
  PROCEDURE SYNC_INTERVENANT_PERMANENT IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT_PERMANENT(
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT_PERMANENT; 
  
  PROCEDURE SYNC_INTERVENANT_EXTERIEUR IS
  BEGIN
    BEGIN
      OSE_IMPORT.MAJ_INTERVENANT_EXTERIEUR(
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR SOURCE_CODE IN (SELECT SOURCE_CODE FROM "INTERVENANT"))'
      );
    EXCEPTION WHEN OTHERS THEN
      OSE_IMPORT.SYNC_LOG( SQLERRM );
    END;
  END SYNC_INTERVENANT_EXTERIEUR;   

  PROCEDURE SYNC_TABLES IS
  BEGIN
    OSE_IMPORT.SET_CURRENT_USER( OSE_PARAMETRE.GET_OSE_USER() );
 
    SYNC_ETABLISSEMENT;
    SYNC_STRUCTURE;
    SYNC_ADRESSE_STRUCTURE;

    SYNC_PERSONNEL;
    SYNC_ROLE;
    
    SYNC_CORPS;

    SYNC_INTERVENANT;
    SYNC_INTERVENANT_EXTERIEUR;
    SYNC_INTERVENANT_PERMANENT;
    
    SYNC_AFFECTATION_RECHERCHE;
    SYNC_ADRESSE_INTERVENANT;
    SYNC_GROUPE_TYPE_FORMATION;
    SYNC_TYPE_FORMATION;
    SYNC_ETAPE;
    SYNC_ELEMENT_PEDAGOGIQUE;
    SYNC_CHEMIN_PEDAGOGIQUE;
    SYNC_ELEMENT_PORTEUR_PORTE;
    SYNC_DISCIPLINE;
    SYNC_ELEMENT_DISCIPLINE;
    SYNC_VOLUME_HORAIRE_ENS;
  END;

  PROCEDURE SYNCHRONISATION IS
  BEGIN
    SYNC_MVS;
    SYNC_TABLES;
  END SYNCHRONISATION;

  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;

  -- AUTOMATIC GENERATION --

  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_STRUCTURE.* FROM V_DIFF_STRUCTURE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.STRUCTURE
            ( id, ETABLISSEMENT_ID,LIBELLE_COURT,LIBELLE_LONG,NIVEAU,PARENTE_ID,STRUCTURE_NIV2_ID,TYPE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,STRUCTURE_ID_SEQ.NEXTVAL), diff_row.ETABLISSEMENT_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.NIVEAU,diff_row.PARENTE_ID,diff_row.STRUCTURE_NIV2_ID,diff_row.TYPE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ETABLISSEMENT_ID = 1 AND IN_COLUMN_LIST('ETABLISSEMENT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET ETABLISSEMENT_ID = diff_row.ETABLISSEMENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENTE_ID = 1 AND IN_COLUMN_LIST('PARENTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET PARENTE_ID = diff_row.PARENTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_NIV2_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_NIV2_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET STRUCTURE_NIV2_ID = diff_row.STRUCTURE_NIV2_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_STRUCTURE;



  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_PEDAGOGIQUE.* FROM V_DIFF_ELEMENT_PEDAGOGIQUE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_PEDAGOGIQUE
            ( id, ETAPE_ID,FA,FC,FI,LIBELLE,PERIODE_ID,STRUCTURE_ID,TAUX_FOAD, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ETAPE_ID,diff_row.FA,diff_row.FC,diff_row.FI,diff_row.LIBELLE,diff_row.PERIODE_ID,diff_row.STRUCTURE_ID,diff_row.TAUX_FOAD, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_PEDAGOGIQUE;



  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT.* FROM V_DIFF_INTERVENANT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT
            ( id, BIC,CIVILITE_ID,DATE_NAISSANCE,DEP_NAISSANCE_CODE_INSEE,DEP_NAISSANCE_LIBELLE,EMAIL,IBAN,LAST_SYNC_STATUT_ID,NOM_PATRONYMIQUE,NOM_USUEL,NUMERO_INSEE,NUMERO_INSEE_CLE,NUMERO_INSEE_PROVISOIRE,PAYS_NAISSANCE_CODE_INSEE,PAYS_NAISSANCE_LIBELLE,PAYS_NATIONALITE_CODE_INSEE,PAYS_NATIONALITE_LIBELLE,PRENOM,STATUT_ID,STRUCTURE_ID,TEL_MOBILE,TEL_PRO,TYPE_ID,VILLE_NAISSANCE_CODE_INSEE,VILLE_NAISSANCE_LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_ID_SEQ.NEXTVAL), diff_row.BIC,diff_row.CIVILITE_ID,diff_row.DATE_NAISSANCE,diff_row.DEP_NAISSANCE_CODE_INSEE,diff_row.DEP_NAISSANCE_LIBELLE,diff_row.EMAIL,diff_row.IBAN,diff_row.LAST_SYNC_STATUT_ID,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.NUMERO_INSEE,diff_row.NUMERO_INSEE_CLE,diff_row.NUMERO_INSEE_PROVISOIRE,diff_row.PAYS_NAISSANCE_CODE_INSEE,diff_row.PAYS_NAISSANCE_LIBELLE,diff_row.PAYS_NATIONALITE_CODE_INSEE,diff_row.PAYS_NATIONALITE_LIBELLE,diff_row.PRENOM,diff_row.STATUT_ID,diff_row.STRUCTURE_ID,diff_row.TEL_MOBILE,diff_row.TEL_PRO,diff_row.TYPE_ID,diff_row.VILLE_NAISSANCE_CODE_INSEE,diff_row.VILLE_NAISSANCE_LIBELLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LAST_SYNC_STATUT_ID = 1 AND IN_COLUMN_LIST('LAST_SYNC_STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET LAST_SYNC_STATUT_ID = diff_row.LAST_SYNC_STATUT_ID WHERE ID = diff_row.id; END IF;
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
          UPDATE OSE.INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LAST_SYNC_STATUT_ID = 1 AND IN_COLUMN_LIST('LAST_SYNC_STATUT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET LAST_SYNC_STATUT_ID = diff_row.LAST_SYNC_STATUT_ID WHERE ID = diff_row.id; END IF;
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

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT;



  PROCEDURE MAJ_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_DISCIPLINE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_DISCIPLINE.* FROM V_DIFF_DISCIPLINE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.DISCIPLINE
            ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,DISCIPLINE_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.DISCIPLINE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.DISCIPLINE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.DISCIPLINE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_DISCIPLINE;



  PROCEDURE MAJ_ELEMENT_DISCIPLINE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_DISCIPLINE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_DISCIPLINE.* FROM V_DIFF_ELEMENT_DISCIPLINE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_DISCIPLINE
            ( id, DISCIPLINE_ID,ELEMENT_PEDAGOGIQUE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_DISCIPLINE_ID_SEQ.NEXTVAL), diff_row.DISCIPLINE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_DISCIPLINE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_DISCIPLINE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_DISCIPLINE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_DISCIPLINE;



  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETAPE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETAPE.* FROM V_DIFF_ETAPE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ETAPE
            ( id, LIBELLE,NIVEAU,SPECIFIQUE_ECHANGES,STRUCTURE_ID,TYPE_FORMATION_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ETAPE_ID_SEQ.NEXTVAL), diff_row.LIBELLE,diff_row.NIVEAU,diff_row.SPECIFIQUE_ECHANGES,diff_row.STRUCTURE_ID,diff_row.TYPE_FORMATION_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ETAPE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETAPE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ETAPE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETAPE;



  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ETABLISSEMENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ETABLISSEMENT.* FROM V_DIFF_ETABLISSEMENT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ETABLISSEMENT
            ( id, DEPARTEMENT,LIBELLE,LOCALISATION, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ETABLISSEMENT_ID_SEQ.NEXTVAL), diff_row.DEPARTEMENT,diff_row.LIBELLE,diff_row.LOCALISATION, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ETABLISSEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ETABLISSEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ETABLISSEMENT;



  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CHEMIN_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CHEMIN_PEDAGOGIQUE.* FROM V_DIFF_CHEMIN_PEDAGOGIQUE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.CHEMIN_PEDAGOGIQUE
            ( id, ELEMENT_PEDAGOGIQUE_ID,ETAPE_ID,ORDRE,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.ETAPE_ID,diff_row.ORDRE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CHEMIN_PEDAGOGIQUE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.CHEMIN_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_CHEMIN_PEDAGOGIQUE;



  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_INTERVENANT.* FROM V_DIFF_ADRESSE_INTERVENANT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ADRESSE_INTERVENANT
            ( id, CODE_POSTAL,INTERVENANT_ID,LOCALITE,MENTION_COMPLEMENTAIRE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,TEL_DOMICILE,VALIDITE_DEBUT,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ADRESSE_INTERVENANT_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.INTERVENANT_ID,diff_row.LOCALITE,diff_row.MENTION_COMPLEMENTAIRE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.TEL_DOMICILE,diff_row.VALIDITE_DEBUT,diff_row.VILLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

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
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

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
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ADRESSE_INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_INTERVENANT;



  PROCEDURE MAJ_ROLE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ROLE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ROLE.* FROM V_DIFF_ROLE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ROLE
            ( id, PERSONNEL_ID,STRUCTURE_ID,TYPE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ROLE_ID_SEQ.NEXTVAL), diff_row.PERSONNEL_ID,diff_row.STRUCTURE_ID,diff_row.TYPE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ROLE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_PERSONNEL_ID = 1 AND IN_COLUMN_LIST('PERSONNEL_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET PERSONNEL_ID = diff_row.PERSONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_ID = 1 AND IN_COLUMN_LIST('TYPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET TYPE_ID = diff_row.TYPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ROLE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ROLE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ROLE;



  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_CORPS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_CORPS.* FROM V_DIFF_CORPS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.CORPS
            ( id, LIBELLE_COURT,LIBELLE_LONG,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,CORPS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.CORPS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.CORPS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.CORPS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_CORPS;



  PROCEDURE MAJ_INTERVENANT_PERMANENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_PERMANENT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_PERMANENT.* FROM V_DIFF_INTERVENANT_PERMANENT ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT_PERMANENT
            ( id, CORPS_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_PERMANENT_ID_SEQ.NEXTVAL), diff_row.CORPS_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_PERMANENT SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT_PERMANENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_PERMANENT;



  PROCEDURE MAJ_INTERVENANT_EXTERIEUR(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT_EXTERIEUR%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT_EXTERIEUR.* FROM V_DIFF_INTERVENANT_EXTERIEUR ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.INTERVENANT_EXTERIEUR
            ( id, SITUATION_FAMILIALE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,INTERVENANT_EXTERIEUR_ID_SEQ.NEXTVAL), diff_row.SITUATION_FAMILIALE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_SITUATION_FAMILIALE_ID = 1 AND IN_COLUMN_LIST('SITUATION_FAMILIALE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET SITUATION_FAMILIALE_ID = diff_row.SITUATION_FAMILIALE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT_EXTERIEUR SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.INTERVENANT_EXTERIEUR SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_INTERVENANT_EXTERIEUR;



  PROCEDURE MAJ_ELEMENT_PORTEUR_PORTE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ELEMENT_PORTEUR_PORTE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ELEMENT_PORTEUR_PORTE.* FROM V_DIFF_ELEMENT_PORTEUR_PORTE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ELEMENT_PORTEUR_PORTE
            ( id, ELEMENT_PORTEUR_ID,ELEMENT_PORTE_ID,TYPE_INTERVENTION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ELEMENT_PORTEUR_PORTE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PORTEUR_ID,diff_row.ELEMENT_PORTE_ID,diff_row.TYPE_INTERVENTION_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ELEMENT_PORTEUR_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTEUR_ID = diff_row.ELEMENT_PORTEUR_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PORTE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTE_ID = diff_row.ELEMENT_PORTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ELEMENT_PORTEUR_PORTE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ELEMENT_PORTEUR_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTEUR_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTEUR_ID = diff_row.ELEMENT_PORTEUR_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PORTE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PORTE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET ELEMENT_PORTE_ID = diff_row.ELEMENT_PORTE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PORTEUR_PORTE SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ELEMENT_PORTEUR_PORTE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ELEMENT_PORTEUR_PORTE;



  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_AFFECTATION_RECHERCHE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_AFFECTATION_RECHERCHE.* FROM V_DIFF_AFFECTATION_RECHERCHE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.AFFECTATION_RECHERCHE
            ( id, INTERVENANT_ID,STRUCTURE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,AFFECTATION_RECHERCHE_ID_SEQ.NEXTVAL), diff_row.INTERVENANT_ID,diff_row.STRUCTURE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.AFFECTATION_RECHERCHE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.AFFECTATION_RECHERCHE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_AFFECTATION_RECHERCHE;



  PROCEDURE MAJ_VOLUME_HORAIRE_ENS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_VOLUME_HORAIRE_ENS%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_VOLUME_HORAIRE_ENS.* FROM V_DIFF_VOLUME_HORAIRE_ENS ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.VOLUME_HORAIRE_ENS
            ( id, ANNEE_ID,ELEMENT_DISCIPLINE_ID,HEURES,TYPE_INTERVENTION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,VOLUME_HORAIRE_ENS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_DISCIPLINE_ID,diff_row.HEURES,diff_row.TYPE_INTERVENTION_ID, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ELEMENT_DISCIPLINE_ID = diff_row.ELEMENT_DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.VOLUME_HORAIRE_ENS SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET ELEMENT_DISCIPLINE_ID = diff_row.ELEMENT_DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
          UPDATE OSE.VOLUME_HORAIRE_ENS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_VOLUME_HORAIRE_ENS;



  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_ADRESSE_STRUCTURE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_ADRESSE_STRUCTURE.* FROM V_DIFF_ADRESSE_STRUCTURE ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.ADRESSE_STRUCTURE
            ( id, CODE_POSTAL,LOCALITE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,STRUCTURE_ID,TELEPHONE,VALIDITE_DEBUT,VALIDITE_FIN,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,ADRESSE_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.LOCALITE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.STRUCTURE_ID,diff_row.TELEPHONE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN,diff_row.VILLE, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

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
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

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
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
          UPDATE OSE.ADRESSE_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_ADRESSE_STRUCTURE;



  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_PERSONNEL%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_PERSONNEL.* FROM V_DIFF_PERSONNEL ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.PERSONNEL
            ( id, CIVILITE_ID,EMAIL,NOM_PATRONYMIQUE,NOM_USUEL,PRENOM,STRUCTURE_ID,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,PERSONNEL_ID_SEQ.NEXTVAL), diff_row.CIVILITE_ID,diff_row.EMAIL,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.PRENOM,diff_row.STRUCTURE_ID,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.PERSONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.PERSONNEL SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
          UPDATE OSE.PERSONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_PERSONNEL;



  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_TYPE_FORMATION.* FROM V_DIFF_TYPE_FORMATION ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.TYPE_FORMATION
            ( id, GROUPE_ID,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.GROUPE_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          UPDATE OSE.TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_TYPE_FORMATION;



  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_GROUPE_TYPE_FORMATION%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_GROUPE_TYPE_FORMATION.* FROM V_DIFF_GROUPE_TYPE_FORMATION ' || SQL_CRITERION;
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row;
      EXIT WHEN diff_cur%NOTFOUND;

      CASE diff_row.import_action
        WHEN 'insert' THEN
          INSERT INTO OSE.GROUPE_TYPE_FORMATION
            ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE,PERTINENCE_NIVEAU, source_id, source_code, histo_createur_id, histo_modificateur_id )
          VALUES
            ( COALESCE(diff_row.id,GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE,diff_row.PERTINENCE_NIVEAU, diff_row.source_id, diff_row.source_code, v_current_user, v_current_user );

        WHEN 'update' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;

        WHEN 'delete' THEN
          UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = v_current_user WHERE ID = diff_row.id;

        WHEN 'undelete' THEN
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;
          UPDATE OSE.GROUPE_TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

      END CASE;

    END LOOP;
    CLOSE diff_cur;

  END MAJ_GROUPE_TYPE_FORMATION;

  -- END OF AUTOMATIC GENERATION --
END ose_import;
/

UPDATE "OSE"."STATUT_INTERVENANT" SET SYNCHRONISABLE = '1' WHERE source_code = 'AUTRES';
UPDATE "OSE"."STATUT_INTERVENANT" SET TYPE_INTERVENANT_ID = '1' WHERE source_code = 'BIATSS';
INSERT INTO "OSE"."STATUT_INTERVENANT" (ID, LIBELLE, SERVICE_STATUTAIRE, DEPASSEMENT, PLAFOND_REFERENTIEL, MAXIMUM_HETD, FONCTION_E_C, TYPE_INTERVENANT_ID, SYNCHRONISABLE, SOURCE_ID, SOURCE_CODE, VALIDITE_DEBUT, HISTO_CREATION, HISTO_CREATEUR_ID, HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID, ORDRE)
                                VALUES (STATUT_INTERVENANT_ID_SEQ.NEXTVAL, 'Non autorisés', 0,0,0,0,0,2,1,2, 'NON_AUTORISE', SYSDATE, SYSDATE, 1, SYSDATE, 1, 20);

Insert into TYPE_INTERVENTION (ID,CODE,LIBELLE,ORDRE,TAUX_HETD_SERVICE,TAUX_HETD_COMPLEMENTAIRE,INTERVENTION_INDIVIDUALISEE,VISIBLE,VALIDITE_DEBUT,VALIDITE_FIN,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID) 
                       values (TYPE_INTERVENTION_id_seq.nextval,'FOAD','FOAD (CEMU)','5','1','1','0','0',sysdate,null,sysdate,'1',sysdate,'1',null,null);

Insert into TYPE_INTERVENTION_STRUCTURE (id,TYPE_INTERVENTION_ID,STRUCTURE_ID,VISIBLE,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID) 
                                values (TYPE_INTERVENTION_STRUC_id_seq.nextval,(select id from type_intervention where code = 'FOAD'),(SELECT id from structure where source_code='C53'),'1',sysdate,'1',sysdate,'1',null,null);


CREATE OR REPLACE FORCE VIEW "OSE"."SRC_AFFECTATION_RECHERCHE" ("ID", "INTERVENANT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
SELECT id, intervenant_id, structure_id, source_id, source_code, validite_debut, validite_fin FROM 
(SELECT * FROM (SELECT
  NULL id,
  i.id intervenant_id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  aff.SOURCE_ID,
  aff.SOURCE_CODE,
  aff.VALIDITE_DEBUT,
  aff.VALIDITE_FIN,
  min(nvl(aff.validite_fin,sysdate)) over(partition BY i.id) min_validite_fin,
  max(aff.SOURCE_CODE) over(partition by i.id) max_source_code
FROM
  mv_affectation_recherche aff
  LEFT JOIN intervenant i ON (i.source_code = aff.z_intervenant_id)
  LEFT JOIN structure s ON (s.source_code = aff.z_structure_id)
) tmp1
WHERE
  nvl(VALIDITE_FIN,sysdate) = min_validite_fin
) tmp2
WHERE
  source_code = max_source_code;
  
  
  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_PEDAGOGIQUE" ("ID", "LIBELLE", "ETAPE_ID", "STRUCTURE_ID", "PERIODE_ID", "TAUX_FOAD", "FC", "FI", "FA", "SOURCE_ID", "SOURCE_CODE") AS 
  SELECT
  null id,
  E.LIBELLE,
  etp.id ETAPE_ID,
  NVL(str.STRUCTURE_NIV2_ID,str.id) structure_id,
  per.id periode_id,
  E.TAUX_FOAD,
  e.fc,
  e.fi,
  e.fa,
  E.SOURCE_ID,
  E.SOURCE_CODE
FROM
  MV_ELEMENT_PEDAGOGIQUE E
  LEFT JOIN etape etp ON etp.source_code = E.Z_ETAPE_ID
  LEFT JOIN structure str ON str.source_code = E.Z_STRUCTURE_ID
  LEFT JOIN periode per ON per.libelle_court = E.Z_PERIODE_ID;

  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ETAPE" ("ID", "LIBELLE", "TYPE_FORMATION_ID", "NIVEAU", "SPECIFIQUE_ECHANGES", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
  SELECT
  null id,
  e.libelle,
  tf.id type_formation_id,
  e.niveau,
  e.specifique_echanges,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  e.source_id,
  e.source_code,
  e.validite_debut,
  e.validite_fin
FROM
  MV_ETAPE e
  LEFT JOIN TYPE_FORMATION tf ON tf.source_code = E.Z_TYPE_FORMATION_ID
  LEFT JOIN STRUCTURE s ON s.source_code = E.Z_STRUCTURE_ID;
  
  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT" 
 ( "ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "TYPE_ID", "STATUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC", "LAST_SYNC_STATUT_ID"
  )  AS 
  SELECT
  null id,
  i."CIVILITE_ID",
  i."NOM_USUEL",
  i."PRENOM",
  i."NOM_PATRONYMIQUE",
  NVL(i."DATE_NAISSANCE",TO_DATE('2099-01-01','YYYY-MM-DD')) DATE_NAISSANCE,
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
  TI.ID type_id,
  si.id statut_id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  i."SOURCE_ID",
  i."SOURCE_CODE",
  i."NUMERO_INSEE",
  i."NUMERO_INSEE_CLE",
  i."NUMERO_INSEE_PROVISOIRE",
  i."IBAN",
  i."BIC",
  si.id last_sync_statut_id
FROM
  mv_intervenant i
  LEFT JOIN statut_intervenant si ON si.source_code = i.z_statut_id
  LEFT JOIN type_intervenant ti ON ti.code = I.Z_TYPE_ID
  LEFT JOIN structure s ON s.source_code = i.z_structure_id;
  
  CREATE OR REPLACE FORCE VIEW "OSE"."SRC_PERSONNEL" ("ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "EMAIL", "DATE_NAISSANCE", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
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
  p.source_code,
  p.validite_debut,
  p.validite_fin
FROM
  mv_personnel p
  JOIN structure s ON s.source_code = p.z_structure_id;
  
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ROLE" ("ID", "STRUCTURE_ID", "PERSONNEL_ID", "TYPE_ID", "SOURCE_ID", "SOURCE_CODE", "VALIDITE_DEBUT", "VALIDITE_FIN") AS 
SELECT
  NULL id,
  NVL(s.STRUCTURE_NIV2_ID,s.id) structure_id,
  p.id personnel_id,
  tr.id type_id,
  r.source_id,
  r.source_code,
  r.validite_debut,
  r.validite_fin
FROM
  mv_role r
  LEFT JOIN personnel p ON p.source_code = r.z_personnel_id
  LEFT JOIN structure s ON s.source_code = r.z_structure_id
  LEFT JOIN type_role tr ON tr.code = r.z_type_id;
  
  /
  
  create or replace TRIGGER validation_vol_horaire_ck
BEFORE UPDATE OR DELETE ON validation_vol_horaire
FOR EACH ROW
DECLARE 
  contrat_blinde NUMERIC;  
  pragma autonomous_transaction;
BEGIN
  
SELECT
  count(*) INTO contrat_blinde
FROM
  volume_horaire vh
  JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
WHERE
  vh.id = :OLD.volume_horaire_id;
  
  -- Si des volumes horaires ont déjà fait l'objet de contrats alors pas de dévalidation possible des heures
  IF contrat_blinde = 1 THEN
    raise_application_error(-20101, 'La dévalidation est impossible car un contrat a déjà été édité sur la base de ces heures.');
  END IF;

END;

/

create or replace TRIGGER validation_ck
BEFORE UPDATE OR DELETE ON validation
FOR EACH ROW
DECLARE 
  nb_validations_blindees NUMERIC;  
  pragma autonomous_transaction;
BEGIN
  
SELECT
  SUM(CASE WHEN c.id IS NOT NULL THEN 1 ELSE 0 END) INTO nb_validations_blindees
FROM
  validation_vol_horaire vvh
  JOIN volume_horaire vh ON vh.id = vvh.volume_horaire_id
  LEFT JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
WHERE
  vvh.validation_id = :OLD.id;
  
  -- Si des volumes horaires ont déjà fait l'objet de contrats alors pas de dévalidation possible des heures
  IF nb_validations_blindees > 0 THEN
    raise_application_error(-20101, 'La dévalidation est impossible car des contrats ont déjà été édités sur la base de ces heures.');
  END IF;

END;

/

CREATE OR REPLACE FORCE VIEW "OSE"."V_ELEMENT_TYPE_INTERVENTION" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID"
  )  AS 
  SELECT
  ep.id element_pedagogique_id,
  ti.id type_intervention_id
FROM
  element_pedagogique ep
  JOIN structure s_ep ON s_ep.id = ep.structure_id
  JOIN type_intervention ti ON 1=1
  LEFT JOIN TYPE_INTERVENTION_EP ti_ep ON TI_EP.ELEMENT_PEDAGOGIQUE_ID = EP.ID AND TI_EP.TYPE_INTERVENTION_ID = TI.ID AND TI_EP.HISTO_CREATION <= SYSDATE AND NVL(TI_EP.HISTO_DESTRUCTION,SYSDATE) >= SYSDATE
  --LEFT JOIN TYPE_INTERVENTION_STRUCTURE ti_s ON OSE_DIVERS.STRUCTURE_DANS_STRUCTURE( EP.STRUCTURE_ID, TI_S.STRUCTURE_ID) = 1 AND TI_S.TYPE_INTERVENTION_ID = TI.ID AND TI_S.HISTO_CREATION <= SYSDATE AND NVL(TI_S.HISTO_DESTRUCTION,SYSDATE) >= SYSDATE
  LEFT JOIN TYPE_INTERVENTION_STRUCTURE ti_s ON S_EP.STRUCTURE_NIV2_ID = TI_S.STRUCTURE_ID AND TI_S.TYPE_INTERVENTION_ID = TI.ID AND TI_S.HISTO_CREATION <= SYSDATE AND NVL(TI_S.HISTO_DESTRUCTION,SYSDATE) >= SYSDATE
WHERE
  CASE
    WHEN TI_EP.VISIBLE IS NULL THEN
      CASE
        WHEN TI_S.VISIBLE IS NULL THEN TI.VISIBLE
        ELSE TI_S.VISIBLE
      END
    ELSE TI_EP.VISIBLE
  END = 1
ORDER BY
  TI.ORDRE;