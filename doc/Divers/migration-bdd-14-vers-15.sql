--------------------------------------------------
-- view.drop
--------------------------------------------------

DROP VIEW V_INDIC_DIFF_DOSSIER;
/




--------------------------------------------------
-- materialized-view.drop
--------------------------------------------------

DROP MATERIALIZED VIEW MV_EXT_SERVICE;
/




--------------------------------------------------
-- table.create
--------------------------------------------------

CREATE TABLE "ADRESSE_NUMERO_COMPL"
   (  "ID" NUMBER NOT NULL ENABLE,
  "CODE" VARCHAR2(5 CHAR) NOT NULL ENABLE,
  "LIBELLE" VARCHAR2(120 CHAR) NOT NULL ENABLE
   );
/

CREATE TABLE "DOSSIER_CHAMP_AUTRE"
   (  "ID" NUMBER NOT NULL ENABLE,
  "DOSSIER_CHAMP_AUTRE_TYPE_ID" NUMBER DEFAULT 1 NOT NULL ENABLE,
  "LIBELLE" VARCHAR2(200 CHAR),
  "CONTENU" CLOB,
  "OBLIGATOIRE" NUMBER(1) DEFAULT 1 NOT NULL ENABLE,
  "SQL_VALUE" VARCHAR2(4000 CHAR),
  "JSON_VALUE" CLOB,
  "DESCRIPTION" VARCHAR2(3000 CHAR)
   );
/

CREATE TABLE "DOSSIER_CHAMP_AUTRE_PAR_STATUT"
   (  "DOSSIER_CHAMP_AUTRE_ID" NUMBER(*,0) NOT NULL ENABLE,
  "STATUT_ID" NUMBER(*,0) NOT NULL ENABLE
   );
/

CREATE TABLE "DOSSIER_CHAMP_AUTRE_TYPE"
   (  "ID" NUMBER NOT NULL ENABLE,
  "CODE" VARCHAR2(15 CHAR) NOT NULL ENABLE,
  "LIBELLE" VARCHAR2(50 CHAR) NOT NULL ENABLE
   );
/

CREATE TABLE "EMPLOYEUR"
   (  "ID" NUMBER NOT NULL ENABLE,
  "SIREN" VARCHAR2(100 CHAR) NOT NULL ENABLE,
  "RAISON_SOCIALE" VARCHAR2(250 CHAR) NOT NULL ENABLE,
  "NOM_COMMERCIAL" VARCHAR2(250 CHAR),
  "IDENTIFIANT_ASSOCIATION" VARCHAR2(250 CHAR),
  "CRITERE_RECHERCHE" VARCHAR2(500 CHAR),
  "SOURCE_ID" NUMBER(*,0) NOT NULL ENABLE,
  "SOURCE_CODE" VARCHAR2(100 CHAR),
  "HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
  "HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
  "HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
  "HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
  "HISTO_DESTRUCTION" DATE,
  "HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   );
/

CREATE TABLE "INTERVENANT_DOSSIER"
   (  "ID" NUMBER(*,0) NOT NULL ENABLE,
  "INTERVENANT_ID" NUMBER(*,0) NOT NULL ENABLE,
  "STATUT_ID" NUMBER(*,0) NOT NULL ENABLE,
  "CIVILITE_ID" NUMBER(*,0),
  "NOM_USUEL" VARCHAR2(60 CHAR),
  "PRENOM" VARCHAR2(60 CHAR),
  "DATE_NAISSANCE" DATE,
  "NOM_PATRONYMIQUE" VARCHAR2(60 CHAR),
  "COMMUNE_NAISSANCE" VARCHAR2(60 CHAR),
  "PAYS_NAISSANCE_ID" NUMBER(*,0),
  "DEPARTEMENT_NAISSANCE_ID" NUMBER(*,0),
  "PAYS_NATIONALITE_ID" NUMBER(*,0),
  "TEL_PRO" VARCHAR2(30 CHAR),
  "TEL_PERSO" VARCHAR2(30 CHAR),
  "EMAIL_PRO" VARCHAR2(255 CHAR),
  "EMAIL_PERSO" VARCHAR2(255 CHAR),
  "ADRESSE_PRECISIONS" VARCHAR2(240 CHAR),
  "ADRESSE_NUMERO" VARCHAR2(4 CHAR),
  "ADRESSE_NUMERO_COMPL_ID" NUMBER,
  "ADRESSE_VOIRIE_ID" NUMBER(*,0),
  "ADRESSE_VOIE" VARCHAR2(60 CHAR),
  "ADRESSE_LIEU_DIT" VARCHAR2(60 CHAR),
  "ADRESSE_CODE_POSTAL" VARCHAR2(15 CHAR),
  "ADRESSE_COMMUNE" VARCHAR2(100 CHAR),
  "ADRESSE_PAYS_ID" NUMBER(*,0),
  "NUMERO_INSEE" VARCHAR2(20 CHAR),
  "NUMERO_INSEE_PROVISOIRE" NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
  "IBAN" VARCHAR2(50 CHAR),
  "BIC" VARCHAR2(20 CHAR),
  "RIB_HORS_SEPA" NUMBER(1) DEFAULT 0 NOT NULL ENABLE,
  "AUTRE_1" VARCHAR2(1000 CHAR),
  "AUTRE_2" VARCHAR2(1000 CHAR),
  "AUTRE_3" VARCHAR2(1000 CHAR),
  "AUTRE_4" VARCHAR2(1000 CHAR),
  "AUTRE_5" VARCHAR2(1000 CHAR),
  "EMPLOYEUR_ID" NUMBER(*,0),
  "HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
  "HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
  "HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
  "HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
  "HISTO_DESTRUCTION" DATE,
  "HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   );
/

CREATE TABLE "INTERVENANT_PAR_DEFAUT"
   (  "ID" NUMBER NOT NULL ENABLE,
  "INTERVENANT_ID" NUMBER NOT NULL ENABLE,
  "INTERVENANT_CODE" VARCHAR2(60 CHAR) NOT NULL ENABLE,
  "ANNEE_ID" NUMBER NOT NULL ENABLE
   );
/

CREATE TABLE "VOIRIE"
   (  "ID" NUMBER NOT NULL ENABLE,
  "CODE" VARCHAR2(5 CHAR) NOT NULL ENABLE,
  "LIBELLE" VARCHAR2(120 CHAR) NOT NULL ENABLE,
  "SOURCE_ID" NUMBER(*,0) NOT NULL ENABLE,
  "SOURCE_CODE" VARCHAR2(100 CHAR) NOT NULL ENABLE,
  "HISTO_CREATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
  "HISTO_CREATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
  "HISTO_MODIFICATION" DATE DEFAULT SYSDATE NOT NULL ENABLE,
  "HISTO_MODIFICATEUR_ID" NUMBER(*,0) NOT NULL ENABLE,
  "HISTO_DESTRUCTION" DATE,
  "HISTO_DESTRUCTEUR_ID" NUMBER(*,0)
   );
/




--------------------------------------------------
-- table.alter
--------------------------------------------------

COMMENT ON TABLE "CIVILITE" IS 'columns-order=ID,SEXE,LIBELLE_COURT,LIBELLE_LONG;
Liste des civilités';
/

ALTER TABLE "DEPARTEMENT" ADD ("LIBELLE" VARCHAR2(120 CHAR) NOT NULL ENABLE);
/

ALTER TABLE "DEPARTEMENT" MODIFY ("CODE" NOT NULL);
/

ALTER TABLE "DEPARTEMENT" DROP COLUMN "LIBELLE_COURT";
/

ALTER TABLE "DEPARTEMENT" DROP COLUMN "LIBELLE_LONG";
/

ALTER TABLE "IMPORT_TABLES" ADD ("KEY_COLUMNS" VARCHAR2(1000 CHAR));
/

ALTER TABLE "IMPORT_TABLES" ADD ("SYNC_NON_IMPORTABLES" NUMBER(1) DEFAULT 0 NOT NULL ENABLE);
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_CODE_POSTAL" VARCHAR2(15 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_COMMUNE" VARCHAR2(50 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_LIEU_DIT" VARCHAR2(60 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_NUMERO" VARCHAR2(4 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_NUMERO_COMPL_ID" NUMBER);
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_PAYS_ID" NUMBER);
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_PRECISIONS" VARCHAR2(240 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_VOIE" VARCHAR2(60 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("ADRESSE_VOIRIE_ID" NUMBER);
/

ALTER TABLE "INTERVENANT" ADD ("AUTRE_1" VARCHAR2(1000 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("AUTRE_2" VARCHAR2(1000 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("AUTRE_3" VARCHAR2(1000 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("AUTRE_4" VARCHAR2(1000 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("AUTRE_5" VARCHAR2(1000 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("CODE_RH" VARCHAR2(60 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("COMMUNE_NAISSANCE" VARCHAR2(60 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("DEPARTEMENT_NAISSANCE_ID" NUMBER(*,0));
/

ALTER TABLE "INTERVENANT" ADD ("EMAIL_PERSO" VARCHAR2(255 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("EMAIL_PRO" VARCHAR2(255 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("EMPLOYEUR_ID" NUMBER);
/

ALTER TABLE "INTERVENANT" ADD ("SYNC_STATUT" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "INTERVENANT" ADD ("SYNC_STRUCTURE" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "INTERVENANT" ADD ("SYNC_UTILISATEUR_CODE" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "INTERVENANT" ADD ("TEL_PERSO" VARCHAR2(30 CHAR));
/

ALTER TABLE "INTERVENANT" ADD ("VALIDITE_DEBUT" DATE);
/

ALTER TABLE "INTERVENANT" ADD ("VALIDITE_FIN" DATE);
/

ALTER TABLE "INTERVENANT" MODIFY ("CODE" NOT NULL);
/

ALTER TABLE "INTERVENANT" MODIFY ("NUMERO_INSEE" VARCHAR2(20 CHAR));
/

ALTER TABLE "INTERVENANT" MODIFY ("NUMERO_INSEE_PROVISOIRE" NOT NULL);
/

ALTER TABLE "INTERVENANT" MODIFY ("NUMERO_INSEE_PROVISOIRE" DEFAULT 0 );
/

ALTER TABLE "INTERVENANT" MODIFY ("SOURCE_CODE" NOT NULL);
/

ALTER TABLE "INTERVENANT" MODIFY ("TEL_PRO" VARCHAR2(30 CHAR));
/

ALTER TABLE "INTERVENANT" DROP COLUMN "DEP_NAISSANCE_ID";
/

ALTER TABLE "INTERVENANT" DROP COLUMN "EMAIL";
/

ALTER TABLE "INTERVENANT" DROP COLUMN "NUMERO_INSEE_CLE";
/

ALTER TABLE "INTERVENANT" DROP COLUMN "PREMIER_RECRUTEMENT";
/

ALTER TABLE "INTERVENANT" DROP COLUMN "TEL_MOBILE";
/

ALTER TABLE "INTERVENANT" DROP COLUMN "VILLE_NAISSANCE_CODE_INSEE";
/

ALTER TABLE "INTERVENANT" DROP COLUMN "VILLE_NAISSANCE_LIBELLE";
/

COMMENT ON TABLE "INTERVENANT" IS 'columns-order=ID,ANNEE_ID,CODE,UTILISATEUR_CODE,STRUCTURE_ID,STATUT_ID,GRADE_ID,DISCIPLINE_ID,CIVILITE_ID,NOM_USUEL,PRENOM,DATE_NAISSANCE,NOM_PATRONYMIQUE,COMMUNE_NAISSANCE,PAYS_NAISSANCE_ID,DEPARTEMENT_NAISSANCE_ID,PAYS_NATIONALITE_ID,TEL_PRO,TEL_PERSO,EMAIL_PRO,EMAIL_PERSO,ADDR_PRECISIONS,ADDR_NUMERO,ADDR_NUMERO_COMPL_ID,ADDR_VOIRIE_ID,ADDR_VOIE,ADDR_LIEU_DIT,ADDR_CODE_POSTAL,ADDR_COMMUNE,ADDR_PAYS_ID,NUMERO_INSEE,NUMERO_INSEE_PROVISOIRE,IBAN,BIC,RIB_HORS_SEPA,AUTRE_1,AUTRE_2,AUTRE_3,AUTRE_4,AUTRE_5,EMPLOYEUR_ID,MONTANT_INDEMNITE_FC,CRITERE_RECHERCHE,SOURCE_ID,SOURCE_CODE,SYNC_STATUT,SYNC_STRUCTURE,HISTO_CREATION,HISTO_CREATEUR_ID,HISTO_MODIFICATION,HISTO_MODIFICATEUR_ID,HISTO_DESTRUCTION,HISTO_DESTRUCTEUR_ID;';
/

ALTER TABLE "PAYS" ADD ("CODE" VARCHAR2(15 CHAR) NOT NULL ENABLE);
/

ALTER TABLE "PAYS" ADD ("LIBELLE" VARCHAR2(120 CHAR) NOT NULL ENABLE);
/

ALTER TABLE "PAYS" DROP COLUMN "LIBELLE_COURT";
/

ALTER TABLE "PAYS" DROP COLUMN "LIBELLE_LONG";
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("CODE" VARCHAR2(50 CHAR) NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_ADRESSE" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_CONTACT" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_EMAIL_PERSO" NUMBER(1) DEFAULT 0 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_EMPLOYEUR" NUMBER(1) DEFAULT 0 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_IBAN" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_IDENTITE_COMP" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_INSEE" NUMBER(1) DEFAULT 1 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" ADD ("DOSSIER_TEL_PERSO" NUMBER(1) DEFAULT 0 NOT NULL ENABLE);
/

ALTER TABLE "STATUT_INTERVENANT" DROP COLUMN "SOURCE_CODE";
/

ALTER TABLE "STATUT_INTERVENANT" DROP COLUMN "SOURCE_ID";
/

ALTER TABLE "STATUT_INTERVENANT" DROP COLUMN "CODES_CORRESP_1";
/

ALTER TABLE "STATUT_INTERVENANT" DROP COLUMN "CODES_CORRESP_2";
/

ALTER TABLE "STATUT_INTERVENANT" DROP COLUMN "CODES_CORRESP_3";
/

ALTER TABLE "STATUT_INTERVENANT" DROP COLUMN "CODES_CORRESP_4";
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_CODE_POSTAL" VARCHAR2(15 CHAR));
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_COMMUNE" VARCHAR2(50 CHAR));
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_LIEU_DIT" VARCHAR2(60 CHAR));
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_NUMERO" VARCHAR2(4 CHAR));
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_NUMERO_COMPL_ID" NUMBER);
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_PAYS_ID" NUMBER(*,0));
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_PRECISIONS" VARCHAR2(240 CHAR));
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_VOIE" VARCHAR2(60 CHAR));
/

ALTER TABLE "STRUCTURE" ADD ("ADRESSE_VOIRIE_ID" NUMBER(*,0));
/

ALTER TABLE "TBL" ADD ("PARAMETRES" VARCHAR2(500 CHAR));
/

ALTER TABLE "TBL_AGREMENT" MODIFY ("CODE_INTERVENANT" NOT NULL);
/

ALTER TABLE "TBL_AGREMENT" MODIFY ("DUREE_VIE" NOT NULL);
/

ALTER TABLE "TBL_AGREMENT" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_CHARGENS" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_CHARGENS_SEUILS_DEF" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_CLOTURE_REALISE" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_CONTRAT" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_DMEP_LIQUIDATION" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_ADRESSE" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_AUTRES" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_CONTACT" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_EMPLOYEUR" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_IBAN" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_IDENTITE" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_IDENTITE_COMP" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_INSEE" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" ADD ("COMPLETUDE_STATUT" NUMBER(*,0) DEFAULT 0);
/

ALTER TABLE "TBL_DOSSIER" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_LIEN" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_PAIEMENT" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_PIECE_JOINTE" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_PIECE_JOINTE_DEMANDE" MODIFY ("CODE_INTERVENANT" NOT NULL);
/

ALTER TABLE "TBL_PIECE_JOINTE_DEMANDE" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_PIECE_JOINTE_FOURNIE" MODIFY ("CODE_INTERVENANT" NOT NULL);
/

ALTER TABLE "TBL_PIECE_JOINTE_FOURNIE" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_SERVICE" MODIFY ("TYPE_VOLUME_HORAIRE_CODE" NOT NULL);
/

ALTER TABLE "TBL_SERVICE" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_SERVICE_REFERENTIEL" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_SERVICE_SAISIE" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_VALIDATION_ENSEIGNEMENT" DROP COLUMN "TO_DELETE";
/

ALTER TABLE "TBL_VALIDATION_REFERENTIEL" DROP COLUMN "TO_DELETE";
/




--------------------------------------------------
-- view.alter
--------------------------------------------------

CREATE OR REPLACE FORCE VIEW V_AGREMENT_EXPORT_CSV AS
WITH heures_s AS (
  SELECT
    i.id                                      intervenant_id,
    COALESCE(ep.structure_id,i.structure_id)  structure_id,
    SUM(frs.service_fi)                       service_fi,
    SUM(frs.service_fa)                       service_fa,
    SUM(frs.service_fc)                       service_fc,
    SUM(frs.heures_compl_fi)                  heures_compl_fi,
    SUM(frs.heures_compl_fa)                  heures_compl_fa,
    SUM(frs.heures_compl_fc)                  heures_compl_fc,
    SUM(frs.heures_compl_fc_majorees)         heures_compl_fc_majorees,
    SUM(frs.total)                            total
  FROM
              formule_resultat_service frs
         JOIN type_volume_horaire      tvh ON tvh.code = 'PREVU'
         JOIN etat_volume_horaire      evh ON evh.code = 'valide'
         JOIN formule_resultat          fr ON fr.id = frs.formule_resultat_id
                                          AND fr.type_volume_horaire_id = tvh.id
                                          AND fr.etat_volume_horaire_id = evh.id
         JOIN intervenant                i ON i.id = fr.intervenant_id
         JOIN service                    s ON s.id = frs.service_id
    LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
  GROUP BY
    i.id,
    ep.structure_id,
    i.structure_id
)
SELECT
  a.id                                                                        annee_id,
  i.id                                                                        intervenant_id,
  s.id                                                                        structure_id,

  a.libelle                                                                   annee,
  s.libelle_court                                                             structure_libelle,
  i.code                                                                      intervenant_code,
  i.nom_usuel                                                                 intervenant_nom_usuel,
  i.nom_patronymique                                                          intervenant_nom_patronymique,
  i.prenom                                                                    intervenant_prenom,

  si.libelle                                                                  intervenant_statut_libelle,
  d.libelle_court                                                             discipline,

  COALESCE(heures_s.service_fi, fr.service_fi)
  + COALESCE(heures_s.heures_compl_fi, fr.heures_compl_fi)
                                                                              hetd_fi,
  COALESCE(heures_s.service_fa, fr.service_fa)
  + COALESCE(heures_s.heures_compl_fa, fr.heures_compl_fa)
                                                                              hetd_fa,
  COALESCE(heures_s.service_fc, fr.service_fc)
  + COALESCE(heures_s.heures_compl_fc, fr.heures_compl_fc)
  + COALESCE(heures_s.heures_compl_fc_majorees, fr.heures_compl_fc_majorees)
                                                                              hetd_fc,
  COALESCE(heures_s.total, fr.total)                                          hetd_total,




  tagr.libelle                                                                type_agrement,
  CASE WHEN agr.id IS NULL THEN 0 ELSE 1 END                                  agree,
  agr.date_decision                                                           date_decision,
  u.display_name                                                              modificateur,
  agr.histo_modification                                                      date_modification
FROM
            tbl_agrement             ta
       JOIN intervenant               i ON i.id = ta.intervenant_id
       JOIN statut_intervenant       si ON si.id = i.statut_id
       JOIN annee                     a ON a.id = ta.annee_id
       JOIN type_agrement          tagr ON tagr.id = ta.type_agrement_id
       JOIN type_volume_horaire     tvh ON tvh.code = 'PREVU'
       JOIN etat_volume_horaire     evh ON evh.code = 'valide'


  LEFT JOIN STRUCTURE                 s ON s.id = ta.structure_id
  LEFT JOIN agrement                agr ON agr.id = ta.agrement_id
  LEFT JOIN utilisateur               u ON u.id = agr.histo_modificateur_id
  LEFT JOIN discipline                d ON d.id = i.discipline_id

  LEFT JOIN formule_resultat         fr ON fr.intervenant_id = i.id
                                       AND fr.type_volume_horaire_id = tvh.id
                                       AND fr.etat_volume_horaire_id = evh.id

  LEFT JOIN heures_s                    ON heures_s.intervenant_id = i.id
                                       AND heures_s.structure_id = s.id;
/

CREATE OR REPLACE FORCE VIEW V_CONTRAT_MAIN AS
WITH hs AS (
  SELECT contrat_id, SUM(heures) "serviceTotal" FROM V_CONTRAT_SERVICES GROUP BY contrat_id
)
SELECT
  ct.annee_id,
  ct.structure_id,
  ct.intervenant_id,
  ct.formule_resultat_id,
  ct.id contrat_id,

  ct."annee",
  ct."nom",
  ct."prenom",
  ct."civilite",
  ct."e",
  ct."dateNaissance",
  ct."adresse",
  ct."numInsee",
  ct."statut",
  ct."totalHETD",
  ct."tauxHoraireValeur",
  ct."tauxHoraireDate",
  ct."dateSignature",
  ct."modifieComplete",
  CASE WHEN ct.est_contrat=1 THEN 1 ELSE NULL END "contrat1",
  CASE WHEN ct.est_contrat=1 THEN NULL ELSE 1 END "avenant1",
  CASE WHEN ct.est_contrat=1 THEN '3' ELSE '2' END "n",
  to_char(SYSDATE, 'dd/mm/YYYY - hh24:mi:ss') "horodatage",
  'Exemplaire à conserver' "exemplaire1",
  'Exemplaire à retourner' || ct."exemplaire2" "exemplaire2",
  ct."serviceTotal",

  CASE ct.est_contrat
    WHEN 1 THEN -- contrat
      'Contrat de travail'
    ELSE
      'Avenant au contrat de travail initial modifiant le volume horaire initial'
      || ' de recrutement en qualité'
  END                                         "titre",
  CASE WHEN ct.est_atv = 1 THEN
    'd''agent temporaire vacataire'
  ELSE
    'de chargé' || ct."e" || ' d''enseignement vacataire'
  END                                         "qualite",

  CASE
    WHEN ct.est_projet = 1 AND ct.est_contrat = 1 THEN 'Projet de contrat'
    WHEN ct.est_projet = 0 AND ct.est_contrat = 1 THEN 'Contrat n°' || ct.id
    WHEN ct.est_projet = 1 AND ct.est_contrat = 0 THEN 'Projet d''avenant'
    WHEN ct.est_projet = 0 AND ct.est_contrat = 0 THEN 'Avenant n°' || ct.contrat_id || '.' || ct.numero_avenant
  END                                         "titreCourt"
FROM
(
  SELECT
    c.*,
    i.annee_id                                                                                    annee_id,
    fr.id                                                                                         formule_resultat_id,
    a.libelle                                                                                     "annee",
    COALESCE(d.nom_usuel,i.nom_usuel)                                                             "nom",
    COALESCE(d.prenom,i.prenom)                                                                   "prenom",
    civ.libelle_court                                                                             "civilite",
    CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END                                                 "e",
    to_char(COALESCE(d.date_naissance,i.date_naissance), 'dd/mm/YYYY')                            "dateNaissance",
    COALESCE(
    ose_divers.formatted_adresse(
        d.adresse_precisions, d.adresse_lieu_dit,
        d.adresse_numero, d.adresse_numero_compl_id, d.adresse_voirie_id, d.adresse_voie,
        d.adresse_code_postal, d.adresse_commune, d.adresse_pays_id
      ),
      ose_divers.formatted_adresse(
        i.adresse_precisions, i.adresse_lieu_dit,
        i.adresse_numero, i.adresse_numero_compl_id, i.adresse_voirie_id, i.adresse_voie,
        i.adresse_code_postal, i.adresse_commune, i.adresse_pays_id
      )
    ) "adresse",
    COALESCE(d.numero_insee,i.numero_insee)                                                       "numInsee",
    si.libelle                                                                                    "statut",
    REPLACE(ltrim(to_char(COALESCE(c.total_hetd, fr.total,0), '999999.00')),'.',',')              "totalHETD",
    REPLACE(ltrim(to_char(COALESCE(th.valeur,0), '999999.00')),'.',',')                           "tauxHoraireValeur",
    COALESCE(to_char(th.histo_creation, 'dd/mm/YYYY'), 'TAUX INTROUVABLE')                        "tauxHoraireDate",
    to_char(COALESCE(v.histo_creation, c.histo_creation), 'dd/mm/YYYY')                           "dateSignature",
    CASE WHEN c.structure_id <> COALESCE(cp.structure_id,0) THEN 'modifié' ELSE 'complété' END    "modifieComplete",
    CASE WHEN s.aff_adresse_contrat = 1 THEN
      ' signé à l''adresse suivante :' || CHR(13) || CHR(10) ||
      s.libelle_court || ' - ' || REPLACE(ose_divers.formatted_adresse(
        s.adresse_precisions, s.adresse_lieu_dit,
        s.adresse_numero, s.adresse_numero_compl_id, s.adresse_voirie_id, s.adresse_voie,
        s.adresse_code_postal, s.adresse_commune, s.adresse_pays_id
      ), CHR(13), ' - ')
    ELSE '' END                                                                                   "exemplaire2",
    REPLACE(ltrim(to_char(COALESCE(hs."serviceTotal",0), '999999.00')),'.',',')                   "serviceTotal",
    CASE WHEN c.contrat_id IS NULL THEN 1 ELSE 0 END                                              est_contrat,
    CASE WHEN v.id IS NULL THEN 1 ELSE 0 END                                                      est_projet,
    si.tem_atv                                                                                    est_atv

  FROM
              contrat               c
         JOIN type_contrat         tc ON tc.id = c.type_contrat_id
         JOIN intervenant           i ON i.id = c.intervenant_id
         JOIN annee                 a ON a.id = i.annee_id
         JOIN statut_intervenant   si ON si.id = i.statut_id
         JOIN STRUCTURE             s ON s.id = c.structure_id
    LEFT JOIN intervenant_dossier   d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
         JOIN civilite            civ ON civ.id = COALESCE(d.civilite_id,i.civilite_id)
    LEFT JOIN validation            v ON v.id = c.validation_id AND v.histo_destruction IS NULL
         JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
         JOIN etat_volume_horaire evh ON evh.code = 'valide'
    LEFT JOIN formule_resultat     fr ON fr.intervenant_id = i.id AND fr.type_volume_horaire_id = tvh.id AND fr.etat_volume_horaire_id = evh.id
    LEFT JOIN taux_horaire_hetd    th ON c.histo_creation BETWEEN th.histo_creation AND COALESCE(th.histo_destruction,SYSDATE)
    LEFT JOIN                      hs ON hs.contrat_id = c.id
    LEFT JOIN contrat              cp ON cp.id = c.contrat_id
  WHERE
    c.histo_destruction IS NULL
) ct;
/

CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
SELECT
  c.id                                             contrat_id,
  str.libelle_court                                "serviceComposante",
  ep.code                                          "serviceCode",
  ep.libelle                                       "serviceLibelle",
  SUM(vh.heures)                                   heures,
  REPLACE(ltrim(to_char(SUM(vh.heures), '999999.00')),'.',',') "serviceHeures"
FROM
            contrat                  c
       JOIN STRUCTURE              str ON str.id = c.structure_id
       JOIN volume_horaire          vh ON vh.contrat_id = c.id AND vh.histo_destruction IS NULL
       JOIN service                  s ON s.id = vh.service_id
  LEFT JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id
GROUP BY
  c.id, str.libelle_court, ep.code, ep.libelle;
/

CREATE OR REPLACE FORCE VIEW V_ETAT_PAIEMENT AS
SELECT
             annee_id,
             type_intervenant_id,
             structure_id,
             periode_id,
             intervenant_id,
             centre_cout_id,
             domaine_fonctionnel_id,

             annee_id || '/' || (annee_id+1) annee,
             etat,
             composante,
             date_mise_en_paiement,
             periode,
             statut,
             intervenant_code,
             intervenant_nom,
             intervenant_numero_insee,
             centre_cout_code,
             centre_cout_libelle,
             domaine_fonctionnel_code,
             domaine_fonctionnel_libelle,
             hetd,
             CASE WHEN pourc_ecart >= 0 THEN
                 CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END DESC) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
                  ELSE
                 CASE WHEN RANK() OVER (PARTITION BY periode_id, intervenant_id, etat, structure_id ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / ABS(pourc_ecart) * 0.001) ELSE hetd_pourc END
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

                  1-CASE WHEN hetd > 0 THEN SUM( hetd_pourc ) OVER ( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END pourc_ecart


           FROM (

                SELECT
                       periode_id,
                       structure_id,
                       type_intervenant_id,
                       intervenant_id,
                       annee_id,
                       centre_cout_id,
                       domaine_fonctionnel_id,
                       etat,
                       composante,
                       date_mise_en_paiement,
                       periode,
                       statut,
                       intervenant_code,
                       intervenant_nom,
                       intervenant_numero_insee,
                       centre_cout_code,
                       centre_cout_libelle,
                       domaine_fonctionnel_code,
                       domaine_fonctionnel_libelle,
                       hetd,
                       ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) hetd_pourc,
                       ROUND( hetd * taux_horaire, 2 ) hetd_montant,
                       ROUND( fc_majorees * taux_horaire, 2 ) rem_fc_d714,
                       exercice_aa,
                       ROUND( exercice_aa * taux_horaire, 2 ) exercice_aa_montant,
                       exercice_ac,
                       ROUND( exercice_ac * taux_horaire, 2 ) exercice_ac_montant,


                       (CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END)
                             -
                       ROUND( CASE WHEN hetd > 0 THEN hetd / SUM( hetd ) OVER( PARTITION BY periode_id, intervenant_id, etat, structure_id) ELSE 0 END, 3 ) pourc_diff

                FROM (
                     WITH dep AS ( -- détails par état de paiement
                         SELECT
                                CASE WHEN th.code = 'fc_majorees' THEN 1 ELSE 0 END                 is_fc_majoree,
                                p.id                                                                periode_id,
                                s.id                                                                structure_id,
                                i.id                                                                intervenant_id,
                                i.annee_id                                                          annee_id,
                                cc.id                                                               centre_cout_id,
                                df.id                                                               domaine_fonctionnel_id,
                                ti.id                                                               type_intervenant_id,
                                CASE
                                      WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
                                      ELSE 'mis-en-paiement'
                                    END                                                                 etat,

                                TRIM(p.libelle_long || ' ' || to_char( add_months( a.date_debut, p.ecart_mois ), 'yyyy' )) periode,
                                mep.date_mise_en_paiement                                           date_mise_en_paiement,
                                s.libelle_court                                                     composante,
                                ti.libelle                                                          statut,
                                i.source_code                                                       intervenant_code,
                                i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
                                i.numero_insee                                                      intervenant_numero_insee,
                                cc.source_code                                                      centre_cout_code,
                                cc.libelle                                                          centre_cout_libelle,
                                df.source_code                                                      domaine_fonctionnel_code,
                                df.libelle                                                          domaine_fonctionnel_libelle,
                                CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
                                CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
                                mep.heures * 4 / 10                                                 exercice_aa,
                                mep.heures * 6 / 10                                                 exercice_ac,
                             --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 4 / 10                                                 exercice_aa,
                             --CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 6 / 10                                                 exercice_ac,
                                OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
                         FROM
                              v_mep_intervenant_structure  mis
                                    JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
                                    JOIN type_heures              th ON  th.id = mep.type_heures_id
                                    JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
                                    JOIN intervenant               i ON   i.id = mis.intervenant_id      AND i.histo_destruction IS NULL
                                    JOIN annee                     a ON   a.id = i.annee_id
                                    JOIN statut_intervenant       si ON  si.id = i.statut_id
                                    JOIN type_intervenant         ti ON  ti.id = si.type_intervenant_id
                                    JOIN STRUCTURE                 s ON   s.id = mis.structure_id
                                    LEFT JOIN validation           v ON   v.id = mep.validation_id       AND v.histo_destruction IS NULL
                                    LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
                                    LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
                     )
                     SELECT
                            periode_id,
                            structure_id,
                            type_intervenant_id,
                            intervenant_id,
                            annee_id,
                            centre_cout_id,
                            domaine_fonctionnel_id,
                            etat,
                            periode,
                            composante,
                            date_mise_en_paiement,
                            statut,
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
                              periode_id,
                              structure_id,
                              type_intervenant_id,
                              intervenant_id,
                              annee_id,
                              centre_cout_id,
                              domaine_fonctionnel_id,
                              etat,
                              periode,
                              composante,
                              date_mise_en_paiement,
                              statut,
                              intervenant_code,
                              intervenant_nom,
                              intervenant_numero_insee,
                              centre_cout_code,
                              centre_cout_libelle,
                              domaine_fonctionnel_code,
                              domaine_fonctionnel_libelle,
                              taux_horaire,
                              is_fc_majoree
                     )
                         dep2
                )
                    dep3
           )
               dep4
      ORDER BY
               annee_id,
               type_intervenant_id,
               structure_id,
               periode_id,
               intervenant_nom;
/

CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_WINPAIE AS
SELECT
  annee_id,
  type_intervenant_id,
  structure_id,
  periode_id,
  intervenant_id,

  insee,
  nom,
  '20' carte,
  code_origine,
  CASE WHEN type_intervenant_code = 'P' THEN '0204' ELSE '2251' END retenue,
  '0' sens,
  'B' mc,
  nbu,
  montant,
  libelle || ' ' || LPAD(TO_CHAR(FLOOR(nbu)),2,'00') || ' H' ||
  CASE to_char(ROUND( nbu-FLOOR(nbu), 2 )*100,'00')
  WHEN ' 00' THEN '' ELSE ' ' || LPAD(ROUND( nbu-FLOOR(nbu), 2 )*100,2,'00') END libelle
FROM (
  SELECT
    i.annee_id                                                                                          annee_id,
    ti.id                                                                                               type_intervenant_id,
    ti.code                                                                                             type_intervenant_code,
    t2.structure_id                                                                                     structure_id,
    t2.periode_paiement_id                                                                              periode_id,
    i.id                                                                                                intervenant_id,
    CASE WHEN i.numero_insee IS NULL THEN '''' ELSE
      '''' || TRIM(i.numero_insee)
    END                                                                                                 insee,
    i.nom_usuel || ',' || i.prenom                                                                      nom,
    t2.code_origine                                                                                     code_origine,
    CASE WHEN ind <> CEIL(t2.nbu/max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu*(ind-1) END                nbu,
    t2.nbu                                                                                              tnbu,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) )                          montant,
    COALESCE(t2.unite_budgetaire,'') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id+1)      libelle
  FROM (
    SELECT
      structure_id,
      periode_paiement_id,
      intervenant_id,
      code_origine,
      ROUND( SUM(nbu), 2) nbu,
      unite_budgetaire,
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
          cc.unite_budgetaire,
          mep.date_mise_en_paiement
        FROM
          v_mep_intervenant_structure  mis
          JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
          JOIN centre_cout              cc ON cc.id = mep.centre_cout_id
          JOIN type_heures              th ON th.id = mep.type_heures_id
        WHERE
          mep.date_mise_en_paiement IS NOT NULL
          AND mep.periode_paiement_id IS NOT NULL
          AND th.eligible_extraction_paie = 1
      )
      SELECT
        mep.id,
        mep.structure_id,
        mep.periode_paiement_id,
        mep.intervenant_id,
        2 code_origine,
        mep.heures * 4 / 10 nbu,
        mep.unite_budgetaire,
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
        mep.unite_budgetaire,
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
      unite_budgetaire,
      date_mise_en_paiement
  ) t2
  JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu ON CEIL(t2.nbu / max_nbu) >= ind
  JOIN intervenant         i ON i.id = t2.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant   ti ON ti.id = si.type_intervenant_id
  JOIN STRUCTURE           s ON s.id = t2.structure_id
) t3
ORDER BY
  annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC;
/

CREATE OR REPLACE FORCE VIEW V_EXPORT_SERVICE AS
WITH t AS ( SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  NULL                              service_referentiel_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  NULL                              structure_aff_id,
  NULL                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  NULL                              fonction_referentiel_id,
  NULL                              motif_non_paiement_id,

  s.description                     service_description,

  vh.heures                         heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
  NULL                              motif_non_paiement,
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
  NULL                              service_ref_formation,
  NULL                              commentaires
FROM
  formule_resultat_vh                frvh
  JOIN formule_resultat                fr ON fr.id = frvh.formule_resultat_id
  JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND vh.histo_destruction IS NULL
  JOIN service                          s ON s.id = vh.service_id AND s.intervenant_id = fr.intervenant_id AND s.histo_destruction IS NULL

UNION ALL

SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  NULL                              service_referentiel_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  vhe.etat_volume_horaire_id        etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etablissement_id                etablissement_id,
  NULL                              structure_aff_id,
  NULL                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  NULL                              fonction_referentiel_id,
  mnp.id                            motif_non_paiement_id,

  s.description                     service_description,

  vh.heures                         heures,
  0                                 heures_ref,
  1                                 heures_non_payees,
  mnp.libelle_court                 motif_non_paiement,
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
  COALESCE(fr.solde,0)              solde,
  NULL                              service_ref_formation,
  NULL                              commentaires
FROM
  volume_horaire                  vh
  JOIN service                     s ON s.id = vh.service_id
  JOIN v_vol_horaire_etat_multi  vhe ON vhe.volume_horaire_id = vh.id
  JOIN motif_non_paiement        mnp ON mnp.id = vh.motif_non_paiement_id
  LEFT JOIN formule_resultat      fr ON fr.intervenant_id = s.intervenant_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
WHERE
  vh.histo_destruction IS NULL
  AND s.histo_destruction IS NULL

UNION ALL

SELECT
  'vh_ref_' || vhr.id               id,
  NULL                              service_id,
  sr.id                             service_referentiel_id,
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
  NULL                              motif_non_paiement_id,

  NULL                              service_description,

  0                                 heures,
  vhr.heures                        heures_ref,
  0                                 heures_non_payees,
  NULL                              motif_non_paiement,
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
  sr.formation                      service_ref_formation,
  sr.commentaires                   commentaires
FROM
  formule_resultat_vh_ref       frvr
  JOIN formule_resultat           fr ON fr.id = frvr.formule_resultat_id
  JOIN volume_horaire_ref        vhr ON vhr.id =  frvr.volume_horaire_ref_id AND vhr.histo_destruction IS NULL
  JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.histo_destruction IS NULL

UNION ALL

SELECT
  'vh_0_' || i.id                   id,
  NULL                              service_id,
  NULL                              service_referentiel_id,
  i.id                              intervenant_id,
  tvh.id                            type_volume_horaire_id,
  evh.id                            etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  NULL                              structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  NULL                              fonction_referentiel_id,
  NULL                              motif_non_paiement_id,

  NULL                              service_description,

  0                                 heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
  NULL                              motif_non_paiement,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  0                                 service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_compl_fc_majorees,
  NULL                              heures_compl_referentiel,
  0                                 total,
  0                                 solde,
  NULL                              service_ref_formation,
  NULL                              commentaires
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN etat_volume_horaire evh ON evh.code IN ('saisi','valide')
  JOIN type_volume_horaire tvh ON tvh.code IN ('PREVU','REALISE')
  LEFT JOIN modification_service_du msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
  LEFT JOIN motif_modification_service mms ON mms.id = msd.motif_id
WHERE
  i.histo_destruction IS NULL
  AND si.service_statutaire > 0
GROUP BY
  i.id, si.service_statutaire, evh.id, tvh.id
HAVING
  si.service_statutaire + SUM(msd.heures * mms.multiplicateur) = 0


), ponds AS (
SELECT
  ep.id                                          element_pedagogique_id,
  MAX(COALESCE( m.ponderation_service_du, 1))    ponderation_service_du,
  MAX(COALESCE( m.ponderation_service_compl, 1)) ponderation_service_compl
FROM
            element_pedagogique ep
  LEFT JOIN element_modulateur  em ON em.element_id = ep.id
                                  AND em.histo_destruction IS NULL
  LEFT JOIN modulateur          m ON m.id = em.modulateur_id
WHERE
  ep.histo_destruction IS NULL
GROUP BY
  ep.id
)
SELECT
  t.id                              id,
  t.service_id                      service_id,
  t.service_referentiel_id          service_referentiel_id,
  i.id                              intervenant_id,
  si.id                             statut_intervenant_id,
  ti.id                             type_intervenant_id,
  i.annee_id                        annee_id,
  t.type_volume_horaire_id          type_volume_horaire_id,
  t.etat_volume_horaire_id          etat_volume_horaire_id,
  etab.id                           etablissement_id,
  saff.id                           structure_aff_id,
  sens.id                           structure_ens_id,
  gtf.id                            groupe_type_formation_id,
  tf.id                             type_formation_id,
  CASE
    WHEN 1 <> gtf.pertinence_niveau OR etp.niveau IS NULL OR etp.niveau < 1 OR gtf.id < 1 THEN NULL
    ELSE gtf.id * 256 + niveau END  niveau_formation_id,
  etp.id                            etape_id,
  ep.id                             element_pedagogique_id,
  t.periode_id                      periode_id,
  t.type_intervention_id            type_intervention_id,
  t.fonction_referentiel_id         fonction_referentiel_id,
  di.id                             intervenant_discipline_id,
  de.id                             element_discipline_id,
  t.motif_non_paiement_id           motif_non_paiement_id,

  tvh.libelle || ' ' || evh.libelle type_etat,
  his.histo_modification            service_date_modification,

  i.source_code                     intervenant_code,
  i.nom_usuel || ' ' || i.prenom    intervenant_nom,
  i.date_naissance                  intervenant_date_naissance,
  si.libelle                        intervenant_statut_libelle,
  ti.code                           intervenant_type_code,
  ti.libelle                        intervenant_type_libelle,
  g.source_code                     intervenant_grade_code,
  g.libelle_court                   intervenant_grade_libelle,
  di.source_code                    intervenant_discipline_code,
  di.libelle_court                  intervenant_discipline_libelle,
  saff.libelle_court                service_structure_aff_libelle,

  sens.libelle_court                service_structure_ens_libelle,
  etab.libelle                      etablissement_libelle,
  gtf.libelle_court                 groupe_type_formation_libelle,
  tf.libelle_court                  type_formation_libelle,
  etp.niveau                        etape_niveau,
  etp.source_code                   etape_code,
  etp.libelle                       etape_libelle,
  ep.source_code                    element_code,
  COALESCE(ep.libelle,to_char(t.service_description)) element_libelle,
  de.source_code                    element_discipline_code,
  de.libelle_court                  element_discipline_libelle,
  fr.libelle_long                   fonction_referentiel_libelle,
  ep.taux_fi                        element_taux_fi,
  ep.taux_fc                        element_taux_fc,
  ep.taux_fa                        element_taux_fa,
  t.service_ref_formation           service_ref_formation,
  t.commentaires                    commentaires,
  p.libelle_court                   periode_libelle,
  CASE WHEN ponds.ponderation_service_compl = 1 THEN NULL ELSE ponds.ponderation_service_compl END element_ponderation_compl,
  src.libelle                       element_source_libelle,

  t.heures                          heures,
  t.heures_ref                      heures_ref,
  t.heures_non_payees               heures_non_payees,
  t.motif_non_paiement              motif_non_paiement,
  si.service_statutaire             service_statutaire,
  fi.heures_service_modifie         service_du_modifie,
  t.service_fi                      service_fi,
  t.service_fa                      service_fa,
  t.service_fc                      service_fc,
  t.service_referentiel             service_referentiel,
  t.heures_compl_fi                 heures_compl_fi,
  t.heures_compl_fa                 heures_compl_fa,
  t.heures_compl_fc                 heures_compl_fc,
  t.heures_compl_fc_majorees        heures_compl_fc_majorees,
  t.heures_compl_referentiel        heures_compl_referentiel,
  t.total                           total,
  t.solde                           solde,
  v.histo_modification              date_cloture_realise

FROM
  t
  JOIN intervenant                        i ON i.id     = t.intervenant_id AND i.histo_destruction IS NULL
  JOIN statut_intervenant                si ON si.id    = i.statut_id
  JOIN type_intervenant                  ti ON ti.id    = si.type_intervenant_id
  JOIN etablissement                   etab ON etab.id  = t.etablissement_id
  JOIN type_volume_horaire              tvh ON tvh.id   = t.type_volume_horaire_id
  JOIN etat_volume_horaire              evh ON evh.id   = t.etat_volume_horaire_id
  LEFT JOIN histo_intervenant_service   his ON his.intervenant_id = i.id AND his.type_volume_horaire_id = tvh.id AND his.referentiel = 0
  LEFT JOIN grade                         g ON g.id     = i.grade_id
  LEFT JOIN discipline                   di ON di.id    = i.discipline_id
  LEFT JOIN STRUCTURE                  saff ON saff.id  = i.structure_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique          ep ON ep.id    = t.element_pedagogique_id
  LEFT JOIN discipline                   de ON de.id    = ep.discipline_id
  LEFT JOIN STRUCTURE                  sens ON sens.id  = NVL(t.structure_ens_id, ep.structure_id)
  LEFT JOIN periode                       p ON p.id     = t.periode_id
  LEFT JOIN SOURCE                      src ON src.id   = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
  LEFT JOIN etape                       etp ON etp.id   = ep.etape_id
  LEFT JOIN type_formation               tf ON tf.id    = etp.type_formation_id AND tf.histo_destruction IS NULL
  LEFT JOIN groupe_type_formation       gtf ON gtf.id   = tf.groupe_id AND gtf.histo_destruction IS NULL
  LEFT JOIN v_formule_intervenant        fi ON fi.intervenant_id = i.id
  LEFT JOIN ponds                     ponds ON ponds.element_pedagogique_id = ep.id
  LEFT JOIN fonction_referentiel         fr ON fr.id    = t.fonction_referentiel_id
  LEFT JOIN type_validation              tv ON tvh.code = 'REALISE' AND tv.code = 'CLOTURE_REALISE'
  LEFT JOIN validation                    v ON v.intervenant_id = i.id AND v.type_validation_id = tv.id AND v.histo_destruction IS NULL;
/

CREATE OR REPLACE FORCE VIEW V_FR_SERVICE_CENTRE_COUT AS
SELECT
  frs.id formule_resultat_service_id, cc.id centre_cout_id
FROM
  formule_resultat_service   frs
  JOIN parametre               p ON p.nom = 'centres_couts_paye'
  JOIN service                 s ON s.id = frs.service_id
  JOIN intervenant             i ON i.id = s.intervenant_id
  JOIN statut_intervenant     si ON si.id = i.statut_id
  JOIN type_intervenant       ti ON ti.id = si.type_intervenant_id
  JOIN element_pedagogique    ep ON ep.id = s.element_pedagogique_id
  JOIN centre_cout            cc ON cc.histo_destruction IS NULL

  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id
                                AND ccs.structure_id = CASE WHEN p.valeur = 'enseignement' OR ti.code = 'E' THEN ep.structure_id ELSE COALESCE(i.structure_id,ep.structure_id) END
                                AND ccs.histo_destruction IS NULL

  JOIN cc_activite             a ON a.id = cc.activite_id
                                AND a.histo_destruction IS NULL

  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id
                                AND tr.histo_destruction IS NULL
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  )

UNION

SELECT
  frs.id formule_resultat_service_id, cc.id centre_cout_id
FROM
  formule_resultat_service   frs
  JOIN service                 s ON s.id = frs.service_id
                                AND s.element_pedagogique_id IS NULL

  JOIN intervenant             i ON i.id = s.intervenant_id
  JOIN centre_cout            cc ON cc.histo_destruction IS NULL

  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id
                                AND ccs.structure_id = i.structure_id
                                AND ccs.histo_destruction IS NULL

  JOIN cc_activite             a ON a.id = cc.activite_id
                                AND a.histo_destruction IS NULL

  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id
                                AND tr.histo_destruction IS NULL
WHERE
  (
    (frs.heures_compl_fi > 0 AND tr.fi = 1 AND a.fi = 1 )
    OR (frs.heures_compl_fa > 0 AND tr.fa = 1 AND a.fa = 1 )
    OR (frs.heures_compl_fc > 0 AND tr.fc = 1 AND a.fc = 1 )
    OR (frs.heures_compl_fc_majorees > 0 AND tr.fc_majorees = 1 AND a.fc_majorees = 1 )
  );
/

CREATE OR REPLACE FORCE VIEW V_IMPORT_TAB_COLS AS
WITH importable_tables (TABLE_NAME )AS (
  SELECT
  t.table_name
FROM
  user_tab_cols c
  JOIN user_tables t ON t.table_name = c.table_name
WHERE
  c.column_name = 'SOURCE_CODE'

MINUS

SELECT
  mview_name TABLE_NAME
FROM
  USER_MVIEWS
), c_values (TABLE_NAME, column_name, c_table_name, c_column_name) AS (
SELECT
  tc.table_name,
  tc.column_name,
  pcc.table_name c_table_name,
  pcc.column_name c_column_name
FROM
  user_tab_cols tc
  JOIN USER_CONS_COLUMNS cc ON cc.table_name = tc.table_name AND cc.column_name = tc.column_name
  JOIN USER_CONSTRAINTS c ON c.constraint_name = cc.constraint_name
  JOIN USER_CONSTRAINTS pc ON pc.constraint_name = c.r_constraint_name
  JOIN USER_CONS_COLUMNS pcc ON pcc.constraint_name = pc.constraint_name
WHERE
  c.constraint_type = 'R' AND pc.constraint_type = 'P'
)
SELECT
  tc.table_name,
  tc.column_name,
  CASE WHEN ',' || it.key_columns || ',' LIKE '%,' || tc.column_name || ',%' THEN 1 ELSE 0 END is_key,
  tc.data_type,
  CASE WHEN tc.char_length = 0 THEN NULL ELSE tc.char_length END LENGTH,
  CASE WHEN tc.nullable = 'Y' THEN 1 ELSE 0 END NULLABLE,
  CASE WHEN tc.data_default IS NOT NULL THEN 1 ELSE 0 END has_default,
  cv.c_table_name,
  cv.c_column_name,
  CASE WHEN stc.table_name IS NULL THEN 0 ELSE 1 END AS import_actif
FROM
  user_tab_cols tc
  JOIN importable_tables t ON t.table_name = tc.table_name
  LEFT JOIN import_tables it ON it.table_name = tc.table_name
  LEFT JOIN c_values cv ON cv.table_name = tc.table_name AND cv.column_name = tc.column_name
  LEFT JOIN user_tab_cols stc ON stc.table_name = 'SRC_' || tc.table_name AND stc.column_name = tc.column_name
WHERE
  tc.column_name NOT LIKE 'HISTO_%'
  AND tc.column_name <> 'ID'
  AND tc.table_name <> 'SYNC_LOG'
ORDER BY
  it.ordre, tc.table_name, tc.column_id;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_340 AS
SELECT
  rownum id,
  s.annee_id,
  s.intervenant_id,
  s.structure_id
FROM
  tbl_service s
  JOIN tbl_workflow w ON w.intervenant_id = s.intervenant_id AND w.structure_id = s.structure_id
WHERE
  s.type_intervenant_code = 'V'
  AND s.type_volume_horaire_code = 'PREVU'
  AND nbvh <> valide
  AND w.etape_code = 'CONTRAT'
  AND w.atteignable = 1
  AND w.objectif > 0
  AND w.realisation = w.objectif;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_361 AS
SELECT
  rownum id,
  t."INTERVENANT_ID",t."ANNEE_ID",t."STRUCTURE_ID",t."CONTRAT_ID"
FROM (
SELECT DISTINCT
  i.id intervenant_id,
  i.annee_id annee_id,
  c.structure_id structure_id,
  c.id contrat_id
FROM
  contrat                c
  JOIN intervenant i ON i.id = c.intervenant_id
  JOIN tbl_workflow w ON w.intervenant_id = i.id AND w.structure_id = c.structure_id AND w.etape_code = 'CONTRAT' AND w.atteignable = 1
  JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
  LEFT JOIN contrat_fichier cf ON cf.contrat_id = c.id
  LEFT JOIN fichier f ON f.id = cf.fichier_id AND f.histo_destruction IS NULL
WHERE
  c.histo_destruction IS NULL
  AND f.id IS NULL
  AND c.date_envoi_email IS NOT NULL
) t;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_410 AS
SELECT
  rownum id,
  d.annee_id,
  d.intervenant_id,
  i.structure_id
FROM
  tbl_dossier d
  JOIN intervenant i ON i.id = d.intervenant_id
WHERE
  d.dossier_id IS NOT NULL
  /*Complétude des différents bloc dossier*/
  AND d.completude_identite = 1
  AND d.completude_identite_comp = 1
  AND d.completude_contact = 1
  AND d.completude_adresse = 1
  AND d.completude_insee = 1
  AND d.completude_iban = 1
  AND d.completude_employeur = 1
  AND d.completude_autres = 1
  AND d.completude_statut = 1
  AND d.validation_id IS NULL
  AND d.peut_saisir_dossier = 1;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_730 AS
SELECT rownum id, t."ANNEE_ID", t."INTERVENANT_ID", t."STRUCTURE_ID"
FROM (
         SELECT DISTINCT w.annee_id,
                         w.intervenant_id,
                         w.structure_id
         FROM tbl_workflow w
         WHERE w.etape_code = 'SERVICE_VALIDATION'
           AND w.type_intervenant_code = 'P'
           AND w.atteignable = 1
           AND w.objectif > w.realisation
     ) t;
/

CREATE OR REPLACE FORCE VIEW V_INDICATEUR_740 AS
SELECT rownum id, t."ANNEE_ID", t."INTERVENANT_ID", t."STRUCTURE_ID"
FROM (
         SELECT DISTINCT w.annee_id,
                         w.intervenant_id,
                         w.structure_id
         FROM tbl_workflow w
         WHERE w.etape_code = 'SERVICE_VALIDATION_REALISE'
           AND w.type_intervenant_code = 'P'
           AND w.atteignable = 1
           AND w.objectif > w.realisation
     ) t;
/

CREATE OR REPLACE FORCE VIEW V_NIVEAU_FORMATION AS
SELECT DISTINCT
  CASE
    WHEN 1 <> gtf.pertinence_niveau OR e.niveau IS NULL OR e.niveau < 1 OR gtf.id < 1 THEN NULL
    ELSE gtf.id * 256 + niveau END id,
  gtf.libelle_court || e.niveau code,
  gtf.libelle_long,
  e.niveau,
  gtf.id groupe_type_formation_id
FROM
  etape e
  JOIN type_formation tf ON tf.id = e.type_formation_id AND tf.histo_destruction IS NULL
  JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id AND gtf.histo_destruction IS NULL
WHERE
  e.histo_destruction IS NULL
  AND CASE
    WHEN 1 <> gtf.pertinence_niveau OR e.niveau IS NULL OR e.niveau < 1 OR gtf.id < 1 THEN NULL
    ELSE gtf.id * 256 + niveau END IS NOT NULL
ORDER BY
  gtf.libelle_long, e.niveau;
/

CREATE OR REPLACE FORCE VIEW V_PRIVILEGES_ROLES AS
WITH statuts_roles AS (
SELECT
  rp.privilege_id,
  r.code ROLE
FROM
  role_privilege rp
  JOIN ROLE r ON r.id = rp.role_id AND r.histo_destruction IS NULL

UNION ALL

SELECT
  sp.privilege_id,
  'statut/' || s.code ROLE
FROM
  statut_privilege sp
  JOIN statut_intervenant s ON s.id = sp.statut_id AND s.histo_destruction IS NULL
)
SELECT
  cp.code || '-' || p.code privilege,
  sr.role
FROM
  privilege p
  JOIN categorie_privilege cp ON cp.id = p.categorie_id
  LEFT JOIN statuts_roles sr ON sr.privilege_id = p.id;
/

CREATE OR REPLACE FORCE VIEW V_TBL_AGREMENT AS
WITH i_s AS (
  SELECT
    fr.intervenant_id,
    ep.structure_id structure_id
  FROM
    formule_resultat fr
    JOIN type_volume_horaire  tvh ON tvh.code = 'PREVU' AND tvh.id = fr.type_volume_horaire_id
    JOIN etat_volume_horaire  evh ON evh.code = 'valide' AND evh.id = fr.etat_volume_horaire_id

    JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
    JOIN service s ON s.id = frs.service_id
    JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  WHERE
    frs.total > 0
    /*@INTERVENANT_ID=fr.intervenant_id*/
),
avi AS (
    SELECT
        i.code                intervenant_code,
        i.annee_id            annee_id,
        a.type_agrement_id    type_agrement_id,
        a.id                  agrement_id,
        a.structure_id        structure_id
    FROM intervenant i
      JOIN agrement a ON a.intervenant_id = i.id
    WHERE
      a.histo_destruction IS NULL
)
SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE" FROM (
    SELECT
      i.annee_id                     annee_id,
      CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END   annee_agrement,
      tas.type_agrement_id                       type_agrement_id,
      i.id                                       intervenant_id,
      i.code                                     code_intervenant,
      NULL                                       structure_id,
      tas.obligatoire                            obligatoire,
      avi.agrement_id                       agrement_id,
      tas.duree_vie                              duree_vie,
      RANK() OVER(
        PARTITION BY i.code,i.annee_id ORDER BY
        CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END DESC
      ) rank
    FROM
      type_agrement                  ta
      JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                        AND tas.histo_destruction IS NULL

      JOIN intervenant                 i ON i.histo_destruction IS NULL
                                        AND i.statut_id = tas.statut_intervenant_id

      JOIN                           i_s ON i_s.intervenant_id = i.id

      LEFT JOIN                      avi ON i.code = avi.intervenant_code
                                        AND avi.type_agrement_id = tas.type_agrement_id
                                        AND i.annee_id < avi.annee_id + tas.duree_vie
                                        AND i.annee_id >= avi.annee_id


    WHERE
      ta.code = 'CONSEIL_ACADEMIQUE'
      /*@INTERVENANT_ID=i.id*/
      /*@ANNEE_ID=i.annee_id*/
  )
WHERE
  rank = 1

UNION ALL
SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE" FROM (
    SELECT
      i.annee_id                                  annee_id,
      CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END    annee_agrement,
      tas.type_agrement_id                        type_agrement_id,
      i.id                                        intervenant_id,
      i.code                                      code_intervenant,
      i_s.structure_id                structure_id,
      tas.obligatoire                             obligatoire,
      avi.agrement_id                         agrement_id,
      tas.duree_vie                               duree_vie,
      RANK() OVER(
        PARTITION BY i.code,i.annee_id,i_s.structure_id ORDER BY
        CASE
        WHEN COALESCE (avi.agrement_id,0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END DESC
      ) rank
    FROM
      type_agrement                   ta
      JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                        AND tas.histo_destruction IS NULL

      JOIN intervenant                 i ON i.histo_destruction IS NULL
                                        AND i.statut_id = tas.statut_intervenant_id

      JOIN                           i_s ON i_s.intervenant_id = i.id

      LEFT JOIN                      avi ON i.code = avi.intervenant_code
                                        AND avi.type_agrement_id = tas.type_agrement_id
                                        AND COALESCE(avi.structure_id,0) = COALESCE(i_s.structure_id,0)
                                        AND i.annee_id < avi.annee_id + tas.duree_vie
                                        AND i.annee_id >= avi.annee_id


    WHERE
      ta.code = 'CONSEIL_RESTREINT'
      /*@INTERVENANT_ID=i.id*/
      /*@ANNEE_ID=i.annee_id*/
  )
WHERE
  rank = 1;
/

CREATE OR REPLACE FORCE VIEW V_TBL_CHARGENS AS
WITH t AS (
SELECT
  n.annee_id                        annee_id,
  n.noeud_id                        noeud_id,
  sn.scenario_id                    scenario_id,
  sne.type_heures_id                type_heures_id,
  ti.id                             type_intervention_id,

  n.element_pedagogique_id          element_pedagogique_id,
  n.element_pedagogique_etape_id    etape_id,
  sne.etape_id                      etape_ens_id,
  n.structure_id                    structure_id,
  n.groupe_type_formation_id        groupe_type_formation_id,

  vhe.heures                        heures,
  vhe.heures * ti.taux_hetd_service hetd,

  GREATEST(COALESCE(sns.ouverture, 1),1)                                           ouverture,
  GREATEST(COALESCE(sns.dedoublement, snsetp.dedoublement, csdd.dedoublement,1),1) dedoublement,
  COALESCE(sns.assiduite,1)                                                        assiduite,
  sne.effectif*COALESCE(sns.assiduite,1)                                           effectif,

  SUM(sne.effectif*COALESCE(sns.assiduite,1)) OVER (PARTITION BY n.noeud_id, sn.scenario_id, ti.id) t_effectif

FROM
            scenario_noeud_effectif    sne
       JOIN etape                        e ON e.id = sne.etape_id
                                          AND e.histo_destruction IS NULL

       JOIN scenario_noeud              sn ON sn.id = sne.scenario_noeud_id
                                          AND sn.histo_destruction IS NULL
                                          /*@NOEUD_ID=sn.noeud_id*/
                                          /*@SCENARIO_ID=sn.scenario_id*/

       JOIN tbl_noeud                       n ON n.noeud_id = sn.noeud_id
                                          /*@ANNEE_ID=n.annee_id*/
                                          /*@ELEMENT_PEDAGOGIQUE_ID=n.element_pedagogique_id*/
                                          /*@ETAPE_ID=n.element_pedagogique_etape_id*/

       JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = n.element_pedagogique_id
                                          AND vhe.histo_destruction IS NULL
                                          AND vhe.heures > 0

       JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id

  LEFT JOIN tbl_noeud                 netp ON netp.etape_id = e.id

  LEFT JOIN scenario_noeud           snetp ON snetp.scenario_id = sn.scenario_id
                                          AND snetp.noeud_id = netp.noeud_id
                                          AND snetp.histo_destruction IS NULL

  LEFT JOIN scenario_noeud_seuil    snsetp ON snsetp.scenario_noeud_id = snetp.id
                                          AND snsetp.type_intervention_id = ti.id

  LEFT JOIN tbl_chargens_seuils_def   csdd ON csdd.annee_id = n.annee_id
                                          AND csdd.scenario_id = sn.scenario_id
                                          AND csdd.type_intervention_id = ti.id
                                          AND csdd.groupe_type_formation_id = n.groupe_type_formation_id
                                          AND csdd.structure_id = n.structure_id

  LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id
                                          AND sns.type_intervention_id = ti.id
WHERE
  1=1
  /*@ETAPE_ENS_ID=sne.etape_id*/
)
SELECT
  annee_id,
  noeud_id,
  scenario_id,
  type_heures_id,
  type_intervention_id,

  element_pedagogique_id,
  etape_id,
  etape_ens_id,
  structure_id,
  groupe_type_formation_id,

  ouverture,
  dedoublement,
  assiduite,
  effectif,
  heures heures_ens,
  --t_effectif,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    CEIL( t_effectif / dedoublement ) * effectif / t_effectif
  END groupes,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    CEIL( t_effectif / dedoublement ) * heures * effectif / t_effectif
  END heures,

  CASE WHEN t_effectif < ouverture THEN 0 ELSE
    CEIL( t_effectif / dedoublement ) * hetd * effectif / t_effectif
  END  hetd

FROM
  t;
/

CREATE OR REPLACE FORCE VIEW V_TBL_CLOTURE_REALISE AS
WITH t AS (
  SELECT
    i.annee_id              annee_id,
    i.id                    intervenant_id,
    si.peut_cloturer_saisie peut_cloturer_saisie,
    CASE WHEN v.id IS NULL THEN 0 ELSE 1 END cloture
  FROM
              intervenant         i
         JOIN statut_intervenant si ON si.id = i.statut_id
         JOIN type_validation    tv ON tv.code = 'CLOTURE_REALISE'

    LEFT JOIN validation          v ON v.intervenant_id = i.id
                                   AND v.type_validation_id = tv.id
                                   AND v.histo_destruction IS NULL

  WHERE
    i.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/
)
SELECT
  annee_id,
  intervenant_id,
  peut_cloturer_saisie,
  CASE WHEN SUM(cloture) = 0 THEN 0 ELSE 1 END cloture
FROM
  t
GROUP BY
  annee_id,
  intervenant_id,
  peut_cloturer_saisie;
/

CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT AS
WITH t AS (
  SELECT
    i.annee_id                                                                annee_id,
    i.id                                                                      intervenant_id,
    si.peut_avoir_contrat                                                     peut_avoir_contrat,
    NVL(ep.structure_id, i.structure_id)                                      structure_id,
    CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
    CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
  FROM
              intervenant                 i

         JOIN statut_intervenant         si ON si.id = i.statut_id

         JOIN service                     s ON s.intervenant_id = i.id
                                           AND s.histo_destruction IS NULL

         JOIN type_volume_horaire       tvh ON tvh.code = 'PREVU'

         JOIN volume_horaire             vh ON vh.service_id = s.id
                                           AND vh.histo_destruction IS NULL
                                           AND vh.heures <> 0
                                           AND vh.type_volume_horaire_id = tvh.id
                                           AND vh.motif_non_paiement_id IS NULL

         JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id

         JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                           AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')

         JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id

  WHERE
    i.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/
    AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')

  UNION ALL

  SELECT
    i.annee_id                                                                annee_id,
    i.id                                                                      intervenant_id,
    si.peut_avoir_contrat                                                     peut_avoir_contrat,
    s.structure_id                                                            structure_id,
    CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
    CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
  FROM
              intervenant                 i

         JOIN statut_intervenant         si ON si.id = i.statut_id

         JOIN service_referentiel         s ON s.intervenant_id = i.id
                                           AND s.histo_destruction IS NULL

         JOIN type_volume_horaire       tvh ON tvh.code = 'PREVU'

         JOIN volume_horaire_ref         vh ON vh.service_referentiel_id = s.id
                                           AND vh.histo_destruction IS NULL
                                           AND vh.heures <> 0
                                           AND vh.type_volume_horaire_id = tvh.id

         JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id

         JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                           AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')

  WHERE
    i.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/
    AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')
)
SELECT
  annee_id,
  intervenant_id,
  peut_avoir_contrat,
  structure_id,
  COUNT(*) AS nbvh,
  SUM(edite) AS edite,
  SUM(signe) AS signe
FROM
  t
GROUP BY
  annee_id,
  intervenant_id,
  peut_avoir_contrat,
  structure_id;
/

CREATE OR REPLACE FORCE VIEW V_TBL_DMEP_LIQUIDATION AS
SELECT
  annee_id,
  type_ressource_id,
  structure_id,
  SUM(heures) heures
FROM
(
  SELECT
    i.annee_id,
    cc.type_ressource_id,
    COALESCE( ep.structure_id, i.structure_id ) structure_id,
    mep.heures
  FROM
              mise_en_paiement         mep
         JOIN centre_cout               cc ON cc.id = mep.centre_cout_id
         JOIN formule_resultat_service frs ON frs.id = mep.formule_res_service_id
         JOIN service                    s ON s.id = frs.service_id
         JOIN intervenant                i ON i.id = s.intervenant_id
    LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
  WHERE
    mep.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/

  UNION ALL

  SELECT
    i.annee_id,
    cc.type_ressource_id,
    sr.structure_id structure_id,
    heures
  FROM
              mise_en_paiement              mep
         JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
         JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
         JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
         JOIN intervenant                     i ON i.id = sr.intervenant_id

  WHERE
    mep.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/

) t1
GROUP BY
  annee_id, type_ressource_id, structure_id;
/

CREATE OR REPLACE FORCE VIEW V_TBL_DOSSIER AS
SELECT
  i.annee_id,
  i.id intervenant_id,
  si.peut_saisir_dossier,
  d.id dossier_id,
  v.id validation_id,
  /*Complétude statut*/
  CASE WHEN si.code = 'AUTRES' THEN 0
  ELSE 1 END completude_statut,
  /*Complétude identité*/
  CASE WHEN
    (
      d.civilite_id IS NOT NULL
      AND d.nom_usuel IS NOT NULL
      AND d.prenom IS NOT NULL
    ) THEN 1 ELSE 0 END completude_identite,
   /*Complétude identité complémentaire*/
  CASE WHEN si.dossier_identite_comp = 0 THEN 1
  ELSE
        CASE WHEN
        (
           d.date_naissance IS NOT NULL
       AND NOT (OSE_DIVERS.str_reduce(pn.LIBELLE) = 'france' AND d.departement_naissance_id IS NULL)
           AND d.pays_naissance_id IS NOT NULL
           AND d.pays_nationalite_id IS NOT NULL
           AND d.commune_naissance IS NOT NULL
        ) THEN 1 ELSE 0 END
   END completude_identite_comp,
   /*Complétude contact*/
   CASE WHEN si.dossier_contact = 0 THEN 1
   ELSE
   (
        CASE WHEN
        (
          (CASE WHEN si.dossier_email_perso = 1 THEN
             CASE WHEN d.email_perso IS NOT NULL THEN 1 ELSE 0 END
           ELSE
             CASE WHEN d.email_pro IS NOT NULL OR d.email_perso IS NOT NULL THEN 1 ELSE 0 END
           END) = 1
           AND
          (CASE WHEN si.dossier_tel_perso = 1 THEN
             CASE WHEN d.tel_perso IS NOT NULL AND d.tel_pro IS NOT NULL THEN 1 ELSE 0 END
           ELSE
             CASE WHEN d.tel_pro IS NOT NULL OR d.tel_perso IS NOT NULL THEN 1 ELSE 0 END
           END) = 1
        ) THEN 1 ELSE 0 END
   ) END completude_contact,
   /*Complétude adresse*/
   CASE WHEN si.dossier_adresse = 0 THEN 1
   ELSE
   (
      CASE WHEN
      (
         d.adresse_precisions IS NOT NULL
         OR d.adresse_lieu_dit IS NOT NULL
         OR (d.adresse_voie IS NOT NULL AND d.adresse_numero IS NOT NULL)
      ) AND
      (
       d.adresse_commune IS NOT NULL
         AND d.adresse_code_postal IS NOT NULL
      ) THEN 1 ELSE 0 END
    ) END completude_adresse,
     /*Complétude INSEE*/
     CASE WHEN si.dossier_insee = 0 THEN 1
     ELSE
     (
       CASE WHEN
       (
       d.numero_insee IS NOT NULL OR COALESCE(d.numero_insee_provisoire,0) = 1
       ) THEN 1 ELSE 0 END
     ) END completude_insee,
     /*Complétude IBAN*/
     CASE WHEN si.dossier_iban = 0 THEN 1
     ELSE
     (
       CASE WHEN
       (
         (d.iban IS NOT NULL
        AND d.bic IS NOT NULL)
        OR COALESCE(d.rib_hors_sepa,0) = 1
       ) THEN 1 ELSE 0 END
     ) END completude_iban,
     /*Complétude employeur*/
     CASE WHEN si.dossier_employeur = 0 THEN 1
     ELSE
     (
       CASE WHEN
       (
         d.employeur_id IS NOT NULL
       ) THEN 1 ELSE 0 END
     ) END completude_employeur,
     /*Complétude champs autres*/
     CASE WHEN
     (
       NOT (d.autre_1 IS NULL AND COALESCE(dca1.obligatoire,0) = 1)
       AND NOT (d.autre_2 IS NULL AND COALESCE(dca2.obligatoire,0) = 1)
       AND NOT (d.autre_3 IS NULL AND COALESCE(dca3.obligatoire,0) = 1)
       AND NOT (d.autre_4 IS NULL AND COALESCE(dca4.obligatoire,0) = 1)
       AND NOT (d.autre_5 IS NULL AND COALESCE(dca5.obligatoire,0) = 1)
     ) THEN 1 ELSE 0 END completude_autres

FROM
            intervenant         i
       JOIN statut_intervenant si ON si.id = i.statut_id
  LEFT JOIN intervenant_dossier d ON d.intervenant_id = i.id
                                 AND d.histo_destruction IS NULL
  LEFT JOIN pays               pn ON pn.id = d.pays_naissance_id

       JOIN type_validation tv ON tv.code = 'DONNEES_PERSO_PAR_COMP'
  LEFT JOIN validation       v ON v.intervenant_id = i.id
                              AND v.type_validation_id = tv.id
                              AND v.histo_destruction IS NULL
  /*Champs autre 1*/
  LEFT JOIN dossier_champ_autre_par_statut dcas1 ON dcas1.dossier_champ_autre_id = 1 AND dcas1.statut_id = si.id
  LEFT JOIN dossier_champ_autre dca1 ON dca1.id = 1 AND dcas1.dossier_champ_autre_id = dca1.id
 /*Champs autre 2*/
  LEFT JOIN dossier_champ_autre_par_statut dcas2 ON dcas2.dossier_champ_autre_id = 2 AND dcas2.statut_id = si.id
  LEFT JOIN dossier_champ_autre dca2 ON dca2.id = 2 AND dcas2.dossier_champ_autre_id = dca2.id
 /*Champs autre 3*/
  LEFT JOIN dossier_champ_autre_par_statut dcas3 ON dcas3.dossier_champ_autre_id = 3 AND dcas3.statut_id = si.id
  LEFT JOIN dossier_champ_autre dca3 ON dca3.id = 3 AND dcas3.dossier_champ_autre_id = dca3.id
 /*Champs autre 4*/
  LEFT JOIN dossier_champ_autre_par_statut dcas4 ON dcas4.dossier_champ_autre_id = 4 AND dcas4.statut_id = si.id
  LEFT JOIN dossier_champ_autre dca4 ON dca4.id = 4 AND dcas4.dossier_champ_autre_id = dca4.id
 /*Champs autre 5*/
  LEFT JOIN dossier_champ_autre_par_statut dcas5 ON dcas5.dossier_champ_autre_id = 5 AND dcas5.statut_id = si.id
  LEFT JOIN dossier_champ_autre dca5 ON dca5.id = 5 AND dcas5.dossier_champ_autre_id = dca5.id
WHERE
  i.histo_destruction IS NULL
   /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/;
/

CREATE OR REPLACE FORCE VIEW V_TBL_PAIEMENT AS
SELECT
  i.annee_id                                  annee_id,
  frs.service_id                              service_id,
  NULL                                        service_referentiel_id,
  frs.id                                      formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  i.id                                        intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
  COUNT(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
  NVL(mep.heures,0)                           heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
FROM
            formule_resultat_service        frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
       JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                               AND mep.histo_destruction IS NULL

UNION ALL

SELECT
  i.annee_id                                  annee_id,
  NULL                                        service_id,
  frs.service_referentiel_id                  service_referentiel_id,
  NULL                                        formule_res_service_id,
  frs.id                                      formule_res_service_ref_id,
  i.id                                        intervenant_id,
  s.structure_id                              structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  frs.heures_compl_referentiel                heures_a_payer,
  COUNT(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
  NVL(mep.heures,0)                           heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
FROM
            formule_resultat_service_ref    frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
       JOIN service_referentiel               s ON s.id = frs.service_referentiel_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                               AND mep.histo_destruction IS NULL;
/

CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE AS
WITH t AS (
  SELECT
    pjd.annee_id                                                annee_id,
    pjd.type_piece_jointe_id                                    type_piece_jointe_id,
    pjd.intervenant_id                                          intervenant_id,
    CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END      demandee,
    SUM(CASE WHEN pjf.id IS NULL THEN 0 ELSE 1 END)             fournie,
    SUM(CASE WHEN pjf.validation_id IS NULL THEN 0 ELSE 1 END)  validee,
    COALESCE(pjd.heures_pour_seuil,0)                           heures_pour_seuil,
    COALESCE(pjd.obligatoire,1)                                 obligatoire
  FROM
              tbl_piece_jointe_demande  pjd
    LEFT JOIN tbl_piece_jointe_fournie  pjf ON pjf.code_intervenant = pjd.code_intervenant
                                           AND pjf.type_piece_jointe_id = pjd.type_piece_jointe_id
                                           AND pjd.annee_id BETWEEN pjf.annee_id AND COALESCE(pjf.date_archive - 1,(pjf.annee_id + pjf.duree_vie - 1))
  WHERE
    1=1
    /*@INTERVENANT_ID=pjd.intervenant_id*/
    /*@ANNEE_ID=pjd.annee_id*/
  GROUP BY
    pjd.annee_id, pjd.type_piece_jointe_id, pjd.intervenant_id, pjd.intervenant_id, pjd.heures_pour_seuil, pjd.obligatoire

  UNION ALL

  SELECT
    pjf.annee_id                                                annee_id,
    pjf.type_piece_jointe_id                                    type_piece_jointe_id,
    pjf.intervenant_id                                          intervenant_id,
    0                                                           demandee,
    1                                                           fournie,
    SUM(CASE WHEN pjf.validation_id IS NULL THEN 0 ELSE 1 END)  validee,
    0                                                           heures_pour_seuil,
    0                                                           obligatoire
  FROM
              tbl_piece_jointe_fournie pjf
    LEFT JOIN tbl_piece_jointe_demande pjd ON pjd.intervenant_id = pjf.intervenant_id
                                          AND pjd.type_piece_jointe_id = pjf.type_piece_jointe_id
  WHERE
    pjd.id IS NULL
    /*@INTERVENANT_ID=pjf.intervenant_id*/
    /*@ANNEE_ID=pjf.annee_id*/
  GROUP BY
    pjf.annee_id, pjf.type_piece_jointe_id, pjf.intervenant_id
)
SELECT
  annee_id,
  type_piece_jointe_id,
  intervenant_id,
  demandee,
  CASE WHEN fournie <> 0 THEN 1 ELSE 0 END fournie,
  CASE WHEN validee <> 0 THEN 1 ELSE 0 END validee,
  heures_pour_seuil,
  obligatoire
FROM
  t;
/

CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE_DEMANDE AS
WITH i_h AS (
  SELECT
    s.intervenant_id,
    SUM(CASE WHEN vh.MOTIF_NON_PAIEMENT_ID IS NULL THEN vh.heures ELSE 0 END) heures,
    SUM(CASE WHEN vh.MOTIF_NON_PAIEMENT_ID IS NOT NULL THEN vh.heures ELSE 0 END) heures_non_payables,
    SUM(ep.taux_fc) fc
  FROM
         service               s
    JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
    JOIN volume_horaire       vh ON vh.service_id = s.id
                                AND vh.type_volume_horaire_id = tvh.id
                                AND vh.histo_destruction IS NULL
    JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id -- Service sur l'établissement
  WHERE
    s.histo_destruction IS NULL
    /*@INTERVENANT_ID=s.intervenant_id*/
  GROUP BY
    s.intervenant_id
)
SELECT
  i.annee_id                      annee_id,
  i.code code_intervenant,
  i.id                            intervenant_id,
  tpj.id                          type_piece_jointe_id,
  MAX(COALESCE(i_h.heures, 0))    heures_pour_seuil,
  tpjs.obligatoire obligatoire
FROM
            intervenant                 i

  LEFT JOIN intervenant_dossier         d ON d.intervenant_id = i.id
                                         AND d.histo_destruction IS NULL

       JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                         AND tpjs.histo_destruction IS NULL
                                         AND i.annee_id BETWEEN COALESCE(tpjs.annee_debut_id,i.annee_id) AND COALESCE(tpjs.annee_fin_id,i.annee_id)

       JOIN type_piece_jointe         tpj ON tpj.id = tpjs.type_piece_jointe_id
                                         AND tpj.histo_destruction IS NULL

  LEFT JOIN                           i_h ON i_h.intervenant_id = i.id
WHERE
  -- Gestion de l'historique
  i.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/

  -- Seuil HETD ou PJ obligatoire meme avec des heures non payables
  AND (COALESCE(i_h.heures,0) > COALESCE(tpjs.seuil_hetd,-1) OR (COALESCE(i_h.heures_non_payables,0) > 0 AND tpjs.obligatoire_hnp = 1 ))


  -- Le RIB n'est demandé QUE s'il est différent!!
  AND CASE
        WHEN tpjs.changement_rib = 0 OR d.id IS NULL THEN 1
        ELSE CASE WHEN REPLACE(i.bic, ' ', '') = REPLACE(d.bic, ' ', '') AND REPLACE(i.iban, ' ', '') = REPLACE(d.iban, ' ', '') THEN 0 ELSE 1 END
      END = 1

  -- Filtre FC
  AND (tpjs.fc = 0 OR i_h.fc > 0)
GROUP BY
  i.annee_id,
  i.id,
  i.code,
  tpj.id,
  tpjs.obligatoire;
/

CREATE OR REPLACE FORCE VIEW V_TBL_PIECE_JOINTE_FOURNIE AS
SELECT
  i.annee_id,
  i.code code_intervenant,
  pj.type_piece_jointe_id,
  pj.intervenant_id,
  pj.id piece_jointe_id,
  v.id validation_id,
  f.id fichier_id,
  CASE WHEN MIN(COALESCE(tpjs.duree_vie,1)) IS NULL THEN 1 ELSE MIN(COALESCE(tpjs.duree_vie,1)) END duree_vie,
  CASE WHEN MIN(COALESCE(tpjs.duree_vie,1)) IS NULL THEN i.annee_id+1 ELSE MIN(i.annee_id+COALESCE(tpjs.duree_vie,1)) END date_validite,
  pj.date_archive date_archive
FROM
            piece_jointe          pj
       JOIN intervenant            i ON i.id = pj.intervenant_id
                                    AND i.histo_destruction IS NULL
       JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
       JOIN fichier                f ON f.id = pjf.fichier_id
                                    AND f.histo_destruction IS NULL
        LEFT JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                           AND tpjs.type_piece_jointe_id = pj.type_piece_jointe_id
                                           AND tpjs.HISTO_DESTRUCTION IS NULL

 LEFT JOIN validation             v ON v.id = pj.validation_id
                                    AND v.histo_destruction IS NULL
WHERE
  pj.histo_destruction IS NULL
GROUP BY
i.annee_id,
  i.code,
  pj.type_piece_jointe_id,
  pj.intervenant_id,
  pj.id,
  v.id,
  f.id,
  pj.date_archive;
/

CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE AS
WITH t AS (
SELECT
  s.id                                                                                      service_id,
  s.intervenant_id                                                                          intervenant_id,
  ep.structure_id                                                                           structure_id,
  ep.id                                                                                     element_pedagogique_id,
  ep.periode_id                                                                             element_pedagogique_periode_id,
  etp.id                                                                                    etape_id,

  vh.type_volume_horaire_id                                                                 type_volume_horaire_id,
  vh.heures                                                                                 heures,
  tvh.code                                                                                  type_volume_horaire_code,

  CASE WHEN ep.histo_destruction IS NULL THEN 1 ELSE 0 END                                  element_pedagogique_histo,
  CASE WHEN etp.histo_destruction IS NULL OR cp.id IS NOT NULL THEN 1 ELSE 0 END            etape_histo,

  CASE WHEN ep.periode_id IS NOT NULL THEN
    SUM( CASE WHEN vh.periode_id <> ep.periode_id THEN 1 ELSE 0 END ) OVER( PARTITION BY vh.service_id, vh.periode_id, vh.type_volume_horaire_id, vh.type_intervention_id )
  ELSE 0 END has_heures_mauvaise_periode,

  CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
FROM
  service                                       s
  LEFT JOIN element_pedagogique                ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             etp ON etp.id = ep.etape_id
  LEFT JOIN chemin_pedagogique                 cp ON cp.etape_id = etp.id
                                                 AND cp.element_pedagogique_id = ep.id
                                                 AND cp.histo_destruction IS NULL

       JOIN volume_horaire                     vh ON vh.service_id = s.id
                                                 AND vh.histo_destruction IS NULL

       JOIN type_volume_horaire               tvh ON tvh.id = vh.type_volume_horaire_id

  LEFT JOIN validation_vol_horaire            vvh ON vvh.volume_horaire_id = vh.id

  LEFT JOIN validation                          v ON v.id = vvh.validation_id
                                                 AND v.histo_destruction IS NULL
WHERE
  s.histo_destruction IS NULL
  /*@INTERVENANT_ID=s.intervenant_id*/
)
SELECT
  i.annee_id                                                                                annee_id,
  i.id                                                                                      intervenant_id,
  i.structure_id                                                                            intervenant_structure_id,
  NVL( t.structure_id, i.structure_id )                                                     structure_id,
  ti.id                                                                                     type_intervenant_id,
  ti.code                                                                                   type_intervenant_code,
  si.peut_saisir_service                                                                    peut_saisir_service,

  t.element_pedagogique_id,
  t.service_id,
  t.element_pedagogique_periode_id,
  t.etape_id,
  t.type_volume_horaire_id,
  t.type_volume_horaire_code,
  t.element_pedagogique_histo,
  t.etape_histo,

  CASE WHEN SUM(t.has_heures_mauvaise_periode) > 0 THEN 1 ELSE 0 END has_heures_mauvaise_periode,

  CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE COUNT(*) END nbvh,
  CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE SUM(t.heures) END heures,
  SUM(valide) valide
FROM
  t
  JOIN intervenant                              i ON i.id = t.intervenant_id
  JOIN statut_intervenant                      si ON si.id = i.statut_id
  JOIN type_intervenant                        ti ON ti.id = si.type_intervenant_id
WHERE
  1=1
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
GROUP BY
  i.annee_id,
  i.id,
  i.structure_id,
  t.structure_id,
  i.structure_id,
  ti.id,
  ti.code,
  si.peut_saisir_service,
  t.element_pedagogique_id,
  t.service_id,
  t.element_pedagogique_periode_id,
  t.etape_id,
  t.type_volume_horaire_id,
  t.type_volume_horaire_code,
  t.element_pedagogique_histo,
  t.etape_histo;
/

CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE_REFERENTIEL AS
WITH t AS (

  SELECT
    i.annee_id,
    i.id intervenant_id,
    si.peut_saisir_referentiel peut_saisir_service,
    vh.type_volume_horaire_id,
    s.structure_id,
    CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
  FROM
              intervenant                     i

         JOIN statut_intervenant          si ON si.id = i.statut_id

    LEFT JOIN service_referentiel          s ON s.intervenant_id = i.id
                                            AND s.histo_destruction IS NULL

    LEFT JOIN volume_horaire_ref          vh ON vh.service_referentiel_id = s.id
                                            AND vh.histo_destruction IS NULL

    LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id

    LEFT JOIN validation                   v ON v.id = vvh.validation_id
                                            AND v.histo_destruction IS NULL
  WHERE
    i.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/
)
SELECT
  annee_id,
  intervenant_id,
  peut_saisir_service,
  type_volume_horaire_id,
  structure_id,
  CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE COUNT(*) END nbvh,
  SUM(valide) valide
FROM
  t
WHERE
  NOT (structure_id IS NOT NULL AND type_volume_horaire_id IS NULL)
GROUP BY
  annee_id,
  intervenant_id,
  peut_saisir_service,
  type_volume_horaire_id,
  structure_id;
/

CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE_SAISIE AS
SELECT
  i.annee_id,
  i.id intervenant_id,
  si.peut_saisir_service,
  si.peut_saisir_referentiel,
  SUM( CASE WHEN tvhs.code = 'PREVU'   THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_prev,
  SUM( CASE WHEN tvhs.code = 'PREVU'   THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_prev,
  SUM( CASE WHEN tvhs.code = 'REALISE' THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_real,
  SUM( CASE WHEN tvhs.code = 'REALISE' THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_real
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  LEFT JOIN service s ON s.intervenant_id = i.id AND s.histo_destruction IS NULL
  LEFT JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
  LEFT JOIN type_volume_horaire tvhs ON tvhs.id = vh.type_volume_horaire_id

  LEFT JOIN service_referentiel sr ON sr.intervenant_id = i.id AND sr.histo_destruction IS NULL
  LEFT JOIN volume_horaire_ref vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
  LEFT JOIN type_volume_horaire tvhrs ON tvhrs.id = vhr.type_volume_horaire_id
WHERE
  i.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
GROUP BY
  i.annee_id,
  i.id,
  si.peut_saisir_service,
  si.peut_saisir_referentiel;
/

CREATE OR REPLACE FORCE VIEW V_TBL_VALIDATION_ENSEIGNEMENT AS
SELECT DISTINCT
  i.annee_id,
  i.id intervenant_id,
  CASE WHEN rsv.priorite = 'affectation' THEN
    COALESCE( i.structure_id, ep.structure_id )
  ELSE
    COALESCE( ep.structure_id, i.structure_id )
  END structure_id,
  vh.type_volume_horaire_id,
  s.id service_id,
  vh.id volume_horaire_id,
  vh.auto_validation,
  v.id validation_id
FROM
  service s
  JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
  JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
WHERE
  s.histo_destruction IS NULL
  AND NOT (vvh.validation_id IS NOT NULL AND v.id IS NULL)
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/;
/

CREATE OR REPLACE FORCE VIEW V_TBL_VALIDATION_REFERENTIEL AS
SELECT DISTINCT
  i.annee_id,
  i.id intervenant_id,
  CASE WHEN rsv.priorite = 'affectation' THEN
    COALESCE( i.structure_id, s.structure_id )
  ELSE
    COALESCE( s.structure_id, i.structure_id )
  END structure_id,
  vh.type_volume_horaire_id,
  s.id service_referentiel_id,
  vh.id volume_horaire_ref_id,
  vh.auto_validation,
  v.id validation_id
FROM
  service_referentiel s
  JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND vh.histo_destruction IS NULL
  JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
  LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
  LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
WHERE
  s.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/;
/

CREATE OR REPLACE FORCE VIEW V_TBL_VOLUME_HORAIRE AS
WITH has_cp AS (
SELECT
  etape_id
FROM
  chemin_pedagogique cp
WHERE
  cp.histo_destruction IS NULL
GROUP BY
  etape_id
)
SELECT
  i.annee_id                                                                                annee_id,
  i.id                                                                                      intervenant_id,
  i.structure_id                                                                            intervenant_structure_id,
  NVL(ep.structure_id, i.structure_id)                                                      structure_id,
  ti.id                                                                                     type_intervenant_id,
  s.id                                                                                      service_id,
  vh.id                                                                                     volume_horaire_id,
  vh.type_intervention_id                                                                   type_intervention_id,
  vh.motif_non_paiement_id                                                                  motif_non_paiement_id,
  vh.periode_id                                                                             volume_horaire_periode_id,
  tvh.id                                                                                    type_volume_horaire_id,
  evh.id                                                                                    etat_volume_horaire_id,
  ep.id                                                                                     element_pedagogique_id,
  ep.periode_id                                                                             element_pedagogique_periode_id,
  etp.id                                                                                    etape_id,

  ti.code                                                                                   type_intervenant_code,
  tvh.code                                                                                  type_volume_horaire_code,
  evh.code                                                                                  etat_volume_horaire_code,
  si.peut_saisir_service                                                                    peut_saisir_service,
  vh.heures                                                                                 heures,

  CASE WHEN ep.histo_destruction IS NULL THEN 1 ELSE 0 END                                  element_pedagogique_histo,
  CASE WHEN etp.histo_destruction IS NULL OR has_cp.etape_id IS NOT NULL THEN 1 ELSE 0 END  etape_histo,
  CASE WHEN ep.periode_id IS NOT NULL AND vh.periode_id <> ep.periode_id THEN 0 ELSE 1 END  periode_corresp

FROM
  intervenant                                   i
  JOIN statut_intervenant                      si ON si.id = i.statut_id
  JOIN type_intervenant                        ti ON ti.id = si.type_intervenant_id
  JOIN service                                  s ON s.intervenant_id = i.id
                                                 AND s.histo_destruction IS NULL
  JOIN element_pedagogique                     ep ON ep.id = s.element_pedagogique_id
  JOIN etape                                  etp ON etp.id = ep.etape_id
  JOIN volume_horaire                          vh ON vh.service_id = s.id
                                                 AND vh.histo_destruction IS NULL
  JOIN type_volume_horaire                    tvh ON tvh.id = vh.type_volume_horaire_id
  JOIN v_volume_horaire_etat                  vhe ON vhe.volume_horaire_id = vh.id
  JOIN etat_volume_horaire                    evh ON evh.id = vhe.etat_volume_horaire_id
  LEFT JOIN has_cp                                ON has_cp.etape_id = etp.id
WHERE
  i.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/;
/




--------------------------------------------------
-- table.drop
--------------------------------------------------

DROP TABLE ADRESSE_INTERVENANT;
/

DROP TABLE ADRESSE_STRUCTURE;
/

DROP TABLE DOSSIER;
/

DROP TABLE INTERVENANT_SAISIE;
/

DROP TABLE TBL_DEMS;
/
