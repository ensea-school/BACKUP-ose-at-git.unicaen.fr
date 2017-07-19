-- ********************************************************************* --
-- *          à faire AVANT avoir mis à jour le code source            * --
-- ********************************************************************* --

BEGIN DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"', force => TRUE); END; 
/

---------------------------
--Nouveau SEQUENCE
--MESSAGES_ID_SEQ
---------------------------
 CREATE SEQUENCE "OSE"."MESSAGES_ID_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 NOCACHE NOORDER NOCYCLE;
---------------------------
--Modifié TABLE
--VOLUME_HORAIRE
---------------------------
ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD ("BUFF_PFM_HEURES" FLOAT(126));
ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD ("BUFF_PFM_HISTO_MODIFICATEUR_ID" NUMBER(*,0));
ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD ("BUFF_PFM_HISTO_MODIFICATION" DATE);
ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD ("BUFF_PFM_MOTIF_NON_PAIEMENT_ID" NUMBER(*,0));
ALTER TABLE "OSE"."VOLUME_HORAIRE" ADD ("TEM_PLAFOND_FC_MAJ" NUMBER(2,0) DEFAULT 0 NOT NULL ENABLE);

---------------------------
--Modifié TABLE
--ROLE
---------------------------
ALTER TABLE "OSE"."ROLE" ADD ("PEUT_CHANGER_STRUCTURE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE);


---------------------------
--Modifié TABLE
--MOTIF_MODIFICATION_SERVICE
---------------------------
ALTER TABLE "OSE"."MOTIF_MODIFICATION_SERVICE" ADD ("DECHARGE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE);

---------------------------
--Nouveau TABLE
--MESSAGE
---------------------------
  CREATE TABLE "OSE"."MESSAGE" 
   (	"ID" NUMBER(*,0) NOT NULL ENABLE,
	"CODE" VARCHAR2(150 CHAR) NOT NULL ENABLE,
	"TEXTE" CLOB NOT NULL ENABLE,
	CONSTRAINT "MESSAGES_PK" PRIMARY KEY ("ID") ENABLE,
	CONSTRAINT "MESSAGES__UN" UNIQUE ("CODE") ENABLE
   );
---------------------------
--Modifié TABLE
--INTERVENANT
---------------------------
ALTER TABLE "OSE"."INTERVENANT" ADD ("CRITERE_RECHERCHE" VARCHAR2(255 CHAR));
ALTER TABLE "OSE"."INTERVENANT" DROP ("TYPE_ID");
ALTER TABLE "OSE"."INTERVENANT" DROP CONSTRAINT "IIT_FK";

---------------------------
--Modifié TABLE
--CATEGORIE_PRIVILEGE
---------------------------
ALTER TABLE "OSE"."CATEGORIE_PRIVILEGE" ADD ("ORDRE" NUMBER(*,0));


---------------------------
--Modifié VIEW
--V_SYMPA_INT_VACATAIRES_2014
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_SYMPA_INT_VACATAIRES_2014" 
 ( "EMAIL"
  )  AS 
  SELECT DISTINCT
  email
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN service s ON s.intervenant_id = i.id AND 1 = ose_divers.comprise_entre(s.histo_creation,s.histo_destruction)
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation,vh.histo_destruction)
WHERE
  ti.code = 'E'
  AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  AND i.annee_id = 2014
GROUP BY
  email
HAVING
  sum(vh.heures) > 0;
---------------------------
--Modifié VIEW
--V_SYMPA_INT_PERMANENTS_2014
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_SYMPA_INT_PERMANENTS_2014" 
 ( "EMAIL"
  )  AS 
  SELECT DISTINCT
  email
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN service s ON s.intervenant_id = i.id AND 1 = ose_divers.comprise_entre(s.histo_creation,s.histo_destruction)
  JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation,vh.histo_destruction)
WHERE
  ti.code = 'P'
  AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  AND i.annee_id = 2014
GROUP BY
  email
HAVING
  sum(vh.heures) > 0;
---------------------------
--Nouveau VIEW
--V_PLAFOND_FC_MAJ
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_PLAFOND_FC_MAJ" 
 ( "INTERVENANT_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  fr.intervenant_id intervenant_id,
  fr.type_volume_horaire_id,
  fr.etat_volume_horaire_id,
  ROUND( (NVL(si.plafond_hc_remu_fc,0) - NVL(montant_indemnite_fc,0)) / NVL(thh.valeur,1), 2 ) plafond,
  SUM(NVL(frs.heures_compl_fc_majorees,0)) heures
FROM
       intervenant                i 
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN taux_horaire_hetd        thh ON 1 = ose_divers.comprise_entre( thh.histo_creation, thh.histo_destruction )
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id 
  JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
GROUP BY
  fr.intervenant_id, fr.type_volume_horaire_id, fr.etat_volume_horaire_id,
  si.plafond_hc_remu_fc, montant_indemnite_fc, thh.valeur;
---------------------------
--Nouveau VIEW
--V_INTERVENANT_RECHERCHE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INTERVENANT_RECHERCHE" 
 ( "ID", "SOURCE_CODE", "NOM_USUEL", "NOM_PATRONYMIQUE", "PRENOM", "DATE_NAISSANCE", "STRUCTURE", "CIVILITE", "CRITERE", "ANNEE_ID"
  )  AS 
  SELECT
  i.id,
  i.source_code,
  i.nom_usuel,
  i.nom_patronymique,
  i.prenom,
  i.date_naissance,
  s.libelle_court structure,
  c.libelle_long civilite,
  i.critere_recherche critere,
  i.annee_id
FROM
  intervenant i
  JOIN structure s ON s.id = i.structure_id
  JOIN civilite c ON c.id = i.civilite_id
WHERE
  1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  
UNION ALL

SELECT
  null id,
  mir.source_code,
  mir.nom_usuel,
  mir.nom_patronymique,
  mir.prenom,
  mir.date_naissance,
  mir.structure,
  mir.civilite,
  mir.critere,
  mir.annee_id
FROM
  mv_intervenant_recherche mir;
---------------------------
--Modifié VIEW
--V_INDICATEUR_210
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_210" 
 ( "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_AGREMENT_ID"
  )  AS 
  SELECT DISTINCT
  --rownum   id,
  i.id     intervenant_id,
  i.structure_id structure_id,
  ta.id    type_agrement_id
FROM 
  intervenant i
  JOIN statut_intervenant     s ON s.id = i.statut_id
  JOIN type_intervenant      ti ON ti.id = s.type_intervenant_id 
  JOIN wf_intervenant_etape wie ON wie.intervenant_id = i.id
  JOIN type_agrement         ta ON 1=1
  JOIN wf_etape              we ON we.id = wie.etape_id AND we.code = ta.code
WHERE
  ta.code = 'CONSEIL_RESTREINT'
  AND ti.code = 'E' 
  AND wie.courante = 1;
---------------------------
--Modifié VIEW
--V_FORMULE_SERVICE_MODIFIE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_FORMULE_SERVICE_MODIFIE" 
 ( "ID", "INTERVENANT_ID", "HEURES", "HEURES_DECHARGE"
  )  AS 
  SELECT
  msd.intervenant_id id,
  msd.intervenant_id,
  NVL( SUM( msd.heures * mms.multiplicateur ), 0 ) heures,
  NVL( SUM( msd.heures * mms.multiplicateur * mms.decharge ), 0 ) heures_decharge
FROM
  modification_service_du msd
  JOIN MOTIF_MODIFICATION_SERVICE mms ON 
    mms.id = msd.motif_id
    AND 1 = ose_divers.comprise_entre( mms.histo_creation, mms.histo_destruction, ose_formule.get_date_obs )
  JOIN intervenant i ON i.id = msd.intervenant_id
WHERE
  1 = ose_divers.comprise_entre( msd.histo_creation, msd.histo_destruction, ose_formule.get_date_obs)
  AND 1 = ose_divers.intervenant_has_privilege(msd.intervenant_id, 'modif-service-du-association')
GROUP BY
  msd.intervenant_id;
---------------------------
--Modifié VIEW
--V_EXPORT_PAIEMENT_WINPAIE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_EXPORT_PAIEMENT_WINPAIE" 
 ( "TYPE_INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_ID", "PERIODE_PAIEMENT_ID", "INTERVENANT_ID", "INSEE", "NOM", "CARTE", "CODE_ORIGINE", "RETENUE", "SENS", "MC", "NBU", "MONTANT", "LIBELLE"
  )  AS 
  SELECT
  si.type_intervenant_id type_intervenant_id,
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
JOIN statut_intervenant si ON si.id = i.statut_id
JOIN structure s ON s.id = t2.structure_id;
---------------------------
--Modifié VIEW
--V_ETAT_PAIEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_ETAT_PAIEMENT" 
 ( "PERIODE_PAIEMENT_ID", "STRUCTURE_ID", "INTERVENANT_TYPE_ID", "INTERVENANT_ID", "ANNEE_ID", "CENTRE_COUT_ID", "DOMAINE_FONCTIONNEL_ID", "ETAT", "STRUCTURE_LIBELLE", "DATE_MISE_EN_PAIEMENT", "PERIODE_PAIEMENT_LIBELLE", "INTERVENANT_TYPE", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_NUMERO_INSEE", "CENTRE_COUT_CODE", "CENTRE_COUT_LIBELLE", "DOMAINE_FONCTIONNEL_CODE", "DOMAINE_FONCTIONNEL_LIBELLE", "HETD", "HETD_POURC", "HETD_MONTANT", "REM_FC_D714", "EXERCICE_AA", "EXERCICE_AA_MONTANT", "EXERCICE_AC", "EXERCICE_AC_MONTANT"
  )  AS 
  SELECT
  periode_paiement_id,
  structure_id,
  intervenant_type_id,
  intervenant_id, 
  annee_id, 
  centre_cout_id, 
  domaine_fonctionnel_id,
  etat,
  structure_libelle,
  date_mise_en_paiement,
  periode_paiement_libelle,
  intervenant_type,
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
  intervenant_type_id,
  intervenant_id, 
  annee_id, 
  centre_cout_id, 
  domaine_fonctionnel_id,
  etat,
  structure_libelle,
  date_mise_en_paiement,
  periode_paiement_libelle,
  intervenant_type,
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
    ti.id                                                               intervenant_type_id,
    CASE
        WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
        ELSE 'mis-en-paiement'
    END                                                                 etat,

    p.libelle_long || ' ' || to_char( add_months( a.date_debut, p.ecart_mois ), 'yyyy' ) periode_paiement_libelle,
    mep.date_mise_en_paiement                                           date_mise_en_paiement,
    s.libelle_court                                                     structure_libelle,
    ti.libelle                                                          intervenant_type,
    i.source_code                                                       intervenant_code,
    i.nom_usuel || ' ' || i.prenom                                      intervenant_nom,
    TRIM( NVL(i.numero_insee,'') || NVL(TO_CHAR(i.numero_insee_cle,'00'),'') ) intervenant_numero_insee,
    cc.source_code                                                      centre_cout_code,
    cc.libelle                                                          centre_cout_libelle,
    df.source_code                                                      domaine_fonctionnel_code,
    df.libelle                                                          domaine_fonctionnel_libelle,
    CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END        hetd,
    CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END        fc_majorees,
    CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 4 / 10 exercice_aa,
    CASE WHEN th.code = 'fc_majorees' THEN 0 ELSE mep.heures END * 6 / 10 exercice_ac,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) )      taux_horaire
  FROM
    v_mep_intervenant_structure  mis
    JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND 1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
    JOIN type_heures              th ON  th.id = mep.type_heures_id
    JOIN centre_cout              cc ON  cc.id = mep.centre_cout_id      -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
    JOIN intervenant               i ON   i.id = mis.intervenant_id      AND 1 = ose_divers.comprise_entre(   i.histo_creation,   i.histo_destruction )
    JOIN annee                     a ON   a.id = i.annee_id
    JOIN statut_intervenant       si ON  si.id = i.statut_id
    JOIN type_intervenant         ti ON  ti.id = si.type_intervenant_id
    JOIN structure                 s ON   s.id = mis.structure_id
    LEFT JOIN validation           v ON   v.id = mep.validation_id       AND 1 = ose_divers.comprise_entre(   v.histo_creation,   v.histo_destruction )
    LEFT JOIN domaine_fonctionnel df ON  df.id = mis.domaine_fonctionnel_id
    LEFT JOIN periode              p ON   p.id = mep.periode_paiement_id
  )
  SELECT
    periode_paiement_id,
    structure_id, 
    intervenant_type_id,
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    structure_libelle,
    date_mise_en_paiement,
    intervenant_type,
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
    intervenant_type_id,
    intervenant_id, 
    annee_id, 
    centre_cout_id, 
    domaine_fonctionnel_id, 
    etat,
    periode_paiement_libelle,
    structure_libelle,
    date_mise_en_paiement,
    intervenant_type,
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
--V_DIFF_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "BIC", "CIVILITE_ID", "DATE_NAISSANCE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "DISCIPLINE_ID", "EMAIL", "GRADE_ID", "IBAN", "NOM_PATRONYMIQUE", "NOM_USUEL", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "PRENOM", "STATUT_ID", "STRUCTURE_ID", "TEL_MOBILE", "TEL_PRO", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "U_ANNEE_ID", "U_BIC", "U_CIVILITE_ID", "U_DATE_NAISSANCE", "U_DEP_NAISSANCE_CODE_INSEE", "U_DEP_NAISSANCE_LIBELLE", "U_DISCIPLINE_ID", "U_EMAIL", "U_GRADE_ID", "U_IBAN", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_NUMERO_INSEE", "U_NUMERO_INSEE_CLE", "U_NUMERO_INSEE_PROVISOIRE", "U_PAYS_NAISSANCE_CODE_INSEE", "U_PAYS_NAISSANCE_LIBELLE", "U_PAYS_NATIONALITE_CODE_INSEE", "U_PAYS_NATIONALITE_LIBELLE", "U_PRENOM", "U_STATUT_ID", "U_STRUCTURE_ID", "U_TEL_MOBILE", "U_TEL_PRO", "U_VILLE_NAISSANCE_CODE_INSEE", "U_VILLE_NAISSANCE_LIBELLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."BIC",diff."CIVILITE_ID",diff."DATE_NAISSANCE",diff."DEP_NAISSANCE_CODE_INSEE",diff."DEP_NAISSANCE_LIBELLE",diff."DISCIPLINE_ID",diff."EMAIL",diff."GRADE_ID",diff."IBAN",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."NUMERO_INSEE",diff."NUMERO_INSEE_CLE",diff."NUMERO_INSEE_PROVISOIRE",diff."PAYS_NAISSANCE_CODE_INSEE",diff."PAYS_NAISSANCE_LIBELLE",diff."PAYS_NATIONALITE_CODE_INSEE",diff."PAYS_NATIONALITE_LIBELLE",diff."PRENOM",diff."STATUT_ID",diff."STRUCTURE_ID",diff."TEL_MOBILE",diff."TEL_PRO",diff."VILLE_NAISSANCE_CODE_INSEE",diff."VILLE_NAISSANCE_LIBELLE",diff."U_ANNEE_ID",diff."U_BIC",diff."U_CIVILITE_ID",diff."U_DATE_NAISSANCE",diff."U_DEP_NAISSANCE_CODE_INSEE",diff."U_DEP_NAISSANCE_LIBELLE",diff."U_DISCIPLINE_ID",diff."U_EMAIL",diff."U_GRADE_ID",diff."U_IBAN",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_NUMERO_INSEE",diff."U_NUMERO_INSEE_CLE",diff."U_NUMERO_INSEE_PROVISOIRE",diff."U_PAYS_NAISSANCE_CODE_INSEE",diff."U_PAYS_NAISSANCE_LIBELLE",diff."U_PAYS_NATIONALITE_CODE_INSEE",diff."U_PAYS_NATIONALITE_LIBELLE",diff."U_PRENOM",diff."U_STATUT_ID",diff."U_STRUCTURE_ID",diff."U_TEL_MOBILE",diff."U_TEL_PRO",diff."U_VILLE_NAISSANCE_CODE_INSEE",diff."U_VILLE_NAISSANCE_LIBELLE" from (SELECT
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
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DISCIPLINE_ID ELSE S.DISCIPLINE_ID END DISCIPLINE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.GRADE_ID ELSE S.GRADE_ID END GRADE_ID,
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
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_CODE_INSEE ELSE S.VILLE_NAISSANCE_CODE_INSEE END VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_LIBELLE ELSE S.VILLE_NAISSANCE_LIBELLE END VILLE_NAISSANCE_LIBELLE,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL) THEN 1 ELSE 0 END U_BIC,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL) THEN 1 ELSE 0 END U_DATE_NAISSANCE,
    CASE WHEN D.DEP_NAISSANCE_CODE_INSEE <> S.DEP_NAISSANCE_CODE_INSEE OR (D.DEP_NAISSANCE_CODE_INSEE IS NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.DEP_NAISSANCE_CODE_INSEE IS NOT NULL AND S.DEP_NAISSANCE_CODE_INSEE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_CODE_INSEE,
    CASE WHEN D.DEP_NAISSANCE_LIBELLE <> S.DEP_NAISSANCE_LIBELLE OR (D.DEP_NAISSANCE_LIBELLE IS NULL AND S.DEP_NAISSANCE_LIBELLE IS NOT NULL) OR (D.DEP_NAISSANCE_LIBELLE IS NOT NULL AND S.DEP_NAISSANCE_LIBELLE IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_LIBELLE,
    CASE WHEN D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL) THEN 1 ELSE 0 END U_DISCIPLINE_ID,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
    CASE WHEN D.GRADE_ID <> S.GRADE_ID OR (D.GRADE_ID IS NULL AND S.GRADE_ID IS NOT NULL) OR (D.GRADE_ID IS NOT NULL AND S.GRADE_ID IS NULL) THEN 1 ELSE 0 END U_GRADE_ID,
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
  OR D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
  OR D.GRADE_ID <> S.GRADE_ID OR (D.GRADE_ID IS NULL AND S.GRADE_ID IS NOT NULL) OR (D.GRADE_ID IS NOT NULL AND S.GRADE_ID IS NULL)
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
  OR D.VILLE_NAISSANCE_CODE_INSEE <> S.VILLE_NAISSANCE_CODE_INSEE OR (D.VILLE_NAISSANCE_CODE_INSEE IS NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL) OR (D.VILLE_NAISSANCE_CODE_INSEE IS NOT NULL AND S.VILLE_NAISSANCE_CODE_INSEE IS NULL)
  OR D.VILLE_NAISSANCE_LIBELLE <> S.VILLE_NAISSANCE_LIBELLE OR (D.VILLE_NAISSANCE_LIBELLE IS NULL AND S.VILLE_NAISSANCE_LIBELLE IS NOT NULL) OR (D.VILLE_NAISSANCE_LIBELLE IS NOT NULL AND S.VILLE_NAISSANCE_LIBELLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--SRC_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT" 
 ( "ID", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_CODE_INSEE", "PAYS_NAISSANCE_LIBELLE", "DEP_NAISSANCE_CODE_INSEE", "DEP_NAISSANCE_LIBELLE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_CODE_INSEE", "PAYS_NATIONALITE_LIBELLE", "TEL_PRO", "TEL_MOBILE", "EMAIL", "STATUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC", "GRADE_ID", "DISCIPLINE_ID", "ANNEE_ID", "CRITERE_RECHERCHE"
  )  AS 
  WITH srci as (
SELECT
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  COALESCE(i.date_naissance,TO_DATE('2099-01-01','YYYY-MM-DD')) date_naissance,
  i.pays_naissance_code_insee,   i.pays_naissance_libelle,
  i.dep_naissance_code_insee,    i.dep_naissance_libelle,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_code_insee, i.pays_nationalite_libelle,
  i.tel_pro, i.tel_mobile, i.email,
  i.statut_id, i.statut_code,
  NVL(s.structure_niv2_id,s.id) structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  g.id grade_id,
  NVL( d.id, d99.id ) discipline_id,
  i.critere_recherche
FROM
            mv_intervenant  i
       JOIN structure       s ON s.source_code = i.z_structure_id
  LEFT JOIN grade           g ON g.source_code = i.z_grade_id
  LEFT JOIN discipline d99 ON d99.source_code = '99'
  LEFT JOIN discipline d ON
    1 = CASE WHEN -- si rien n'ac été défini
    
      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, i.z_discipline_id_spe_cnu, i.z_discipline_id_dis2deg ) IS NULL
      AND d.source_code = '00'
    
    THEN 1 WHEN -- si une CNU ou une spécialité a été définie...
      
      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, z_discipline_id_spe_cnu ) IS NOT NULL
    
    THEN CASE WHEN -- alors on teste par les sections CNU et spécialités

      (
           ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'') || ',%'
        OR ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'00') || ',%'
      )
      AND ',' || NVL(d.CODES_CORRESP_3,'000') || ',' LIKE  '%,' || NVL(CASE WHEN d.CODES_CORRESP_3 IS NOT NULL THEN z_discipline_id_spe_cnu ELSE NULL END,'000') || ',%'
    
    THEN 1 ELSE 0 END ELSE CASE WHEN -- sinon on teste par les disciplines du 2nd degré
    
      i.z_discipline_id_dis2deg IS NOT NULL
      AND ',' || NVL(d.CODES_CORRESP_4,'') || ',' LIKE  '%,' || i.z_discipline_id_dis2deg || ',%'
      
    THEN 1 ELSE 0 END END -- fin du test
WHERE
  i.ordre = i.min_ordre
)
SELECT
  null id,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_code_insee,   i.pays_naissance_libelle,
  i.dep_naissance_code_insee,    i.dep_naissance_libelle,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_code_insee, i.pays_nationalite_libelle,
  i.tel_pro, i.tel_mobile, i.email,
  CASE WHEN i.statut_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE i.statut_id END statut_id,
  i. structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  ose_import.get_current_annee annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = ose_import.get_current_annee
  LEFT JOIN dossier               d  ON d.id = i2.dossier_id

UNION ALL

SELECT
  null id,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_code_insee,   i.pays_naissance_libelle,
  i.dep_naissance_code_insee,    i.dep_naissance_libelle,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_code_insee, i.pays_nationalite_libelle,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE(i2.statut_id,i.statut_id) statut_id,
  COALESCE(i2.structure_id,i.structure_id) structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  ose_import.get_current_annee - 1 annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = ose_import.get_current_annee - 1
  LEFT JOIN dossier               d  ON d.id = i2.dossier_id;
---------------------------
--Modifié VIEW
--SRC_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CENTRE_COUT" 
 ( "ID", "LIBELLE", "ACTIVITE_ID", "TYPE_RESSOURCE_ID", "PARENT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  null              id,
  mvcc.libelle      libelle,
  a.id              activite_id,
  tr.id             type_ressource_id,
  cc.id             parent_id,
  NVL(s.structure_niv2_id,s.id) structure_id,
  mvcc.source_id    source_id,
  mvcc.source_code  source_code
FROM
  MV_centre_cout mvcc
  LEFT JOIN cc_activite        a ON a.code         = mvcc.z_activite_id
  LEFT JOIN type_ressource    tr ON tr.code        = mvcc.z_type_ressource_id
  LEFT JOIN centre_cout       cc ON cc.source_code = mvcc.z_parent_id
  LEFT JOIN structure          s ON s.source_code  = mvcc.z_structure_id
WHERE
  mvcc.z_activite_id IS NOT NULL;
---------------------------
--Modifié VIEW
--SRC_ADRESSE_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ADRESSE_STRUCTURE" 
 ( "ID", "STRUCTURE_ID", "PRINCIPALE", "TELEPHONE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  NULL id,
  NVL(s.structure_niv2_id,s.id) structure_id,
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
--Nouveau MATERIALIZED VIEW
--MV_INTERVENANT_RECHERCHE
---------------------------
CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT_RECHERCHE" ("SOURCE_CODE","NOM_USUEL","NOM_PATRONYMIQUE","PRENOM","DATE_NAISSANCE","STRUCTURE","CIVILITE","CRITERE","ANNEE_ID") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  i.source_code,
  i.nom_usuel,
  i.nom_patronymique,
  i.prenom,
  i.date_naissance,
  s.libelle_court structure,
  c.libelle_long civilite,
  i.critere_recherche critere,
  i.annee_id
FROM
  src_intervenant i
  JOIN structure s ON s.id = i.structure_id
  JOIN civilite c ON c.id = i.civilite_id
WHERE
  i.id IS NULL;
---------------------------
--Modifié MATERIALIZED VIEW
--MV_INTERVENANT
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT";
CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT" ("ANNEE_CREATION","CIVILITE_ID","NOM_USUEL","PRENOM","NOM_PATRONYMIQUE","DATE_NAISSANCE","PAYS_NAISSANCE_CODE_INSEE","PAYS_NAISSANCE_LIBELLE","DEP_NAISSANCE_CODE_INSEE","DEP_NAISSANCE_LIBELLE","VILLE_NAISSANCE_CODE_INSEE","VILLE_NAISSANCE_LIBELLE","PAYS_NATIONALITE_CODE_INSEE","PAYS_NATIONALITE_LIBELLE","TEL_PRO","TEL_MOBILE","EMAIL","STATUT_ID","STATUT_CODE","Z_STRUCTURE_ID","SOURCE_ID","SOURCE_CODE","NUMERO_INSEE","NUMERO_INSEE_CLE","NUMERO_INSEE_PROVISOIRE","IBAN","BIC","Z_GRADE_ID","ORDRE","MIN_ORDRE","Z_DISCIPLINE_ID_CNU","Z_DISCIPLINE_ID_SOUS_CNU","Z_DISCIPLINE_ID_SPE_CNU","Z_DISCIPLINE_ID_DIS2DEG","CRITERE_RECHERCHE") 
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
  pbs_divers__cicg.c_grade@harpprod(individu.no_individu, TRUNC(validite.fin) ) z_grade_id,
  NVL(si.ordre,0)                                 ordre,
  NVL(min(si.ordre) over(partition BY individu.no_individu),0) min_ordre,
  CASE WHEN psc.no_dossier_pers IS NOT NULL THEN psc.c_section_cnu        ELSE ca.c_section_cnu       END z_discipline_id_cnu,
  CASE WHEN psc.no_dossier_pers IS NOT NULL THEN psc.c_sous_section_cnu   ELSE ca.c_sous_section_cnu  END z_discipline_id_sous_cnu,
  CASE WHEN psc.no_dossier_pers IS NOT NULL THEN psc.c_specialite_cnu     ELSE ca.c_specialite_cnu    END z_discipline_id_spe_cnu,
  CASE WHEN pss.no_dossier_pers IS NOT NULL THEN pss.c_disc_second_degre  ELSE ca.c_disc_second_degre END z_discipline_id_dis2deg,
  to_char(ose_divers.str_reduce(individu.nom_usuel || ' ' || individu.nom_patronymique || ' ' || individu.prenom)) critere_recherche
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
  LEFT JOIN contrat_avenant@harpprod    ca                 ON ca.no_dossier_pers             = individu.no_individu AND 1 = ose_divers.comprise_entre( ca.d_deb_contrat_trav, NVL(ca.d_fin_execution,ca.d_fin_contrat_trav), TRUNC(validite.fin), 1 )
  LEFT JOIN contrat_travail@harpprod    ct                 ON ct.no_dossier_pers             = ca.no_dossier_pers AND ct.no_contrat_travail = ca.no_contrat_travail
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
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('C3','CA','CB','CD','CS','HA','HD','HS','MA','S3','SX','SW','SY','SZ','VA') THEN 'BIATSS'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET') THEN 'NON_AUTORISE'

         WHEN c.c_type_population IN ('DA','OA','DC')                THEN 'ENS_2ND_DEG'
         WHEN c.c_type_population IN ('SA')                          THEN 'ENS_CH'
         WHEN c.c_type_population IN ('AA','AC','BA','IA','MA')      THEN 'BIATSS'
         WHEN c.c_type_population IN ('MG','SB')                     THEN 'HOSPITALO_UNIV'

                                                                     ELSE 'AUTRES' END
  LEFT JOIN periodes_sp_cnu@harpprod    psc                ON psc.no_dossier_pers = a.no_dossier_pers AND psc.no_seq_carriere = a.no_seq_carriere AND 1 = ose_divers.comprise_entre( psc.d_deb, psc.d_fin, TRUNC(validite.fin), 1 )
  LEFT JOIN periodes_sp_sd_deg@harpprod pss                ON pss.no_dossier_pers = a.no_dossier_pers AND pss.no_seq_carriere = a.no_seq_carriere AND 1 = ose_divers.comprise_entre( pss.d_deb, pss.d_fin, TRUNC(validite.fin), 1 );








DROP INDEX ELEMENT_PEDAGOGIQUE_ANNEE_IDX;
DROP INDEX NOTIF_INDICATEUR_UFK_IDX;
DROP INDEX DEPARTEMENT_HCFK_IDX;
DROP INDEX CENTRE_COUT_TYPE_RESSOURCE_IDX;
DROP INDEX CHEMIN_PEDAGOGIQUE_ETAPE_IDX;
DROP INDEX SR_IP_FK_IDX;
DROP INDEX TYPE_MODULATEUR_STRUCTU_HCIDX;
DROP INDEX TYPE_INTERVENTION_EP_SOUR_IDX;
DROP INDEX PAYS_HDFK_IDX;
DROP INDEX VOLUME_HORAIRE_ENS_HCFK_IDX;
DROP INDEX MOTIF_MODIFICATION_SERV_HCIDX;
DROP INDEX DOMAINE_FONCTIONNEL_HDFK_IDX;
DROP INDEX ETAPE_STRUCTURE_FK_IDX;
DROP INDEX INTERVENANT_STRUCTURE_FK_IDX;
DROP INDEX WF_INTERVENANT_ETAPE_EFK_IDX;
DROP INDEX CONTRAT_HCFK_IDX;
DROP INDEX CHEMIN_PEDAGOGIQUE_SOURCE_IDX;
DROP INDEX FICHIER_HMFK_IDX;
DROP INDEX VALIDATION_HDFK_IDX;
DROP INDEX SERVICE_ETABLISSEMENT_IDX;
DROP INDEX TIEP_TYPE_INTERVENTION_FK_IDX;
DROP INDEX ELEMENT_MODULATEUR_HMFK_IDX;
DROP INDEX TYPE_VALIDATION_HDFK_IDX;
DROP INDEX TIS_STRUCTURE_FK_IDX;
DROP INDEX INTERVENANT_DISCIPLINE_FK_IDX;
DROP INDEX DEPARTEMENT_SOURCE_FK_IDX;
DROP INDEX DOTATION_HCFK_IDX;
DROP INDEX DEPARTEMENT_HMFK_IDX;
DROP INDEX MODULATEUR_HCFK_IDX;
DROP INDEX EFFECTIFS_HMFK_IDX;
DROP INDEX PIECE_JOINTE_HMFK_IDX;
DROP INDEX INTERVENANT_NOM_IDX;
DROP INDEX ADRESSE_STRUCTURE_HCFK_IDX;
DROP INDEX VOLUME_HORAIRE_MNP_IDX;
DROP INDEX CONTRAT_HDFK_IDX;
DROP INDEX TYPE_RESSOURCE_HCFK_IDX;
DROP INDEX TYPE_MODULATEUR_STRUCTU_HMIDX;
DROP INDEX AGREMENT_HCFK_IDX;
DROP INDEX indic_diff_dossier_PK;
DROP INDEX TYPE_CONTRAT_HCFK_IDX;
DROP INDEX TPJS_STATUT_INTERVENANT_FK_IDX;
DROP INDEX ETAPE_HDFK_IDX;
DROP INDEX WF_INTERVENANT_ETAPE_IFK_IDX;
DROP INDEX MISE_EN_PAIEMENT_HCFK_IDX;
DROP INDEX TYPE_FORMATION_HCFK_IDX;
DROP INDEX TYPE_INTERVENTION_STRUC_HCIDX;
DROP INDEX PAYS_SOURCE_FK_IDX;
DROP INDEX TIS_TYPE_INTERVENTION_FK_IDX;
DROP INDEX CONTRAT_VALIDATION_FK_IDX;
DROP INDEX SR_STRUCTURE_FK_IDX;
DROP INDEX ETAPE_SOURCE_FK_IDX;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HDIDX;
DROP INDEX TYPE_MODULATEUR_HCFK_IDX;
DROP INDEX TYPE_INTERVENTION_HMFK_IDX;
DROP INDEX DOTATION_STRUCTURE_FK_IDX;
DROP INDEX WF_ETAPE_AFK_IDX;
DROP INDEX STATUT_INTERVENANT_HDFK_IDX;
DROP INDEX CCEP_TYPE_HEURES_FK_IDX;
DROP INDEX DS_MDS_FK_IDX;
DROP INDEX DISCIPLINE_HDFK_IDX;
DROP INDEX ELEMENT_PEDAGOGIQUE_PERIO_IDX;
DROP INDEX FRVH_FORMULE_RESULTAT_FK_IDX;
DROP INDEX VOLUME_HORAIRE_ENS_HMFK_IDX;
DROP INDEX AFFECTATION_HDFK_IDX;
DROP INDEX VOLUME_HORAIRE_HDFK_IDX;
DROP INDEX MEP_CENTRE_COUT_FK_IDX;
DROP INDEX TYPE_DOTATION_HCFK_IDX;
DROP INDEX ETABLISSEMENT_HMFK_IDX;
DROP INDEX ELEMENT_PEDAGOGIQUE_HMFK_IDX;
DROP INDEX TYPE_INTERVENTION_HCFK_IDX;
DROP INDEX ADRESSE_STRUCTURE_STRUCTU_IDX;
DROP INDEX AFFECTATION_R_HMFK_IDX;
DROP INDEX CONTRAT_HMFK_IDX;
DROP INDEX PERSONNEL_CIVILITE_FK_IDX;
DROP INDEX TYPE_MODULATEUR_HMFK_IDX;
DROP INDEX ELEMENT_MODULATEUR_HDFK_IDX;
DROP INDEX PARAMETRE_HCFK_IDX;
DROP INDEX SERVICE_REFERENTIEL_HCFK_IDX;
DROP INDEX ETABLISSEMENT_HDFK_IDX;
DROP INDEX AFFECTATION_PERSONNEL_FK_IDX;
DROP INDEX MISE_EN_PAIEMENT_HDFK_IDX;
DROP INDEX TYPE_FORMATION_HDFK_IDX;
DROP INDEX SERVICE_ELEMENT_IDX;
DROP INDEX VHENS_ELEMENT_DISCIPLINE_IDX;
DROP INDEX TIEP_ELEMENT_PEDAGOGIQUE_IDX;
DROP INDEX CC_ACTIVITE_HMFK_IDX;
DROP INDEX WF_INTERVENANT_ETAPE_SFK_IDX;
DROP INDEX STATUT_INTERVENANT_TYPE_FK_IDX;
DROP INDEX TYPE_AGREMENT_STATUT_HCFK_IDX;
DROP INDEX FORMULE_RES_SERVICE_REF_ID_IDX;
DROP INDEX DOSSIER_HCFK_IDX;
DROP INDEX NOTIF_INDICATEUR_SFK_IDX;
DROP INDEX SERVICE_REFERENTIEL_HMFK_IDX;
DROP INDEX FRVHR_VOLUME_HORAIRE_REF_IDX;
DROP INDEX CENTRE_COUT_EP_HMFK_IDX;
DROP INDEX VALIDATION_INTERVENANT_FK_IDX;
DROP INDEX MODIFICATION_SERVICE_DU_HDIDX;
DROP INDEX FRVHR_FORMULE_RESULTAT_FK_IDX;
DROP INDEX MISE_EN_PAIEMENT_HMFK_IDX;
DROP INDEX TYPE_VOLUME_HORAIRE_HDFK_IDX;
DROP INDEX DOSSIER_HDFK_IDX;
DROP INDEX TYPE_VOLUME_HORAIRE_HCFK_IDX;
DROP INDEX TYPE_RESSOURCE_HDFK_IDX;
DROP INDEX GROUPE_TYPE_FORMATION_HDFK_IDX;
DROP INDEX ETAPE_TYPE_FORMATION_FK_IDX;
DROP INDEX SERVICE_INTERVENANT_IDX;
DROP INDEX TYPE_INTERVENANT_HMFK_IDX;
DROP INDEX ADRESSE_INTERVENANT_HDFK_IDX;
DROP INDEX ETAPE_DOMAINE_FONCTIONNEL_IDX;
DROP INDEX CENTRE_COUT_HDFK_IDX;
DROP INDEX ELEMENT_TAUX_REGIMES_HMFK_IDX;
DROP INDEX INTERVENANTS_CIVILITES_FK_IDX;
DROP INDEX TYPE_INTERVENTION_HDFK_IDX;
DROP INDEX MODULATEUR_HMFK_IDX;
DROP INDEX TYPE_AGREMENT_HMFK_IDX;
DROP INDEX TYPE_DOTATION_SOURCE_FK_IDX;
DROP INDEX CENTRE_COUT_CENTRE_COUT_FK_IDX;
DROP INDEX MODULATEUR_HDFK_IDX;
DROP INDEX PAYS_HMFK_IDX;
DROP INDEX CORPS_HCFK_IDX;
DROP INDEX FRS_FORMULE_RESULTAT_FK_IDX;
DROP INDEX VHR_TYPE_VOLUME_HORAIRE_FK_IDX;
DROP INDEX MOTIF_MODIFICATION_SERV_HMIDX;
DROP INDEX INTERVENANT_HCFK_IDX;
DROP INDEX INTERVENANT_HDFK_IDX;
DROP INDEX DOSSIER_HMFK_IDX;
DROP INDEX MODULATEUR_TYPE_MODULATEUR_IDX;
DROP INDEX TYPE_VOLUME_HORAIRE_HMFK_IDX;
DROP INDEX ELEMENT_PEDAGOGIQUE_ETAPE_IDX;
DROP INDEX TYPE_VALIDATION_HMFK_IDX;
DROP INDEX AFFECTATION_HMFK_IDX;
DROP INDEX TYPE_PIECE_JOINTE_HCFK_IDX;
DROP INDEX TYPE_AGREMENT_STATUT_HDFK_IDX;
DROP INDEX ADRESSE_INTERVENANT_HCFK_IDX;
DROP INDEX MODIFICATION_SERVICE_DU_HMIDX;
DROP INDEX TYPE_MODULATEUR_EP_HMFK_IDX;
DROP INDEX TYPE_VALIDATION_HCFK_IDX;
DROP INDEX VOLUME_HORAIRE_CONTRAT_IDX;
DROP INDEX TYPE_FORMATION_SOURCE_FK_IDX;
DROP INDEX INTERVENANT_HMFK_IDX;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HCIDX;
DROP INDEX TAUX_HORAIRE_HETD_HDFK_IDX;
DROP INDEX TYPE_INTERVENTION_EP_HMFK_IDX;
DROP INDEX SERVICE_HCFK_IDX;
DROP INDEX CCEP_SOURCE_FK_IDX;
DROP INDEX PJ_TYPE_PIECE_JOINTE_FK_IDX;
DROP INDEX ADRESSE_INTERVENANT_SOURCE_IDX;
DROP INDEX ELEMENT_PEDAGOGIQUE_HCFK_IDX;
DROP INDEX GROUPE_TYPE_FORMATION_HMFK_IDX;
DROP INDEX VVH_VOLUME_HORAIRE_FK_IDX;
DROP INDEX CONTRAT_FICHIER_FFK_IDX;
DROP INDEX EFFECTIFS_HCFK_IDX;
DROP INDEX TYPE_HEURES_TYPE_HEURES_FK_IDX;
DROP INDEX TYPE_MODULATEUR_EP_HDFK_IDX;
DROP INDEX CC_ACTIVITE_HDFK_IDX;
DROP INDEX NOTIF_INDICATEUR_IFK_IDX;
DROP INDEX CENTRE_COUT_ACTIVITE_FK_IDX;
DROP INDEX TYPE_HEURES_HMFK_IDX;
DROP INDEX PARAMETRE_HMFK_IDX;
DROP INDEX STRUCTURE_SOURCE_FK_IDX;
DROP INDEX TYPE_INTERVENTION_EP_HDFK_IDX;
DROP INDEX STAT_PRIV_PRIVILEGE_FK_IDX;
DROP INDEX MOTIF_NON_PAIEMENT_HCFK_IDX;
DROP INDEX PERIODE_HCFK_IDX;
DROP INDEX CONTRAT_STRUCTURE_FK_IDX;
DROP INDEX TMS_STRUCTURE_FK_IDX;
DROP INDEX EFFECTIFS_HDFK_IDX;
DROP INDEX CHEMIN_PEDAGOGIQUE_HCFK_IDX;
DROP INDEX MISE_EN_PAIEMENT_VALIDATI_IDX;
DROP INDEX CENTRE_COUT_STRUCTURE_FK_IDX;
DROP INDEX FONCTION_REFERENTIEL_HMFK_IDX;
DROP INDEX PARAMETRE_HDFK_IDX;
DROP INDEX TYPE_INTERVENANT_HDFK_IDX;
DROP INDEX VOLUME_HORAIRE_ENS_HDFK_IDX;
DROP INDEX FONCTION_REFERENTIEL_HCFK_IDX;
DROP INDEX VOLUME_HORAIRE_ENS_SOURCE_IDX;
DROP INDEX CENTRE_COUT_EP_HDFK_IDX;
DROP INDEX PERSONNEL_HMFK_IDX;
DROP INDEX TYPE_AGREMENT_HCFK_IDX;
DROP INDEX AFFECTATION_R_STRUCTURE_FK_IDX;
DROP INDEX ETAPE_HCFK_IDX;
DROP INDEX AFFECTATION_R_INTERVENANT_IDX;
DROP INDEX SRFR_FK_IDX;
DROP INDEX AFFECTATION_STRUCTURE_FK_IDX;
DROP INDEX ELEMENT_MODULATEUR_HCFK_IDX;
DROP INDEX PAYS_HCFK_IDX;
DROP INDEX VALIDATION_HMFK_IDX;
DROP INDEX ROLE_PERIMETRE_FK_IDX;
DROP INDEX STRUCTURE_ETABLISSEMENT_FK_IDX;
DROP INDEX TYPE_STRUCTURE_HCFK_IDX;
DROP INDEX PIECE_JOINTE_FICHIER_FFK_IDX;
DROP INDEX TYPE_AGREMENT_HDFK_IDX;
DROP INDEX ETAT_VOLUME_HORAIRE_HCFK_IDX;
DROP INDEX FRS_SERVICE_FK_IDX;
DROP INDEX ADRESSE_INTERVENANT__UN;
DROP INDEX DOTATION_HMFK_IDX;
DROP INDEX MISE_EN_PAIEMENT_PERIODE_IDX;
DROP INDEX VHENS_TYPE_INTERVENTION_FK_IDX;
DROP INDEX ETAT_VOLUME_HORAIRE_HDFK_IDX;
DROP INDEX AFFECTATION_R_SOURCE_FK_IDX;
DROP INDEX ROLE_HCFK_IDX;
DROP INDEX CORPS_SOURCE_FK_IDX;
DROP INDEX TME_SOURCE_FK_IDX;
DROP INDEX VALIDATION_STRUCTURE_FK_IDX;
DROP INDEX INTERVENANT_STATUT_FK_IDX;
DROP INDEX ELEMENT_TAUX_REGIMES_HDFK_IDX;
DROP INDEX VVHR_VOLUME_HORAIRE_REF_FK_IDX;
DROP INDEX TYPE_INTERVENTION_EP_HCFK_IDX;
DROP INDEX INTERVENANT_SOURCE_FK_IDX;
DROP INDEX TYPE_DOTATION_HDFK_IDX;
DROP INDEX ROLE_HDFK_IDX;
DROP INDEX CORPS_HMFK_IDX;
DROP INDEX ROLE_HMFK_IDX;
DROP INDEX AGREMENT_HMFK_IDX;
DROP INDEX FONCTION_REFERENTIEL_SFK_IDX;
DROP INDEX EPS_FK_IDX;
DROP INDEX PERIODE_HDFK_IDX;
DROP INDEX GTYPE_FORMATION_SOURCE_FK_IDX;
DROP INDEX VOLUME_HORAIRE_SERVICE_IDX;
DROP INDEX STRUCTURES_STRUCTURES_FK_IDX;
DROP INDEX AFFECTATION_HCFK_IDX;
DROP INDEX FICHIER_VALID_FK_IDX;
DROP INDEX ADRESSE_INTERVENANT_HMFK_IDX;
DROP INDEX DS_IP_FK_IDX;
DROP INDEX ETR_SOURCE_FK_IDX;
DROP INDEX PERSONNEL_HCFK_IDX;
DROP INDEX PERIODE_HMFK_IDX;
DROP INDEX ETAPE_HMFK_IDX;
DROP INDEX GROUPE_TYPE_INTERVENTION_IDX;
DROP INDEX ELEMENT_PEDAGOGIQUE_HDFK_IDX;
DROP INDEX STATUT_INTERVENANT_HMFK_IDX;
DROP INDEX TAUX_HORAIRE_HETD_HMFK_IDX;
DROP INDEX AFFECTATION_ROLE_FK_IDX;
DROP INDEX FICHIER_HCFK_IDX;
DROP INDEX TYPE_STRUCTURE_HMFK_IDX;
DROP INDEX FONCTION_REFERENTIEL_HDFK_IDX;
DROP INDEX PERSONNEL_STRUCTURE_FK_IDX;
DROP INDEX GROUPE_HMFK_IDX;
DROP INDEX CENTRE_COUT_HMFK_IDX;
DROP INDEX GROUPE_HCFK_IDX;
DROP INDEX EFFECTIFS_SOURCE_FK_IDX;
DROP INDEX STRUCTURE_HMFK_IDX;
DROP INDEX AGREMENT_HDFK_IDX;
DROP INDEX INTERVENANT_ANNEE_FK_IDX;
DROP INDEX SERVICE_HISTO_IDX;
DROP INDEX FRVH_VOLUME_HORAIRE_FK_IDX;
DROP INDEX FRES_TYPE_VOLUME_HORAIRE_IDX;
DROP INDEX TYPE_HEURES_HDFK_IDX;
DROP INDEX MEP_TYPE_HEURES_FK_IDX;
DROP INDEX VOLUME_HORAIRE_PERIODE_IDX;
DROP INDEX AFFECTATION_R_HDFK_IDX;
DROP INDEX CONTRAT_TYPE_CONTRAT_FK_IDX;
DROP INDEX TYPE_CONTRAT_HDFK_IDX;
DROP INDEX PJ_DOSSIER_FK_IDX;
DROP INDEX DOTATION_ANNEE_FK_IDX;
DROP INDEX DISCIPLINE_HCFK_IDX;
DROP INDEX AFFECTATION_R_HCFK_IDX;
DROP INDEX PIECE_JOINTE_HCFK_IDX;
DROP INDEX PIECE_JOINTE_VFK_IDX;
DROP INDEX HIS_REFERENTIEL_IDX;
DROP INDEX CENTRE_COUT_EP_HCFK_IDX;
DROP INDEX CHEMIN_PEDAGOGIQUE_HMFK_IDX;
DROP INDEX FRR_FORMULE_RESULTAT_FK_IDX;
DROP INDEX DOTATION_HDFK_IDX;
DROP INDEX PIECE_JOINTE_HDFK_IDX;
DROP INDEX TYPE_INTERVENANT_HCFK_IDX;
DROP INDEX TYPE_FORMATION_HMFK_IDX;
DROP INDEX FORMULE_RES_SERVICE_ID_IDX;
DROP INDEX STRUCTURE_HDFK_IDX;
DROP INDEX TYPE_DOTATION_HMFK_IDX;
DROP INDEX DOMAINE_FONCTIONNEL_HMFK_IDX;
DROP INDEX EFFECTIFS_ELEMENT_FK_IDX;
DROP INDEX DOTATION_TYPE_DOTATION_FK_IDX;
DROP INDEX TYPE_MODULATEUR_HDFK_IDX;
DROP INDEX VOLUME_HORAIRE_TI_IDX;
DROP INDEX ETAT_VOLUME_HORAIRE_HMFK_IDX;
DROP INDEX CC_ACTIVITE_HCFK_IDX;
DROP INDEX TYPE_PIECE_JOINTE_HDFK_IDX;
DROP INDEX ELEMENT_PEDAGOGIQUE_SOURCE_IDX;
DROP INDEX TAUX_HORAIRE_HETD_HCFK_IDX;
DROP INDEX DISCIPLINE_HMFK_IDX;
DROP INDEX MOTIF_NON_PAIEMENT_HMFK_IDX;
DROP INDEX FICHIER_HDFK_IDX;
DROP INDEX STATUT_INTERVENANT_SOURCE_IDX;
DROP INDEX SERVICE_REFERENTIEL_HDFK_IDX;
DROP INDEX VALIDATION_HCFK_IDX;
DROP INDEX TYPE_MODULATEUR_STRUCTU_HDIDX;
DROP INDEX ETABLISSEMENT_HCFK_IDX;
DROP INDEX VHR_SERVICE_REFERENTIEL_FK_IDX;
DROP INDEX MOTIF_NON_PAIEMENT_HDFK_IDX;
DROP INDEX STRUCTURE_HCFK_IDX;
DROP INDEX AFFECTATION_SOURCE_FK_IDX;
DROP INDEX TYPE_INTERVENTION_STRUCT_HMIDX;
DROP INDEX TYPE_MODULATEUR_EP_HCFK_IDX;
DROP INDEX ADRESSE_STRUCTURE_SOURCE_IDX;
DROP INDEX CHEMIN_PEDAGOGIQUE_HDFK_IDX;
DROP INDEX TYPE_STRUCTURE_HDFK_IDX;
DROP INDEX STRUCTURE_STRUCTURE_FK_IDX;
DROP INDEX TD_TYPE_RESSOURCE_FK_IDX;
DROP INDEX MOTIF_MODIFICATION_SERV_HDIDX;
DROP INDEX AGREMENT_STRUCTURE_FK_IDX;
DROP INDEX TYPE_PIECE_JOINTE_HMFK_IDX;
DROP INDEX ADRESSE_STRUCTURE_HDFK_IDX;
DROP INDEX PERSONNEL_HDFK_IDX;
DROP INDEX ADRESSE_STRUCTURE_HMFK_IDX;
DROP INDEX CORPS_HDFK_IDX;
DROP INDEX GROUPE_TYPE_FORMATION_HCFK_IDX;
DROP INDEX CENTRE_COUT_SOURCE_FK_IDX;
DROP INDEX TYPE_RESSOURCE_HMFK_IDX;
DROP INDEX MODIFICATION_SERVICE_DU_HCIDX;
DROP INDEX TYPE_CONTRAT_HMFK_IDX;
DROP INDEX GROUPE_HDFK_IDX;
DROP INDEX STATUT_INTERVENANT_HCFK_IDX;
DROP INDEX DEPARTEMENT_HDFK_IDX;
DROP INDEX TAS_STATUT_INTERVENANT_FK_IDX;
DROP INDEX VOLUME_HORAIRE_HCFK_IDX;
DROP INDEX FRES_ETAT_VOLUME_HORAIRE_IDX;
DROP INDEX ELEMENT_TAUX_REGIMES_HCFK_IDX;
DROP INDEX DOMAINE_FONCTIONNEL_SOURCE_IDX;
DROP INDEX ROLE_PRIVILEGE_ROLE_FK_IDX;
DROP INDEX VOLUME_HORAIRE_HMFK_IDX;
DROP INDEX PERSONNEL_SOURCE_FK_IDX;
DROP INDEX DOMAINE_FONCTIONNEL_HCFK_IDX;
DROP INDEX TYPE_INTERVENTION_STRUC_HDIDX;
DROP INDEX CCEP_ELEMENT_PEDAGOGIQUE_IDX;
DROP INDEX TYPE_AGREMENT_STATUT_HMFK_IDX;
DROP INDEX TME_ELEMENT_PEDAGOGIQUE_FK_IDX;
DROP INDEX TYPE_PIECE_JOINTE_STATUT_HMIDX;
DROP INDEX ETABLISSEMENT_SOURCE_FK_IDX;
DROP INDEX CONTRAT_CONTRAT_FK_IDX;
DROP INDEX TYPE_FORMATION_GROUPE_FK_IDX;
DROP INDEX SERVICE_HMFK_IDX;
DROP INDEX EM_MODULATEUR_FK_IDX;
DROP INDEX CENTRE_COUT_HCFK_IDX;
DROP INDEX VOLUME_HORAIRE_TYPE_IDX;
DROP INDEX AGREMENT_INTERVENANT_FK_IDX;
DROP INDEX TYPE_HEURES_HCFK_IDX;
DROP INDEX SERVICE_HDFK_IDX;
DROP INDEX FRSR_SERVICE_REFERENTIEL_IDX;
DROP INDEX DISCIPLINE_SOURCE_FK_IDX;
DROP INDEX STRUCTURE_TYPE_STRUCTURE_IDX;

---------------------------
--Nouveau INDEX
--DEPARTEMENT_HDFK
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HDFK" ON "OSE"."DEPARTEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TMS_TYPE_MODUL_FK
---------------------------
  CREATE INDEX "OSE"."TMS_TYPE_MODUL_FK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("TYPE_MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--GRADE_HDFK
---------------------------
  CREATE INDEX "OSE"."GRADE_HDFK" ON "OSE"."GRADE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_UFK
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_UFK" ON "OSE"."NOTIFICATION_INDICATEUR" ("PERSONNEL_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_EFK
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_EFK" ON "OSE"."WF_INTERVENANT_ETAPE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_STRUCTURE_FK" ON "OSE"."AFFECTATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_STRUCTURE_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_STRUCTURE_HCFK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HCFK
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HCFK" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MV_INTERVENANT_NOM_USUEL_IDX
---------------------------
  CREATE INDEX "OSE"."MV_INTERVENANT_NOM_USUEL_IDX" ON "OSE"."MV_INTERVENANT" ("NOM_USUEL");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_HMFK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_HMFK" ON "OSE"."ADRESSE_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HCFK
---------------------------
  CREATE INDEX "OSE"."GROUPE_HCFK" ON "OSE"."GROUPE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HDFK" ON "OSE"."TYPE_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FRES_ETAT_VOLUME_HORAIRE_FK
---------------------------
  CREATE INDEX "OSE"."FRES_ETAT_VOLUME_HORAIRE_FK" ON "OSE"."FORMULE_RESULTAT" ("ETAT_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--CCEP_TYPE_HEURES_FK
---------------------------
  CREATE INDEX "OSE"."CCEP_TYPE_HEURES_FK" ON "OSE"."CENTRE_COUT_EP" ("TYPE_HEURES_ID");
---------------------------
--Nouveau INDEX
--EPS_FK
---------------------------
  CREATE INDEX "OSE"."EPS_FK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_SOURCE_CODE_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_SOURCE_CODE_IDX" ON "OSE"."INTERVENANT" ("SOURCE_CODE");
---------------------------
--Nouveau INDEX
--GROUPE_HDFK
---------------------------
  CREATE INDEX "OSE"."GROUPE_HDFK" ON "OSE"."GROUPE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HCFK
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HCFK" ON "OSE"."AGREMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION_TYPE_DOTATION_FK
---------------------------
  CREATE INDEX "OSE"."DOTATION_TYPE_DOTATION_FK" ON "OSE"."DOTATION" ("TYPE_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HMFK
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HMFK" ON "OSE"."PERSONNEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HCFK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HCFK" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_PERIODE_FK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_PERIODE_FK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("PERIODE_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HCFK
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HCFK" ON "OSE"."DOSSIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HDFK
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HDFK" ON "OSE"."EFFECTIFS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_SFK
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_SFK" ON "OSE"."NOTIFICATION_INDICATEUR" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--INDIC_DIFF_DOSSIER_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."INDIC_DIFF_DOSSIER_PK" ON "OSE"."INDIC_MODIF_DOSSIER" ("ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_PERSONNEL_FK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_PERSONNEL_FK" ON "OSE"."AFFECTATION" ("PERSONNEL_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_TYPE_HEURES_FK
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_TYPE_HEURES_FK" ON "OSE"."TYPE_HEURES" ("TYPE_HEURES_ELEMENT_ID");
---------------------------
--Nouveau INDEX
--FICHIER_VALID_FK
---------------------------
  CREATE INDEX "OSE"."FICHIER_VALID_FK" ON "OSE"."FICHIER" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--ETAPE_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."ETAPE_SOURCE_FK" ON "OSE"."ETAPE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CORPS_HDFK
---------------------------
  CREATE INDEX "OSE"."CORPS_HDFK" ON "OSE"."CORPS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HDFK
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HDFK" ON "OSE"."STATUT_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HMFK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HMFK" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_TYPE_CONTRAT_FK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_TYPE_CONTRAT_FK" ON "OSE"."CONTRAT" ("TYPE_CONTRAT_ID");
---------------------------
--Nouveau INDEX
--INDIC_MODIF_DOSSIER_HCFK
---------------------------
  CREATE INDEX "OSE"."INDIC_MODIF_DOSSIER_HCFK" ON "OSE"."INDIC_MODIF_DOSSIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HCFK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HCFK" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_SOURCE_FK" ON "OSE"."AFFECTATION_RECHERCHE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HMFK
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HMFK" ON "OSE"."PARAMETRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FRES_TYPE_VOLUME_HORAIRE_FK
---------------------------
  CREATE INDEX "OSE"."FRES_TYPE_VOLUME_HORAIRE_FK" ON "OSE"."FORMULE_RESULTAT" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--CORPS_HCFK
---------------------------
  CREATE INDEX "OSE"."CORPS_HCFK" ON "OSE"."CORPS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MV_INTERVENANT_RECHERCHE_IDX
---------------------------
  CREATE INDEX "OSE"."MV_INTERVENANT_RECHERCHE_IDX" ON "OSE"."MV_INTERVENANT" ("CRITERE_RECHERCHE");
---------------------------
--Nouveau INDEX
--ETAPE_HDFK
---------------------------
  CREATE INDEX "OSE"."ETAPE_HDFK" ON "OSE"."ETAPE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HMFK
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HMFK" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_SOURCE_FK" ON "OSE"."INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HCFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HCFK" ON "OSE"."VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_SOURCE_FK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_VALIDATION_FK
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_VALIDATION_FK" ON "OSE"."MISE_EN_PAIEMENT" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HDFK
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HDFK" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."DOTATION_STRUCTURE_FK" ON "OSE"."DOTATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--MEP_TYPE_HEURES_FK
---------------------------
  CREATE INDEX "OSE"."MEP_TYPE_HEURES_FK" ON "OSE"."MISE_EN_PAIEMENT" ("TYPE_HEURES_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HCFK
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HCFK" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_INTERVENTION_FK
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_INTERVENTION_FK" ON "OSE"."GROUPE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_HCFK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_HCFK" ON "OSE"."ADRESSE_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HMFK
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HMFK" ON "OSE"."DEPARTEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TIS_ANNEE_FIN_FK
---------------------------
  CREATE INDEX "OSE"."TIS_ANNEE_FIN_FK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("ANNEE_FIN_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HDFK
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HDFK" ON "OSE"."DISCIPLINE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TPJS_STATUT_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."TPJS_STATUT_INTERVENANT_FK" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("STATUT_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HCFK" ON "OSE"."TYPE_AGREMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HMFK" ON "OSE"."TYPE_VALIDATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_PERIMETRE_FK
---------------------------
  CREATE INDEX "OSE"."ROLE_PERIMETRE_FK" ON "OSE"."ROLE" ("PERIMETRE_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_SOURCE_FK" ON "OSE"."STRUCTURE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_ANNEE_FK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_ANNEE_FK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--GRADE_HCFK
---------------------------
  CREATE INDEX "OSE"."GRADE_HCFK" ON "OSE"."GRADE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HDFK
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HDFK" ON "OSE"."PIECE_JOINTE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GTYPE_FORMATION_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."GTYPE_FORMATION_SOURCE_FK" ON "OSE"."GROUPE_TYPE_FORMATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HMFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HMFK" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HCFK" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_ETABLISSEMENT_FK
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_ETABLISSEMENT_FK" ON "OSE"."STRUCTURE" ("ETABLISSEMENT_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HCFK" ON "OSE"."TYPE_RESSOURCE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CORPS_HMFK
---------------------------
  CREATE INDEX "OSE"."CORPS_HMFK" ON "OSE"."CORPS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--HSM_UTILISATEUR_FK
---------------------------
  CREATE INDEX "OSE"."HSM_UTILISATEUR_FK" ON "OSE"."HISTO_INTERVENANT_SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HCFK" ON "OSE"."TYPE_INTERVENTION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FRVHR_FORMULE_RESULTAT_FK
---------------------------
  CREATE INDEX "OSE"."FRVHR_FORMULE_RESULTAT_FK" ON "OSE"."FORMULE_RESULTAT_VH_REF" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--MOTIF_MODIFICATION_SERVIC_HDFK
---------------------------
  CREATE INDEX "OSE"."MOTIF_MODIFICATION_SERVIC_HDFK" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HMFK
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HMFK" ON "OSE"."MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HCFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HCFK" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HDFK
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HDFK" ON "OSE"."ETABLISSEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."CCEP_SOURCE_FK" ON "OSE"."CENTRE_COUT_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_SOURCE_FK" ON "OSE"."TYPE_DOTATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HCFK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HCFK" ON "OSE"."CENTRE_COUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETR_ELEMENT_FK
---------------------------
  CREATE INDEX "OSE"."ETR_ELEMENT_FK" ON "OSE"."ELEMENT_TAUX_REGIMES" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HMFK
---------------------------
  CREATE INDEX "OSE"."PERIODE_HMFK" ON "OSE"."PERIODE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--NOTIF_INDICATEUR_IFK
---------------------------
  CREATE INDEX "OSE"."NOTIF_INDICATEUR_IFK" ON "OSE"."NOTIFICATION_INDICATEUR" ("INDICATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HDFK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HDFK" ON "OSE"."CENTRE_COUT_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PJ_TYPE_PIECE_JOINTE_FK
---------------------------
  CREATE INDEX "OSE"."PJ_TYPE_PIECE_JOINTE_FK" ON "OSE"."PIECE_JOINTE" ("TYPE_PIECE_JOINTE_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HMFK
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HMFK" ON "OSE"."EFFECTIFS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."ETAPE_STRUCTURE_FK" ON "OSE"."ETAPE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_SOURCE_FK" ON "OSE"."DEPARTEMENT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--INDIC_MODIF_DOSSIER_HDFK
---------------------------
  CREATE INDEX "OSE"."INDIC_MODIF_DOSSIER_HDFK" ON "OSE"."INDIC_MODIF_DOSSIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_STATUT_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT_HCFK" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_STRUCTU_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_STRUCTU_HMFK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HDFK
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HDFK" ON "OSE"."PERSONNEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_SOURCE_FK" ON "OSE"."ADRESSE_STRUCTURE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_INTERVENANT_FK" ON "OSE"."CONTRAT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HDFK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HDFK" ON "OSE"."CONTRAT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HMFK
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HMFK" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PAYS_HMFK
---------------------------
  CREATE INDEX "OSE"."PAYS_HMFK" ON "OSE"."PAYS" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HMFK
---------------------------
  CREATE INDEX "OSE"."FICHIER_HMFK" ON "OSE"."FICHIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HDFK" ON "OSE"."TYPE_MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--GRADE_HMFK
---------------------------
  CREATE INDEX "OSE"."GRADE_HMFK" ON "OSE"."GRADE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HMFK
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HMFK" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HDFK
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HDFK" ON "OSE"."PARAMETRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HCFK
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HCFK" ON "OSE"."MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HCFK
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HCFK" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_CIVILITE_FK
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_CIVILITE_FK" ON "OSE"."PERSONNEL" ("CIVILITE_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HCFK" ON "OSE"."TYPE_FORMATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HDFK
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HDFK" ON "OSE"."AGREMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_HCFK" ON "OSE"."TYPE_HEURES" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HMFK
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HMFK" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CPEP_FK
---------------------------
  CREATE INDEX "OSE"."CPEP_FK" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--FRVHR_VOLUME_HORAIRE_REF_FK
---------------------------
  CREATE INDEX "OSE"."FRVHR_VOLUME_HORAIRE_REF_FK" ON "OSE"."FORMULE_RESULTAT_VH_REF" ("VOLUME_HORAIRE_REF_ID");
---------------------------
--Nouveau INDEX
--TIS_TYPE_INTERVENTION_FK
---------------------------
  CREATE INDEX "OSE"."TIS_TYPE_INTERVENTION_FK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HCFK
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HCFK" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_FICHIER_FK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_FICHIER_FK" ON "OSE"."CONTRAT_FICHIER" ("CONTRAT_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HCFK
---------------------------
  CREATE INDEX "OSE"."PERIODE_HCFK" ON "OSE"."PERIODE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_HCFK
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_HCFK" ON "OSE"."EFFECTIFS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_TYPE_RESSOURCE_FK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_TYPE_RESSOURCE_FK" ON "OSE"."CENTRE_COUT" ("TYPE_RESSOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_HMFK" ON "OSE"."TYPE_HEURES" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_TYPE_AGREMENT_FK
---------------------------
  CREATE INDEX "OSE"."AGREMENT_TYPE_AGREMENT_FK" ON "OSE"."AGREMENT" ("TYPE_AGREMENT_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HCFK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HCFK" ON "OSE"."CENTRE_COUT_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_HCFK
---------------------------
  CREATE INDEX "OSE"."ROLE_HCFK" ON "OSE"."ROLE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_STATUT_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT_HMFK" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_TYPE_FORMATION_HDFK
---------------------------
  CREATE INDEX "OSE"."GROUPE_TYPE_FORMATION_HDFK" ON "OSE"."GROUPE_TYPE_FORMATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VHMNP_FK
---------------------------
  CREATE INDEX "OSE"."VHMNP_FK" ON "OSE"."VOLUME_HORAIRE" ("MOTIF_NON_PAIEMENT_ID");
---------------------------
--Nouveau INDEX
--SERVICE_ETABLISSEMENT_FK
---------------------------
  CREATE INDEX "OSE"."SERVICE_ETABLISSEMENT_FK" ON "OSE"."SERVICE" ("ETABLISSEMENT_ID");
---------------------------
--Nouveau INDEX
--DS_MDS_FK
---------------------------
  CREATE INDEX "OSE"."DS_MDS_FK" ON "OSE"."MODIFICATION_SERVICE_DU" ("MOTIF_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HMFK
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HMFK" ON "OSE"."ETABLISSEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TAS_STATUT_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."TAS_STATUT_INTERVENANT_FK" ON "OSE"."TYPE_AGREMENT_STATUT" ("STATUT_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--FRVH_VOLUME_HORAIRE_FK
---------------------------
  CREATE INDEX "OSE"."FRVH_VOLUME_HORAIRE_FK" ON "OSE"."FORMULE_RESULTAT_VH" ("VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--FRS_SERVICE_FK
---------------------------
  CREATE INDEX "OSE"."FRS_SERVICE_FK" ON "OSE"."FORMULE_RESULTAT_SERVICE" ("SERVICE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."SERVICE_INTERVENANT_FK" ON "OSE"."SERVICE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_ETAPE_FK
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_ETAPE_FK" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--PAYS_HCFK
---------------------------
  CREATE INDEX "OSE"."PAYS_HCFK" ON "OSE"."PAYS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_DOMAINE_FONCTIONNEL_FK
---------------------------
  CREATE INDEX "OSE"."ETAPE_DOMAINE_FONCTIONNEL_FK" ON "OSE"."ETAPE" ("DOMAINE_FONCTIONNEL_ID");
---------------------------
--Nouveau INDEX
--STAT_PRIV_STATUT_FK
---------------------------
  CREATE INDEX "OSE"."STAT_PRIV_STATUT_FK" ON "OSE"."STATUT_PRIVILEGE" ("STATUT_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HMFK" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HCFK
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HCFK" ON "OSE"."VALIDATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--FONC_REF_DOMAINE_FONCT_FK
---------------------------
  CREATE INDEX "OSE"."FONC_REF_DOMAINE_FONCT_FK" ON "OSE"."FONCTION_REFERENTIEL" ("DOMAINE_FONCTIONNEL_ID");
---------------------------
--Nouveau INDEX
--INDIC_MODIF_DOSSIER_HMFK
---------------------------
  CREATE INDEX "OSE"."INDIC_MODIF_DOSSIER_HMFK" ON "OSE"."INDIC_MODIF_DOSSIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_DOMAINE_FONCTIONNEL_FK
---------------------------
  CREATE INDEX "OSE"."MEP_DOMAINE_FONCTIONNEL_FK" ON "OSE"."MISE_EN_PAIEMENT" ("DOMAINE_FONCTIONNEL_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_STRUCTU_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_STRUCTU_HCFK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SR_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."SR_STRUCTURE_FK" ON "OSE"."SERVICE_REFERENTIEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HMFK" ON "OSE"."TYPE_INTERVENTION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_STRUCTU_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_STRUCTU_HDFK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HDFK
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HDFK" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MOTIF_MODIFICATION_SERVIC_HCFK
---------------------------
  CREATE INDEX "OSE"."MOTIF_MODIFICATION_SERVIC_HCFK" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_TYPE_MODULATEUR_FK
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_TYPE_MODULATEUR_FK" ON "OSE"."MODULATEUR" ("TYPE_MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_NOM_PATRO_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_NOM_PATRO_IDX" ON "OSE"."INTERVENANT" ("NOM_PATRONYMIQUE");
---------------------------
--Nouveau INDEX
--MODIFICATION_SERVICE_DU_HMFK
---------------------------
  CREATE INDEX "OSE"."MODIFICATION_SERVICE_DU_HMFK" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--GROUPE_HMFK
---------------------------
  CREATE INDEX "OSE"."GROUPE_HMFK" ON "OSE"."GROUPE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_RECHERCHE_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_RECHERCHE_IDX" ON "OSE"."INTERVENANT" ("CRITERE_RECHERCHE");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_HCFK
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_HCFK" ON "OSE"."ETABLISSEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_TYPE_FORMATION_FK
---------------------------
  CREATE INDEX "OSE"."ETAPE_TYPE_FORMATION_FK" ON "OSE"."ETAPE" ("TYPE_FORMATION_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HCFK" ON "OSE"."TYPE_DOTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VHENS_TYPE_INTERVENTION_FK
---------------------------
  CREATE INDEX "OSE"."VHENS_TYPE_INTERVENTION_FK" ON "OSE"."VOLUME_HORAIRE_ENS" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_STATUT_FK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_STATUT_FK" ON "OSE"."INTERVENANT" ("STATUT_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HMFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HMFK" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HDFK
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HDFK" ON "OSE"."STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FRS_FORMULE_RESULTAT_FK
---------------------------
  CREATE INDEX "OSE"."FRS_FORMULE_RESULTAT_FK" ON "OSE"."FORMULE_RESULTAT_SERVICE" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--SR_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."SR_INTERVENANT_FK" ON "OSE"."SERVICE_REFERENTIEL" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_SOURCE_FK" ON "OSE"."PERSONNEL" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_HDFK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_HDFK" ON "OSE"."ADRESSE_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_SFK
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_SFK" ON "OSE"."WF_INTERVENANT_ETAPE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HMFK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HMFK" ON "OSE"."CENTRE_COUT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_ELEMENT_PEDAGOGIQUE_FK
---------------------------
  CREATE INDEX "OSE"."CCEP_ELEMENT_PEDAGOGIQUE_FK" ON "OSE"."CENTRE_COUT_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HCFK
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HCFK" ON "OSE"."PIECE_JOINTE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CCEP_CENTRE_COUT_FK
---------------------------
  CREATE INDEX "OSE"."CCEP_CENTRE_COUT_FK" ON "OSE"."CENTRE_COUT_EP" ("CENTRE_COUT_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HMFK
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HMFK" ON "OSE"."CC_ACTIVITE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_HDFK
---------------------------
  CREATE INDEX "OSE"."ROLE_HDFK" ON "OSE"."ROLE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HCFK" ON "OSE"."TYPE_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VVH_VOLUME_HORAIRE_FK
---------------------------
  CREATE INDEX "OSE"."VVH_VOLUME_HORAIRE_FK" ON "OSE"."VALIDATION_VOL_HORAIRE" ("VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HDFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HDFK" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_FICHIER_FFK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_FICHIER_FFK" ON "OSE"."CONTRAT_FICHIER" ("FICHIER_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_SFK
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_SFK" ON "OSE"."FONCTION_REFERENTIEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_SOURCE_FK" ON "OSE"."STATUT_INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--SRFR_FK
---------------------------
  CREATE INDEX "OSE"."SRFR_FK" ON "OSE"."SERVICE_REFERENTIEL" ("FONCTION_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."VALIDATION_INTERVENANT_FK" ON "OSE"."VALIDATION" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--TME_ELEMENT_PEDAGOGIQUE_FK
---------------------------
  CREATE INDEX "OSE"."TME_ELEMENT_PEDAGOGIQUE_FK" ON "OSE"."TYPE_MODULATEUR_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--TMS_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."TMS_STRUCTURE_FK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_VALIDATION_FK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_VALIDATION_FK" ON "OSE"."CONTRAT" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_HMFK
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_HMFK" ON "OSE"."MISE_EN_PAIEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HDFK
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HDFK" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HMFK
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HMFK" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HDFK
---------------------------
  CREATE INDEX "OSE"."FICHIER_HDFK" ON "OSE"."FICHIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_ELEMENT_FK
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_ELEMENT_FK" ON "OSE"."EFFECTIFS" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--PAYS_HDFK
---------------------------
  CREATE INDEX "OSE"."PAYS_HDFK" ON "OSE"."PAYS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--FRR_FORMULE_RESULTAT_FK
---------------------------
  CREATE INDEX "OSE"."FRR_FORMULE_RESULTAT_FK" ON "OSE"."FORMULE_RESULTAT_SERVICE_REF" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HCFK" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HMFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HMFK" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HDFK" ON "OSE"."TYPE_CONTRAT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HDFK" ON "OSE"."TYPE_AGREMENT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_HMFK
---------------------------
  CREATE INDEX "OSE"."ROLE_HMFK" ON "OSE"."ROLE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HMFK
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HMFK" ON "OSE"."DISCIPLINE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MODULATEUR_HDFK
---------------------------
  CREATE INDEX "OSE"."MODULATEUR_HDFK" ON "OSE"."MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_SOURCE_FK" ON "OSE"."ADRESSE_INTERVENANT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_TYPE_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_TYPE_STRUCTURE_FK" ON "OSE"."STRUCTURE" ("TYPE_ID");
---------------------------
--Nouveau INDEX
--ROLE_PRIVILEGE_ROLE_FK
---------------------------
  CREATE INDEX "OSE"."ROLE_PRIVILEGE_ROLE_FK" ON "OSE"."ROLE_PRIVILEGE" ("ROLE_ID");
---------------------------
--Nouveau INDEX
--MESSAGES__UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."MESSAGES__UN" ON "OSE"."MESSAGE" ("CODE");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HCFK" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ROLE_PRIVILEGE_PRIVILEGE_FK
---------------------------
  CREATE INDEX "OSE"."ROLE_PRIVILEGE_PRIVILEGE_FK" ON "OSE"."ROLE_PRIVILEGE" ("PRIVILEGE_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HMFK" ON "OSE"."TYPE_DOTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HDFK" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VVHR_VOLUME_HORAIRE_REF_FK
---------------------------
  CREATE INDEX "OSE"."VVHR_VOLUME_HORAIRE_REF_FK" ON "OSE"."VALIDATION_VOL_HORAIRE_REF" ("VOLUME_HORAIRE_REF_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_STRUCTURE_FK" ON "OSE"."AFFECTATION_RECHERCHE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--VOLUMES_HORAIRES_SERVICES_FK
---------------------------
  CREATE INDEX "OSE"."VOLUMES_HORAIRES_SERVICES_FK" ON "OSE"."VOLUME_HORAIRE" ("SERVICE_ID");
---------------------------
--Nouveau INDEX
--EP_DISCIPLINE_FK
---------------------------
  CREATE INDEX "OSE"."EP_DISCIPLINE_FK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("DISCIPLINE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HMFK" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HCFK
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HCFK" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VHR_SERVICE_REFERENTIEL_FK
---------------------------
  CREATE INDEX "OSE"."VHR_SERVICE_REFERENTIEL_FK" ON "OSE"."VOLUME_HORAIRE_REF" ("SERVICE_REFERENTIEL_ID");
---------------------------
--Nouveau INDEX
--TMS_ANNEE_FIN_FK
---------------------------
  CREATE INDEX "OSE"."TMS_ANNEE_FIN_FK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("ANNEE_FIN_ID");
---------------------------
--Nouveau INDEX
--FICHIER_HCFK
---------------------------
  CREATE INDEX "OSE"."FICHIER_HCFK" ON "OSE"."FICHIER" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HCFK
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HCFK" ON "OSE"."STATUT_INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--EM_MODULATEUR_FK
---------------------------
  CREATE INDEX "OSE"."EM_MODULATEUR_FK" ON "OSE"."ELEMENT_MODULATEUR" ("MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HMFK
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HMFK" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_ACTIVITE_FK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_ACTIVITE_FK" ON "OSE"."CENTRE_COUT" ("ACTIVITE_ID");
---------------------------
--Nouveau INDEX
--TIEP_ELEMENT_PEDAGOGIQUE_FK
---------------------------
  CREATE INDEX "OSE"."TIEP_ELEMENT_PEDAGOGIQUE_FK" ON "OSE"."TYPE_INTERVENTION_EP" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--FRVH_FORMULE_RESULTAT_FK
---------------------------
  CREATE INDEX "OSE"."FRVH_FORMULE_RESULTAT_FK" ON "OSE"."FORMULE_RESULTAT_VH" ("FORMULE_RESULTAT_ID");
---------------------------
--Nouveau INDEX
--TAUX_HORAIRE_HETD_HCFK
---------------------------
  CREATE INDEX "OSE"."TAUX_HORAIRE_HETD_HCFK" ON "OSE"."TAUX_HORAIRE_HETD" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MODIFICATION_SERVICE_DU_HCFK
---------------------------
  CREATE INDEX "OSE"."MODIFICATION_SERVICE_DU_HCFK" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TME_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."TME_SOURCE_FK" ON "OSE"."TYPE_MODULATEUR_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_REF_HCFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_REF_HCFK" ON "OSE"."VOLUME_HORAIRE_REF" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HCFK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HCFK" ON "OSE"."INTERVENANT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--WF_INTERVENANT_ETAPE_IFK
---------------------------
  CREATE INDEX "OSE"."WF_INTERVENANT_ETAPE_IFK" ON "OSE"."WF_INTERVENANT_ETAPE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--PRIVILEGE_CATEGORIE_FK
---------------------------
  CREATE INDEX "OSE"."PRIVILEGE_CATEGORIE_FK" ON "OSE"."PRIVILEGE" ("CATEGORIE_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HMFK" ON "OSE"."TYPE_CONTRAT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VH_TYPE_VOLUME_HORAIRE_FK
---------------------------
  CREATE INDEX "OSE"."VH_TYPE_VOLUME_HORAIRE_FK" ON "OSE"."VOLUME_HORAIRE" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HCFK
---------------------------
  CREATE INDEX "OSE"."DOTATION_HCFK" ON "OSE"."DOTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HDFK" ON "OSE"."TYPE_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_TYPE_FK
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_TYPE_FK" ON "OSE"."STATUT_INTERVENANT" ("TYPE_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HCFK
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HCFK" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--STATUT_INTERVENANT_HMFK
---------------------------
  CREATE INDEX "OSE"."STATUT_INTERVENANT_HMFK" ON "OSE"."STATUT_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_DOTATION_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_DOTATION_HDFK" ON "OSE"."TYPE_DOTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION_ANNEE_FK
---------------------------
  CREATE INDEX "OSE"."DOTATION_ANNEE_FK" ON "OSE"."DOTATION" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--TME_TYPE_MODULATEUR_FK
---------------------------
  CREATE INDEX "OSE"."TME_TYPE_MODULATEUR_FK" ON "OSE"."TYPE_MODULATEUR_EP" ("TYPE_MODULATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_SOURCE_FK" ON "OSE"."AFFECTATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HMFK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HMFK" ON "OSE"."AFFECTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HDFK" ON "OSE"."TYPE_VALIDATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HMFK
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HMFK" ON "OSE"."STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HDFK
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HDFK" ON "OSE"."DOSSIER" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HCFK
---------------------------
  CREATE INDEX "OSE"."ETAPE_HCFK" ON "OSE"."ETAPE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_SOURCE_FK" ON "OSE"."TYPE_FORMATION" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HDFK" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HMFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HMFK" ON "OSE"."VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_STRUCTURE_FK" ON "OSE"."INTERVENANT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HMFK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HMFK" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_REF_HMFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_REF_HMFK" ON "OSE"."VOLUME_HORAIRE_REF" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HDFK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HDFK" ON "OSE"."AFFECTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_HEURES_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_HEURES_HDFK" ON "OSE"."TYPE_HEURES" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TIEP_TYPE_INTERVENTION_FK
---------------------------
  CREATE INDEX "OSE"."TIEP_TYPE_INTERVENTION_FK" ON "OSE"."TYPE_INTERVENTION_EP" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--GRADE_CORPS_FK
---------------------------
  CREATE INDEX "OSE"."GRADE_CORPS_FK" ON "OSE"."GRADE" ("CORPS_ID");
---------------------------
--Nouveau INDEX
--STAT_PRIV_PRIVILEGE_FK
---------------------------
  CREATE INDEX "OSE"."STAT_PRIV_PRIVILEGE_FK" ON "OSE"."STATUT_PRIVILEGE" ("PRIVILEGE_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_FICHIER_FFK
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_FICHIER_FFK" ON "OSE"."PIECE_JOINTE_FICHIER" ("FICHIER_ID");
---------------------------
--Nouveau INDEX
--FRSR_SERVICE_REFERENTIEL_FK
---------------------------
  CREATE INDEX "OSE"."FRSR_SERVICE_REFERENTIEL_FK" ON "OSE"."FORMULE_RESULTAT_SERVICE_REF" ("SERVICE_REFERENTIEL_ID");
---------------------------
--Nouveau INDEX
--DEPARTEMENT_HCFK
---------------------------
  CREATE INDEX "OSE"."DEPARTEMENT_HCFK" ON "OSE"."DEPARTEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--MODIFICATION_SERVICE_DU_HDFK
---------------------------
  CREATE INDEX "OSE"."MODIFICATION_SERVICE_DU_HDFK" ON "OSE"."MODIFICATION_SERVICE_DU" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TAS_TYPE_AGREMENT_FK
---------------------------
  CREATE INDEX "OSE"."TAS_TYPE_AGREMENT_FK" ON "OSE"."TYPE_AGREMENT_STATUT" ("TYPE_AGREMENT_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_CONTRAT_FK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_CONTRAT_FK" ON "OSE"."CONTRAT" ("CONTRAT_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HMFK" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--FRES_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."FRES_INTERVENANT_FK" ON "OSE"."FORMULE_RESULTAT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_HMFK
---------------------------
  CREATE INDEX "OSE"."AGREMENT_HMFK" ON "OSE"."AGREMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_VOLUME_HORAIRE_HCFK" ON "OSE"."TYPE_VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_STRUCTURE_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_STRUCTURE_HMFK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HDFK
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HDFK" ON "OSE"."CC_ACTIVITE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_SOURCE_FK" ON "OSE"."DISCIPLINE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_SOURCE_FK" ON "OSE"."VOLUME_HORAIRE_ENS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ETR_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."ETR_SOURCE_FK" ON "OSE"."ELEMENT_TAUX_REGIMES" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--FONCTION_REFERENTIEL_HDFK
---------------------------
  CREATE INDEX "OSE"."FONCTION_REFERENTIEL_HDFK" ON "OSE"."FONCTION_REFERENTIEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HDFK
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HDFK" ON "OSE"."VALIDATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_STRUCTURE_FK" ON "OSE"."CENTRE_COUT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_HDFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_HDFK" ON "OSE"."VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_HDFK
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_HDFK" ON "OSE"."DOMAINE_FONCTIONNEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_INTERVENANT_HDFK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_INTERVENANT_HDFK" ON "OSE"."ADRESSE_INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--EM_ELEMENT_PEDAGOGIQUE_FK
---------------------------
  CREATE INDEX "OSE"."EM_ELEMENT_PEDAGOGIQUE_FK" ON "OSE"."ELEMENT_MODULATEUR" ("ELEMENT_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_VFK
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_VFK" ON "OSE"."PIECE_JOINTE" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_HMFK" ON "OSE"."TYPE_PIECE_JOINTE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_HCFK
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_HCFK" ON "OSE"."STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HCFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HCFK" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PAYS_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."PAYS_SOURCE_FK" ON "OSE"."PAYS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_VALIDATION_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_VALIDATION_HCFK" ON "OSE"."TYPE_VALIDATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_HDFK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_HDFK" ON "OSE"."CENTRE_COUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_STATUT_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_STATUT_HDFK" ON "OSE"."TYPE_AGREMENT_STATUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VHR_TYPE_VOLUME_HORAIRE_FK
---------------------------
  CREATE INDEX "OSE"."VHR_TYPE_VOLUME_HORAIRE_FK" ON "OSE"."VOLUME_HORAIRE_REF" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--WF_ETAPE_AFK
---------------------------
  CREATE INDEX "OSE"."WF_ETAPE_AFK" ON "OSE"."WF_ETAPE" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_HDFK
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_HDFK" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HCFK
---------------------------
  CREATE INDEX "OSE"."SERVICE_HCFK" ON "OSE"."SERVICE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_HMFK
---------------------------
  CREATE INDEX "OSE"."DOSSIER_HMFK" ON "OSE"."DOSSIER" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MOTIF_MODIFICATION_SERVIC_HMFK
---------------------------
  CREATE INDEX "OSE"."MOTIF_MODIFICATION_SERVIC_HMFK" ON "OSE"."MOTIF_MODIFICATION_SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HCFK" ON "OSE"."TYPE_STRUCTURE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HDFK
---------------------------
  CREATE INDEX "OSE"."SERVICE_HDFK" ON "OSE"."SERVICE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TPJS_TYPE_PIECE_JOINTE_FK
---------------------------
  CREATE INDEX "OSE"."TPJS_TYPE_PIECE_JOINTE_FK" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("TYPE_PIECE_JOINTE_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_HMFK
---------------------------
  CREATE INDEX "OSE"."VALIDATION_HMFK" ON "OSE"."VALIDATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_ELEMENT_PEDAGOGIQUE_FK
---------------------------
  CREATE INDEX "OSE"."SERVICE_ELEMENT_PEDAGOGIQUE_FK" ON "OSE"."SERVICE" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--PARAMETRE_HCFK
---------------------------
  CREATE INDEX "OSE"."PARAMETRE_HCFK" ON "OSE"."PARAMETRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_HCFK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_HCFK" ON "OSE"."AFFECTATION" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--PJ_DOSSIER_FK
---------------------------
  CREATE INDEX "OSE"."PJ_DOSSIER_FK" ON "OSE"."PIECE_JOINTE" ("DOSSIER_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HMFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HMFK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VVH_VALIDATION_FK
---------------------------
  CREATE INDEX "OSE"."VVH_VALIDATION_FK" ON "OSE"."VALIDATION_VOL_HORAIRE" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--MESSAGES_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."MESSAGES_PK" ON "OSE"."MESSAGE" ("ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HMFK" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MEP_CENTRE_COUT_FK
---------------------------
  CREATE INDEX "OSE"."MEP_CENTRE_COUT_FK" ON "OSE"."MISE_EN_PAIEMENT" ("CENTRE_COUT_ID");
---------------------------
--Nouveau INDEX
--TIS_ANNEE_DEBUT_FK
---------------------------
  CREATE INDEX "OSE"."TIS_ANNEE_DEBUT_FK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("ANNEE_DEBUT_ID");
---------------------------
--Nouveau INDEX
--DOMAINE_FONCTIONNEL_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."DOMAINE_FONCTIONNEL_SOURCE_FK" ON "OSE"."DOMAINE_FONCTIONNEL" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--EFFECTIFS_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."EFFECTIFS_SOURCE_FK" ON "OSE"."EFFECTIFS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HDFK" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_SOURCE_FK" ON "OSE"."TYPE_INTERVENTION_EP" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--VALIDATION_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."VALIDATION_STRUCTURE_FK" ON "OSE"."VALIDATION" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--SERVICE_HMFK
---------------------------
  CREATE INDEX "OSE"."SERVICE_HMFK" ON "OSE"."SERVICE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_CONTRAT_FK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_CONTRAT_FK" ON "OSE"."VOLUME_HORAIRE" ("CONTRAT_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HCFK
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HCFK" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--VVHR_VALIDATION_FK
---------------------------
  CREATE INDEX "OSE"."VVHR_VALIDATION_FK" ON "OSE"."VALIDATION_VOL_HORAIRE_REF" ("VALIDATION_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_TAUX_REGIMES_HCFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_TAUX_REGIMES_HCFK" ON "OSE"."ELEMENT_TAUX_REGIMES" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_EP_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_EP_HCFK" ON "OSE"."TYPE_INTERVENTION_EP" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HMFK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HMFK" ON "OSE"."INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_HCFK
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_HCFK" ON "OSE"."PERSONNEL" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HDFK
---------------------------
  CREATE INDEX "OSE"."DOTATION_HDFK" ON "OSE"."DOTATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TMS_ANNEE_DEBUT_FK
---------------------------
  CREATE INDEX "OSE"."TMS_ANNEE_DEBUT_FK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("ANNEE_DEBUT_ID");
---------------------------
--Nouveau INDEX
--MEP_FR_SERVICE_REF_FK
---------------------------
  CREATE INDEX "OSE"."MEP_FR_SERVICE_REF_FK" ON "OSE"."MISE_EN_PAIEMENT" ("FORMULE_RES_SERVICE_REF_ID");
---------------------------
--Nouveau INDEX
--MISE_EN_PAIEMENT_PERIODE_FK
---------------------------
  CREATE INDEX "OSE"."MISE_EN_PAIEMENT_PERIODE_FK" ON "OSE"."MISE_EN_PAIEMENT" ("PERIODE_PAIEMENT_ID");
---------------------------
--Nouveau INDEX
--TD_TYPE_RESSOURCE_FK
---------------------------
  CREATE INDEX "OSE"."TD_TYPE_RESSOURCE_FK" ON "OSE"."TYPE_DOTATION" ("TYPE_RESSOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_AGREMENT_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_AGREMENT_HMFK" ON "OSE"."TYPE_AGREMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_HDFK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_HDFK" ON "OSE"."AFFECTATION_RECHERCHE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."AGREMENT_STRUCTURE_FK" ON "OSE"."AGREMENT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--ETAPE_HMFK
---------------------------
  CREATE INDEX "OSE"."ETAPE_HMFK" ON "OSE"."ETAPE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HDFK" ON "OSE"."TYPE_RESSOURCE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HMFK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HMFK" ON "OSE"."CONTRAT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--VHIT_FK
---------------------------
  CREATE INDEX "OSE"."VHIT_FK" ON "OSE"."VOLUME_HORAIRE" ("TYPE_INTERVENTION_ID");
---------------------------
--Nouveau INDEX
--CORPS_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."CORPS_SOURCE_FK" ON "OSE"."CORPS" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENTION_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENTION_HDFK" ON "OSE"."TYPE_INTERVENTION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MSD_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."MSD_INTERVENANT_FK" ON "OSE"."MODIFICATION_SERVICE_DU" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--INTERVENANTS_CIVILITES_FK
---------------------------
  CREATE INDEX "OSE"."INTERVENANTS_CIVILITES_FK" ON "OSE"."INTERVENANT" ("CIVILITE_ID");
---------------------------
--Nouveau INDEX
--TYPE_CONTRAT_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_CONTRAT_HCFK" ON "OSE"."TYPE_CONTRAT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HCFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HCFK" ON "OSE"."TYPE_MODULATEUR" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_CENTRE_COUT_FK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_CENTRE_COUT_FK" ON "OSE"."CENTRE_COUT" ("PARENT_ID");
---------------------------
--Nouveau INDEX
--VHENS_ELEMENT_PEDAGOGIQUE_FK
---------------------------
  CREATE INDEX "OSE"."VHENS_ELEMENT_PEDAGOGIQUE_FK" ON "OSE"."VOLUME_HORAIRE_ENS" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--PERSONNEL_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."PERSONNEL_STRUCTURE_FK" ON "OSE"."PERSONNEL" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HDFK
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HDFK" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--HSM_TYPE_VOLUME_HORAIRE_FK
---------------------------
  CREATE INDEX "OSE"."HSM_TYPE_VOLUME_HORAIRE_FK" ON "OSE"."HISTO_INTERVENANT_SERVICE" ("TYPE_VOLUME_HORAIRE_ID");
---------------------------
--Nouveau INDEX
--ETAT_VOLUME_HORAIRE_HMFK
---------------------------
  CREATE INDEX "OSE"."ETAT_VOLUME_HORAIRE_HMFK" ON "OSE"."ETAT_VOLUME_HORAIRE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_HDFK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_HDFK" ON "OSE"."INTERVENANT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--INDIC_DIFF_DOSSIER_INT_FK
---------------------------
  CREATE INDEX "OSE"."INDIC_DIFF_DOSSIER_INT_FK" ON "OSE"."INDIC_MODIF_DOSSIER" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_GRADE_FK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_GRADE_FK" ON "OSE"."INTERVENANT" ("GRADE_ID");
---------------------------
--Nouveau INDEX
--MV_INTERVENANT_NOM_PATRO_IDX
---------------------------
  CREATE INDEX "OSE"."MV_INTERVENANT_NOM_PATRO_IDX" ON "OSE"."MV_INTERVENANT" ("NOM_PATRONYMIQUE");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_GROUPE_FK
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_GROUPE_FK" ON "OSE"."TYPE_FORMATION" ("GROUPE_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_DISCIPLINE_FK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_DISCIPLINE_FK" ON "OSE"."INTERVENANT" ("DISCIPLINE_ID");
---------------------------
--Nouveau INDEX
--TYPE_PIECE_JOINTE_STATUT_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_PIECE_JOINTE_STATUT_HDFK" ON "OSE"."TYPE_PIECE_JOINTE_STATUT" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--PERIODE_HDFK
---------------------------
  CREATE INDEX "OSE"."PERIODE_HDFK" ON "OSE"."PERIODE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--DISCIPLINE_HCFK
---------------------------
  CREATE INDEX "OSE"."DISCIPLINE_HCFK" ON "OSE"."DISCIPLINE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_STRUCTURE_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_STRUCTURE_HMFK" ON "OSE"."TYPE_STRUCTURE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--DOTATION_HMFK
---------------------------
  CREATE INDEX "OSE"."DOTATION_HMFK" ON "OSE"."DOTATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_MODULATEUR_HDFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_MODULATEUR_HDFK" ON "OSE"."ELEMENT_MODULATEUR" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--SERVICE_REFERENTIEL_HDFK
---------------------------
  CREATE INDEX "OSE"."SERVICE_REFERENTIEL_HDFK" ON "OSE"."SERVICE_REFERENTIEL" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TIS_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."TIS_STRUCTURE_FK" ON "OSE"."TYPE_INTERVENTION_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--HSM_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."HSM_INTERVENANT_FK" ON "OSE"."HISTO_INTERVENANT_SERVICE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_ENS_HDFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_ENS_HDFK" ON "OSE"."VOLUME_HORAIRE_ENS" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--AFFECTATION_R_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_R_INTERVENANT_FK" ON "OSE"."AFFECTATION_RECHERCHE" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."STRUCTURE_STRUCTURE_FK" ON "OSE"."STRUCTURE" ("STRUCTURE_NIV2_ID");
---------------------------
--Nouveau INDEX
--VH_PERIODE_FK
---------------------------
  CREATE INDEX "OSE"."VH_PERIODE_FK" ON "OSE"."VOLUME_HORAIRE" ("PERIODE_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HMFK" ON "OSE"."TYPE_FORMATION" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_RESSOURCE_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_RESSOURCE_HMFK" ON "OSE"."TYPE_RESSOURCE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_NOM_USUEL_IDX
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_NOM_USUEL_IDX" ON "OSE"."INTERVENANT" ("NOM_USUEL");
---------------------------
--Nouveau INDEX
--AFFECTATION_ROLE_FK
---------------------------
  CREATE INDEX "OSE"."AFFECTATION_ROLE_FK" ON "OSE"."AFFECTATION" ("ROLE_ID");
---------------------------
--Nouveau INDEX
--ETABLISSEMENT_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."ETABLISSEMENT_SOURCE_FK" ON "OSE"."ETABLISSEMENT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--CC_ACTIVITE_HCFK
---------------------------
  CREATE INDEX "OSE"."CC_ACTIVITE_HCFK" ON "OSE"."CC_ACTIVITE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_INTERVENANT_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_INTERVENANT_HMFK" ON "OSE"."TYPE_INTERVENANT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--MV_INTERVENANT_PRENOM_IDX
---------------------------
  CREATE INDEX "OSE"."MV_INTERVENANT_PRENOM_IDX" ON "OSE"."MV_INTERVENANT" ("PRENOM");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_STRUCTURE_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_STRUCTURE_HDFK" ON "OSE"."TYPE_MODULATEUR_STRUCTURE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_FORMATION_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_FORMATION_HDFK" ON "OSE"."TYPE_FORMATION" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HMFK
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HMFK" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--AGREMENT_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."AGREMENT_INTERVENANT_FK" ON "OSE"."AGREMENT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HDFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HDFK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CHEMIN_PEDAGOGIQUE_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."CHEMIN_PEDAGOGIQUE_SOURCE_FK" ON "OSE"."CHEMIN_PEDAGOGIQUE" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--ADRESSE_STRUCTURE_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."ADRESSE_STRUCTURE_STRUCTURE_FK" ON "OSE"."ADRESSE_STRUCTURE" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_SOURCE_FK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_SOURCE_FK" ON "OSE"."CENTRE_COUT" ("SOURCE_ID");
---------------------------
--Nouveau INDEX
--MEP_FR_SERVICE_FK
---------------------------
  CREATE INDEX "OSE"."MEP_FR_SERVICE_FK" ON "OSE"."MISE_EN_PAIEMENT" ("FORMULE_RES_SERVICE_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_EP_HDFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_EP_HDFK" ON "OSE"."TYPE_MODULATEUR_EP" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--VOLUME_HORAIRE_REF_HDFK
---------------------------
  CREATE INDEX "OSE"."VOLUME_HORAIRE_REF_HDFK" ON "OSE"."VOLUME_HORAIRE_REF" ("HISTO_DESTRUCTEUR_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_STRUCTURE_FK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_STRUCTURE_FK" ON "OSE"."CONTRAT" ("STRUCTURE_ID");
---------------------------
--Nouveau INDEX
--STRUCTURES_STRUCTURES_FK
---------------------------
  CREATE INDEX "OSE"."STRUCTURES_STRUCTURES_FK" ON "OSE"."STRUCTURE" ("PARENTE_ID");
---------------------------
--Nouveau INDEX
--CONTRAT_HCFK
---------------------------
  CREATE INDEX "OSE"."CONTRAT_HCFK" ON "OSE"."CONTRAT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_HCFK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_HCFK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--CENTRE_COUT_EP_HMFK
---------------------------
  CREATE INDEX "OSE"."CENTRE_COUT_EP_HMFK" ON "OSE"."CENTRE_COUT_EP" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_FICHIER_PJFK
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_FICHIER_PJFK" ON "OSE"."PIECE_JOINTE_FICHIER" ("PIECE_JOINTE_ID");
---------------------------
--Nouveau INDEX
--ELEMENT_PEDAGOGIQUE_ETAPE_FK
---------------------------
  CREATE INDEX "OSE"."ELEMENT_PEDAGOGIQUE_ETAPE_FK" ON "OSE"."ELEMENT_PEDAGOGIQUE" ("ETAPE_ID");
---------------------------
--Nouveau INDEX
--GROUPE_ELEMENT_PEDAGOGIQUE_FK
---------------------------
  CREATE INDEX "OSE"."GROUPE_ELEMENT_PEDAGOGIQUE_FK" ON "OSE"."GROUPE" ("ELEMENT_PEDAGOGIQUE_ID");
---------------------------
--Nouveau INDEX
--DOSSIER_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."DOSSIER_INTERVENANT_FK" ON "OSE"."DOSSIER" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--AII_FK
---------------------------
  CREATE INDEX "OSE"."AII_FK" ON "OSE"."ADRESSE_INTERVENANT" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--MOTIF_NON_PAIEMENT_HCFK
---------------------------
  CREATE INDEX "OSE"."MOTIF_NON_PAIEMENT_HCFK" ON "OSE"."MOTIF_NON_PAIEMENT" ("HISTO_CREATEUR_ID");
---------------------------
--Nouveau INDEX
--INTERVENANT_ANNEE_FK
---------------------------
  CREATE INDEX "OSE"."INTERVENANT_ANNEE_FK" ON "OSE"."INTERVENANT" ("ANNEE_ID");
---------------------------
--Nouveau INDEX
--PIECE_JOINTE_HMFK
---------------------------
  CREATE INDEX "OSE"."PIECE_JOINTE_HMFK" ON "OSE"."PIECE_JOINTE" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Nouveau INDEX
--TYPE_MODULATEUR_HMFK
---------------------------
  CREATE INDEX "OSE"."TYPE_MODULATEUR_HMFK" ON "OSE"."TYPE_MODULATEUR" ("HISTO_MODIFICATEUR_ID");
---------------------------
--Modifié TRIGGER
--PJ_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PJ_INTERVENANT"
  AFTER UPDATE OF ID, DATE_NAISSANCE, STATUT_ID, HISTO_CREATION, HISTO_DESTRUCTION, PREMIER_RECRUTEMENT, DOSSIER_ID ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN
  
  OSE_PJ.add_intervenant_to_update( :NEW.id );
  
END;



DROP TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE";
DROP TRIGGER "OSE"."F_CONTRAT";

/
---------------------------
--Nouveau TRIGGER
--PFM_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."PFM_VOLUME_HORAIRE"
  BEFORE UPDATE ON "OSE"."VOLUME_HORAIRE"
  REFERENCING FOR EACH ROW
  BEGIN
    -- si on met en buffer le temps de contrôler le plafond
    IF :NEW.tem_plafond_fc_maj <> 1 THEN
      :NEW.buff_pfm_heures                := :OLD.heures;
      :NEW.buff_pfm_motif_non_paiement_id := :OLD.motif_non_paiement_id;
      :NEW.buff_pfm_histo_modification    := :OLD.histo_modification;
      :NEW.buff_pfm_histo_modificateur_id := :OLD.histo_modificateur_id;
    END IF;
END;
/
---------------------------
--Nouveau TRIGGER
--INTERVENANT_RECHERCHE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."INTERVENANT_RECHERCHE"
  BEFORE INSERT OR UPDATE OF NOM_USUEL, PRENOM, NOM_PATRONYMIQUE ON "OSE"."INTERVENANT"
  REFERENCING FOR EACH ROW
  BEGIN

  :NEW.critere_recherche := ose_divers.str_reduce( :NEW.nom_usuel || ' ' || :NEW.nom_patronymique || ' ' || :NEW.prenom );
  
END;
/
---------------------------
--Modifié TRIGGER
--F_VOLUME_HORAIRE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_VOLUME_HORAIRE"
  AFTER INSERT OR DELETE OR UPDATE OF TYPE_VOLUME_HORAIRE_ID, SERVICE_ID, PERIODE_ID, TYPE_INTERVENTION_ID, HEURES, MOTIF_NON_PAIEMENT_ID, CONTRAT_ID, HISTO_CREATION, HISTO_MODIFICATION, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE"
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
--F_INTERVENANT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_INTERVENANT"
  AFTER UPDATE OF ID, DATE_NAISSANCE, STATUT_ID, STRUCTURE_ID, HISTO_CREATION, HISTO_DESTRUCTION, PREMIER_RECRUTEMENT, ANNEE_ID, DOSSIER_ID ON "OSE"."INTERVENANT"
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
  AFTER DELETE OR UPDATE OF ID, STRUCTURE_ID, PERIODE_ID, TAUX_FI, TAUX_FC, TAUX_FA, TAUX_FOAD, FI, FC, FA, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
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
---------------------------
--Modifié TRIGGER
--F_CONTRAT
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_CONTRAT"
  AFTER DELETE OR UPDATE OF INTERVENANT_ID, STRUCTURE_ID, VALIDATION_ID, DATE_RETOUR_SIGNE, HISTO_CREATION, HISTO_DESTRUCTION ON "OSE"."CONTRAT"
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
--Modifié PACKAGE
--OSE_WORKFLOW
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_WORKFLOW" AS 

  PROCEDURE add_intervenant_to_update         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenant_etapes         (p_intervenant_id NUMERIC);
  PROCEDURE update_intervenants_etapes;
  PROCEDURE update_all_intervenants_etapes    (p_annee_id NUMERIC DEFAULT 2015);
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
--Modifié PACKAGE
--OSE_TEST
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_TEST" AS 

  -- SET SERVEROUTPUT ON

  PROCEDURE SHOW_SUCCES;

  PROCEDURE HIDE_SUCCES;

  PROCEDURE ECHO( MSG CLOB );

  PROCEDURE INIT;

  PROCEDURE SHOW_STATS;

  PROCEDURE DEBUT( TEST_NAME CLOB );
  
  PROCEDURE FIN;

  PROCEDURE ASSERT( condition BOOLEAN, MSG CLOB );
  
  PROCEDURE DELETE_TEST_DATA;

  FUNCTION GET_USER RETURN NUMERIC;

  FUNCTION GET_SOURCE RETURN NUMERIC;


  FUNCTION GET_CIVILITE( libelle_court VARCHAR2 DEFAULT NULL ) RETURN civilite%rowtype;

  FUNCTION GET_TYPE_INTERVENANT( code VARCHAR2 DEFAULT NULL ) RETURN type_intervenant%rowtype;

  FUNCTION GET_TYPE_INTERVENANT_BY_ID( id NUMERIC ) RETURN type_intervenant%rowtype;

  FUNCTION GET_STATUT_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN statut_intervenant%rowtype;
  
  FUNCTION GET_STATUT_INTERVENANT_BY_ID( id NUMERIC ) RETURN statut_intervenant%rowtype;

  FUNCTION GET_TYPE_STRUCTURE( code VARCHAR2 DEFAULT NULL ) RETURN type_structure%rowtype;

  FUNCTION GET_STRUCTURE( source_code VARCHAR2 DEFAULT NULL ) RETURN structure%rowtype;
  
  FUNCTION GET_STRUCTURE_BY_ID( id NUMERIC ) RETURN structure%rowtype;
  
  FUNCTION GET_STRUCTURE_ENS_BY_NIVEAU( niveau NUMERIC ) RETURN structure%rowtype;

  FUNCTION GET_STRUCTURE_UNIV RETURN "STRUCTURE"%rowtype;

  FUNCTION ADD_STRUCTURE(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    parente_id    NUMERIC,
    type_id       NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_INTERVENANT( source_code VARCHAR2 DEFAULT NULL ) RETURN intervenant%rowtype;

  FUNCTION GET_INTERVENANT_BY_ID( id NUMERIC DEFAULT NULL ) RETURN intervenant%rowtype;

  FUNCTION GET_INTERVENANT_BY_STATUT( statut_id NUMERIC ) RETURN intervenant%rowtype;

  FUNCTION ADD_INTERVENANT(
    civilite_id     NUMERIC,
    nom_usuel       VARCHAR2,
    prenom          VARCHAR2,
    date_naissance  DATE,
    email           VARCHAR2,
    statut_id       NUMERIC,
    structure_id    NUMERIC,
    source_code     VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_GROUPE_TYPE_FORMATION( source_code VARCHAR2 DEFAULT NULL ) RETURN groupe_type_formation%rowtype;
  
  FUNCTION ADD_GROUPE_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    source_code   VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_TYPE_FORMATION( source_code VARCHAR2 ) RETURN type_formation%rowtype;
  
  FUNCTION ADD_TYPE_FORMATION(
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    groupe_id     NUMERIC,
    source_code   VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_ETAPE( source_code VARCHAR2 DEFAULT NULL ) RETURN etape%rowtype;
  
  FUNCTION ADD_ETAPE(
    libelle           VARCHAR2,
    type_formation_id NUMERIC,
    niveau            NUMERIC,
    structure_id      NUMERIC,
    source_code       VARCHAR2
  ) RETURN NUMERIC;

  FUNCTION GET_PERIODE( code VARCHAR2 DEFAULT NULL ) RETURN periode%rowtype;

  FUNCTION GET_ELEMENT_PEDAGOGIQUE( source_code VARCHAR2 DEFAULT NULL ) RETURN element_pedagogique%rowtype;
  
  FUNCTION GET_ELEMENT_PEDAGOGIQUE_BY_ID( ID NUMERIC ) RETURN element_pedagogique%rowtype;
  
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
  ) RETURN NUMERIC;

  FUNCTION GET_TYPE_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN type_modulateur%rowtype;
  
  FUNCTION ADD_TYPE_MODULATEUR(
    code        VARCHAR2,
    libelle     VARCHAR2,
    publique    NUMERIC,
    obligatoire NUMERIC
  ) RETURN NUMERIC;

  FUNCTION GET_MODULATEUR( code VARCHAR2 DEFAULT NULL ) RETURN modulateur%rowtype;
  
  FUNCTION ADD_MODULATEUR(
    code                      VARCHAR2,
    libelle                   VARCHAR2,
    type_modulateur_id        NUMERIC,
    ponderation_service_du    FLOAT,
    ponderation_service_compl FLOAT
  ) RETURN NUMERIC;

  FUNCTION ADD_ELEMENT_MODULATEUR(
    element_id    NUMERIC,
    modulateur_id NUMERIC
  ) RETURN NUMERIC;

  FUNCTION GET_FONCTION_REFERENTIEL( code VARCHAR2 DEFAULT NULL ) RETURN fonction_referentiel%rowtype;
  
  FUNCTION ADD_FONCTION_REFERENTIEL(
    code          VARCHAR2,
    libelle_long  VARCHAR2,
    libelle_court VARCHAR2,
    plafond       FLOAT
  ) RETURN NUMERIC;
  
  FUNCTION ADD_SERVICE_REFERENTIEL(
    fonction_id     NUMERIC,
    intervenant_id  NUMERIC,
    structure_id    NUMERIC
  ) RETURN NUMERIC;
  
  FUNCTION ADD_MODIFICATION_SERVICE_DU(
    intervenant_id  NUMERIC,
    heures          FLOAT,
    motif_id        NUMERIC,
    commentaires    CLOB DEFAULT NULL
  ) RETURN NUMERIC;

  FUNCTION GET_MOTIF_MODIFICATION_SERVICE( code VARCHAR2 DEFAULT NULL, multiplicateur FLOAT DEFAULT NULL ) RETURN motif_modification_service%rowtype;

  FUNCTION GET_ETABLISSEMENT( source_code VARCHAR2 DEFAULT NULL ) RETURN etablissement%rowtype;
  
  FUNCTION GET_SERVICE_BY_ID( id NUMERIC ) RETURN service%rowtype;

  FUNCTION ADD_SERVICE(
    intervenant_id          NUMERIC,
    element_pedagogique_id  NUMERIC,
    etablissement_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC;

  FUNCTION GET_ETAT_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN etat_volume_horaire%rowtype;
  
  FUNCTION GET_TYPE_VOLUME_HORAIRE( code VARCHAR2 DEFAULT NULL ) RETURN type_volume_horaire%rowtype;
  
  FUNCTION GET_TYPE_INTERVENTION( code VARCHAR2 DEFAULT NULL ) RETURN type_intervention%rowtype;

  FUNCTION GET_TYPE_INTERVENTION_BY_ID( id NUMERIC ) RETURN type_intervention%rowtype;

  FUNCTION GET_TYPE_INTERVENTION_BY_ELEMT( ELEMENT_ID NUMERIC ) RETURN type_intervention%rowtype;

  FUNCTION GET_MOTIF_NON_PAIEMENT( code VARCHAR2 DEFAULT NULL ) RETURN motif_non_paiement%rowtype;
  
  FUNCTION GET_VOLUME_HORAIRE( id NUMERIC DEFAULT NULL ) RETURN volume_horaire%rowtype;
  
  FUNCTION ADD_VOLUME_HORAIRE(
    type_volume_horaire_id  NUMERIC,
    service_id              NUMERIC,
    periode_id              NUMERIC,
    type_intervention_id    NUMERIC,
    heures                  FLOAT,
    motif_non_paiement_id   NUMERIC DEFAULT NULL
  ) RETURN NUMERIC;

  FUNCTION ADD_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL
  ) RETURN NUMERIC;

  PROCEDURE DEL_VALIDATION_VOLUME_HORAIRE(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL,
    validation_id     NUMERIC DEFAULT NULL
  );

  FUNCTION GET_CONTRAT_BY_ID( ID NUMERIC ) RETURN contrat%rowtype;

  FUNCTION ADD_CONTRAT(
    structure_id      NUMERIC DEFAULT NULL,
    intervenant_id    NUMERIC DEFAULT NULL,
    volume_horaire_id NUMERIC DEFAULT NULL,
    service_id        NUMERIC DEFAULT NULL    
  ) RETURN NUMERIC;
  
  FUNCTION SIGNATURE_CONTRAT( contrat_id NUMERIC ) RETURN NUMERIC;
  
  FUNCTION ADD_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC;

  FUNCTION DEL_CONTRAT_VALIDATION( contrat_id NUMERIC ) RETURN NUMERIC;

  FUNCTION GET_TYPE_VALIDATION( code VARCHAR2 DEFAULT NULL ) RETURN type_validation%rowtype;
END OSE_TEST;
/
---------------------------
--Nouveau PACKAGE
--OSE_SERVICE
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_SERVICE" AS 

  PROCEDURE controle_plafond_fc_maj( intervenant_id NUMERIC, type_volume_horaire_id NUMERIC );

END OSE_SERVICE;
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

  PROCEDURE MAJ_TYPE_MODULATEUR_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_INTERVENTION_EP(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PERSONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_PAYS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_GROUPE_TYPE_FORMATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_GRADE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETAPE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ETABLISSEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_TAUX_REGIMES(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ELEMENT_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_EFFECTIFS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DOMAINE_FONCTIONNEL(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_DEPARTEMENT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CORPS(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CHEMIN_PEDAGOGIQUE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_CENTRE_COUT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION_RECHERCHE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_AFFECTATION(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_STRUCTURE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');
  PROCEDURE MAJ_ADRESSE_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '');

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
    heures_decharge                FLOAT DEFAULT 0,
    heures_service_statutaire      FLOAT DEFAULT 0,
    heures_service_modifie         FLOAT DEFAULT 0,
    depassement_service_du_sans_hc FLOAT DEFAULT 0
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
--Nouveau PACKAGE
--OSE_EVENT
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_EVENT" AS 

  PROCEDURE on_formule_calculee( intervenant_id numeric );

END OSE_EVENT;
/
---------------------------
--Modifié PACKAGE
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_DIVERS" AS 

  FUNCTION GET_MSG( code VARCHAR2 ) RETURN CLOB;

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  FUNCTION intervenant_est_permanent( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_est_non_autorise( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  FUNCTION intervenant_peut_saisir_serv( INTERVENANT_ID NUMERIC ) RETURN NUMERIC;

  PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE );

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

  FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2;
  
  FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
  RETURN VARCHAR2;
  
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
      WHEN ose_formule.d_intervenant.depassement_service_du_sans_hc = 1 -- HC traitées comme du service
        OR ose_formule.d_intervenant.heures_decharge < 0 -- s'il y a une décharge => aucune HC     
        
      THEN 9999 
      ELSE ose_formule.d_intervenant.heures_service_statutaire + ose_formule.d_intervenant.heures_service_modifie
    END;
ose_test.echo('D = ' || ose_formule.d_resultat.service_du );
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
      WHEN ose_formule.d_intervenant.depassement_service_du_sans_hc = 1 OR ose_formule.d_intervenant.heures_decharge < 0
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
  PROCEDURE Update_All_Intervenants_Etapes (p_annee_id NUMERIC DEFAULT 2015)
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
    exist_etapes NUMERIC;
  BEGIN
    --
    -- Année concernée.
    --
    select i.annee_id into v_annee_id from intervenant i where i.id = p_intervenant_id;
    
    --
    -- Création si besoin des étapes pour l'année concernée.
    --
    select count(*) into exist_etapes from wf_etape where annee_id = v_annee_id;
    if exist_etapes = 0 then
      insert into WF_ETAPE (ID,CODE,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURE_DEPENDANT,ORDRE,STRUCTURES_IDS_FUNC,ANNEE_ID)
        select wf_etape_id_seq.nextval, CODE,LIBELLE,PERTIN_FUNC,FRANCH_FUNC,STEP_CLASS,VISIBLE,STRUCTURE_DEPENDANT,ORDRE,STRUCTURES_IDS_FUNC, v_annee_id from WF_ETAPE;
    end if;
    
    --
    -- RAZ progression.
    --
    DELETE FROM wf_intervenant_etape ie WHERE ie.intervenant_id = p_intervenant_id;
    
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
    SELECT CASE WHEN i.dossier_id IS NULL THEN 0 ELSE 1 END INTO res FROM intervenant i where i.id = p_intervenant_id;
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
    -- teste le statut de l'intervenant issu de la table INTERVENANT
    SELECT si.peut_saisir_service INTO res 
    FROM statut_intervenant si 
    JOIN intervenant i ON i.statut_id = si.id 
    WHERE i.id = p_intervenant_id;
    
    if res > 0 then
      RETURN res;
    end if;
    
    -- teste sinon le statut saisi dans l'éventuel dossier
    SELECT
      count(*) INTO res 
    FROM
      intervenant i
      JOIN dossier d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
      JOIN statut_intervenant si on si.id = d.statut_id and si.peut_saisir_service = 1
    WHERE
      i.id = p_intervenant_id
    ;
    
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
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction) /*AND s.annee_id = ose_parametre.get_annee()*/;
    ELSE
      SELECT count(*) INTO res FROM service s 
      JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id 
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
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
      --JOIN etape e ON e.id = ep.etape_id AND 1 = ose_divers.comprise_entre(e.histo_creation, e.histo_destruction)
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
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    ELSE
      SELECT count(*) INTO res FROM service_referentiel s 
      JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation, vh.histo_destruction)
      JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = p_type_volume_horaire_code
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction)
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
      WHERE s.intervenant_id = p_intervenant_id AND 1 = ose_divers.comprise_entre(s.histo_creation, s.histo_destruction);
    ref_rec ref_cur%rowtype;
    res NUMERIC := 0;
    nb numeric;
  BEGIN
    -- si aucun référentiel, la validation doit être considérée comme faite
    if ose_workflow.possede_referentiel_tvh(p_type_volume_horaire_code, p_intervenant_id, p_structure_id) < 1 then
      return 1;
    end if;
  
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
    SELECT
      count(*) into res
    FROM
      intervenant i
      JOIN dossier d on d.id = i.dossier_id  and 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
      JOIN PIECE_JOINTE pj ON pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
      JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id     
    WHERE
      i.id = p_intervenant_id
    ;
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
          SELECT
            I.ID INTERVENANT_ID, 
            I.SOURCE_CODE, 
            count( distinct pj.id) NB
          FROM 
            INTERVENANT i
            JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
            JOIN piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant, VALIDEES OU NON
          SELECT
            I.ID INTERVENANT_ID,
            I.SOURCE_CODE,
            count( distinct pj.ID) NB
          FROM 
            INTERVENANT i
            JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
            JOIN PIECE_JOINTE pj ON pj.DOSSIER_ID = d.ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
            JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
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
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(distinct pj.id) NB
          FROM
            INTERVENANT I
            JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
            JOIN piece_jointe pj on pj.dossier_id = d.id AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          WHERE 
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
          GROUP BY I.ID, I.SOURCE_CODE
      ), 
      FOURNI_OBLIGATOIRE AS (
          -- nombres de pj OBLIGATOIRES FOURNIES AVEC FICHIER par chaque intervenant et VALIDEES 
          SELECT I.ID INTERVENANT_ID, I.SOURCE_CODE, count(distinct pj.ID) NB
          FROM INTERVENANT I
          INNER JOIN DOSSIER d ON d.id = i.dossier_id AND 1 = ose_divers.comprise_entre(d.histo_creation, d.histo_destruction)
          INNER JOIN PIECE_JOINTE pj ON d.ID = pj.DOSSIER_ID AND 1 = ose_divers.comprise_entre(pj.histo_creation, pj.histo_destruction)
          INNER JOIN PIECE_JOINTE_FICHIER pjf ON pjf.piece_jointe_id = pj.id -- AVEC FICHIER
          WHERE
            1 = ose_divers.comprise_entre(i.histo_creation, i.histo_destruction)
            AND pj.OBLIGATOIRE = 1
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
    WHERE tas.PREMIER_RECRUTEMENT = NVL(i.PREMIER_RECRUTEMENT,1) AND tas.OBLIGATOIRE = 1 
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
    WHERE tas.PREMIER_RECRUTEMENT = NVL(i.PREMIER_RECRUTEMENT,1) AND tas.OBLIGATOIRE = 1 
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
    -- si l'intervenant possède déjà des demande de MEP, il peut demander des MEP
    if possede_demande_mep(p_intervenant_id, p_structure_id) = 1 then
      return 1;
    end if;
  
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
      select count(*) into res from v_total_demande_mep_structure where intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from v_total_demande_mep_structure where intervenant_id = p_intervenant_id and structure_id = p_structure_id;
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
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where periode_paiement_id is not null and intervenant_id = p_intervenant_id;
    ELSE
      select count(*) into res from V_MEP_INTERVENANT_STRUCTURE where periode_paiement_id is not null and intervenant_id = p_intervenant_id and structure_id = p_structure_id;
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
  BEGIN
    entity_id := INTERVENANT_ID_SEQ.NEXTVAL;
    statut := GET_STATUT_INTERVENANT_BY_ID( statut_id );
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
      statut_id,
      structure_id,
      GET_SOURCE,
      source_code,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'intervenant', entity_id);
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
    modulateur_id NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := ELEMENT_MODULATEUR_ID_SEQ.NEXTVAL;
    INSERT INTO ELEMENT_MODULATEUR (
      ID,
      ELEMENT_ID,
      MODULATEUR_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      element_id,
      modulateur_id,
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
    structure_id    NUMERIC
  ) RETURN NUMERIC IS
    entity_id NUMERIC;
  BEGIN
    entity_id := SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL;
    INSERT INTO SERVICE_REFERENTIEL (
      ID,
      FONCTION_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      fonction_id,
      intervenant_id,
      structure_id,
      GET_USER,
      GET_USER
    );
    INSERT INTO TEST_BUFFER(ID, TABLE_NAME, DATA_ID) VALUES(TEST_BUFFER_ID_SEQ.NEXTVAL, 'service_referentiel', entity_id);
    RETURN entity_id;
  END;
  
  FUNCTION ADD_MODIFICATION_SERVICE_DU(
    intervenant_id  NUMERIC,    
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
      HEURES,
      MOTIF_ID,
      COMMENTAIRES,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
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
      ETABLISSEMENT_ID,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATEUR_ID
    )VALUES(
      entity_id,
      intervenant_id,
      element_pedagogique_id,
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
--OSE_SERVICE
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_SERVICE" AS

  /**
   * Retourne true si le test passe, false sinon
   */
  FUNCTION test_plafond_fc_maj( intervenant_id NUMERIC, type_volume_horaire_id NUMERIC ) RETURN BOOLEAN IS
    heures_restantes FLOAT;
  BEGIN
    BEGIN
      SELECT
        pla.plafond - pla.heures INTO heures_restantes 
      FROM
        v_plafond_fc_maj pla
        JOIN etat_volume_horaire evh ON evh.code = 'saisi' AND evh.id = pla.etat_volume_horaire_id
      WHERE
            intervenant_id         = test_plafond_fc_maj.intervenant_id
        AND type_volume_horaire_id = test_plafond_fc_maj.type_volume_horaire_id;
        
      RETURN heures_restantes >= 0;
    EXCEPTION
      WHEN NO_DATA_FOUND THEN RETURN TRUE;
    END;
  END;



  /**
   * Contrôle du plafond FC D714-60
   */
  PROCEDURE controle_plafond_fc_maj( intervenant_id NUMERIC, type_volume_horaire_id NUMERIC ) IS
  BEGIN
    IF test_plafond_fc_maj(intervenant_id, type_volume_horaire_id) THEN
      
      /* On dit que le contrôle a été effectué !! */
      UPDATE volume_horaire 
      SET tem_plafond_fc_maj = 1 
      WHERE 
        type_volume_horaire_id = controle_plafond_fc_maj.type_volume_horaire_id
        AND service_id IN (SELECT s.id FROM service s WHERE s.intervenant_id = controle_plafond_fc_maj.intervenant_id);
      
    ELSE
      
      /* Suppression des volumes horaires induement créés */
      DELETE FROM volume_horaire 
      WHERE
        tem_plafond_fc_maj <> 1
        AND buff_pfm_heures IS NULL -- on ne détruit que les nouvellement créés
        AND type_volume_horaire_id = controle_plafond_fc_maj.type_volume_horaire_id
        AND service_id IN (SELECT ID FROM service WHERE intervenant_id = controle_plafond_fc_maj.intervenant_id);

      /* remise à l'état antérieur des volumes horaires induement modifiés */
      UPDATE volume_horaire SET
        heures                         = buff_pfm_heures,
        motif_non_paiement_id          = buff_pfm_motif_non_paiement_id,
        histo_modification             = buff_pfm_histo_modification,
        histo_modificateur_id          = buff_pfm_histo_modificateur_id,
        buff_pfm_heures                = NULL,
        buff_pfm_motif_non_paiement_id = NULL,
        buff_pfm_histo_modification    = NULL,
        buff_pfm_histo_modificateur_id = NULL,
        tem_plafond_fc_maj             = 1
      WHERE
        tem_plafond_fc_maj <> 1
        AND buff_pfm_heures IS NOT NULL -- on ne met à jour que les anciennes données
        AND type_volume_horaire_id = controle_plafond_fc_maj.type_volume_horaire_id
        AND service_id IN (SELECT ID FROM service WHERE intervenant_id = controle_plafond_fc_maj.intervenant_id);
        
      /* Purge de la liste des services devenus inutiles (le cas échéant) */
      DELETE FROM service WHERE
        intervenant_id = controle_plafond_fc_maj.intervenant_id
        AND NOT EXISTS(SELECT * FROM volume_horaire WHERE service_id = service.id);
    
      COMMIT; 
      /* Renvoi de l'exception */
      raise_application_error(-20101, ose_divers.get_msg('service-pladond-fc-maj-depasse'));

    END IF;
  END;

END OSE_SERVICE;
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
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR ID IN (SELECT ID FROM "INTERVENANT"))'
        
      WHEN 'INTERVENANT_PERMANENT' THEN
        'WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR ID IN (SELECT ID FROM "INTERVENANT"))'
        
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
    REFRESH_MV('MV_GRADE');
    
    REFRESH_MV('MV_INTERVENANT');
    REFRESH_MV('MV_AFFECTATION_RECHERCHE');
    REFRESH_MV('MV_ADRESSE_INTERVENANT');
    REFRESH_MV('MV_INTERVENANT_RECHERCHE'); -- pour la recherche d'intervenants
    
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
    MAJ_GRADE();

    MAJ_INTERVENANT();
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



  PROCEDURE MAJ_INTERVENANT(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_INTERVENANT%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_INTERVENANT.* FROM V_DIFF_INTERVENANT ' || get_sql_criterion('INTERVENANT',SQL_CRITERION);
    dbms_output.put_line(sql_query);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.INTERVENANT
              ( id, ANNEE_ID,BIC,CIVILITE_ID,DATE_NAISSANCE,DEP_NAISSANCE_CODE_INSEE,DEP_NAISSANCE_LIBELLE,DISCIPLINE_ID,EMAIL,GRADE_ID,IBAN,NOM_PATRONYMIQUE,NOM_USUEL,NUMERO_INSEE,NUMERO_INSEE_CLE,NUMERO_INSEE_PROVISOIRE,PAYS_NAISSANCE_CODE_INSEE,PAYS_NAISSANCE_LIBELLE,PAYS_NATIONALITE_CODE_INSEE,PAYS_NATIONALITE_LIBELLE,PRENOM,STATUT_ID,STRUCTURE_ID,TEL_MOBILE,TEL_PRO,VILLE_NAISSANCE_CODE_INSEE,VILLE_NAISSANCE_LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,INTERVENANT_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.BIC,diff_row.CIVILITE_ID,diff_row.DATE_NAISSANCE,diff_row.DEP_NAISSANCE_CODE_INSEE,diff_row.DEP_NAISSANCE_LIBELLE,diff_row.DISCIPLINE_ID,diff_row.EMAIL,diff_row.GRADE_ID,diff_row.IBAN,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.NUMERO_INSEE,diff_row.NUMERO_INSEE_CLE,diff_row.NUMERO_INSEE_PROVISOIRE,diff_row.PAYS_NAISSANCE_CODE_INSEE,diff_row.PAYS_NAISSANCE_LIBELLE,diff_row.PAYS_NATIONALITE_CODE_INSEE,diff_row.PAYS_NATIONALITE_LIBELLE,diff_row.PRENOM,diff_row.STATUT_ID,diff_row.STRUCTURE_ID,diff_row.TEL_MOBILE,diff_row.TEL_PRO,diff_row.VILLE_NAISSANCE_CODE_INSEE,diff_row.VILLE_NAISSANCE_LIBELLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_CODE_INSEE = diff_row.DEP_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DEP_NAISSANCE_LIBELLE = diff_row.DEP_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GRADE_ID = 1 AND IN_COLUMN_LIST('GRADE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET GRADE_ID = diff_row.GRADE_ID WHERE ID = diff_row.id; END IF;
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
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GRADE_ID = 1 AND IN_COLUMN_LIST('GRADE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.INTERVENANT SET GRADE_ID = diff_row.GRADE_ID WHERE ID = diff_row.id; END IF;
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



  PROCEDURE MAJ_GRADE(SQL_CRITERION CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '') IS
    TYPE r_cursor IS REF CURSOR;
    sql_query CLOB;
    diff_cur r_cursor;
    diff_row V_DIFF_GRADE%ROWTYPE;
  BEGIN
    sql_query := 'SELECT V_DIFF_GRADE.* FROM V_DIFF_GRADE ' || get_sql_criterion('GRADE',SQL_CRITERION);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO OSE.GRADE
              ( id, CORPS_ID,ECHELLE,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,GRADE_ID_SEQ.NEXTVAL), diff_row.CORPS_ID,diff_row.ECHELLE,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ECHELLE = 1 AND IN_COLUMN_LIST('ECHELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET ECHELLE = diff_row.ECHELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE OSE.GRADE SET histo_destruction = SYSDATE, histo_destructeur_id = get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ECHELLE = 1 AND IN_COLUMN_LIST('ECHELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET ECHELLE = diff_row.ECHELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.GRADE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE OSE.GRADE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        OSE_IMPORT.SYNC_LOG( SQLERRM, 'GRADE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END MAJ_GRADE;



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
              ( id, ANNEE_ID,DISCIPLINE_ID,ETAPE_ID,FA,FC,FI,LIBELLE,PERIODE_ID,STRUCTURE_ID,TAUX_FA,TAUX_FC,TAUX_FI,TAUX_FOAD, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.DISCIPLINE_ID,diff_row.ETAPE_ID,diff_row.FA,diff_row.FC,diff_row.FI,diff_row.LIBELLE,diff_row.PERIODE_ID,diff_row.STRUCTURE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI,diff_row.TAUX_FOAD, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
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
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ELEMENT_PEDAGOGIQUE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
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
              ( id, CODE_POSTAL,INTERVENANT_ID,LOCALITE,MENTION_COMPLEMENTAIRE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,TEL_DOMICILE,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ADRESSE_INTERVENANT_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.INTERVENANT_ID,diff_row.LOCALITE,diff_row.MENTION_COMPLEMENTAIRE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.TEL_DOMICILE,diff_row.VILLE, diff_row.source_id, diff_row.source_code, get_current_user, get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',IGNORE_UPD_COLS) = 0) THEN UPDATE OSE.ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
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
      NVL( SUM(heures), 0),
      NVL( SUM(heures_decharge), 0)
    INTO
      d_intervenant.heures_service_modifie,
      d_intervenant.heures_decharge
    FROM
      v_formule_service_modifie fsm
    WHERE
      fsm.intervenant_id = POPULATE_INTERVENANT.INTERVENANT_ID;
  
  EXCEPTION WHEN NO_DATA_FOUND THEN
    d_intervenant.structure_id := null;
    d_intervenant.heures_service_statutaire := 0;
    d_intervenant.depassement_service_du_sans_hc := 0;
    d_intervenant.heures_service_modifie := 0;
    d_intervenant.heures_decharge := 0;
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


  PROCEDURE POPULATE_TYPE_ETAT_VH( d_volume_horaire t_lst_volume_horaire, d_volume_horaire_ref t_lst_volume_horaire_ref, d_type_etat_vh OUT t_lst_type_etat_vh ) IS
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
    
    id := d_volume_horaire_ref.FIRST;
    LOOP EXIT WHEN id IS NULL;
      IF NOT ordres_found.EXISTS(d_volume_horaire_ref(id).type_volume_horaire_id) THEN
        ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) := d_volume_horaire_ref(id).etat_volume_horaire_ordre;
      ELSIF ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) < d_volume_horaire_ref(id).etat_volume_horaire_ordre THEN
        ordres_found( d_volume_horaire_ref(id).type_volume_horaire_id ) := d_volume_horaire_ref(id).etat_volume_horaire_ordre;
      END IF;
      id := d_volume_horaire_ref.NEXT(id);
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
      POPULATE_TYPE_ETAT_VH       ( d_all_volume_horaire, d_all_volume_horaire_ref, d_type_etat_vh );
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

    OSE_EVENT.on_formule_calculee( intervenant_id );
  END;

END OSE_FORMULE;
/
---------------------------
--Nouveau PACKAGE BODY
--OSE_EVENT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_EVENT" AS

  PROCEDURE on_formule_calculee( intervenant_id NUMERIC ) IS
  BEGIN
    OSE_PJ.UPDATE_INTERVENANT( INTERVENANT_ID ); -- nécessité de MAJ des PJ!!
  END;

END OSE_EVENT;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_DIVERS" AS

/**
 * Retourne un texte de message à partir de son code
 */
FUNCTION GET_MSG( code VARCHAR2 ) RETURN CLOB IS
  msg CLOB;
BEGIN
  SELECT texte into msg FROM message WHERE code = GET_MSG.code;
  RETURN msg;
END;



FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC IS
  statut statut_intervenant%rowtype;
  itype  type_intervenant%rowtype;
  res NUMERIC;
BEGIN
  res := 1;
  SELECT si.* INTO statut FROM statut_intervenant si JOIN intervenant i ON i.statut_id = si.id WHERE i.id = intervenant_id;
  SELECT ti.* INTO itype  FROM type_intervenant ti WHERE ti.id = statut.type_intervenant_id;
  
  /* DEPRECATED */
  IF 'saisie_service' = privilege_name THEN
    res := statut.peut_saisir_service;
    RETURN res;
  ELSIF 'saisie_service_exterieur' = privilege_name THEN
    --IF INTERVENANT_HAS_PRIVILEGE( intervenant_id, 'saisie_service' ) = 0 OR itype.code = 'E' THEN -- cascade
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
    RETURN res;
  ELSIF 'saisie_service_referentiel' = privilege_name THEN
    IF itype.code = 'E' THEN
      res := 0;
    END IF;
    RETURN res;
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
  SELECT
    COUNT(*) INTO resultat
  FROM
    intervenant i
    JOIN statut_intervenant si ON si.id = i.statut_id
    JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  WHERE 
    i.id = INTERVENANT_ID
    AND ti.code = 'P';
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

PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE ) AS
BEGIN
    MERGE INTO histo_intervenant_service his USING dual ON (
    
          his.INTERVENANT_ID                = intervenant_horodatage_service.INTERVENANT_ID
      AND NVL(his.TYPE_VOLUME_HORAIRE_ID,0) = NVL(intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,0)
      AND his.REFERENTIEL                   = intervenant_horodatage_service.REFERENTIEL

    ) WHEN MATCHED THEN UPDATE SET

      HISTO_MODIFICATEUR_ID = intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
      HISTO_MODIFICATION = intervenant_horodatage_service.HISTO_MODIFICATION

    WHEN NOT MATCHED THEN INSERT (

      ID,
      INTERVENANT_ID,
      TYPE_VOLUME_HORAIRE_ID,
      REFERENTIEL,
      HISTO_MODIFICATEUR_ID,
      HISTO_MODIFICATION
    ) VALUES (
      HISTO_INTERVENANT_SERVI_ID_SEQ.NEXTVAL,
      intervenant_horodatage_service.INTERVENANT_ID,
      intervenant_horodatage_service.TYPE_VOLUME_HORAIRE_ID,
      intervenant_horodatage_service.REFERENTIEL,
      intervenant_horodatage_service.HISTO_MODIFICATEUR_ID,
      intervenant_horodatage_service.HISTO_MODIFICATION

    );
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

FUNCTION FORMATTED_RIB (bic VARCHAR2, iban VARCHAR2) RETURN VARCHAR2 IS
BEGIN
  if bic is null and iban is null then
    return null;
  end if;
  RETURN regexp_replace(bic, '[[:space:]]+', '') || '-' || regexp_replace(iban, '[[:space:]]+', '');
END;

FUNCTION FORMATTED_ADRESSE(
    no_voie                VARCHAR2,
    nom_voie               VARCHAR2,
    batiment               VARCHAR2,
    mention_complementaire VARCHAR2,
    localite               VARCHAR2,
    code_postal            VARCHAR2,
    ville                  VARCHAR2,
    pays_libelle           VARCHAR2)
  RETURN VARCHAR2
IS
BEGIN
  return
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' FROM REPLACE(', ' || NVL(no_voie,'#') || ', ' || NVL(nom_voie,'#') || ', ' || NVL(batiment,'#') || ', ' || NVL(mention_complementaire,'#'), ', #', ''))) ||
    -- saut de ligne complet
    chr(13) || chr(10) ||
    -- concaténation des éléments non null séparés par ', '
    trim(trim(',' FROM REPLACE(', ' || NVL(localite,'#') || ', ' || NVL(code_postal,'#') || ', ' || NVL(ville,'#') || ', ' || NVL(pays_libelle,'#'), ', #', '')));
END;

END OSE_DIVERS;
/


DROP TRIGGER "OSE"."INTERVENANT_STATUT_CK";


-- ********************************************************************* --
-- *          à faire APRÈS avoir mis à jour le code source            * --
-- ********************************************************************* --

ALTER INDEX ADRESSE_INTERVENANT__UN RENAME TO AII_FK;
ALTER INDEX "indic_diff_dossier_PK" RENAME TO INDIC_DIFF_DOSSIER_PK;

UPDATE motif_modification_service SET decharge = 1 WHERE code = 'DECHARGE';
UPDATE role SET peut_changer_structure = 1 WHERE code = 'administrateur';

INSERT
INTO
  MESSAGE
  (
    ID,
    CODE,
    TEXTE
  )
  VALUES
  (
    1,
    'service-pladond-fc-maj-depasse',
    'Il est impossible d''ajouter ces heures d''enseignement : le plafond du montant de la rémunération D714-60 serait dépassé.'
  );

/
BEGIN
  DBMS_SCHEDULER.enable(name=>'"OSE"."OSE_SRC_SYNC"');
  ose_formule.calculer_tout;
  ose_workflow.update_all_intervenants_etapes;
END;
/