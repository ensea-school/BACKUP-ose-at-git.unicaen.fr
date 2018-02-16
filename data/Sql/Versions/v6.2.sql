---------------------------
--Modifié TABLE
--VOLUME_HORAIRE
---------------------------
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("BUFF_PFM_HEURES");
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("BUFF_PFM_HISTO_MODIFICATEUR_ID");
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("BUFF_PFM_HISTO_MODIFICATION");
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("BUFF_PFM_MOTIF_NON_PAIEMENT_ID");
ALTER TABLE "OSE"."VOLUME_HORAIRE" DROP ("TEM_PLAFOND_FC_MAJ");

---------------------------
--Modifié TABLE
--UTILISATEUR
---------------------------
ALTER TABLE "OSE"."UTILISATEUR" ADD ("CODE" VARCHAR2(60 CHAR));

---------------------------
--Modifié TABLE
--TYPE_VOLUME_HORAIRE
---------------------------
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP ("HISTO_CREATEUR_ID");
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP ("HISTO_CREATION");
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP ("HISTO_DESTRUCTEUR_ID");
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP ("HISTO_DESTRUCTION");
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP ("HISTO_MODIFICATEUR_ID");
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP ("HISTO_MODIFICATION");
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP CONSTRAINT "TYPE_VOLUME_HORAIRE_HCFK";
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP CONSTRAINT "TYPE_VOLUME_HORAIRE_HDFK";
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" DROP CONSTRAINT "TYPE_VOLUME_HORAIRE_HMFK";
ALTER TABLE "OSE"."TYPE_VOLUME_HORAIRE" ADD CONSTRAINT "TYPE_VOLUME_HORAIRE__UN" UNIQUE ("CODE") ENABLE;

---------------------------
--Modifié TABLE
--TYPE_VALIDATION
---------------------------
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP ("HISTO_CREATEUR_ID");
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP ("HISTO_CREATION");
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP ("HISTO_DESTRUCTEUR_ID");
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP ("HISTO_DESTRUCTION");
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP ("HISTO_MODIFICATEUR_ID");
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP ("HISTO_MODIFICATION");
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP CONSTRAINT "TYPE_VALIDATION_HCFK";
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP CONSTRAINT "TYPE_VALIDATION_HDFK";
ALTER TABLE "OSE"."TYPE_VALIDATION" DROP CONSTRAINT "TYPE_VALIDATION_HMFK";

---------------------------
--Modifié TABLE
--STRUCTURE
---------------------------
ALTER TABLE "OSE"."STRUCTURE" DROP ("ETABLISSEMENT_ID");
ALTER TABLE "OSE"."STRUCTURE" DROP ("NIVEAU");
ALTER TABLE "OSE"."STRUCTURE" DROP ("PARENTE_ID");
ALTER TABLE "OSE"."STRUCTURE" DROP ("STRUCTURE_NIV2_ID");
ALTER TABLE "OSE"."STRUCTURE" DROP ("TYPE_ID");
ALTER TABLE "OSE"."STRUCTURE" DROP CONSTRAINT "STRUCTURES_STRUCTURES_FK";
ALTER TABLE "OSE"."STRUCTURE" DROP CONSTRAINT "STRUCTURE_ETABLISSEMENT_FK";
ALTER TABLE "OSE"."STRUCTURE" DROP CONSTRAINT "STRUCTURE_STRUCTURE_FK";
ALTER TABLE "OSE"."STRUCTURE" DROP CONSTRAINT "STRUCTURE_TYPE_STRUCTURE_FK";
ALTER TABLE "OSE"."STRUCTURE" DROP CONSTRAINT "STRUCTURE_SOURCE_ID_UN";
ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_CODE_UN" UNIQUE ("CODE","HISTO_DESTRUCTION") ENABLE;
ALTER TABLE "OSE"."STRUCTURE" ADD CONSTRAINT "STRUCTURE_SOURCE_CODE_UN" UNIQUE ("SOURCE_CODE","HISTO_DESTRUCTION") ENABLE;

---------------------------
--Modifié TABLE
--SERVICE_REFERENTIEL
---------------------------
ALTER TABLE "OSE"."SERVICE_REFERENTIEL" ADD ("FORMATION" VARCHAR2(256 CHAR));

---------------------------
--Modifié TABLE
--NOTIFICATION_INDICATEUR
---------------------------
ALTER TABLE "OSE"."NOTIFICATION_INDICATEUR" DROP CONSTRAINT "NI_AFFECTATION_FK";

---------------------------
--Modifié TABLE
--INTERVENANT
---------------------------
ALTER TABLE "OSE"."INTERVENANT" ADD ("UTILISATEUR_CODE" VARCHAR2(60 CHAR));
ALTER TABLE "OSE"."INTERVENANT" DROP ("SUPANN_EMP_ID");
ALTER TABLE "OSE"."INTERVENANT" DROP CONSTRAINT "INTERVENANT_SUPANN_UN";
ALTER TABLE "OSE"."INTERVENANT" ADD CONSTRAINT "INTERVENANT_UTIL_CODE_UN" UNIQUE ("UTILISATEUR_CODE","ANNEE_ID","STATUT_ID") ENABLE;

---------------------------
--Nouveau TABLE
--IMPORT_TABLES
---------------------------
  CREATE TABLE "OSE"."IMPORT_TABLES" 
   (	"TABLE_NAME" VARCHAR2(30 CHAR) NOT NULL ENABLE,
	"SYNC_FILTRE" VARCHAR2(2000 CHAR),
	"SYNC_ENABLED" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
	CONSTRAINT "IMPORT_TABLES_PK" PRIMARY KEY ("TABLE_NAME") ENABLE
   );
---------------------------
--Modifié TABLE
--GRADE
---------------------------
ALTER TABLE "OSE"."GRADE" ADD CONSTRAINT "GRADE_SOURCE_FK" FOREIGN KEY ("SOURCE_ID") REFERENCES "OSE"."SOURCE"("ID") ENABLE;

---------------------------
--Modifié TABLE
--FONCTION_REFERENTIEL
---------------------------
ALTER TABLE "OSE"."FONCTION_REFERENTIEL" ADD ("ETAPE_REQUISE" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE);

---------------------------
--Modifié TABLE
--ETAT_VOLUME_HORAIRE
---------------------------
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP ("HISTO_CREATEUR_ID");
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP ("HISTO_CREATION");
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP ("HISTO_DESTRUCTEUR_ID");
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP ("HISTO_DESTRUCTION");
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP ("HISTO_MODIFICATEUR_ID");
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP ("HISTO_MODIFICATION");
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP CONSTRAINT "ETAT_VOLUME_HORAIRE_HCFK";
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP CONSTRAINT "ETAT_VOLUME_HORAIRE_HDFK";
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP CONSTRAINT "ETAT_VOLUME_HORAIRE_HMFK";
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" DROP CONSTRAINT "ETAT_VOLUME_HORAIRE__UN";
ALTER TABLE "OSE"."ETAT_VOLUME_HORAIRE" ADD CONSTRAINT "ETAT_VOLUME_HORAIRE__UN" UNIQUE ("CODE") ENABLE;

---------------------------
--Modifié TABLE
--CENTRE_COUT_STRUCTURE
---------------------------
ALTER TABLE "OSE"."CENTRE_COUT_STRUCTURE" ADD CONSTRAINT "CCS_SOURCE_FK" FOREIGN KEY ("SOURCE_ID") REFERENCES "OSE"."SOURCE"("ID") ENABLE;

---------------------------
--Modifié TABLE
--AFFECTATION
---------------------------
ALTER TABLE "OSE"."AFFECTATION" DROP ("PERSONNEL_ID");
ALTER TABLE "OSE"."AFFECTATION" MODIFY ("UTILISATEUR_ID" NUMBER(*,0));
ALTER TABLE "OSE"."AFFECTATION" MODIFY ("UTILISATEUR_ID" NOT NULL ENABLE);
ALTER TABLE "OSE"."AFFECTATION" DROP CONSTRAINT "AFFECTATION_PERSONNEL_FK";
ALTER TABLE "OSE"."AFFECTATION" DROP CONSTRAINT "AFFECTATION__UN";
ALTER TABLE "OSE"."AFFECTATION" ADD CONSTRAINT "AFFECTATION_UTILISATEUR_FK" FOREIGN KEY ("UTILISATEUR_ID") REFERENCES "OSE"."UTILISATEUR"("ID") ENABLE;
ALTER TABLE "OSE"."AFFECTATION" ADD CONSTRAINT "AFFECTATION__UN" UNIQUE ("ROLE_ID","STRUCTURE_ID","HISTO_DESTRUCTION","UTILISATEUR_ID") ENABLE;

---------------------------
--Modifié VIEW
--V_UNICAEN_OCTOPUS_VACATAIRES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_UNICAEN_OCTOPUS_VACATAIRES" 
 ( "C_SOURCE", "C_SRC_INDIVIDU", "C_SRC_STRUCTURE", "TYPE_ID", "ID_ORIG", "DATE_DEBUT", "DATE_FIN", "T_PRINCIPALE"
  )  AS 
  SELECT DISTINCT
  'HARP'                                                      c_source,
  i.code                                                      c_src_individu,
  sens.source_code                                            c_src_structure,
  4                                                           type_id,
  'OSE-' || a.id || '-' || i.code || '-' || sens.source_code  id_orig,
  a.date_debut                                                date_debut,
  a.date_fin                                                  date_fin,
  CASE WHEN sens.id = i.structure_id THEN 'O' ELSE 'N' END    t_principale
FROM
       source       sharpege
  JOIN intervenant         i ON i.source_id = sharpege.id AND i.histo_destruction IS NULL
  JOIN annee               a ON a.id = i.annee_id
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant   ti ON ti.id = si.type_intervenant_id 
                            AND ti.code = 'E'
  JOIN v_tbl_service        ts ON ts.intervenant_id = i.id 
                            AND ts.heures > 0
  JOIN structure        sens ON sens.id = ts.structure_id 
                            AND sens.source_id = sharpege.id
WHERE
 sharpege.code = 'Harpege';
---------------------------
--Modifié VIEW
--V_UNICAEN_OCTOPUS_TITULAIRES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_UNICAEN_OCTOPUS_TITULAIRES" 
 ( "C_SOURCE", "C_SRC_INDIVIDU", "C_SRC_STRUCTURE", "TYPE_ID", "ID_ORIG", "DATE_DEBUT", "DATE_FIN", "T_PRINCIPALE"
  )  AS 
  SELECT DISTINCT
  'HARP'                                                      c_source,
  i.code                                                      c_src_individu,
  sens.source_code                                            c_src_structure,
  4                                                           type_id,
  'OSE-' || a.id || '-' || i.code || '-' || sens.source_code  id_orig,
  a.date_debut                                                date_debut,
  a.date_fin                                                  date_fin,
  CASE WHEN sens.id = i.structure_id THEN 'O' ELSE 'N' END    t_principale
FROM
       source       sharpege
  JOIN intervenant         i ON i.source_id = sharpege.id AND i.histo_destruction IS NULL
  JOIN annee               a ON a.id = i.annee_id
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant   ti ON ti.id = si.type_intervenant_id 
                            AND ti.code = 'P'
  JOIN v_tbl_service        ts ON ts.intervenant_id = i.id 
                            AND ts.heures > 0
  JOIN structure        sens ON sens.id = ts.structure_id 
                            AND sens.source_id = sharpege.id
WHERE
 sharpege.code = 'Harpege'

UNION
  
SELECT DISTINCT
  'HARP'                                                      c_source,
  i.code                                                      c_src_individu,
  sens.source_code                                            c_src_structure,
  4                                                           type_id,
  'OSE-' || a.id || '-' || i.code || '-' || sens.source_code  id_orig,
  a.date_debut                                                date_debut,
  a.date_fin                                                  date_fin,
  CASE WHEN sens.id = i.structure_id THEN 'O' ELSE 'N' END    t_principale
FROM
       source       sharpege
  JOIN intervenant         i ON i.source_id = sharpege.id AND i.histo_destruction IS NULL
  JOIN annee               a ON a.id = i.annee_id
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant   ti ON ti.id = si.type_intervenant_id 
                            AND ti.code = 'P'
  JOIN v_tbl_service_referentiel ts ON ts.intervenant_id = i.id 
                            AND ts.nbvh > 0
  JOIN structure        sens ON sens.id = ts.structure_id 
                            AND sens.source_id = sharpege.id
WHERE
 sharpege.code = 'Harpege';
---------------------------
--Modifié VIEW
--V_TBL_VALIDATION_REFERENTIEL
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_VALIDATION_REFERENTIEL" 
 ( "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_VOLUME_HORAIRE_ID", "SERVICE_REFERENTIEL_ID", "VOLUME_HORAIRE_REF_ID", "VALIDATION_ID"
  )  AS 
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
  s.histo_destruction IS NULL;
---------------------------
--Modifié VIEW
--V_TBL_VALIDATION_ENSEIGNEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_VALIDATION_ENSEIGNEMENT" 
 ( "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "TYPE_VOLUME_HORAIRE_ID", "SERVICE_ID", "VOLUME_HORAIRE_ID", "VALIDATION_ID"
  )  AS 
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
  s.histo_destruction IS NULL;
---------------------------
--Modifié VIEW
--V_SYMPA_LISTE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_SYMPA_LISTE" 
 ( "EMAIL"
  )  AS 
  SELECT DISTINCT 
  u.email
FROM 
  affectation a
  INNER JOIN role r on a.role_id = r.id and r.histo_destruction IS NULL
  INNER JOIN utilisateur u on a.utilisateur_id = u.id
WHERE 
  r.code in (
     'gestionnaire-composante'
    ,'responsable-composante'
    ,'responsable-drh'
    ,'gestionnaire-drh'
    ,'administrateur'
  )
  AND a.histo_destruction IS NULL
ORDER BY u.email;
---------------------------
--Modifié VIEW
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
  i.histo_destruction IS NULL
  
UNION ALL

SELECT
  null id,
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
  JOIN civilite c ON c.id = i.civilite_id;
---------------------------
--Modifié VIEW
--V_INDICATEUR_690
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_690" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  fr.id id,
  i.annee_id annee_id,
  i.id intervenant_id,
  i.structure_id structure_id,
  si.plafond_referentiel plafond,
  fr.heures_compl_referentiel heures
FROM
  formule_resultat fr
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
WHERE
  tvh.code = 'REALISE'
  AND evh.code = 'saisi'
  AND si.plafond_referentiel < fr.service_referentiel + fr.heures_compl_referentiel;
---------------------------
--Modifié VIEW
--V_INDICATEUR_680
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_680" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  fr.id id,
  i.annee_id annee_id,
  i.id intervenant_id,
  i.structure_id structure_id,
  si.plafond_referentiel plafond,
  fr.heures_compl_referentiel heures
FROM
  formule_resultat fr
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
WHERE
  tvh.code = 'PREVU'
  AND evh.code = 'saisi'
  AND si.plafond_referentiel < fr.service_referentiel + fr.heures_compl_referentiel;
---------------------------
--Nouveau VIEW
--V_INDICATEUR_570
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_570" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'REALISE'
WHERE
  fr.total > si.maximum_hetd;
---------------------------
--Nouveau VIEW
--V_INDICATEUR_560
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_560" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'PREVU'
WHERE
  fr.total > si.maximum_hetd;
---------------------------
--Modifié VIEW
--V_INDICATEUR_550
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_550" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond,
  fr.heures_compl_fc_majorees         heures
FROM
       intervenant                i
  JOIN annee                      a ON a.id = i.annee_id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
WHERE
  fr.heures_compl_fc_majorees > ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 )
  AND tvh.code = 'REALISE';
---------------------------
--Modifié VIEW
--V_INDICATEUR_540
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_540" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond,
  fr.heures_compl_fc_majorees         heures
FROM
       intervenant                i
  JOIN annee                      a ON a.id = i.annee_id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
WHERE
  fr.heures_compl_fc_majorees > ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 )
  AND tvh.code = 'PREVU';
---------------------------
--Nouveau VIEW
--V_INDICATEUR_1220
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_1220" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  i.id id,
  i.annee_id,
  i.id intervenant_id,
  i.structure_id,
  AVG(t.plafond)  plafond,
  AVG(t.heures)   heures
FROM
  (
  SELECT
    vhr.type_volume_horaire_id        type_volume_horaire_id,
    sr.intervenant_id                 intervenant_id,
    fr.plafond                        plafond,
    fr.id                             fr_id,
    SUM(vhr.heures)                   heures
  FROM
         service_referentiel       sr
    JOIN fonction_referentiel      fr ON fr.id = sr.fonction_id
    JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
    JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code= 'REALISE'
  WHERE
    sr.histo_destruction IS NULL
  GROUP BY
    vhr.type_volume_horaire_id,
    sr.intervenant_id,
    fr.plafond,
    fr.id
  ) t
  JOIN intervenant i ON i.id = t.intervenant_id
WHERE
  t.heures > t.plafond
  /*i.id*/
GROUP BY
  t.type_volume_horaire_id,
  i.annee_id,
  i.id,
  i.structure_id;
---------------------------
--Nouveau VIEW
--V_INDICATEUR_1210
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_1210" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID", "PLAFOND", "HEURES"
  )  AS 
  SELECT
  i.id id,
  i.annee_id,
  i.id intervenant_id,
  i.structure_id,
  AVG(t.plafond)  plafond,
  AVG(t.heures)   heures
FROM
  (
  SELECT
    vhr.type_volume_horaire_id        type_volume_horaire_id,
    sr.intervenant_id                 intervenant_id,
    fr.plafond                        plafond,
    fr.id                             fr_id,
    SUM(vhr.heures)                   heures
  FROM
         service_referentiel       sr
    JOIN fonction_referentiel      fr ON fr.id = sr.fonction_id
    JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
    JOIN type_volume_horaire      tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code= 'PREVU'
  WHERE
    sr.histo_destruction IS NULL
  GROUP BY
    vhr.type_volume_horaire_id,
    sr.intervenant_id,
    fr.plafond,
    fr.id
  ) t
  JOIN intervenant i ON i.id = t.intervenant_id
WHERE
  t.heures > t.plafond
  /*i.id*/
GROUP BY
  t.type_volume_horaire_id,
  i.annee_id,
  i.id,
  i.structure_id;
---------------------------
--Nouveau VIEW
--V_INDICATEUR_1021
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_1021" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID"
  )  AS 
  SELECT rownum id, t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM
(
SELECT DISTINCT
  w.annee_id,
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.etape_code = 'PJ_VALIDATION'
  AND wc.etape_code = 'PJ_SAISIE'
  AND w.type_intervenant_code = 'P'
  AND wc.objectif = wc.realisation
  AND w.atteignable = 1
  AND w.objectif > w.realisation
) t;
---------------------------
--Modifié VIEW
--V_INDICATEUR_1020
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_1020" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID"
  )  AS 
  SELECT rownum id, t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM
(
SELECT DISTINCT
  w.annee_id,
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.etape_code = 'PJ_VALIDATION'
  AND wc.etape_code = 'PJ_SAISIE'
  AND w.type_intervenant_code = 'E'
  AND wc.objectif = wc.realisation
  AND w.atteignable = 1
  AND w.objectif > w.realisation
) t;
---------------------------
--Nouveau VIEW
--V_INDICATEUR_1011
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_1011" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID"
  )  AS 
  SELECT rownum id, t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM
(
SELECT DISTINCT
  w.annee_id,
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.etape_code = 'PJ_SAISIE'
  AND wc.etape_code = 'SERVICE_SAISIE'
  AND w.type_intervenant_code = 'P'
  AND wc.realisation > 0
  AND w.atteignable = 1
  AND w.objectif > w.realisation
) t;
---------------------------
--Modifié VIEW
--V_INDICATEUR_1010
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_INDICATEUR_1010" 
 ( "ID", "ANNEE_ID", "INTERVENANT_ID", "STRUCTURE_ID"
  )  AS 
  SELECT rownum id, t."ANNEE_ID",t."INTERVENANT_ID",t."STRUCTURE_ID" FROM
(
SELECT DISTINCT
  w.annee_id,
  w.intervenant_id,
  i.structure_id
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id
WHERE
  w.etape_code = 'PJ_SAISIE'
  AND wc.etape_code = 'SERVICE_SAISIE'
  AND w.type_intervenant_code = 'E'
  AND wc.realisation > 0
  AND w.atteignable = 1
  AND w.objectif > w.realisation
) t;
---------------------------
--Modifié VIEW
--V_IMPORT_TAB_COLS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_IMPORT_TAB_COLS" 
 ( "TABLE_NAME", "COLUMN_NAME", "DATA_TYPE", "LENGTH", "NULLABLE", "HAS_DEFAULT", "C_TABLE_NAME", "C_COLUMN_NAME", "IMPORT_ACTIF"
  )  AS 
  WITH importable_tables (table_name )AS (
  SELECT
  t.table_name
FROM
  user_tab_cols c
  join user_tables t on t.table_name = c.table_name
WHERE
  c.column_name = 'SOURCE_CODE'

MINUS

SELECT
  mview_name table_name
FROM
  USER_MVIEWS
), c_values (table_name, column_name, c_table_name, c_column_name) AS (
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
  tc.data_type,
  CASE WHEN tc.char_length = 0 THEN NULL ELSE tc.char_length END length,
  CASE WHEN tc.nullable = 'Y' THEN 1 ELSE 0 END nullable,
  CASE WHEN tc.data_default IS NOT NULL THEN 1 ELSE 0 END has_default,
  cv.c_table_name,
  cv.c_column_name,
  CASE WHEN stc.table_name IS NULL THEN 0 ELSE 1 END AS import_actif
FROM
  user_tab_cols tc
  JOIN importable_tables t ON t.table_name = tc.table_name
  LEFT JOIN c_values cv ON cv.table_name = tc.table_name AND cv.column_name = tc.column_name
  LEFT JOIN user_tab_cols stc ON stc.table_name = 'SRC_' || tc.table_name AND stc.column_name = tc.column_name
WHERE
  tc.column_name not like 'HISTO_%'
  AND tc.column_name <> 'ID'
  AND tc.table_name <> 'SYNC_LOG'
ORDER BY
  tc.table_name, tc.column_id;
---------------------------
--Modifié VIEW
--V_EXPORT_SERVICE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_EXPORT_SERVICE" 
 ( "ID", "SERVICE_ID", "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "SERVICE_DATE_MODIFICATION", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "ETABLISSEMENT_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "PERIODE_ID", "TYPE_INTERVENTION_ID", "FONCTION_REFERENTIEL_ID", "TYPE_ETAT", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_DATE_NAISSANCE", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "INTERVENANT_GRADE_CODE", "INTERVENANT_GRADE_LIBELLE", "INTERVENANT_DISCIPLINE_CODE", "INTERVENANT_DISCIPLINE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "GROUPE_TYPE_FORMATION_LIBELLE", "TYPE_FORMATION_LIBELLE", "ETAPE_NIVEAU", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "ELEMENT_DISCIPLINE_CODE", "ELEMENT_DISCIPLINE_LIBELLE", "FONCTION_REFERENTIEL_LIBELLE", "ELEMENT_TAUX_FI", "ELEMENT_TAUX_FC", "ELEMENT_TAUX_FA", "SERVICE_REF_FORMATION", "COMMENTAIRES", "PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES", "HEURES_REF", "HEURES_NON_PAYEES", "SERVICE_STATUTAIRE", "SERVICE_DU_MODIFIE", "SERVICE_FI", "SERVICE_FA", "SERVICE_FC", "SERVICE_REFERENTIEL", "HEURES_COMPL_FI", "HEURES_COMPL_FA", "HEURES_COMPL_FC", "HEURES_COMPL_FC_MAJOREES", "HEURES_COMPL_REFERENTIEL", "TOTAL", "SOLDE", "DATE_CLOTURE_REALISE"
  )  AS 
  WITH t AS ( SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
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

  s.description                     service_description,

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

  s.description                     service_description,

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
  NULL                              service_ref_formation,
  NULL                              commentaires 
FROM
  volume_horaire                  vh
  JOIN service                     s ON s.id = vh.service_id
  JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
  JOIN formule_resultat           fr ON fr.intervenant_id = s.intervenant_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = vhe.etat_volume_horaire_id
WHERE
  vh.motif_non_paiement_id IS NOT NULL
  AND vh.histo_destruction IS NULL
  AND s.histo_destruction IS NULL

UNION ALL

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
  
  NULL                              service_description,
  
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
  sr.formation                      service_ref_formation,
  sr.commentaires                   commentaires
FROM
  formule_resultat_vh_ref       frvr
  JOIN formule_resultat           fr ON fr.id = frvr.formule_resultat_id
  JOIN volume_horaire_ref        vhr ON vhr.id =  frvr.volume_horaire_ref_id
  JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.histo_destruction IS NULL
  
UNION ALL

SELECT
  'vh_0_' || i.id                   id,
  NULL                              service_id,
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
  
  NULL                              service_description,
  
  0                                 heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
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


)
SELECT
  t.id                            id,
  t.service_id                    service_id,
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,  
  i.annee_id                      annee_id,
  his.histo_modification          service_date_modification,
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
  
  tvh.libelle || ' ' || evh.libelle type_etat,
  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  i.date_naissance                intervenant_date_naissance,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  g.source_code                   intervenant_grade_code,
  g.libelle_court                 intervenant_grade_libelle,
  di.source_code                  intervenant_discipline_code,
  di.libelle_court                intervenant_discipline_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  gtf.libelle_court               groupe_type_formation_libelle,
  tf.libelle_court                type_formation_libelle,
  etp.niveau                      etape_niveau,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  COALESCE(ep.libelle,to_char(t.service_description)) element_libelle,
  de.source_code                  element_discipline_code,
  de.libelle_court                element_discipline_libelle,
  fr.libelle_long                 fonction_referentiel_libelle,
  ep.taux_fi                      element_taux_fi,
  ep.taux_fc                      element_taux_fc,
  ep.taux_fa                      element_taux_fa,
  t.service_ref_formation         service_ref_formation,
  t.commentaires                  commentaires,
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
  t.solde                         solde,
  v.histo_modification            date_cloture_realise

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
  LEFT JOIN structure                  saff ON saff.id  = i.structure_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique          ep ON ep.id    = t.element_pedagogique_id
  LEFT JOIN discipline                   de ON de.id    = ep.discipline_id
  LEFT JOIN structure                  sens ON sens.id  = NVL(t.structure_ens_id, ep.structure_id)
  LEFT JOIN periode                       p ON p.id     = t.periode_id
  LEFT JOIN source                      src ON src.id   = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
  LEFT JOIN etape                       etp ON etp.id   = ep.etape_id
  LEFT JOIN type_formation               tf ON tf.id    = etp.type_formation_id AND tf.histo_destruction IS NULL
  LEFT JOIN groupe_type_formation       gtf ON gtf.id   = tf.groupe_id AND gtf.histo_destruction IS NULL
  LEFT JOIN v_formule_service_modifie   fsm ON fsm.intervenant_id = i.id
  LEFT JOIN v_formule_service            fs ON fs.id    = t.service_id
  LEFT JOIN fonction_referentiel         fr ON fr.id    = t.fonction_referentiel_id
  LEFT JOIN type_validation              tv ON tvh.code = 'REALISE' AND tv.code = 'CLOTURE_REALISE'
  LEFT JOIN validation                    v ON v.intervenant_id = i.id AND v.type_validation_id = tv.id AND v.histo_destruction IS NULL;
---------------------------
--Modifié VIEW
--V_EXPORT_DMEP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_EXPORT_DMEP" 
 ( "INTERVENANT_ID", "TYPE_INTERVENANT_ID", "ANNEE_ID", "STRUCTURE_AFF_ID", "STRUCTURE_ENS_ID", "STRUCTURE_ID", "CENTRE_COUT_ID", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "TYPE_FORMATION_ID", "GROUPE_TYPE_FORMATION_ID", "STATUT_INTERVENANT_ID", "PERIODE_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_DATE_NAISSANCE", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_CODE", "INTERVENANT_TYPE_LIBELLE", "INTERVENANT_GRADE_CODE", "INTERVENANT_GRADE_LIBELLE", "INTERVENANT_DISCIPLINE_CODE", "INTERVENANT_DISCIPLINE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "GROUPE_TYPE_FORMATION_LIBELLE", "TYPE_FORMATION_LIBELLE", "ETAPE_NIVEAU", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "ELEMENT_DISCIPLINE_CODE", "ELEMENT_DISCIPLINE_LIBELLE", "FONCTION_REFERENTIEL_LIBELLE", "ELEMENT_TAUX_FI", "ELEMENT_TAUX_FC", "ELEMENT_TAUX_FA", "ELEMENT_SOURCE_LIBELLE", "COMMENTAIRES", "ETAT", "TYPE_RESSOURCE_LIBELLE", "CENTRE_COUTS_CODE", "CENTRE_COUTS_LIBELLE", "DOMAINE_FONCTIONNEL_CODE", "DOMAINE_FONCTIONNEL_LIBELLE", "PERIODE_LIBELLE", "DATE_MISE_EN_PAIEMENT", "HEURES_FI", "HEURES_FA", "HEURES_FC", "HEURES_FC_MAJOREES", "HEURES_REFERENTIEL"
  )  AS 
  WITH mep AS (
  SELECT
    frs.service_id,
    frsr.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,
    mep.domaine_fonctionnel_id,
  
    sum(case when th.code = 'fi' then mep.heures else 0 end) heures_fi,
    sum(case when th.code = 'fa' then mep.heures else 0 end) heures_fa,
    sum(case when th.code = 'fc' then mep.heures else 0 end) heures_fc,
    sum(case when th.code = 'fc_majorees' then mep.heures else 0 end) heures_fc_majorees,
    sum(case when th.code = 'referentiel' then mep.heures else 0 end) heures_referentiel
  FROM
              mise_en_paiement              mep
         JOIN type_heures                    th ON th.id   = mep.type_heures_id
    LEFT JOIN formule_resultat_service      frs ON frs.id  = mep.formule_res_service_id
    LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
  WHERE
    mep.histo_destruction IS NULL
  GROUP BY
    frs.service_id,
    frsr.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,
    mep.domaine_fonctionnel_id
)
SELECT 
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  i.annee_id                      annee_id,
  saff.id                         structure_aff_id,
  sens.id                         structure_ens_id,
  NVL(sens.id,saff.id)            structure_id,
  cc.id                           centre_cout_id,
  ep.id                           element_pedagogique_id,
  etp.id                          etape_id,
  tf.id                           type_formation_id,
  gtf.id                          groupe_type_formation_id,
  si.id                           statut_intervenant_id,
  p.id                            periode_id,
    
  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  i.date_naissance                intervenant_date_naissance,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  g.source_code                   intervenant_grade_code,
  g.libelle_court                 intervenant_grade_libelle,
  di.source_code                  intervenant_discipline_code,
  di.libelle_court                intervenant_discipline_libelle,
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
  de.source_code                  element_discipline_code,
  de.libelle_court                element_discipline_libelle,
  fr.libelle_long                 fonction_referentiel_libelle,
  ep.taux_fi                      element_taux_fi,
  ep.taux_fc                      element_taux_fc,
  ep.taux_fa                      element_taux_fa,
  src.libelle                     element_source_libelle,
  COALESCE(to_char(s.description),to_char(sr.commentaires)) commentaires,
  
  CASE
    WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
    ELSE 'mis-en-paiement'
  END                             etat,
  tr.libelle                      type_ressource_libelle,
  cc.source_code                  centre_couts_code,
  cc.libelle                      centre_couts_libelle,
  df.source_code                  domaine_fonctionnel_code,
  df.libelle                      domaine_fonctionnel_libelle,
  p.libelle_long                  periode_libelle,
  mep.date_mise_en_paiement       date_mise_en_paiement,
  mep.heures_fi                   heures_fi,
  mep.heures_fa                   heures_fa,
  mep.heures_fc                   heures_fc,
  mep.heures_fc_majorees          heures_fc_majorees,
  mep.heures_referentiel          heures_referentiel
FROM
              mep
         JOIN centre_cout               cc ON cc.id   = mep.centre_cout_id
         JOIN type_ressource            tr ON tr.id   = cc.type_ressource_id
    LEFT JOIN service                    s ON s.id    = mep.service_id
    LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
    LEFT JOIN source                   src ON src.id  = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
    LEFT JOIN discipline                de ON de.id   = ep.discipline_id
    LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
    LEFT JOIN type_formation            tf ON tf.id   = etp.type_formation_id
    LEFT JOIN groupe_type_formation    gtf ON gtf.id  = tf.groupe_id
    LEFT JOIN service_referentiel       sr ON sr.id   = mep.service_referentiel_id
    LEFT JOIN fonction_referentiel      fr ON fr.id   = sr.fonction_id
         JOIN intervenant                i ON i.id    = NVL( s.intervenant_id, sr.intervenant_id )
         JOIN statut_intervenant        si ON si.id   = i.statut_id
         JOIN type_intervenant          ti ON ti.id   = si.type_intervenant_id
    LEFT JOIN grade                      g ON g.id    = i.grade_id
    LEFT JOIN discipline                di ON di.id   = i.discipline_id
    LEFT JOIN structure               saff ON saff.id = i.structure_id AND ti.code = 'P'
    LEFT JOIN structure               sens ON sens.id = NVL( ep.structure_id, sr.structure_id )
         JOIN etablissement           etab ON etab.id = NVL( s.etablissement_id, ose_parametre.get_etablissement() )
    LEFT JOIN periode                    p ON p.id    = mep.periode_paiement_id
    LEFT JOIN domaine_fonctionnel       df ON df.id   = mep.domaine_fonctionnel_id
ORDER BY
  intervenant_nom,
  service_structure_aff_libelle, 
  service_structure_ens_libelle, 
  etape_libelle, 
  element_libelle;
---------------------------
--Modifié VIEW
--V_DIFF_VOLUME_HORAIRE_ENS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_VOLUME_HORAIRE_ENS" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "GROUPES", "HEURES", "TYPE_INTERVENTION_ID", "U_ELEMENT_PEDAGOGIQUE_ID", "U_GROUPES", "U_HEURES", "U_TYPE_INTERVENTION_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."GROUPES",diff."HEURES",diff."TYPE_INTERVENTION_ID",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_GROUPES",diff."U_HEURES",diff."U_TYPE_INTERVENTION_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.GROUPES ELSE S.GROUPES END GROUPES,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.HEURES ELSE S.HEURES END HEURES,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_INTERVENTION_ID ELSE S.TYPE_INTERVENTION_ID END TYPE_INTERVENTION_ID,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.GROUPES <> S.GROUPES OR (D.GROUPES IS NULL AND S.GROUPES IS NOT NULL) OR (D.GROUPES IS NOT NULL AND S.GROUPES IS NULL) THEN 1 ELSE 0 END U_GROUPES,
    CASE WHEN D.HEURES <> S.HEURES OR (D.HEURES IS NULL AND S.HEURES IS NOT NULL) OR (D.HEURES IS NOT NULL AND S.HEURES IS NULL) THEN 1 ELSE 0 END U_HEURES,
    CASE WHEN D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_INTERVENTION_ID
FROM
  VOLUME_HORAIRE_ENS D
  LEFT JOIN ELEMENT_PEDAGOGIQUE rt ON rt.ID = d.ELEMENT_PEDAGOGIQUE_ID
  FULL JOIN SRC_VOLUME_HORAIRE_ENS S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.GROUPES <> S.GROUPES OR (D.GROUPES IS NULL AND S.GROUPES IS NOT NULL) OR (D.GROUPES IS NOT NULL AND S.GROUPES IS NULL)
  OR D.HEURES <> S.HEURES OR (D.HEURES IS NULL AND S.HEURES IS NOT NULL) OR (D.HEURES IS NOT NULL AND S.HEURES IS NULL)
  OR D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_TYPE_MODULATEUR_EP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_TYPE_MODULATEUR_EP" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "TYPE_MODULATEUR_ID", "U_ELEMENT_PEDAGOGIQUE_ID", "U_TYPE_MODULATEUR_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."TYPE_MODULATEUR_ID",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_TYPE_MODULATEUR_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
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
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID", "U_ELEMENT_PEDAGOGIQUE_ID", "U_TYPE_INTERVENTION_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ELEMENT_PEDAGOGIQUE_ID",diff."TYPE_INTERVENTION_ID",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_TYPE_INTERVENTION_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_INTERVENTION_ID ELSE S.TYPE_INTERVENTION_ID END TYPE_INTERVENTION_ID,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.TYPE_INTERVENTION_ID <> S.TYPE_INTERVENTION_ID OR (D.TYPE_INTERVENTION_ID IS NULL AND S.TYPE_INTERVENTION_ID IS NOT NULL) OR (D.TYPE_INTERVENTION_ID IS NOT NULL AND S.TYPE_INTERVENTION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_INTERVENTION_ID
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
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_TYPE_FORMATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_TYPE_FORMATION" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "GROUPE_ID", "LIBELLE_COURT", "LIBELLE_LONG", "U_GROUPE_ID", "U_LIBELLE_COURT", "U_LIBELLE_LONG"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."GROUPE_ID",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_GROUPE_ID",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  D.id id,
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
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE", "LIBELLE_COURT", "LIBELLE_LONG", "U_CODE", "U_LIBELLE_COURT", "U_LIBELLE_LONG"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_CODE",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE ELSE S.CODE END CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL) THEN 1 ELSE 0 END U_CODE,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG
FROM
  STRUCTURE D
  FULL JOIN SRC_STRUCTURE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL)
  OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_SCENARIO_LIEN
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_SCENARIO_LIEN" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ACTIF", "CHOIX_MAXIMUM", "CHOIX_MINIMUM", "LIEN_ID", "POIDS", "SCENARIO_ID", "U_ACTIF", "U_CHOIX_MAXIMUM", "U_CHOIX_MINIMUM", "U_LIEN_ID", "U_POIDS", "U_SCENARIO_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ACTIF",diff."CHOIX_MAXIMUM",diff."CHOIX_MINIMUM",diff."LIEN_ID",diff."POIDS",diff."SCENARIO_ID",diff."U_ACTIF",diff."U_CHOIX_MAXIMUM",diff."U_CHOIX_MINIMUM",diff."U_LIEN_ID",diff."U_POIDS",diff."U_SCENARIO_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ACTIF ELSE S.ACTIF END ACTIF,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CHOIX_MAXIMUM ELSE S.CHOIX_MAXIMUM END CHOIX_MAXIMUM,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CHOIX_MINIMUM ELSE S.CHOIX_MINIMUM END CHOIX_MINIMUM,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIEN_ID ELSE S.LIEN_ID END LIEN_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.POIDS ELSE S.POIDS END POIDS,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.SCENARIO_ID ELSE S.SCENARIO_ID END SCENARIO_ID,
    CASE WHEN D.ACTIF <> S.ACTIF OR (D.ACTIF IS NULL AND S.ACTIF IS NOT NULL) OR (D.ACTIF IS NOT NULL AND S.ACTIF IS NULL) THEN 1 ELSE 0 END U_ACTIF,
    CASE WHEN D.CHOIX_MAXIMUM <> S.CHOIX_MAXIMUM OR (D.CHOIX_MAXIMUM IS NULL AND S.CHOIX_MAXIMUM IS NOT NULL) OR (D.CHOIX_MAXIMUM IS NOT NULL AND S.CHOIX_MAXIMUM IS NULL) THEN 1 ELSE 0 END U_CHOIX_MAXIMUM,
    CASE WHEN D.CHOIX_MINIMUM <> S.CHOIX_MINIMUM OR (D.CHOIX_MINIMUM IS NULL AND S.CHOIX_MINIMUM IS NOT NULL) OR (D.CHOIX_MINIMUM IS NOT NULL AND S.CHOIX_MINIMUM IS NULL) THEN 1 ELSE 0 END U_CHOIX_MINIMUM,
    CASE WHEN D.LIEN_ID <> S.LIEN_ID OR (D.LIEN_ID IS NULL AND S.LIEN_ID IS NOT NULL) OR (D.LIEN_ID IS NOT NULL AND S.LIEN_ID IS NULL) THEN 1 ELSE 0 END U_LIEN_ID,
    CASE WHEN D.POIDS <> S.POIDS OR (D.POIDS IS NULL AND S.POIDS IS NOT NULL) OR (D.POIDS IS NOT NULL AND S.POIDS IS NULL) THEN 1 ELSE 0 END U_POIDS,
    CASE WHEN D.SCENARIO_ID <> S.SCENARIO_ID OR (D.SCENARIO_ID IS NULL AND S.SCENARIO_ID IS NOT NULL) OR (D.SCENARIO_ID IS NOT NULL AND S.SCENARIO_ID IS NULL) THEN 1 ELSE 0 END U_SCENARIO_ID
FROM
  SCENARIO_LIEN D
  FULL JOIN SRC_SCENARIO_LIEN S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ACTIF <> S.ACTIF OR (D.ACTIF IS NULL AND S.ACTIF IS NOT NULL) OR (D.ACTIF IS NOT NULL AND S.ACTIF IS NULL)
  OR D.CHOIX_MAXIMUM <> S.CHOIX_MAXIMUM OR (D.CHOIX_MAXIMUM IS NULL AND S.CHOIX_MAXIMUM IS NOT NULL) OR (D.CHOIX_MAXIMUM IS NOT NULL AND S.CHOIX_MAXIMUM IS NULL)
  OR D.CHOIX_MINIMUM <> S.CHOIX_MINIMUM OR (D.CHOIX_MINIMUM IS NULL AND S.CHOIX_MINIMUM IS NOT NULL) OR (D.CHOIX_MINIMUM IS NOT NULL AND S.CHOIX_MINIMUM IS NULL)
  OR D.LIEN_ID <> S.LIEN_ID OR (D.LIEN_ID IS NULL AND S.LIEN_ID IS NOT NULL) OR (D.LIEN_ID IS NOT NULL AND S.LIEN_ID IS NULL)
  OR D.POIDS <> S.POIDS OR (D.POIDS IS NULL AND S.POIDS IS NOT NULL) OR (D.POIDS IS NOT NULL AND S.POIDS IS NULL)
  OR D.SCENARIO_ID <> S.SCENARIO_ID OR (D.SCENARIO_ID IS NULL AND S.SCENARIO_ID IS NOT NULL) OR (D.SCENARIO_ID IS NOT NULL AND S.SCENARIO_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_PAYS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_PAYS" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "LIBELLE_COURT", "LIBELLE_LONG", "TEMOIN_UE", "VALIDITE_DEBUT", "VALIDITE_FIN", "U_LIBELLE_COURT", "U_LIBELLE_LONG", "U_TEMOIN_UE", "U_VALIDITE_DEBUT", "U_VALIDITE_FIN"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."TEMOIN_UE",diff."VALIDITE_DEBUT",diff."VALIDITE_FIN",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG",diff."U_TEMOIN_UE",diff."U_VALIDITE_DEBUT",diff."U_VALIDITE_FIN" from (SELECT
  D.id id,
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
--V_DIFF_NOEUD
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_NOEUD" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "CODE", "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "LIBELLE", "LISTE", "STRUCTURE_ID", "U_ANNEE_ID", "U_CODE", "U_ELEMENT_PEDAGOGIQUE_ID", "U_ETAPE_ID", "U_LIBELLE", "U_LISTE", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."CODE",diff."ELEMENT_PEDAGOGIQUE_ID",diff."ETAPE_ID",diff."LIBELLE",diff."LISTE",diff."STRUCTURE_ID",diff."U_ANNEE_ID",diff."U_CODE",diff."U_ELEMENT_PEDAGOGIQUE_ID",diff."U_ETAPE_ID",diff."U_LIBELLE",diff."U_LISTE",diff."U_STRUCTURE_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE ELSE S.CODE END CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ELEMENT_PEDAGOGIQUE_ID ELSE S.ELEMENT_PEDAGOGIQUE_ID END ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ETAPE_ID ELSE S.ETAPE_ID END ETAPE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LISTE ELSE S.LISTE END LISTE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL) THEN 1 ELSE 0 END U_CODE,
    CASE WHEN D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL) THEN 1 ELSE 0 END U_ELEMENT_PEDAGOGIQUE_ID,
    CASE WHEN D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL) THEN 1 ELSE 0 END U_ETAPE_ID,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.LISTE <> S.LISTE OR (D.LISTE IS NULL AND S.LISTE IS NOT NULL) OR (D.LISTE IS NOT NULL AND S.LISTE IS NULL) THEN 1 ELSE 0 END U_LISTE,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID
FROM
  NOEUD D
  FULL JOIN SRC_NOEUD S ON S.source_id = D.source_id AND S.source_code = D.source_code AND S.ANNEE_ID = d.ANNEE_ID
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL)
  OR D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL)
  OR D.ELEMENT_PEDAGOGIQUE_ID <> S.ELEMENT_PEDAGOGIQUE_ID OR (D.ELEMENT_PEDAGOGIQUE_ID IS NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL) OR (D.ELEMENT_PEDAGOGIQUE_ID IS NOT NULL AND S.ELEMENT_PEDAGOGIQUE_ID IS NULL)
  OR D.ETAPE_ID <> S.ETAPE_ID OR (D.ETAPE_ID IS NULL AND S.ETAPE_ID IS NOT NULL) OR (D.ETAPE_ID IS NOT NULL AND S.ETAPE_ID IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.LISTE <> S.LISTE OR (D.LISTE IS NULL AND S.LISTE IS NOT NULL) OR (D.LISTE IS NOT NULL AND S.LISTE IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_LIEN
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_LIEN" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "NOEUD_INF_ID", "NOEUD_SUP_ID", "STRUCTURE_ID", "U_NOEUD_INF_ID", "U_NOEUD_SUP_ID", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."NOEUD_INF_ID",diff."NOEUD_SUP_ID",diff."STRUCTURE_ID",diff."U_NOEUD_INF_ID",diff."U_NOEUD_SUP_ID",diff."U_STRUCTURE_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOEUD_INF_ID ELSE S.NOEUD_INF_ID END NOEUD_INF_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOEUD_SUP_ID ELSE S.NOEUD_SUP_ID END NOEUD_SUP_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN D.NOEUD_INF_ID <> S.NOEUD_INF_ID OR (D.NOEUD_INF_ID IS NULL AND S.NOEUD_INF_ID IS NOT NULL) OR (D.NOEUD_INF_ID IS NOT NULL AND S.NOEUD_INF_ID IS NULL) THEN 1 ELSE 0 END U_NOEUD_INF_ID,
    CASE WHEN D.NOEUD_SUP_ID <> S.NOEUD_SUP_ID OR (D.NOEUD_SUP_ID IS NULL AND S.NOEUD_SUP_ID IS NOT NULL) OR (D.NOEUD_SUP_ID IS NOT NULL AND S.NOEUD_SUP_ID IS NULL) THEN 1 ELSE 0 END U_NOEUD_SUP_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID
FROM
  LIEN D
  LEFT JOIN NOEUD rt ON rt.ID = d.NOEUD_SUP_ID
  FULL JOIN SRC_LIEN S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.NOEUD_INF_ID <> S.NOEUD_INF_ID OR (D.NOEUD_INF_ID IS NULL AND S.NOEUD_INF_ID IS NOT NULL) OR (D.NOEUD_INF_ID IS NOT NULL AND S.NOEUD_INF_ID IS NULL)
  OR D.NOEUD_SUP_ID <> S.NOEUD_SUP_ID OR (D.NOEUD_SUP_ID IS NULL AND S.NOEUD_SUP_ID IS NOT NULL) OR (D.NOEUD_SUP_ID IS NOT NULL AND S.NOEUD_SUP_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_INTERVENANT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "BIC", "CIVILITE_ID", "CODE", "CRITERE_RECHERCHE", "DATE_NAISSANCE", "DEP_NAISSANCE_ID", "DISCIPLINE_ID", "EMAIL", "GRADE_ID", "IBAN", "NOM_PATRONYMIQUE", "NOM_USUEL", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "PAYS_NAISSANCE_ID", "PAYS_NATIONALITE_ID", "PRENOM", "STATUT_ID", "STRUCTURE_ID", "TEL_MOBILE", "TEL_PRO", "UTILISATEUR_CODE", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "U_ANNEE_ID", "U_BIC", "U_CIVILITE_ID", "U_CODE", "U_CRITERE_RECHERCHE", "U_DATE_NAISSANCE", "U_DEP_NAISSANCE_ID", "U_DISCIPLINE_ID", "U_EMAIL", "U_GRADE_ID", "U_IBAN", "U_NOM_PATRONYMIQUE", "U_NOM_USUEL", "U_NUMERO_INSEE", "U_NUMERO_INSEE_CLE", "U_NUMERO_INSEE_PROVISOIRE", "U_PAYS_NAISSANCE_ID", "U_PAYS_NATIONALITE_ID", "U_PRENOM", "U_STATUT_ID", "U_STRUCTURE_ID", "U_TEL_MOBILE", "U_TEL_PRO", "U_UTILISATEUR_CODE", "U_VILLE_NAISSANCE_CODE_INSEE", "U_VILLE_NAISSANCE_LIBELLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."BIC",diff."CIVILITE_ID",diff."CODE",diff."CRITERE_RECHERCHE",diff."DATE_NAISSANCE",diff."DEP_NAISSANCE_ID",diff."DISCIPLINE_ID",diff."EMAIL",diff."GRADE_ID",diff."IBAN",diff."NOM_PATRONYMIQUE",diff."NOM_USUEL",diff."NUMERO_INSEE",diff."NUMERO_INSEE_CLE",diff."NUMERO_INSEE_PROVISOIRE",diff."PAYS_NAISSANCE_ID",diff."PAYS_NATIONALITE_ID",diff."PRENOM",diff."STATUT_ID",diff."STRUCTURE_ID",diff."TEL_MOBILE",diff."TEL_PRO",diff."UTILISATEUR_CODE",diff."VILLE_NAISSANCE_CODE_INSEE",diff."VILLE_NAISSANCE_LIBELLE",diff."U_ANNEE_ID",diff."U_BIC",diff."U_CIVILITE_ID",diff."U_CODE",diff."U_CRITERE_RECHERCHE",diff."U_DATE_NAISSANCE",diff."U_DEP_NAISSANCE_ID",diff."U_DISCIPLINE_ID",diff."U_EMAIL",diff."U_GRADE_ID",diff."U_IBAN",diff."U_NOM_PATRONYMIQUE",diff."U_NOM_USUEL",diff."U_NUMERO_INSEE",diff."U_NUMERO_INSEE_CLE",diff."U_NUMERO_INSEE_PROVISOIRE",diff."U_PAYS_NAISSANCE_ID",diff."U_PAYS_NATIONALITE_ID",diff."U_PRENOM",diff."U_STATUT_ID",diff."U_STRUCTURE_ID",diff."U_TEL_MOBILE",diff."U_TEL_PRO",diff."U_UTILISATEUR_CODE",diff."U_VILLE_NAISSANCE_CODE_INSEE",diff."U_VILLE_NAISSANCE_LIBELLE" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.BIC ELSE S.BIC END BIC,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CIVILITE_ID ELSE S.CIVILITE_ID END CIVILITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE ELSE S.CODE END CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CRITERE_RECHERCHE ELSE S.CRITERE_RECHERCHE END CRITERE_RECHERCHE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DATE_NAISSANCE ELSE S.DATE_NAISSANCE END DATE_NAISSANCE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DEP_NAISSANCE_ID ELSE S.DEP_NAISSANCE_ID END DEP_NAISSANCE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DISCIPLINE_ID ELSE S.DISCIPLINE_ID END DISCIPLINE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.EMAIL ELSE S.EMAIL END EMAIL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.GRADE_ID ELSE S.GRADE_ID END GRADE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.IBAN ELSE S.IBAN END IBAN,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_PATRONYMIQUE ELSE S.NOM_PATRONYMIQUE END NOM_PATRONYMIQUE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_USUEL ELSE S.NOM_USUEL END NOM_USUEL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE ELSE S.NUMERO_INSEE END NUMERO_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE_CLE ELSE S.NUMERO_INSEE_CLE END NUMERO_INSEE_CLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NUMERO_INSEE_PROVISOIRE ELSE S.NUMERO_INSEE_PROVISOIRE END NUMERO_INSEE_PROVISOIRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NAISSANCE_ID ELSE S.PAYS_NAISSANCE_ID END PAYS_NAISSANCE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_NATIONALITE_ID ELSE S.PAYS_NATIONALITE_ID END PAYS_NATIONALITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PRENOM ELSE S.PRENOM END PRENOM,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STATUT_ID ELSE S.STATUT_ID END STATUT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_MOBILE ELSE S.TEL_MOBILE END TEL_MOBILE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TEL_PRO ELSE S.TEL_PRO END TEL_PRO,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.UTILISATEUR_CODE ELSE S.UTILISATEUR_CODE END UTILISATEUR_CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_CODE_INSEE ELSE S.VILLE_NAISSANCE_CODE_INSEE END VILLE_NAISSANCE_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.VILLE_NAISSANCE_LIBELLE ELSE S.VILLE_NAISSANCE_LIBELLE END VILLE_NAISSANCE_LIBELLE,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.BIC <> S.BIC OR (D.BIC IS NULL AND S.BIC IS NOT NULL) OR (D.BIC IS NOT NULL AND S.BIC IS NULL) THEN 1 ELSE 0 END U_BIC,
    CASE WHEN D.CIVILITE_ID <> S.CIVILITE_ID OR (D.CIVILITE_ID IS NULL AND S.CIVILITE_ID IS NOT NULL) OR (D.CIVILITE_ID IS NOT NULL AND S.CIVILITE_ID IS NULL) THEN 1 ELSE 0 END U_CIVILITE_ID,
    CASE WHEN D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL) THEN 1 ELSE 0 END U_CODE,
    CASE WHEN D.CRITERE_RECHERCHE <> S.CRITERE_RECHERCHE OR (D.CRITERE_RECHERCHE IS NULL AND S.CRITERE_RECHERCHE IS NOT NULL) OR (D.CRITERE_RECHERCHE IS NOT NULL AND S.CRITERE_RECHERCHE IS NULL) THEN 1 ELSE 0 END U_CRITERE_RECHERCHE,
    CASE WHEN D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL) THEN 1 ELSE 0 END U_DATE_NAISSANCE,
    CASE WHEN D.DEP_NAISSANCE_ID <> S.DEP_NAISSANCE_ID OR (D.DEP_NAISSANCE_ID IS NULL AND S.DEP_NAISSANCE_ID IS NOT NULL) OR (D.DEP_NAISSANCE_ID IS NOT NULL AND S.DEP_NAISSANCE_ID IS NULL) THEN 1 ELSE 0 END U_DEP_NAISSANCE_ID,
    CASE WHEN D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL) THEN 1 ELSE 0 END U_DISCIPLINE_ID,
    CASE WHEN D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL) THEN 1 ELSE 0 END U_EMAIL,
    CASE WHEN D.GRADE_ID <> S.GRADE_ID OR (D.GRADE_ID IS NULL AND S.GRADE_ID IS NOT NULL) OR (D.GRADE_ID IS NOT NULL AND S.GRADE_ID IS NULL) THEN 1 ELSE 0 END U_GRADE_ID,
    CASE WHEN D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL) THEN 1 ELSE 0 END U_IBAN,
    CASE WHEN D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL) THEN 1 ELSE 0 END U_NOM_PATRONYMIQUE,
    CASE WHEN D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL) THEN 1 ELSE 0 END U_NOM_USUEL,
    CASE WHEN D.NUMERO_INSEE <> S.NUMERO_INSEE OR (D.NUMERO_INSEE IS NULL AND S.NUMERO_INSEE IS NOT NULL) OR (D.NUMERO_INSEE IS NOT NULL AND S.NUMERO_INSEE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE,
    CASE WHEN D.NUMERO_INSEE_CLE <> S.NUMERO_INSEE_CLE OR (D.NUMERO_INSEE_CLE IS NULL AND S.NUMERO_INSEE_CLE IS NOT NULL) OR (D.NUMERO_INSEE_CLE IS NOT NULL AND S.NUMERO_INSEE_CLE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE_CLE,
    CASE WHEN D.NUMERO_INSEE_PROVISOIRE <> S.NUMERO_INSEE_PROVISOIRE OR (D.NUMERO_INSEE_PROVISOIRE IS NULL AND S.NUMERO_INSEE_PROVISOIRE IS NOT NULL) OR (D.NUMERO_INSEE_PROVISOIRE IS NOT NULL AND S.NUMERO_INSEE_PROVISOIRE IS NULL) THEN 1 ELSE 0 END U_NUMERO_INSEE_PROVISOIRE,
    CASE WHEN D.PAYS_NAISSANCE_ID <> S.PAYS_NAISSANCE_ID OR (D.PAYS_NAISSANCE_ID IS NULL AND S.PAYS_NAISSANCE_ID IS NOT NULL) OR (D.PAYS_NAISSANCE_ID IS NOT NULL AND S.PAYS_NAISSANCE_ID IS NULL) THEN 1 ELSE 0 END U_PAYS_NAISSANCE_ID,
    CASE WHEN D.PAYS_NATIONALITE_ID <> S.PAYS_NATIONALITE_ID OR (D.PAYS_NATIONALITE_ID IS NULL AND S.PAYS_NATIONALITE_ID IS NOT NULL) OR (D.PAYS_NATIONALITE_ID IS NOT NULL AND S.PAYS_NATIONALITE_ID IS NULL) THEN 1 ELSE 0 END U_PAYS_NATIONALITE_ID,
    CASE WHEN D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL) THEN 1 ELSE 0 END U_PRENOM,
    CASE WHEN D.STATUT_ID <> S.STATUT_ID OR (D.STATUT_ID IS NULL AND S.STATUT_ID IS NOT NULL) OR (D.STATUT_ID IS NOT NULL AND S.STATUT_ID IS NULL) THEN 1 ELSE 0 END U_STATUT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TEL_MOBILE <> S.TEL_MOBILE OR (D.TEL_MOBILE IS NULL AND S.TEL_MOBILE IS NOT NULL) OR (D.TEL_MOBILE IS NOT NULL AND S.TEL_MOBILE IS NULL) THEN 1 ELSE 0 END U_TEL_MOBILE,
    CASE WHEN D.TEL_PRO <> S.TEL_PRO OR (D.TEL_PRO IS NULL AND S.TEL_PRO IS NOT NULL) OR (D.TEL_PRO IS NOT NULL AND S.TEL_PRO IS NULL) THEN 1 ELSE 0 END U_TEL_PRO,
    CASE WHEN D.UTILISATEUR_CODE <> S.UTILISATEUR_CODE OR (D.UTILISATEUR_CODE IS NULL AND S.UTILISATEUR_CODE IS NOT NULL) OR (D.UTILISATEUR_CODE IS NOT NULL AND S.UTILISATEUR_CODE IS NULL) THEN 1 ELSE 0 END U_UTILISATEUR_CODE,
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
  OR D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL)
  OR D.CRITERE_RECHERCHE <> S.CRITERE_RECHERCHE OR (D.CRITERE_RECHERCHE IS NULL AND S.CRITERE_RECHERCHE IS NOT NULL) OR (D.CRITERE_RECHERCHE IS NOT NULL AND S.CRITERE_RECHERCHE IS NULL)
  OR D.DATE_NAISSANCE <> S.DATE_NAISSANCE OR (D.DATE_NAISSANCE IS NULL AND S.DATE_NAISSANCE IS NOT NULL) OR (D.DATE_NAISSANCE IS NOT NULL AND S.DATE_NAISSANCE IS NULL)
  OR D.DEP_NAISSANCE_ID <> S.DEP_NAISSANCE_ID OR (D.DEP_NAISSANCE_ID IS NULL AND S.DEP_NAISSANCE_ID IS NOT NULL) OR (D.DEP_NAISSANCE_ID IS NOT NULL AND S.DEP_NAISSANCE_ID IS NULL)
  OR D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL)
  OR D.EMAIL <> S.EMAIL OR (D.EMAIL IS NULL AND S.EMAIL IS NOT NULL) OR (D.EMAIL IS NOT NULL AND S.EMAIL IS NULL)
  OR D.GRADE_ID <> S.GRADE_ID OR (D.GRADE_ID IS NULL AND S.GRADE_ID IS NOT NULL) OR (D.GRADE_ID IS NOT NULL AND S.GRADE_ID IS NULL)
  OR D.IBAN <> S.IBAN OR (D.IBAN IS NULL AND S.IBAN IS NOT NULL) OR (D.IBAN IS NOT NULL AND S.IBAN IS NULL)
  OR D.NOM_PATRONYMIQUE <> S.NOM_PATRONYMIQUE OR (D.NOM_PATRONYMIQUE IS NULL AND S.NOM_PATRONYMIQUE IS NOT NULL) OR (D.NOM_PATRONYMIQUE IS NOT NULL AND S.NOM_PATRONYMIQUE IS NULL)
  OR D.NOM_USUEL <> S.NOM_USUEL OR (D.NOM_USUEL IS NULL AND S.NOM_USUEL IS NOT NULL) OR (D.NOM_USUEL IS NOT NULL AND S.NOM_USUEL IS NULL)
  OR D.NUMERO_INSEE <> S.NUMERO_INSEE OR (D.NUMERO_INSEE IS NULL AND S.NUMERO_INSEE IS NOT NULL) OR (D.NUMERO_INSEE IS NOT NULL AND S.NUMERO_INSEE IS NULL)
  OR D.NUMERO_INSEE_CLE <> S.NUMERO_INSEE_CLE OR (D.NUMERO_INSEE_CLE IS NULL AND S.NUMERO_INSEE_CLE IS NOT NULL) OR (D.NUMERO_INSEE_CLE IS NOT NULL AND S.NUMERO_INSEE_CLE IS NULL)
  OR D.NUMERO_INSEE_PROVISOIRE <> S.NUMERO_INSEE_PROVISOIRE OR (D.NUMERO_INSEE_PROVISOIRE IS NULL AND S.NUMERO_INSEE_PROVISOIRE IS NOT NULL) OR (D.NUMERO_INSEE_PROVISOIRE IS NOT NULL AND S.NUMERO_INSEE_PROVISOIRE IS NULL)
  OR D.PAYS_NAISSANCE_ID <> S.PAYS_NAISSANCE_ID OR (D.PAYS_NAISSANCE_ID IS NULL AND S.PAYS_NAISSANCE_ID IS NOT NULL) OR (D.PAYS_NAISSANCE_ID IS NOT NULL AND S.PAYS_NAISSANCE_ID IS NULL)
  OR D.PAYS_NATIONALITE_ID <> S.PAYS_NATIONALITE_ID OR (D.PAYS_NATIONALITE_ID IS NULL AND S.PAYS_NATIONALITE_ID IS NOT NULL) OR (D.PAYS_NATIONALITE_ID IS NOT NULL AND S.PAYS_NATIONALITE_ID IS NULL)
  OR D.PRENOM <> S.PRENOM OR (D.PRENOM IS NULL AND S.PRENOM IS NOT NULL) OR (D.PRENOM IS NOT NULL AND S.PRENOM IS NULL)
  OR D.STATUT_ID <> S.STATUT_ID OR (D.STATUT_ID IS NULL AND S.STATUT_ID IS NOT NULL) OR (D.STATUT_ID IS NOT NULL AND S.STATUT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.TEL_MOBILE <> S.TEL_MOBILE OR (D.TEL_MOBILE IS NULL AND S.TEL_MOBILE IS NOT NULL) OR (D.TEL_MOBILE IS NOT NULL AND S.TEL_MOBILE IS NULL)
  OR D.TEL_PRO <> S.TEL_PRO OR (D.TEL_PRO IS NULL AND S.TEL_PRO IS NOT NULL) OR (D.TEL_PRO IS NOT NULL AND S.TEL_PRO IS NULL)
  OR D.UTILISATEUR_CODE <> S.UTILISATEUR_CODE OR (D.UTILISATEUR_CODE IS NULL AND S.UTILISATEUR_CODE IS NOT NULL) OR (D.UTILISATEUR_CODE IS NOT NULL AND S.UTILISATEUR_CODE IS NULL)
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
  D.id id,
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
--V_DIFF_GRADE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_GRADE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CORPS_ID", "ECHELLE", "LIBELLE_COURT", "LIBELLE_LONG", "U_CORPS_ID", "U_ECHELLE", "U_LIBELLE_COURT", "U_LIBELLE_LONG"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CORPS_ID",diff."ECHELLE",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_CORPS_ID",diff."U_ECHELLE",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CORPS_ID ELSE S.CORPS_ID END CORPS_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ECHELLE ELSE S.ECHELLE END ECHELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN D.CORPS_ID <> S.CORPS_ID OR (D.CORPS_ID IS NULL AND S.CORPS_ID IS NOT NULL) OR (D.CORPS_ID IS NOT NULL AND S.CORPS_ID IS NULL) THEN 1 ELSE 0 END U_CORPS_ID,
    CASE WHEN D.ECHELLE <> S.ECHELLE OR (D.ECHELLE IS NULL AND S.ECHELLE IS NOT NULL) OR (D.ECHELLE IS NOT NULL AND S.ECHELLE IS NULL) THEN 1 ELSE 0 END U_ECHELLE,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG
FROM
  GRADE D
  FULL JOIN SRC_GRADE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CORPS_ID <> S.CORPS_ID OR (D.CORPS_ID IS NULL AND S.CORPS_ID IS NOT NULL) OR (D.CORPS_ID IS NOT NULL AND S.CORPS_ID IS NULL)
  OR D.ECHELLE <> S.ECHELLE OR (D.ECHELLE IS NULL AND S.ECHELLE IS NOT NULL) OR (D.ECHELLE IS NOT NULL AND S.ECHELLE IS NULL)
  OR D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL)
  OR D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ETAPE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ETAPE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "CODE", "DOMAINE_FONCTIONNEL_ID", "LIBELLE", "NIVEAU", "SPECIFIQUE_ECHANGES", "STRUCTURE_ID", "TYPE_FORMATION_ID", "U_ANNEE_ID", "U_CODE", "U_DOMAINE_FONCTIONNEL_ID", "U_LIBELLE", "U_NIVEAU", "U_SPECIFIQUE_ECHANGES", "U_STRUCTURE_ID", "U_TYPE_FORMATION_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."CODE",diff."DOMAINE_FONCTIONNEL_ID",diff."LIBELLE",diff."NIVEAU",diff."SPECIFIQUE_ECHANGES",diff."STRUCTURE_ID",diff."TYPE_FORMATION_ID",diff."U_ANNEE_ID",diff."U_CODE",diff."U_DOMAINE_FONCTIONNEL_ID",diff."U_LIBELLE",diff."U_NIVEAU",diff."U_SPECIFIQUE_ECHANGES",diff."U_STRUCTURE_ID",diff."U_TYPE_FORMATION_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE ELSE S.CODE END CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DOMAINE_FONCTIONNEL_ID ELSE S.DOMAINE_FONCTIONNEL_ID END DOMAINE_FONCTIONNEL_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NIVEAU ELSE S.NIVEAU END NIVEAU,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.SPECIFIQUE_ECHANGES ELSE S.SPECIFIQUE_ECHANGES END SPECIFIQUE_ECHANGES,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_FORMATION_ID ELSE S.TYPE_FORMATION_ID END TYPE_FORMATION_ID,
    CASE WHEN D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL) THEN 1 ELSE 0 END U_ANNEE_ID,
    CASE WHEN D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL) THEN 1 ELSE 0 END U_CODE,
    CASE WHEN D.DOMAINE_FONCTIONNEL_ID <> S.DOMAINE_FONCTIONNEL_ID OR (D.DOMAINE_FONCTIONNEL_ID IS NULL AND S.DOMAINE_FONCTIONNEL_ID IS NOT NULL) OR (D.DOMAINE_FONCTIONNEL_ID IS NOT NULL AND S.DOMAINE_FONCTIONNEL_ID IS NULL) THEN 1 ELSE 0 END U_DOMAINE_FONCTIONNEL_ID,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.NIVEAU <> S.NIVEAU OR (D.NIVEAU IS NULL AND S.NIVEAU IS NOT NULL) OR (D.NIVEAU IS NOT NULL AND S.NIVEAU IS NULL) THEN 1 ELSE 0 END U_NIVEAU,
    CASE WHEN D.SPECIFIQUE_ECHANGES <> S.SPECIFIQUE_ECHANGES OR (D.SPECIFIQUE_ECHANGES IS NULL AND S.SPECIFIQUE_ECHANGES IS NOT NULL) OR (D.SPECIFIQUE_ECHANGES IS NOT NULL AND S.SPECIFIQUE_ECHANGES IS NULL) THEN 1 ELSE 0 END U_SPECIFIQUE_ECHANGES,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.TYPE_FORMATION_ID <> S.TYPE_FORMATION_ID OR (D.TYPE_FORMATION_ID IS NULL AND S.TYPE_FORMATION_ID IS NOT NULL) OR (D.TYPE_FORMATION_ID IS NOT NULL AND S.TYPE_FORMATION_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_FORMATION_ID
FROM
  ETAPE D
  FULL JOIN SRC_ETAPE S ON S.source_id = D.source_id AND S.source_code = D.source_code AND S.ANNEE_ID = d.ANNEE_ID
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ANNEE_ID <> S.ANNEE_ID OR (D.ANNEE_ID IS NULL AND S.ANNEE_ID IS NOT NULL) OR (D.ANNEE_ID IS NOT NULL AND S.ANNEE_ID IS NULL)
  OR D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL)
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
  D.id id,
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
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
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
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ANNEE_ID", "CODE", "DISCIPLINE_ID", "ETAPE_ID", "FA", "FC", "FI", "LIBELLE", "PERIODE_ID", "STRUCTURE_ID", "TAUX_FA", "TAUX_FC", "TAUX_FI", "TAUX_FOAD", "U_ANNEE_ID", "U_CODE", "U_DISCIPLINE_ID", "U_ETAPE_ID", "U_FA", "U_FC", "U_FI", "U_LIBELLE", "U_PERIODE_ID", "U_STRUCTURE_ID", "U_TAUX_FA", "U_TAUX_FC", "U_TAUX_FI", "U_TAUX_FOAD"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ANNEE_ID",diff."CODE",diff."DISCIPLINE_ID",diff."ETAPE_ID",diff."FA",diff."FC",diff."FI",diff."LIBELLE",diff."PERIODE_ID",diff."STRUCTURE_ID",diff."TAUX_FA",diff."TAUX_FC",diff."TAUX_FI",diff."TAUX_FOAD",diff."U_ANNEE_ID",diff."U_CODE",diff."U_DISCIPLINE_ID",diff."U_ETAPE_ID",diff."U_FA",diff."U_FC",diff."U_FI",diff."U_LIBELLE",diff."U_PERIODE_ID",diff."U_STRUCTURE_ID",diff."U_TAUX_FA",diff."U_TAUX_FC",diff."U_TAUX_FI",diff."U_TAUX_FOAD" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ANNEE_ID ELSE S.ANNEE_ID END ANNEE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE ELSE S.CODE END CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.DISCIPLINE_ID ELSE S.DISCIPLINE_ID END DISCIPLINE_ID,
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
    CASE WHEN D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL) THEN 1 ELSE 0 END U_CODE,
    CASE WHEN D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL) THEN 1 ELSE 0 END U_DISCIPLINE_ID,
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
  OR D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL)
  OR D.DISCIPLINE_ID <> S.DISCIPLINE_ID OR (D.DISCIPLINE_ID IS NULL AND S.DISCIPLINE_ID IS NOT NULL) OR (D.DISCIPLINE_ID IS NOT NULL AND S.DISCIPLINE_ID IS NULL)
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
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND d.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
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
  D.id id,
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
--Modifié VIEW
--V_DIFF_DEPARTEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_DEPARTEMENT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE", "LIBELLE_COURT", "LIBELLE_LONG", "U_CODE", "U_LIBELLE_COURT", "U_LIBELLE_LONG"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE",diff."LIBELLE_COURT",diff."LIBELLE_LONG",diff."U_CODE",diff."U_LIBELLE_COURT",diff."U_LIBELLE_LONG" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE ELSE S.CODE END CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_COURT ELSE S.LIBELLE_COURT END LIBELLE_COURT,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE_LONG ELSE S.LIBELLE_LONG END LIBELLE_LONG,
    CASE WHEN D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL) THEN 1 ELSE 0 END U_CODE,
    CASE WHEN D.LIBELLE_COURT <> S.LIBELLE_COURT OR (D.LIBELLE_COURT IS NULL AND S.LIBELLE_COURT IS NOT NULL) OR (D.LIBELLE_COURT IS NOT NULL AND S.LIBELLE_COURT IS NULL) THEN 1 ELSE 0 END U_LIBELLE_COURT,
    CASE WHEN D.LIBELLE_LONG <> S.LIBELLE_LONG OR (D.LIBELLE_LONG IS NULL AND S.LIBELLE_LONG IS NOT NULL) OR (D.LIBELLE_LONG IS NOT NULL AND S.LIBELLE_LONG IS NULL) THEN 1 ELSE 0 END U_LIBELLE_LONG
FROM
  DEPARTEMENT D
  FULL JOIN SRC_DEPARTEMENT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL)
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
  D.id id,
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
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
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
--V_DIFF_CENTRE_COUT_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CENTRE_COUT_STRUCTURE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CENTRE_COUT_ID", "STRUCTURE_ID", "U_CENTRE_COUT_ID", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CENTRE_COUT_ID",diff."STRUCTURE_ID",diff."U_CENTRE_COUT_ID",diff."U_STRUCTURE_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CENTRE_COUT_ID ELSE S.CENTRE_COUT_ID END CENTRE_COUT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN D.CENTRE_COUT_ID <> S.CENTRE_COUT_ID OR (D.CENTRE_COUT_ID IS NULL AND S.CENTRE_COUT_ID IS NOT NULL) OR (D.CENTRE_COUT_ID IS NOT NULL AND S.CENTRE_COUT_ID IS NULL) THEN 1 ELSE 0 END U_CENTRE_COUT_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID
FROM
  CENTRE_COUT_STRUCTURE D
  FULL JOIN SRC_CENTRE_COUT_STRUCTURE S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.CENTRE_COUT_ID <> S.CENTRE_COUT_ID OR (D.CENTRE_COUT_ID IS NULL AND S.CENTRE_COUT_ID IS NOT NULL) OR (D.CENTRE_COUT_ID IS NOT NULL AND S.CENTRE_COUT_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_CENTRE_COUT" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ACTIVITE_ID", "CODE", "LIBELLE", "PARENT_ID", "TYPE_RESSOURCE_ID", "UNITE_BUDGETAIRE", "U_ACTIVITE_ID", "U_CODE", "U_LIBELLE", "U_PARENT_ID", "U_TYPE_RESSOURCE_ID", "U_UNITE_BUDGETAIRE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ACTIVITE_ID",diff."CODE",diff."LIBELLE",diff."PARENT_ID",diff."TYPE_RESSOURCE_ID",diff."UNITE_BUDGETAIRE",diff."U_ACTIVITE_ID",diff."U_CODE",diff."U_LIBELLE",diff."U_PARENT_ID",diff."U_TYPE_RESSOURCE_ID",diff."U_UNITE_BUDGETAIRE" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ACTIVITE_ID ELSE S.ACTIVITE_ID END ACTIVITE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE ELSE S.CODE END CODE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LIBELLE ELSE S.LIBELLE END LIBELLE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PARENT_ID ELSE S.PARENT_ID END PARENT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.TYPE_RESSOURCE_ID ELSE S.TYPE_RESSOURCE_ID END TYPE_RESSOURCE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.UNITE_BUDGETAIRE ELSE S.UNITE_BUDGETAIRE END UNITE_BUDGETAIRE,
    CASE WHEN D.ACTIVITE_ID <> S.ACTIVITE_ID OR (D.ACTIVITE_ID IS NULL AND S.ACTIVITE_ID IS NOT NULL) OR (D.ACTIVITE_ID IS NOT NULL AND S.ACTIVITE_ID IS NULL) THEN 1 ELSE 0 END U_ACTIVITE_ID,
    CASE WHEN D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL) THEN 1 ELSE 0 END U_CODE,
    CASE WHEN D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL) THEN 1 ELSE 0 END U_LIBELLE,
    CASE WHEN D.PARENT_ID <> S.PARENT_ID OR (D.PARENT_ID IS NULL AND S.PARENT_ID IS NOT NULL) OR (D.PARENT_ID IS NOT NULL AND S.PARENT_ID IS NULL) THEN 1 ELSE 0 END U_PARENT_ID,
    CASE WHEN D.TYPE_RESSOURCE_ID <> S.TYPE_RESSOURCE_ID OR (D.TYPE_RESSOURCE_ID IS NULL AND S.TYPE_RESSOURCE_ID IS NOT NULL) OR (D.TYPE_RESSOURCE_ID IS NOT NULL AND S.TYPE_RESSOURCE_ID IS NULL) THEN 1 ELSE 0 END U_TYPE_RESSOURCE_ID,
    CASE WHEN D.UNITE_BUDGETAIRE <> S.UNITE_BUDGETAIRE OR (D.UNITE_BUDGETAIRE IS NULL AND S.UNITE_BUDGETAIRE IS NOT NULL) OR (D.UNITE_BUDGETAIRE IS NOT NULL AND S.UNITE_BUDGETAIRE IS NULL) THEN 1 ELSE 0 END U_UNITE_BUDGETAIRE
FROM
  CENTRE_COUT D
  FULL JOIN SRC_CENTRE_COUT S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ACTIVITE_ID <> S.ACTIVITE_ID OR (D.ACTIVITE_ID IS NULL AND S.ACTIVITE_ID IS NOT NULL) OR (D.ACTIVITE_ID IS NOT NULL AND S.ACTIVITE_ID IS NULL)
  OR D.CODE <> S.CODE OR (D.CODE IS NULL AND S.CODE IS NOT NULL) OR (D.CODE IS NOT NULL AND S.CODE IS NULL)
  OR D.LIBELLE <> S.LIBELLE OR (D.LIBELLE IS NULL AND S.LIBELLE IS NOT NULL) OR (D.LIBELLE IS NOT NULL AND S.LIBELLE IS NULL)
  OR D.PARENT_ID <> S.PARENT_ID OR (D.PARENT_ID IS NULL AND S.PARENT_ID IS NOT NULL) OR (D.PARENT_ID IS NOT NULL AND S.PARENT_ID IS NULL)
  OR D.TYPE_RESSOURCE_ID <> S.TYPE_RESSOURCE_ID OR (D.TYPE_RESSOURCE_ID IS NULL AND S.TYPE_RESSOURCE_ID IS NOT NULL) OR (D.TYPE_RESSOURCE_ID IS NOT NULL AND S.TYPE_RESSOURCE_ID IS NULL)
  OR D.UNITE_BUDGETAIRE <> S.UNITE_BUDGETAIRE OR (D.UNITE_BUDGETAIRE IS NULL AND S.UNITE_BUDGETAIRE IS NOT NULL) OR (D.UNITE_BUDGETAIRE IS NOT NULL AND S.UNITE_BUDGETAIRE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_AFFECTATION_RECHERCHE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_AFFECTATION_RECHERCHE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "INTERVENANT_ID", "STRUCTURE_ID", "U_INTERVENANT_ID", "U_STRUCTURE_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."INTERVENANT_ID",diff."STRUCTURE_ID",diff."U_INTERVENANT_ID",diff."U_STRUCTURE_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
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
--Modifié VIEW
--V_DIFF_AFFECTATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_AFFECTATION" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "ROLE_ID", "STRUCTURE_ID", "UTILISATEUR_ID", "U_ROLE_ID", "U_STRUCTURE_ID", "U_UTILISATEUR_ID"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."ROLE_ID",diff."STRUCTURE_ID",diff."UTILISATEUR_ID",diff."U_ROLE_ID",diff."U_STRUCTURE_ID",diff."U_UTILISATEUR_ID" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.ROLE_ID ELSE S.ROLE_ID END ROLE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.STRUCTURE_ID ELSE S.STRUCTURE_ID END STRUCTURE_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.UTILISATEUR_ID ELSE S.UTILISATEUR_ID END UTILISATEUR_ID,
    CASE WHEN D.ROLE_ID <> S.ROLE_ID OR (D.ROLE_ID IS NULL AND S.ROLE_ID IS NOT NULL) OR (D.ROLE_ID IS NOT NULL AND S.ROLE_ID IS NULL) THEN 1 ELSE 0 END U_ROLE_ID,
    CASE WHEN D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL) THEN 1 ELSE 0 END U_STRUCTURE_ID,
    CASE WHEN D.UTILISATEUR_ID <> S.UTILISATEUR_ID OR (D.UTILISATEUR_ID IS NULL AND S.UTILISATEUR_ID IS NOT NULL) OR (D.UTILISATEUR_ID IS NOT NULL AND S.UTILISATEUR_ID IS NULL) THEN 1 ELSE 0 END U_UTILISATEUR_ID
FROM
  AFFECTATION D
  FULL JOIN SRC_AFFECTATION S ON S.source_id = D.source_id AND S.source_code = D.source_code
WHERE
       (S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE)
    OR (S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE))
    OR (S.source_code IS NOT NULL AND D.source_code IS NULL)
    OR D.ROLE_ID <> S.ROLE_ID OR (D.ROLE_ID IS NULL AND S.ROLE_ID IS NOT NULL) OR (D.ROLE_ID IS NOT NULL AND S.ROLE_ID IS NULL)
  OR D.STRUCTURE_ID <> S.STRUCTURE_ID OR (D.STRUCTURE_ID IS NULL AND S.STRUCTURE_ID IS NOT NULL) OR (D.STRUCTURE_ID IS NOT NULL AND S.STRUCTURE_ID IS NULL)
  OR D.UTILISATEUR_ID <> S.UTILISATEUR_ID OR (D.UTILISATEUR_ID IS NULL AND S.UTILISATEUR_ID IS NOT NULL) OR (D.UTILISATEUR_ID IS NOT NULL AND S.UTILISATEUR_ID IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--V_DIFF_ADRESSE_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."V_DIFF_ADRESSE_STRUCTURE" 
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE_POSTAL", "LOCALITE", "NOM_VOIE", "NO_VOIE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "PRINCIPALE", "STRUCTURE_ID", "TELEPHONE", "VILLE", "U_CODE_POSTAL", "U_LOCALITE", "U_NOM_VOIE", "U_NO_VOIE", "U_PAYS_CODE_INSEE", "U_PAYS_LIBELLE", "U_PRINCIPALE", "U_STRUCTURE_ID", "U_TELEPHONE", "U_VILLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE_POSTAL",diff."LOCALITE",diff."NOM_VOIE",diff."NO_VOIE",diff."PAYS_CODE_INSEE",diff."PAYS_LIBELLE",diff."PRINCIPALE",diff."STRUCTURE_ID",diff."TELEPHONE",diff."VILLE",diff."U_CODE_POSTAL",diff."U_LOCALITE",diff."U_NOM_VOIE",diff."U_NO_VOIE",diff."U_PAYS_CODE_INSEE",diff."U_PAYS_LIBELLE",diff."U_PRINCIPALE",diff."U_STRUCTURE_ID",diff."U_TELEPHONE",diff."U_VILLE" from (SELECT
  D.id id,
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
 ( "ID", "SOURCE_ID", "SOURCE_CODE", "IMPORT_ACTION", "CODE_POSTAL", "INTERVENANT_ID", "LOCALITE", "MENTION_COMPLEMENTAIRE", "NOM_VOIE", "NO_VOIE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "TEL_DOMICILE", "VILLE", "U_CODE_POSTAL", "U_INTERVENANT_ID", "U_LOCALITE", "U_MENTION_COMPLEMENTAIRE", "U_NOM_VOIE", "U_NO_VOIE", "U_PAYS_CODE_INSEE", "U_PAYS_LIBELLE", "U_TEL_DOMICILE", "U_VILLE"
  )  AS 
  select diff."ID",diff."SOURCE_ID",diff."SOURCE_CODE",diff."IMPORT_ACTION",diff."CODE_POSTAL",diff."INTERVENANT_ID",diff."LOCALITE",diff."MENTION_COMPLEMENTAIRE",diff."NOM_VOIE",diff."NO_VOIE",diff."PAYS_CODE_INSEE",diff."PAYS_LIBELLE",diff."TEL_DOMICILE",diff."VILLE",diff."U_CODE_POSTAL",diff."U_INTERVENANT_ID",diff."U_LOCALITE",diff."U_MENTION_COMPLEMENTAIRE",diff."U_NOM_VOIE",diff."U_NO_VOIE",diff."U_PAYS_CODE_INSEE",diff."U_PAYS_LIBELLE",diff."U_TEL_DOMICILE",diff."U_VILLE" from (SELECT
  D.id id,
  COALESCE( S.source_id, D.source_id ) source_id,
  COALESCE( S.source_code, D.source_code ) source_code,
CASE
    WHEN S.source_code IS NOT NULL AND D.source_code IS NULL THEN 'insert'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) THEN 'update'
    WHEN S.source_code IS NULL AND D.source_code IS NOT NULL AND (D.histo_destruction IS NULL OR D.histo_destruction > SYSDATE) AND rt.ANNEE_ID >= UNICAEN_IMPORT.get_current_annee THEN 'delete'
    WHEN S.source_code IS NOT NULL AND D.source_code IS NOT NULL AND D.histo_destruction IS NOT NULL AND D.histo_destruction <= SYSDATE THEN 'undelete' END import_action,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.CODE_POSTAL ELSE S.CODE_POSTAL END CODE_POSTAL,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.INTERVENANT_ID ELSE S.INTERVENANT_ID END INTERVENANT_ID,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.LOCALITE ELSE S.LOCALITE END LOCALITE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.MENTION_COMPLEMENTAIRE ELSE S.MENTION_COMPLEMENTAIRE END MENTION_COMPLEMENTAIRE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NOM_VOIE ELSE S.NOM_VOIE END NOM_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.NO_VOIE ELSE S.NO_VOIE END NO_VOIE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_CODE_INSEE ELSE S.PAYS_CODE_INSEE END PAYS_CODE_INSEE,
    CASE WHEN S.source_code IS NULL AND D.source_code IS NOT NULL THEN D.PAYS_LIBELLE ELSE S.PAYS_LIBELLE END PAYS_LIBELLE,
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
  OR D.TEL_DOMICILE <> S.TEL_DOMICILE OR (D.TEL_DOMICILE IS NULL AND S.TEL_DOMICILE IS NOT NULL) OR (D.TEL_DOMICILE IS NOT NULL AND S.TEL_DOMICILE IS NULL)
  OR D.VILLE <> S.VILLE OR (D.VILLE IS NULL AND S.VILLE IS NOT NULL) OR (D.VILLE IS NOT NULL AND S.VILLE IS NULL)
) diff JOIN source on source.id = diff.source_id WHERE import_action IS NOT NULL AND source.importable = 1;
---------------------------
--Modifié VIEW
--SRC_VOLUME_HORAIRE_ENS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_VOLUME_HORAIRE_ENS" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID", "HEURES", "GROUPES", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH apogee_fca_query AS (
  SELECT
    vhe.z_element_pedagogique_id            z_element_pedagogique_id,
    CASE vhe.z_type_intervention_id
      WHEN 'MEMOIR' THEN 'Mémoire'
      WHEN 'STAGE'  THEN 'Stage'
      WHEN 'PROJET' THEN 'Projet'
    ELSE
      vhe.z_type_intervention_id
    END                                     z_type_intervention_id,
    vhe.heures                              heures,
    vhe.groupes                             groupes,
    'Apogee'                                z_source_id,
    vhe.annee_id || '_' || vhe.source_code  source_code,
    TO_NUMBER(vhe.annee_id)                 annee_id
  FROM 
    ose_volume_horaire_ens@apoprod vhe
    
  UNION
  
  SELECT
    vhe.z_element_pedagogique_id            z_element_pedagogique_id,
    vhe.z_type_intervention_id              z_type_intervention_id,
    vhe.heures                              heures,
    1                                       groupes,
    'FCAManager'                            z_source_id,
    TO_CHAR(vhe.source_code)                source_code,
    TO_NUMBER(vhe.annee_id)                 annee_id
  FROM 
    fca.ose_volume_horaire_ens@fcaprod vhe
)
SELECT
  ep.id           element_pedagogique_id,
  ti.id           type_intervention_id,
  afq.heures      heures,
  afq.groupes     groupes,
  s.id            source_id,
  afq.source_code source_code
FROM 
            apogee_fca_query   afq
       JOIN source               s ON s.code         = afq.z_source_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = afq.z_element_pedagogique_id 
                                  AND ep.annee_id    = afq.annee_id
  LEFT JOIN type_intervention   ti ON ti.code        = afq.z_type_intervention_id;
---------------------------
--Modifié VIEW
--SRC_TYPE_MODULATEUR_EP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_TYPE_MODULATEUR_EP" 
 ( "TYPE_MODULATEUR_ID", "ELEMENT_PEDAGOGIQUE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  tm.id                               type_modulateur_id,
  ep.id                               element_pedagogique_id,
  src.id                              source_id,
  tm.code || '_' || ep.source_code || '_' || ep.annee_id  source_code
FROM
  element_pedagogique             ep
  JOIN type_modulateur            tm ON tm.histo_destruction IS NULL
  JOIN structure                   s ON s.id = ep.structure_id
  JOIN type_modulateur_structure tms ON tms.type_modulateur_id = tm.id
                                    AND tms.structure_id = s.id
                                    AND tms.histo_destruction IS NULL
                                    AND ep.annee_id BETWEEN COALESCE( tms.annee_debut_id, 1 ) AND COALESCE( tms.annee_fin_id, 999999 )
  JOIN source                    src ON src.code = 'Calcul'
WHERE
  ep.histo_destruction IS NULL
  AND ep.taux_fc > 0;
---------------------------
--Modifié VIEW
--SRC_TYPE_INTERVENTION_EP
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_TYPE_INTERVENTION_EP" 
 ( "TYPE_INTERVENTION_ID", "ELEMENT_PEDAGOGIQUE_ID", "SOURCE_CODE", "SOURCE_ID"
  )  AS 
  WITH t AS (
SELECT
  ti.id                                                   type_intervention_id,
  ti.code                                                 type_intervention_code,
  ep.id                                                   element_pedagogique_id,
  ep.annee_id                                             annee_id,
  ti.code || '_' || ep.source_code || '_' || ep.annee_id  source_code,
  COALESCE(vhe.heures,0)                                  heures,
  SUM(COALESCE(vhe.heures,0)) OVER (PARTITION BY ep.id)   total_heures
FROM
            element_pedagogique              ep

       JOIN type_intervention                ti ON ep.annee_id BETWEEN COALESCE(ti.annee_debut_id,ep.annee_id) AND COALESCE(ti.annee_fin_id, ep.annee_id)
                                               AND ti.histo_destruction IS NULL

  LEFT JOIN type_intervention_structure     tis ON tis.type_intervention_id = ti.id
                                               AND tis.structure_id = ep.structure_id
                                               AND ep.annee_id BETWEEN COALESCE(tis.annee_debut_id,ep.annee_id) AND COALESCE(tis.annee_fin_id, ep.annee_id)
                                               AND tis.histo_destruction IS NULL

  LEFT JOIN volume_horaire_ens              vhe ON vhe.element_pedagogique_id = ep.id
                                               AND vhe.type_intervention_id = COALESCE(ti.type_intervention_maquette_id, ti.id)
                                               AND vhe.histo_destruction IS NULL
WHERE
  ep.histo_destruction IS NULL
  AND COALESCE( tis.visible, ti.visible ) = 1
  AND (ti.regle_foad = 0 OR ep.taux_foad > 0)
  AND (ti.regle_fc = 0 OR ep.taux_fc > 0)
)
SELECT
  t.type_intervention_id    type_intervention_id,
  t.element_pedagogique_id  element_pedagogique_id,
  t.source_code             source_code,
  src.id                    source_id
FROM
  t
  JOIN source src ON src.code = 'Calcul'
WHERE
  heures > 0  --Soit il y a des heures de prévues
  OR total_heures = 0 -- soit on autorise tout
  OR annee_id < 2017 -- règle ne s'appliquant pas avant!;
---------------------------
--Modifié VIEW
--SRC_TYPE_FORMATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_TYPE_FORMATION" 
 ( "LIBELLE_LONG", "LIBELLE_COURT", "GROUPE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  tf.libelle_long   libelle_long,
  tf.libelle_court  libelle_court,
  gtf.id            groupe_id,
  s.id              source_id,
  tf.source_code    source_code
FROM
            ose_type_formation@apoprod tf
       JOIN source                      s ON s.code = 'Apogee'
  LEFT JOIN groupe_type_formation     gtf ON gtf.source_code = tf.z_groupe_id;
---------------------------
--Modifié VIEW
--SRC_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_STRUCTURE" 
 ( "CODE", "LIBELLE_COURT", "LIBELLE_LONG", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH harpege_query AS (
  SELECT
    str.c_structure  code,
    str.lc_structure libelle_court,
    str.ll_structure libelle_long,
    'Harpege'        z_source_id,
    str.c_structure  source_code
  FROM
    structure@harpprod str
  WHERE
    SYSDATE BETWEEN str.date_ouverture AND COALESCE( str.date_fermeture, SYSDATE )
    AND (str.c_structure = 'UNIV' OR str.c_structure_pere = 'UNIV')
)
SELECT
  hq.code          code,
  hq.libelle_court libelle_court,
  hq.libelle_long  libelle_long,
  src.id           source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source       src ON src.code = hq.z_source_id;
---------------------------
--Modifié VIEW
--SRC_SCENARIO_LIEN
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_SCENARIO_LIEN" 
 ( "SCENARIO_ID", "LIEN_ID", "ACTIF", "POIDS", "CHOIX_MINIMUM", "CHOIX_MAXIMUM", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  s.id                            scenario_id,
  li.id                           lien_id,
  1                               actif,
  1                               poids,
  l.choix_minimum                 choix_minimum,
  l.choix_maximum                 choix_maximum,
  src.id                          source_id,
  l.z_source_code || '_s' || s.id source_code
FROM
            ose_lien@apoprod l
       JOIN source         src ON src.code             = 'Apogee'
       JOIN scenario         s ON s.histo_destruction  IS NULL
       JOIN lien            li ON li.source_code       = l.z_source_code
  LEFT JOIN scenario_lien   sl ON sl.lien_id           = li.id 
                              AND sl.scenario_id       = s.id
                              AND sl.histo_destruction IS NULL
                              AND sl.source_id         <> src.id
WHERE
  (l.choix_minimum IS NOT NULL OR l.choix_maximum IS NOT NULL)
  AND sl.id IS NULL;
---------------------------
--Modifié VIEW
--SRC_PAYS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_PAYS" 
 ( "LIBELLE_LONG", "LIBELLE_COURT", "VALIDITE_DEBUT", "VALIDITE_FIN", "TEMOIN_UE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  ll_pays                                                 libelle_long,
  coalesce(lc_pays,ll_pays)                               libelle_court,
  coalesce(d_deb_val, TO_DATE('1900/01/01','YYYY/MM/DD')) validite_debut,
  d_fin_val                                               validite_fin,
  decode(tem_ue, 'O', 1, 0)                               temoin_ue,
  s.id                                                    source_id,
  c_pays                                                  source_code
FROM
  pays@harpprod p
  JOIN source s ON s.code = 'Harpege';
---------------------------
--Modifié VIEW
--SRC_NOEUD
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_NOEUD" 
 ( "CODE", "LIBELLE", "LISTE", "ANNEE_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "STRUCTURE_ID", "STR", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  n.code                code,
  n.libelle_court       libelle,
  n.liste               liste,
  TO_NUMBER(n.annee_id) annee_id,
  e.id                  etape_id,
  ep.id                 element_pedagogique_id,
  str.id                structure_id,
  s.id                  source_id,
  n.z_source_code       source_code
FROM 
            ose_noeud@apoprod           n
       JOIN source                      s ON s.code          = 'Apogee'
  LEFT JOIN etape                       e ON e.source_code   = n.z_etape_id 
                                         AND e.annee_id      = n.annee_id
  LEFT JOIN element_pedagogique        ep ON ep.source_code  = n.z_element_pedagogique_id 
                                         AND ep.annee_id     = n.annee_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure  = n.z_structure_id
  LEFT JOIN structure                 str ON str.source_code = sc.c_structure_n2;
---------------------------
--Modifié VIEW
--SRC_LIEN
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_LIEN" 
 ( "NOEUD_SUP_ID", "NOEUD_INF_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  nsup.id         noeud_sup_id,
  ninf.id         noeud_inf_id,
  str.id          structure_id,
  s.id            source_id,
  l.z_source_code source_code
FROM
            ose_lien@apoprod            l
       JOIN source                      s ON s.code = 'Apogee'
       JOIN noeud                    nsup ON nsup.source_code = l.noeud_sup_id 
                                         AND nsup.annee_id = TO_NUMBER(l.annee_id)
       JOIN noeud                    ninf ON ninf.source_code = l.noeud_inf_id 
                                         AND ninf.annee_id = TO_NUMBER(l.annee_id)
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = l.z_structure_id
  LEFT JOIN structure                 str ON str.source_code = sc.c_structure_n2;
---------------------------
--Modifié VIEW
--SRC_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_INTERVENANT" 
 ( "CODE", "UTILISATEUR_CODE", "CIVILITE_ID", "NOM_USUEL", "PRENOM", "NOM_PATRONYMIQUE", "DATE_NAISSANCE", "PAYS_NAISSANCE_ID", "DEP_NAISSANCE_ID", "VILLE_NAISSANCE_CODE_INSEE", "VILLE_NAISSANCE_LIBELLE", "PAYS_NATIONALITE_ID", "TEL_PRO", "TEL_MOBILE", "EMAIL", "STATUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "NUMERO_INSEE", "NUMERO_INSEE_CLE", "NUMERO_INSEE_PROVISOIRE", "IBAN", "BIC", "GRADE_ID", "DISCIPLINE_ID", "ANNEE_ID", "CRITERE_RECHERCHE"
  )  AS 
  WITH srci as (
SELECT
  i.code,
  c.id civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  COALESCE(i.date_naissance,TO_DATE('2099-01-01','YYYY-MM-DD')) date_naissance,
  pnaiss.id pays_naissance_id,
  dep.id dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  pnat.id pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  si.id statut_id, si.source_code statut_code,
  s.id structure_id,
  src.id source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  g.id grade_id,
  NVL( d.id, d99.id ) discipline_id,
  i.critere_recherche,
  COALESCE (si.ordre,99999) ordre,
  MIN(COALESCE (si.ordre,99999)) OVER (PARTITION BY i.source_code) min_ordre
FROM
            mv_intervenant i
       JOIN source        src ON src.code = 'Harpege'
  LEFT JOIN civilite        c ON c.libelle_court = i.z_civilite_id
  LEFT JOIN structure       s ON s.source_code = i.z_structure_id
  LEFT JOIN statut_intervenant si ON si.source_code = i.z_statut_id
  LEFT JOIN grade           g ON g.source_code = i.z_grade_id
  LEFT JOIN pays       pnaiss ON pnaiss.source_code = i.z_pays_naissance_id  
  LEFT JOIN pays         pnat ON pnat.source_code = i.z_pays_nationalite_id
  LEFT JOIN departement   dep ON dep.source_code = i.z_dep_naissance_id
  LEFT JOIN discipline d99 ON d99.source_code = '99'
  LEFT JOIN discipline d ON
    d.histo_destruction IS NULL
    AND 1 = CASE WHEN -- si rien n'ac été défini
    
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
)
SELECT
  i.code code, lpad(i.code, 8, '0') utilisateur_code,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE( 
    isai.statut_id, 
    CASE WHEN i.statut_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE i.statut_id END
  ) statut_id,
  i. structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee
  LEFT JOIN intervenant_saisie  isai ON isai.intervenant_id = i2.id
  LEFT JOIN dossier               d  ON d.intervenant_id = i2.id AND d.histo_destruction IS NULL
WHERE
  i.ordre = i.min_ordre

UNION ALL

SELECT
  i.code code, lpad(i.code, 8, '0') utilisateur_code,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE(i2.statut_id,i.statut_id) statut_id,
  COALESCE(i2.structure_id,i.structure_id) structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee - 1 annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee - 1
WHERE
  i.ordre = i.min_ordre;
---------------------------
--Modifié VIEW
--SRC_GROUPE_TYPE_FORMATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_GROUPE_TYPE_FORMATION" 
 ( "LIBELLE_COURT", "LIBELLE_LONG", "ORDRE", "PERTINENCE_NIVEAU", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  gtf.libelle_court     libelle_court,
  gtf.libelle_long      libelle_long,
  gtf.ordre             ordre,
  gtf.pertinence_niveau pertinence_niveau,
  s.id                  source_id,
  gtf.source_code       source_code
FROM
  ose_groupe_type_formation@apoprod gtf
  JOIN source s ON s.code = 'Apogee';
---------------------------
--Modifié VIEW
--SRC_GRADE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_GRADE" 
 ( "LIBELLE_LONG", "LIBELLE_COURT", "SOURCE_ID", "SOURCE_CODE", "ECHELLE", "CORPS_ID"
  )  AS 
  WITH harpege_query AS (
  SELECT
    g.ll_grade  libelle_long,
    g.lc_grade  libelle_court,
    'Harpege'   z_source_id,
    g.c_grade   source_code,
    g.echelle   echelle,
    g.c_corps   z_corps_id
  FROM
    grade@harpprod g
  WHERE
    SYSDATE BETWEEN COALESCE(g.d_ouverture,SYSDATE) AND COALESCE(g.d_fermeture+1,SYSDATE)
)
SELECT
  hq.libelle_long   libelle_long,
  hq.libelle_court  libelle_court,
  s.id              source_id,
  hq.source_code    source_code,
  hq.echelle        echelle,
  c.id              corps_id
FROM
       harpege_query hq
  JOIN source         s ON s.code        = hq.z_source_id
  JOIN corps          c ON c.source_code = hq.z_corps_id;
---------------------------
--Modifié VIEW
--SRC_ETAPE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ETAPE" 
 ( "CODE", "LIBELLE", "ANNEE_ID", "TYPE_FORMATION_ID", "NIVEAU", "SPECIFIQUE_ECHANGES", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE", "DOMAINE_FONCTIONNEL_ID"
  )  AS 
  SELECT
  e.cod_etp || '_' || e.cod_vrs_vet   code,
  e.libelle                           libelle,
  to_number(e.annee_id)               annee_id,
  tf.id                               type_formation_id,
  to_number(e.niveau)                 niveau,
  e.specifique_echanges               specifique_echanges,
  s.id                                structure_id,
  src.id                              source_id,
  e.source_code                       source_code,
  df.id                               domaine_fonctionnel_id
FROM
            ose_etape@apoprod           e
       JOIN source                    src ON src.code       = 'Apogee'
  LEFT JOIN type_formation             tf ON tf.source_code = e.z_type_formation_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = e.z_structure_id
  LEFT JOIN structure                   s ON s.source_code  = sc.c_structure_n2
  LEFT JOIN domaine_fonctionnel        df ON df.source_code = e.domaine_fonctionnel

UNION

SELECT
  e.code                              code,
  e.libelle                           libelle,
  to_number(e.annee_id )              annee_id,
  tf.id                               type_formation_id,
  to_number(e.niveau)                 niveau,
  0                                   specifique_echanges,
  s.id                                structure_id,
  src.id                              source_id,
  e.source_code                       source_code,
  df.id                               domaine_fonctionnel_id
FROM
            fca.ose_etape@fcaprod       e
       JOIN source                    src ON src.code       = 'FCAManager'
  LEFT JOIN type_formation             tf ON tf.source_code = e.z_type_formation_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = e.z_structure_id
  LEFT JOIN structure                   s ON s.source_code  = sc.c_structure_n2
  LEFT JOIN domaine_fonctionnel        df ON df.source_code = e.z_domaine_fonctionnel_id;
---------------------------
--Modifié VIEW
--SRC_ETABLISSEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ETABLISSEMENT" 
 ( "LIBELLE", "LOCALISATION", "DEPARTEMENT", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH apogee_query AS (
  SELECT
    e.lib_off_etb libelle,
    e.lic_etb     localisation,
    e.cod_dep     departement,
    'Apogee'      z_source_id,
    e.cod_etb     source_code
  FROM
    etablissement@apoprod e
)
SELECT
  aq.libelle      libelle,
  aq.localisation localisation,
  aq.departement  departement,
  s.id            source_id,
  aq.source_code  source_code
FROM
       apogee_query aq
  JOIN source        s ON s.code = aq.z_source_id;
---------------------------
--Modifié VIEW
--SRC_ELEMENT_TAUX_REGIMES
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_TAUX_REGIMES" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "TAUX_FI", "TAUX_FC", "TAUX_FA", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH apogee_query AS (
  SELECT
    e.z_element_pedagogique_id  z_element_pedagogique_id,
    to_number(e.annee_id) + 1   annee_id,
    e.effectif_fi               effectif_fi,
    e.effectif_fc               effectif_fc,
    e.effectif_fa               effectif_fa,
    'Apogee'                    z_source_id,
    TO_NUMBER(e.annee_id) + 1 || '-' || e.z_element_pedagogique_id source_code
  FROM
    ose_element_effectifs@apoprod e
  WHERE
    (e.effectif_fi + e.effectif_fc + e.effectif_fa) > 0
)
SELECT
  ep.id           element_pedagogique_id,
  aq.annee_id     annee_id,
  OSE_DIVERS.CALCUL_TAUX_FI( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fi,
  OSE_DIVERS.CALCUL_TAUX_FC( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fc,
  OSE_DIVERS.CALCUL_TAUX_FA( aq.effectif_fi, aq.effectif_fc, aq.effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fa,
  s.id           source_id,
  aq.source_code source_code
FROM
       apogee_query aq
  JOIN source s ON s.code = aq.z_source_id
  JOIN ELEMENT_PEDAGOGIQUE ep ON ep.source_code = aq.z_element_pedagogique_id AND ep.annee_id = aq.annee_id
WHERE
  NOT EXISTS( -- on évite de remonter des données issus d'autres sources pour le pas risquer de les écraser!!
    SELECT * FROM element_taux_regimes aq_tbl WHERE
      aq_tbl.element_pedagogique_id = ep.id
      AND aq_tbl.source_id <> s.id
  );
---------------------------
--Modifié VIEW
--SRC_ELEMENT_PEDAGOGIQUE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ELEMENT_PEDAGOGIQUE" 
 ( "CODE", "LIBELLE", "ETAPE_ID", "STRUCTURE_ID", "PERIODE_ID", "TAUX_FI", "TAUX_FC", "TAUX_FA", "TAUX_FOAD", "FC", "FI", "FA", "SOURCE_ID", "SOURCE_CODE", "ANNEE_ID", "DISCIPLINE_ID"
  )  AS 
  WITH apogee_query AS (
  SELECT
    ep.source_code code,
    ep.libelle,
    ep.z_etape_id,
    ep.z_structure_id,
    ep.z_periode_id,
    CASE WHEN ep.fi+ep.fa+ep.fc=0 THEN 1 ELSE ep.fi END fi,
    ep.fc,
    ep.fa,
    ep.taux_foad,
    'Apogee' z_source_id,
    ep.source_code,
    TO_NUMBER(ep.annee_id) annee_id,
    ep.z_discipline_id
  FROM
    ose_element_pedagogique@apoprod ep
)
SELECT
  aq.code,
  aq.libelle,
  etp.id etape_id,
  str.id structure_id,
  per.id periode_id,
  CASE 
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fi( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fi,
  CASE 
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fc( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fc( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fc,
  CASE 
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fa( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fa( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fa,
  aq.taux_foad,
  aq.fc,
  aq.fi,
  aq.fa,
  s.id source_id,
  aq.source_code,
  aq.annee_id,
  NVL( d.id, d99.id ) discipline_id
FROM
            apogee_query aq
       JOIN source                      s ON s.code                     = aq.z_source_id
  LEFT JOIN etape                     etp ON etp.source_code            = aq.z_etape_id 
                                         AND etp.annee_id               = aq.annee_id
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure             = aq.z_structure_id
  LEFT JOIN structure                 str ON str.source_code            = sc.c_structure_n2
  LEFT JOIN periode                   per ON per.libelle_court          = aq.z_periode_id
  LEFT JOIN element_pedagogique        ep ON ep.source_code             = aq.source_code 
                                         AND ep.annee_id                = aq.annee_id
  LEFT JOIN element_taux_regimes      etr ON etr.element_pedagogique_id = ep.id
                                         AND etr.histo_destruction      IS NULL
  LEFT JOIN discipline                d99 ON d99.source_code            = '99'
  LEFT JOIN discipline                  d ON ',' || d.CODES_CORRESP_1 || ',' LIKE '%,' || NVL(aq.z_discipline_id,'00') || ',%'
                                         AND d.histo_destruction        IS NULL
    
UNION

SELECT
  ep.code,
  ep.libelle,
  etp.id etape_id,
  str.id structure_id,
  per.id periode_id,
  ep.taux_fi taux_fi,
  ep.taux_fc taux_fc,
  ep.taux_fa taux_fa,
  ep.taux_foad,
  ep.fc,
  ep.fi,
  ep.fa,
  s.id,
  ep.source_code,
  TO_NUMBER(ep.annee_id) annee_id,
  d99.id discipline_id
FROM
            FCA.OSE_element_pedagogique@fcaprod ep
       JOIN source                               s ON s.code            = 'FCAManager'
  LEFT JOIN etape                              etp ON etp.source_code   = ep.z_etape_id 
                                                  AND etp.annee_id      = ep.annee_id
  LEFT JOIN MV_UNICAEN_STRUCTURE_CODES          sc ON sc.c_structure    = ep.z_structure_id
  LEFT JOIN structure                          str ON str.source_code   = sc.c_structure_n2
  LEFT JOIN periode                            per ON per.libelle_court = ep.z_periode_id
  LEFT JOIN discipline                         d99 ON d99.source_code   = '99';
---------------------------
--Modifié VIEW
--SRC_EFFECTIFS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_EFFECTIFS" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "ANNEE_ID", "FI", "FC", "FA", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  ep.id                                           element_pedagogique_id,
  to_number(e.annee_id)                           annee_id,
  e.effectif_fi                                   fi,
  e.effectif_fc                                   fc,
  e.effectif_fa                                   fa,
  s.id                                            source_id,
  e.annee_id || '-' || e.z_element_pedagogique_id source_code
FROM
       ose_element_effectifs@apoprod e
  JOIN source                        s ON s.code = 'Apogee'
  LEFT JOIN element_pedagogique     ep ON ep.source_code = e.z_element_pedagogique_id 
                                      AND ep.annee_id = to_number(e.annee_id);
---------------------------
--Modifié VIEW
--SRC_DOMAINE_FONCTIONNEL
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_DOMAINE_FONCTIONNEL" 
 ( "LIBELLE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH sifac_query AS (
  SELECT
    B.fkbtx libelle,
    'SIFAC' z_source_id,
    A.fkber source_code
  FROM
    sapsr3.tfkb@sifacp A,
    sapsr3.tfkbt@sifacp B
  WHERE
    A.mandt=B.mandt
    AND A.fkber=B.fkber
    AND B.SPRAS='F'
    AND A.mandt='500'
    AND SYSDATE BETWEEN to_date( NVL(A.datab,'10661231'), 'YYYYMMDD') AND to_date( NVL(A.datbis,'99991231'), 'YYYYMMDD')
    AND a.fkber IN ('D101', 'D102', 'D103', 'D1053', 'D106', 'D107', 'D108', 'D109', 'D110', 'D111', 'D112', 'D1132', 'D1153')
)
SELECT
  sq.libelle     libelle,
  s.id           source_id,
  sq.source_code source_code
FROM
       sifac_query sq
  JOIN source       s ON s.code = sq.z_source_id;
---------------------------
--Modifié VIEW
--SRC_DEPARTEMENT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_DEPARTEMENT" 
 ( "CODE", "LIBELLE_LONG", "LIBELLE_COURT", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH harpege_query AS (
  SELECT
    c_departement  code,
    ll_departement libelle_long,
    lc_departement libelle_court,
    'Harpege'      z_source_id,
    c_departement  source_code
  FROM
    departement@harpprod d
)
SELECT
  hq.code          code,
  hq.libelle_long  libelle_long,
  hq.libelle_court libelle_court,
  s.id             source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source         s ON s.code = hq.z_source_id;
---------------------------
--Modifié VIEW
--SRC_CORPS
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CORPS" 
 ( "LIBELLE_LONG", "LIBELLE_COURT", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH harpege_query AS (
  SELECT
    c.ll_corps  libelle_long,
    c.lc_corps  libelle_court,
    'Harpege'   z_source_id,
    c.c_corps   source_code
  FROM
    corps@harpprod c
  WHERE
    SYSDATE BETWEEN COALESCE(c.d_ouverture_corps,SYSDATE) AND COALESCE(c.d_fermeture_corps+1,SYSDATE)
)
SELECT
  hq.libelle_long  libelle_long,
  hq.libelle_court libelle_court,
  s.id             source_id,
  hq.source_code   source_code
FROM
       harpege_query hq
  JOIN source         s ON s.code = hq.z_source_id;
---------------------------
--Modifié VIEW
--SRC_CHEMIN_PEDAGOGIQUE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CHEMIN_PEDAGOGIQUE" 
 ( "ELEMENT_PEDAGOGIQUE_ID", "ETAPE_ID", "ORDRE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  elp.id                                                               element_pedagogique_id,
  etp.id                                                               etape_id,
  ROW_NUMBER() OVER (PARTITION BY etp.id, aq.annee_id ORDER BY ROWNUM) ordre,
  s.id                                                                 source_id,
  aq.source_code || '_' || aq.annee_id                                 source_code
FROM
            ose_chemin_pedagogique@apoprod aq
       JOIN source                          s ON s.code = 'Apogee'
  LEFT JOIN element_pedagogique           elp ON elp.source_code = aq.z_element_pedagogique_id 
                                             AND elp.annee_id = TO_NUMBER(aq.annee_id)
  LEFT JOIN etape                         etp ON etp.source_code = aq.z_etape_id 
                                             AND etp.annee_id = TO_NUMBER(aq.annee_id)

UNION

SELECT
  elp.id                                                               element_pedagogique_id,
  etp.id                                                               etape_id,
  ROW_NUMBER() OVER (PARTITION BY etp.id, fq.annee_id ORDER BY ROWNUM) ordre,
  s.id                                                                 source_id,
  fq.source_code || '_' || fq.annee_id                                 source_code
FROM
            fca.ose_chemin_pedagogique@fcaprod fq
       JOIN source                              s ON s.code = 'FCAManager'
  LEFT JOIN element_pedagogique               elp ON elp.source_code = fq.z_element_pedagogique_id 
                                                 AND elp.annee_id = TO_NUMBER(fq.annee_id)
  LEFT JOIN etape                             etp ON etp.source_code = fq.z_etape_id 
                                                 AND etp.annee_id = TO_NUMBER(fq.annee_id);
---------------------------
--Modifié VIEW
--SRC_CENTRE_COUT_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CENTRE_COUT_STRUCTURE" 
 ( "CENTRE_COUT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH cc AS (

  SELECT
    cc.id id,
    cc.source_code source_code,
    cc.source_code ori_source_code
  FROM
    centre_cout cc
    LEFT JOIN centre_cout pcc ON pcc.id = cc.parent_id
  WHERE
    pcc.id IS NULL
    
  UNION ALL
  
  SELECT
    cc.id id,
    pcc.source_code source_code,
    cc.source_code ori_source_code
  FROM
    centre_cout cc
    JOIN centre_cout pcc ON pcc.id = cc.parent_id

)
SELECT
  cc.id centre_cout_id,
  s.id structure_id,
  (SELECT id FROM source WHERE code='Calcul') source_id,
  cc.ori_source_code || '_' || s.source_code source_code
FROM
  unicaen_corresp_structure_cc ucs
  JOIN cc ON substr( cc.source_code, 2, 3 ) = ucs.code_sifac
  JOIN structure s ON s.source_code = CASE 
    WHEN cc.source_code = 'P950DRRA' THEN 'ECODOCT'
    WHEN cc.source_code = 'P950FCFCR' THEN 'drh-formation'
    WHEN cc.source_code = 'P950FCFFR' THEN 'drh-formation'
    ELSE ucs.code_harpege 
  END;
---------------------------
--Modifié VIEW
--SRC_CENTRE_COUT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_CENTRE_COUT" 
 ( "CODE", "LIBELLE", "ACTIVITE_ID", "TYPE_RESSOURCE_ID", "UNITE_BUDGETAIRE", "POIDS", "PARENT_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH sifac_query AS (
  SELECT DISTINCT
    TRIM(B.ktext) libelle,
    CASE
      WHEN a.kostl like '%A' THEN 'accueil' -- Activité (au sens compta analytique) ne devant pas permettre la saisie de référentiel
      WHEN a.kostl like '%B' THEN 'enseignement'
      WHEN a.kostl like '%M' THEN 'pilotage'
    END z_activite_id,
    CASE
      WHEN LENGTH(a.kostl) = 5 THEN 'paie-etat'
      WHEN LENGTH(a.kostl) > 5 THEN 'ressources-propres'
    END z_type_ressource_id,
    substr( A.kostl, 2, 3 ) unite_budgetaire,
    NULL z_parent_id,
    'SIFAC' z_source_id,
    A.kostl source_code
  
  FROM
    sapsr3.csks@sifacp A,
    sapsr3.cskt@sifacp B
  WHERE
      A.kostl=B.kostl(+)
      and A.kokrs=B.kokrs(+)
      and B.mandt(+)='500'
      and B.spras(+)='F'
      and A.kokrs='1010'
      and A.bkzkp !='X'
      and a.kostl LIKE 'P%' AND (a.kostl like '%A' OR a.kostl like '%B' OR a.kostl like '%M')
      AND SYSDATE BETWEEN to_date( NVL(A.datab,'10661231'), 'YYYYMMDD') AND to_date( NVL(A.datbi,'99991231'), 'YYYYMMDD')
  
  UNION
  
  SELECT
    TRIM(A.post1) libelle,
    CASE
      WHEN a.fkstl like '%A' THEN 'accueil'
      WHEN a.fkstl like '%B' THEN 'enseignement'
      WHEN a.fkstl like '%M' THEN 'pilotage'
    END z_activite_id,
    CASE
      WHEN LENGTH(a.fkstl) = 5 THEN 'paie-etat'
      WHEN LENGTH(a.fkstl) > 5 THEN 'ressources-propres'
    END z_type_ressource_id,
    substr( A.fkstl, 2, 3 ) unite_budgetaire,
    A.fkstl z_parent_id,
    'SIFAC' z_source_id,
    A.posid source_code
  FROM
    sapsr3.prps@sifacp A,
    sapsr3.prte@sifacp B
  WHERE
    A.pspnr=B.posnr(+)
    AND A.pkokr='1010'
    AND B.mandt(+)='500'
    AND a.fkstl LIKE 'P%' AND (a.fkstl like '%A' OR a.fkstl like '%B' OR a.fkstl like '%M')
    AND SYSDATE BETWEEN to_date( NVL(B.pstrt,'10661231'), 'YYYYMMDD') AND to_date( NVL(B.pende,'99991231'), 'YYYYMMDD')
    
  UNION
  
  SELECT
    TRIM(A.post1) libelle,
    'enseignement' z_activite_id,
    'ressources-propres' z_type_ressource_id,
    substr( A.fkstl, 2, 3 ) unite_budgetaire,
    null z_parent_id,
    'SIFAC' z_source_id,
    A.posid source_code
  FROM
    sapsr3.prps@sifacp A,
    sapsr3.prte@sifacp B
  WHERE
    A.pspnr=B.posnr(+)
    and A.pkokr='1010'
    and B.mandt(+)='500'
    AND (
      A.posid IN ('P950FCFCR', 'P950FCFFR')
    )
    AND SYSDATE BETWEEN to_date( NVL(B.pstrt,'10661231'), 'YYYYMMDD') AND to_date( NVL(B.pende,'99991231'), 'YYYYMMDD')
)
SELECT
  code,
  libelle,
  activite_id,
  type_ressource_id,
  unite_budgetaire,
  poids,
  parent_id,
  source_id,
  source_code
FROM
  (
  SELECT
    sq.source_code                                                      code,
    sq.libelle                                                          libelle,
    a.id                                                                activite_id,
    tr.id                                                               type_ressource_id,
    sq.unite_budgetaire                                                 unite_budgetaire,
    ROW_NUMBER() OVER (PARTITION BY sq.source_code ORDER BY sq.libelle) poids,
    cc.id                                                               parent_id,
    src.id                                                              source_id,
    sq.source_code                                                      source_code
  FROM
              sifac_query    sq
         JOIN source        src ON src.code       = sq.z_source_id
    LEFT JOIN cc_activite     a ON a.code         = sq.z_activite_id
    LEFT JOIN type_ressource tr ON tr.code        = sq.z_type_ressource_id
    LEFT JOIN centre_cout    cc ON cc.source_code = sq.z_parent_id
  WHERE
    sq.z_activite_id IS NOT NULL
) cc
WHERE 
  poids = 1;
---------------------------
--Modifié VIEW
--SRC_AFFECTATION_RECHERCHE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_AFFECTATION_RECHERCHE" 
 ( "INTERVENANT_ID", "STRUCTURE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH harpege_query AS (
  SELECT
    to_char(ar.no_dossier_pers)  z_intervenant_id,
    ar.c_structure               z_structure_id,
    'Harpege'                    z_source_id,
    to_char(ar.no_seq_affe_rech) source_code
  FROM  
    affectation_recherche@harpprod ar
  WHERE
    SYSDATE BETWEEN ar.d_deb_affe_rech AND COALESCE(ar.d_fin_affe_rech + 1,SYSDATE)
)
SELECT
  i.id                                                      intervenant_id,
  s.id                                                      structure_id,
  src.id                                                    source_id,
  hq.source_code || '_' || unicaen_import.get_current_annee source_code
FROM
            harpege_query              hq
       JOIN source                    src ON src.code = 'Harpege'
  LEFT JOIN intervenant                 i ON i.source_code = hq.z_intervenant_id 
                                         AND i.annee_id = unicaen_import.get_current_annee
  LEFT JOIN mv_unicaen_structure_codes sc ON sc.c_structure = hq.z_structure_id
  LEFT JOIN structure                   s ON s.source_code = sc.c_structure_n2;
---------------------------
--Modifié VIEW
--SRC_AFFECTATION
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_AFFECTATION" 
 ( "STRUCTURE_ID", "UTILISATEUR_ID", "ROLE_ID", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  SELECT
  s.id          structure_id,
  u.id          utilisateur_id,
  r.id          role_id,
  src.id        source_id,
  a.source_code source_code
FROM
            mv_affectation a
       JOIN source       src ON src.code = a.z_source_id
  LEFT JOIN utilisateur    u ON u.username = a.username
  LEFT JOIN structure      s ON s.source_code = a.z_structure_id
  LEFT JOIN role           r ON r.code = a.z_role_id
WHERE
  s.id IS NULL -- rôle global
  OR (
    (
      EXISTS (SELECT * FROM element_pedagogique ep WHERE ep.structure_id = s.id) -- soit une resp. dans une composante d'enseignement
      OR a.z_role_id IN ('responsable-recherche-labo')                           -- soit un responsable de labo
    )
  );
---------------------------
--Modifié VIEW
--SRC_ADRESSE_STRUCTURE
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ADRESSE_STRUCTURE" 
 ( "STRUCTURE_ID", "PRINCIPALE", "TELEPHONE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH harpege_query AS (
  SELECT
    z_structure_id, 
    principale, 
    telephone, 
    no_voie, 
    nom_voie, 
    localite, 
    code_postal, 
    ville, 
    pays_code_insee, 
    pays_libelle, 
    'Harpege' z_source_id,
    source_code
  FROM (
  
    SELECT DISTINCT
      ls.c_structure                                                  z_structure_id,
      CASE ls.tem_local_principal WHEN 'O' THEN 1 ELSE 0 END          principale,
      ls.no_telephone                                                 telephone,
      no_voie_a || CASE bis_ter_a
        WHEN 'B' THEN ' BIS'
        WHEN 'T' THEN ' TER'
        WHEN 'Q' THEN ' QUATER'
        WHEN 'C' THEN ' QUINQUIES'
        ELSE ''
      END                                                             no_voie,
      UPPER(TRIM(V.l_voie) || ' ' || TRIM(nom_voie_a))                nom_voie,
      localite_a                                                      localite,
      COALESCE( cp_etranger_admin, code_postal_a )                    code_postal,
      TRIM(ville_a)                                                   ville,
      pays.c_pays                                                     pays_code_insee,
      pays.ll_pays                                                    pays_libelle,
      to_char(aa.id_adresse_admin) || '_' || ls.c_structure           source_code,
      COUNT(*) OVER(PARTITION BY aa.id_adresse_admin,ls.c_structure)  doublons
    FROM
                adresse_administrat@harpprod    aa
           JOIN local@harpprod                   l ON l.id_adresse_admin = aa.id_adresse_admin
           JOIN localisation_structure@harpprod ls ON ls.c_local = l.c_local
      LEFT JOIN pays@harpprod                 pays ON pays.c_pays = aa.c_pays
      LEFT JOIN voirie@harpprod                  v ON v.c_voie = aa.c_voie
    WHERE
      SYSDATE BETWEEN COALESCE(aa.d_deb_val, SYSDATE) AND COALESCE(aa.d_fin_val, SYSDATE)
    ) tmp1
  
  WHERE
    doublons = 1 OR principale = 1
)
SELECT
  s.id                structure_id,
  hq.principale       principale,
  hq.telephone        telephone,
  hq.no_voie          no_voie,
  hq.nom_voie         nom_voie,
  hq.localite         localite,
  hq.code_postal      code_postal,
  hq.ville            ville,
  hq.pays_code_insee  pays_code_insee,
  hq.pays_libelle     pays_libelle,
  src.id              source_id,
  hq.source_code      source_code
FROM
       harpege_query hq
  JOIN source       src ON src.code = hq.z_source_id
  JOIN structure      s ON s.source_code = hq.z_structure_id;
---------------------------
--Modifié VIEW
--SRC_ADRESSE_INTERVENANT
---------------------------
CREATE OR REPLACE FORCE VIEW "OSE"."SRC_ADRESSE_INTERVENANT" 
 ( "INTERVENANT_ID", "TEL_DOMICILE", "MENTION_COMPLEMENTAIRE", "NO_VOIE", "NOM_VOIE", "LOCALITE", "CODE_POSTAL", "VILLE", "PAYS_CODE_INSEE", "PAYS_LIBELLE", "SOURCE_ID", "SOURCE_CODE"
  )  AS 
  WITH harpege_query AS (
  SELECT
    LTRIM(to_char(no_individu,'99999999'))                  z_intervenant_id,
    TRIM(telephone_domicile)                                tel_domicile,
    TRIM(UPPER(habitant_chez))                              mention_complementaire,
    no_voie || CASE bis_ter
      WHEN 'B' THEN ' BIS'
      WHEN 'T' THEN ' TER'
      WHEN 'Q' THEN ' QUATER'
      WHEN 'C' THEN ' QUINQUIES'
      ELSE ''
    END                                                     no_voie,
    UPPER(TRIM(v.l_voie) || ' ' || TRIM(nom_voie))          nom_voie,
    localite                                                localite,
    coalesce( cp_etranger, code_postal )                    code_postal,
    trim(ville)                                             ville,
    pays.c_pays                                             pays_code_insee,
    pays.ll_pays                                            pays_libelle,
    'Harpege'                                               z_source_id,
    to_char(id_adresse_perso)                               source_code
  FROM
              adresse_personnelle@harpprod adresse
    LEFT JOIN pays@harpprod                   pays ON pays.c_pays = adresse.c_pays
    LEFT JOIN voirie@harpprod                    v ON v.c_voie = adresse.c_voie
  WHERE
    adresse.d_creation <= sysdate
    AND tem_adr_pers_princ = 'O' -- on n'importe que les adresses principales
)
SELECT
  i.id                                                      intervenant_id,
  hq.tel_domicile                                           tel_domicile,
  hq.mention_complementaire                                 mention_complementaire,
  hq.no_voie                                                no_voie,
  hq.nom_voie                                               nom_voie,
  hq.localite                                               localite,
  hq.code_postal                                            code_postal,
  hq.ville                                                  ville,
  hq.pays_code_insee                                        pays_code_insee,
  hq.pays_libelle                                           pays_libelle,
  src.id                                                    source_id,
  hq.source_code || '_' || unicaen_import.get_current_annee source_code
FROM
            harpege_query  hq
       JOIN source        src ON src.code = hq.z_source_id
  LEFT JOIN intervenant     i ON i.source_code = hq.z_intervenant_id 
                             AND i.annee_id = unicaen_import.get_current_annee;
---------------------------
--Nouveau MATERIALIZED VIEW
--MV_UNICAEN_STRUCTURE_CODES
---------------------------
CREATE MATERIALIZED VIEW "OSE"."MV_UNICAEN_STRUCTURE_CODES" ("C_STRUCTURE","C_STRUCTURE_N2") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  SELECT
  s9.c_structure c_structure,
  COALESCE(s4.c_structure, s5.c_structure, s6.c_structure, s7.c_structure, s8.c_structure, s9.c_structure) c_structure_n2
FROM
  structure@harpprod s9
  LEFT JOIN structure@harpprod s8 ON s8.c_structure = s9.c_structure_pere AND s8.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s7 ON s7.c_structure = s8.c_structure_pere AND s7.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s6 ON s6.c_structure = s7.c_structure_pere AND s6.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s5 ON s5.c_structure = s6.c_structure_pere AND s5.c_structure <> 'UNIV'
  LEFT JOIN structure@harpprod s4 ON s4.c_structure = s5.c_structure_pere AND s4.c_structure <> 'UNIV'
---------------------------
--Modifié MATERIALIZED VIEW
--MV_INTERVENANT
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT";
CREATE MATERIALIZED VIEW "OSE"."MV_INTERVENANT" ("CODE","Z_CIVILITE_ID","NOM_USUEL","PRENOM","NOM_PATRONYMIQUE","DATE_NAISSANCE","Z_PAYS_NAISSANCE_ID","Z_DEP_NAISSANCE_ID","VILLE_NAISSANCE_CODE_INSEE","VILLE_NAISSANCE_LIBELLE","Z_PAYS_NATIONALITE_ID","TEL_PRO","TEL_MOBILE","EMAIL","Z_STATUT_ID","Z_STRUCTURE_ID","SOURCE_CODE","NUMERO_INSEE","NUMERO_INSEE_CLE","NUMERO_INSEE_PROVISOIRE","IBAN","BIC","Z_GRADE_ID","Z_DISCIPLINE_ID_CNU","Z_DISCIPLINE_ID_SOUS_CNU","Z_DISCIPLINE_ID_SPE_CNU","Z_DISCIPLINE_ID_DIS2DEG","CRITERE_RECHERCHE","DATE_FIN") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  WITH 
i AS (
  SELECT -- permet de fusionner les données pour ne conserver qu'une des tuples (code,statut) sans doublons
    code,
    statut,
    MAX(z_discipline_id_cnu)      z_discipline_id_cnu,
    MAX(z_discipline_id_sous_cnu) z_discipline_id_sous_cnu,
    MAX(z_discipline_id_spe_cnu)  z_discipline_id_spe_cnu,
    MAX(z_discipline_id_dis2deg)  z_discipline_id_dis2deg,
    MAX(date_fin) date_fin
  FROM
  (
    SELECT 
      i.*, -- permet de ne sélectionner que les données (contrats, etc) se terminant le plus tard possible ou bien sans date de fin
      CASE WHEN COUNT(*) OVER (PARTITION BY code,statut) > 1 THEN
        CASE WHEN COALESCE(date_fin,SYSDATE) = MAX(COALESCE(date_fin,SYSDATE)) OVER (PARTITION BY code,statut) THEN 1 ELSE 0 END
      ELSE 1 END ok2,
      COUNT(*) OVER (PARTITION BY code,statut,date_fin) dc
    FROM 
    (
      SELECT
        i.*,
        CASE -- permet de supprimer les données obsolètes ou futures s'il y en a des actuelles (contrat en cours, etc)
          WHEN 
            COUNT(*) OVER (PARTITION BY i.code) > 1 
            AND MAX(i.actuel) OVER (PARTITION BY i.code) = 1 
            AND i.actuel = 0
          THEN 0 ELSE 1 END ok
      FROM
      (
        SELECT
          ca.no_dossier_pers                                 code,
          CASE -- lien entre le contrat de travail Harpège et le statut d'intervenant OSE
            WHEN ct.c_type_contrat_trav IN ('MC','MA')                THEN 'ASS_MI_TPS'
            WHEN ct.c_type_contrat_trav IN ('AT')                     THEN 'ATER'
            WHEN ct.c_type_contrat_trav IN ('AX')                     THEN 'ATER_MI_TPS'
            WHEN ct.c_type_contrat_trav IN ('DO')                     THEN 'DOCTOR'
            WHEN ct.c_type_contrat_trav IN ('GI','PN','ED')           THEN 'ENS_CONTRACT'
            WHEN ct.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
            WHEN ct.c_type_contrat_trav IN ('MB','MP')                THEN 'MAITRE_LANG'
            WHEN ct.c_type_contrat_trav IN ('PT')                     THEN 'HOSPITALO_UNIV'
            WHEN ct.c_type_contrat_trav IN ('C3','CA','CB','CD','CS','HA','HD','HS','MA','S3','SX','SW','SY','SZ','VA') THEN 'BIATSS'
            WHEN ct.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET') THEN 'NON_AUTORISE'
            ELSE 'AUTRES' 
          END                                                statut,
          ca.c_section_cnu                                   z_discipline_id_cnu,
          ca.c_sous_section_cnu                              z_discipline_id_sous_cnu,
          ca.c_specialite_cnu                                z_discipline_id_spe_cnu,
          ca.c_disc_second_degre                             z_discipline_id_dis2deg,
          COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav) date_fin,
          CASE WHEN
            SYSDATE BETWEEN ca.d_deb_contrat_trav-1 AND COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          contrat_avenant@harpprod ca
          JOIN contrat_travail@harpprod ct ON ct.no_dossier_pers = ca.no_dossier_pers AND ct.no_contrat_travail = ca.no_contrat_travail
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
          SYSDATE BETWEEN ca.d_deb_contrat_trav-184 AND COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav,SYSDATE)+184
      
        UNION
      
        SELECT
          a.no_dossier_pers                                  code,
          CASE -- lien entre le type de population Harpège et le statut d'intervenant OSE
            WHEN c.c_type_population IN ('DA','OA','DC')              THEN 'ENS_2ND_DEG'
            WHEN c.c_type_population IN ('SA')                        THEN 'ENS_CH'
            WHEN c.c_type_population IN ('AA','AC','BA','IA','MA')    THEN 'BIATSS'
            WHEN c.c_type_population IN ('MG','SB')                   THEN 'HOSPITALO_UNIV'
            ELSE 'AUTRES' 
          END                                                statut,
          psc.c_section_cnu                                  z_discipline_id_cnu,
          psc.c_sous_section_cnu                             z_discipline_id_sous_cnu,
          psc.c_specialite_cnu                               z_discipline_id_spe_cnu,
          pss.c_disc_second_degre                            z_discipline_id_dis2deg,
          a.d_fin_affectation                                date_fin,
          CASE WHEN
            SYSDATE BETWEEN a.d_deb_affectation-1 AND COALESCE(a.d_fin_affectation,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          affectation@harpprod a
          LEFT JOIN carriere@harpprod c ON c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
          LEFT JOIN periodes_sp_cnu@harpprod    psc                ON psc.no_dossier_pers = a.no_dossier_pers AND psc.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(psc.d_deb,SYSDATE) AND COALESCE(psc.d_fin,SYSDATE)
          LEFT JOIN periodes_sp_sd_deg@harpprod pss                ON pss.no_dossier_pers = a.no_dossier_pers AND pss.no_seq_carriere = a.no_seq_carriere AND COALESCE(a.d_fin_affectation,SYSDATE) BETWEEN COALESCE(pss.d_deb,SYSDATE) AND COALESCE(pss.d_fin,SYSDATE)
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
          SYSDATE BETWEEN a.d_deb_affectation-184 AND COALESCE(a.d_fin_affectation,SYSDATE)+184
      
        UNION
      
        SELECT
          ch.no_individu                                     code,
          'AUTRES'                                           statut, -- pas de statut de défini ici
          ch.c_section_cnu                                   z_discipline_id_cnu,
          ch.c_sous_section_cnu                              z_discipline_id_sous_cnu,
          NULL                                               z_discipline_id_spe_cnu,
          ch.c_disc_second_degre                             z_discipline_id_dis2deg,
          ch.d_fin_str_trav                                  date_fin,
          CASE WHEN
            SYSDATE BETWEEN ch.d_deb_str_trav-1 AND COALESCE(ch.d_fin_str_trav,SYSDATE)+1
          THEN 1 ELSE 0 END                                  actuel
        FROM
          chercheur@harpprod ch
        WHERE -- on sélectionne les données même 6 mois avant et 6 mois après
          SYSDATE BETWEEN ch.d_deb_str_trav-184 AND COALESCE(ch.d_fin_str_trav,SYSDATE)+184
      ) i
    ) i WHERE ok = 1
  )i WHERE ok2 = 1 GROUP BY code,statut
),
comptes (no_individu, rank_compte, nombre_comptes, IBAN, BIC) AS (
  SELECT -- récupération des comptes en banque
    i.no_dossier_pers no_individu,
    dense_rank() over(partition by i.no_dossier_pers order by d_creation) rank_compte,
    count(*) over(partition by i.no_dossier_pers)                   nombre_comptes,
    CASE WHEN i.no_dossier_pers IS NOT NULL THEN
      trim( NVL(i.c_pays_iso || i.cle_controle,'FR00') || ' ' ||
      substr(i.c_banque,0,4) || ' ' ||
      substr(i.c_banque,5,1) || substr(i.c_guichet,0,3) || ' ' ||
      substr(i.c_guichet,4,2) || substr(i.no_compte,0,2) || ' ' ||
      substr(i.no_compte,3,4) || ' ' ||
      substr(i.no_compte,7,4) || ' ' ||
      substr(i.no_compte,11) || i.cle_rib) ELSE NULL END            IBAN,
    CASE WHEN i.no_dossier_pers IS NOT NULL THEN i.c_banque_bic || ' ' || i.c_pays_bic || ' ' || i.c_emplacement || ' ' || i.c_branche ELSE NULL END BIC
  from
    individu_banque@harpprod i
)
SELECT
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             code,
  CASE individu.c_civilite WHEN 'M.' THEN 'M.' ELSE 'Mme' END z_civilite_id,
  initcap(individu.nom_usuel)                                 nom_usuel,
  initcap(individu.prenom)                                    prenom,
  initcap(individu.nom_patronymique)                          nom_patronymique,
  individu.d_naissance                                        date_naissance,
  individu.c_pays_naissance                                   z_pays_naissance_id,
  individu.c_dept_naissance                                   z_dep_naissance_id,
  individu.c_commune_naissance                                ville_naissance_code_insee,
  COALESCE(commune.libelle_commune,individu.ville_de_naissance) ville_naissance_libelle,
  individu.c_pays_nationnalite                                z_pays_nationalite_id,
  individu_telephone.no_telephone                             tel_pro,
  individu.no_tel_portable                                    tel_mobile,
  CASE -- Si le mail n'est pas renseigné dans Harpège, alors on va le chercher dans notre LDAP
    WHEN INDIVIDU_E_MAIL.NO_E_MAIL IS NULL THEN 
      UCBN_LDAP.hid2mail(individu.no_individu) -- (à adapter en fonction de l'établissement)
    ELSE
      INDIVIDU_E_MAIL.NO_E_MAIL
  END                                                         email,
  i.statut                                                    z_statut_id,
  sc.c_structure_n2                                           z_structure_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999'))             source_code,
  code_insee.no_insee                                         numero_insee,
  TO_CHAR(code_insee.cle_insee)                               numero_insee_cle,
  CASE WHEN code_insee.no_insee IS NULL THEN NULL ELSE 0 END  numero_insee_provisoire,
  comptes.iban                                                iban,
  comptes.bic                                                 bic,
  pbs_divers__cicg.c_grade@harpprod(individu.no_individu, COALESCE(i.date_fin,SYSDATE) ) z_grade_id,
  i.z_discipline_id_cnu                                       z_discipline_id_cnu,
  i.z_discipline_id_sous_cnu                                  z_discipline_id_sous_cnu,
  i.z_discipline_id_spe_cnu                                   z_discipline_id_spe_cnu,
  i.z_discipline_id_dis2deg                                   z_discipline_id_dis2deg,
  utl_raw.cast_to_varchar2((nlssort(to_char(individu.nom_usuel || ' ' || individu.nom_patronymique || ' ' || individu.prenom), 'nls_sort=binary_ai'))) critere_recherche,
  i.date_fin
FROM
                                        i
       JOIN individu@harpprod           individu           ON individu.no_individu           = i.code
  LEFT JOIN MV_UNICAEN_STRUCTURE_CODES  sc                 ON sc.c_structure                 = pbs_divers__cicg.c_structure_globale@harpprod(individu.no_individu, COALESCE(i.date_fin,SYSDATE) )
  LEFT JOIN commune@harpprod            commune            ON individu.c_commune_naissance   = commune.c_commune
  LEFT JOIN individu_e_mail@harpprod    individu_e_mail    ON individu_e_mail.no_individu    = i.code
  LEFT JOIN individu_telephone@harpprod individu_telephone ON individu_telephone.no_individu = i.code AND individu_telephone.tem_tel_principal='O' AND individu_telephone.tem_tel='O'
  LEFT JOIN code_insee@harpprod         code_insee         ON code_insee.no_dossier_pers     = i.code
  LEFT JOIN                             comptes            ON comptes.no_individu            = i.code AND comptes.rank_compte = comptes.nombre_comptes
---------------------------
--Modifié MATERIALIZED VIEW
--MV_AFFECTATION
---------------------------
DROP MATERIALIZED VIEW "OSE"."MV_AFFECTATION";
CREATE MATERIALIZED VIEW "OSE"."MV_AFFECTATION" ("DISPLAY_NAME","EMAIL","PASSWORD","STATE","USERNAME","Z_STRUCTURE_ID","Z_ROLE_ID","Z_SOURCE_ID","SOURCE_CODE") 
  BUILD IMMEDIATE
  USING INDEX REFRESH FORCE ON DEMAND
  USING DEFAULT LOCAL ROLLBACK SEGMENT  USING ENFORCED CONSTRAINTS
  DISABLE QUERY REWRITE AS 
  WITH tmp AS (

  SELECT
    i.nom_usuel || ' ' || INITCAP(i.prenom)         display_name,
    UCBN_LDAP.HID2MAIL(i.no_individu)               email,
    'ldap'                                          password,
    1                                               state,
    UCBN_LDAP.HID2ALIAS(i.no_individu)              username,
    
    CASE WHEN c_structure = 'UNIV' THEN NULL ELSE c_structure END z_structure_id,
    CASE
      WHEN lc_fonction LIKE '_D30%' OR t.lc_fonction LIKE '_P71%' THEN 'directeur-composante'
      WHEN lc_fonction LIKE '_R00'  OR t.lc_fonction LIKE '_R40%' THEN 'responsable-composante'
      WHEN lc_fonction LIKE '_R00c' OR t.lc_fonction LIKE '_R40%' THEN 'responsable-recherche-labo'
      WHEN c_structure = 'UNIV' AND t.lc_fonction = '_P00' OR t.lc_fonction LIKE '_P10%' OR t.lc_fonction LIKE '_P50%' THEN 'superviseur-etablissement'
      ELSE NULL
    END z_role_id,
    t.c_structure || '_' || t.no_individu || '_' || t.lc_fonction source_code,
    
    lc_fonction,
    nom_complet, lc_structure, ll_fonction, t.*
  FROM
         ucbn_d2a_respons_struct@harpprod t
    JOIN individu@harpprod                i ON i.no_individu = t.no_individu
  WHERE
    niveau_structure <= 2
    AND SYSDATE BETWEEN t.date_deb_exerc_resp AND NVL(t.date_fin_exerc_resp + 1,SYSDATE)

)
SELECT DISTINCT
  display_name,
  email,
  password,
  state,
  username,
  z_structure_id,
  z_role_id,
  'Harpege' z_source_id,
  MIN( source_code ) source_code
FROM 
  tmp
WHERE
  tmp.z_role_id IS NOT NULL
GROUP BY
  display_name,
  email,
  password,
  state,
  username,
  z_structure_id, 
  z_role_id
---------------------------
--Nouveau INDEX
--WF_ETAPE_DEP_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."WF_ETAPE_DEP_PK" ON "OSE"."WF_ETAPE_DEP" ("ID");
---------------------------
--Nouveau INDEX
--TBL_SERVICE_REF_INTERVENANT_FK
---------------------------
  CREATE INDEX "OSE"."TBL_SERVICE_REF_INTERVENANT_FK" ON "OSE"."TBL_SERVICE_REFERENTIEL" ("INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--STRUCTURE_CODE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."STRUCTURE_CODE_UN" ON "OSE"."STRUCTURE" ("CODE","HISTO_DESTRUCTION");
---------------------------
--Nouveau INDEX
--STRUCTURE_SOURCE_CODE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."STRUCTURE_SOURCE_CODE_UN" ON "OSE"."STRUCTURE" ("SOURCE_CODE","HISTO_DESTRUCTION");
---------------------------
--Modifié INDEX
--ETAT_VOLUME_HORAIRE__UN
---------------------------
DROP INDEX "OSE"."ETAT_VOLUME_HORAIRE__UN";
  CREATE UNIQUE INDEX "OSE"."ETAT_VOLUME_HORAIRE__UN" ON "OSE"."ETAT_VOLUME_HORAIRE" ("CODE");
---------------------------
--Nouveau INDEX
--INTERVENANT_UTIL_CODE_UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."INTERVENANT_UTIL_CODE_UN" ON "OSE"."INTERVENANT" ("UTILISATEUR_CODE","ANNEE_ID","STATUT_ID");
---------------------------
--Nouveau INDEX
--TYPE_VOLUME_HORAIRE__UN_IDX
---------------------------
  CREATE UNIQUE INDEX "OSE"."TYPE_VOLUME_HORAIRE__UN_IDX" ON "OSE"."TYPE_VOLUME_HORAIRE" ("CODE");
---------------------------
--Nouveau INDEX
--REGLE_STRUCTURE_VALIDATION__UN
---------------------------
  CREATE UNIQUE INDEX "OSE"."REGLE_STRUCTURE_VALIDATION__UN" ON "OSE"."REGLE_STRUCTURE_VALIDATION" ("TYPE_VOLUME_HORAIRE_ID","TYPE_INTERVENANT_ID");
---------------------------
--Nouveau INDEX
--IMPORT_TABLES_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."IMPORT_TABLES_PK" ON "OSE"."IMPORT_TABLES" ("TABLE_NAME");
---------------------------
--Nouveau INDEX
--REGLE_STRUCTURE_VALIDATION_PK
---------------------------
  CREATE UNIQUE INDEX "OSE"."REGLE_STRUCTURE_VALIDATION_PK" ON "OSE"."REGLE_STRUCTURE_VALIDATION" ("ID");
---------------------------
--Nouveau INDEX
--TBL_SERVICE_SAISIE_ANNEE_FK
---------------------------
  CREATE INDEX "OSE"."TBL_SERVICE_SAISIE_ANNEE_FK" ON "OSE"."TBL_SERVICE_SAISIE" ("ANNEE_ID");
---------------------------
--Modifié INDEX
--AFFECTATION__UN
---------------------------
DROP INDEX "OSE"."AFFECTATION__UN";
  CREATE UNIQUE INDEX "OSE"."AFFECTATION__UN" ON "OSE"."AFFECTATION" ("ROLE_ID","STRUCTURE_ID","HISTO_DESTRUCTION","UTILISATEUR_ID");
---------------------------
--Modifié TRIGGER
--T_CRG_VOLUME_HORAIRE_ENS
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."T_CRG_VOLUME_HORAIRE_ENS"
  AFTER INSERT OR DELETE OR UPDATE OF ELEMENT_PEDAGOGIQUE_ID, TYPE_INTERVENTION_ID, HEURES, HISTO_DESTRUCTION ON "OSE"."VOLUME_HORAIRE_ENS"
  REFERENCING FOR EACH ROW
  BEGIN
  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  IF DELETING THEN
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('element_pedagogique_id', :OLD.element_pedagogique_id ) );
  ELSE
    UNICAEN_TBL.DEMANDE_CALCUL( 'chargens', unicaen_tbl.make_params('element_pedagogique_id', :NEW.element_pedagogique_id ) );
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

END;
/
---------------------------
--Modifié TRIGGER
--F_ELEMENT_PEDAGOGIQUE
---------------------------
  CREATE OR REPLACE TRIGGER "OSE"."F_ELEMENT_PEDAGOGIQUE"
  AFTER DELETE OR UPDATE OF ID, STRUCTURE_ID, PERIODE_ID, TAUX_FI, TAUX_FC, TAUX_FA, TAUX_FOAD, FI, FC, FA, HISTO_CREATION, HISTO_DESTRUCTION, ANNEE_ID ON "OSE"."ELEMENT_PEDAGOGIQUE"
  REFERENCING FOR EACH ROW
  BEGIN

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN
    ( SELECT DISTINCT s.intervenant_id
    FROM service s
    WHERE (s.element_pedagogique_id = :NEW.id
    OR s.element_pedagogique_id     = :OLD.id)
    AND s.histo_destruction IS NULL
    ) LOOP UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );
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

  IF NOT UNICAEN_TBL.ACTIV_TRIGGERS THEN RETURN; END IF;

  FOR p IN (

    SELECT DISTINCT
      s.intervenant_id
    FROM
      volume_horaire vh
      JOIN service s ON s.id = vh.service_id AND s.histo_destruction IS NULL
    WHERE
      vh.histo_destruction IS NULL
      AND (vh.contrat_id = :OLD.id OR vh.contrat_id = :NEW.id)

  ) LOOP

    UNICAEN_TBL.DEMANDE_CALCUL('formule', UNICAEN_TBL.make_params('INTERVENANT_ID', p.intervenant_id) );

  END LOOP;

END;
/
---------------------------
--Nouveau PACKAGE
--UNICAEN_IMPORT_AUTOGEN_PROCS__
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."UNICAEN_IMPORT_AUTOGEN_PROCS__" IS

  PROCEDURE VOLUME_HORAIRE_ENS;
  PROCEDURE TYPE_MODULATEUR_EP;
  PROCEDURE TYPE_INTERVENTION_EP;
  PROCEDURE TYPE_FORMATION;
  PROCEDURE STRUCTURE;
  PROCEDURE SCENARIO_LIEN;
  PROCEDURE PAYS;
  PROCEDURE NOEUD;
  PROCEDURE LIEN;
  PROCEDURE INTERVENANT;
  PROCEDURE GROUPE_TYPE_FORMATION;
  PROCEDURE GRADE;
  PROCEDURE ETAPE;
  PROCEDURE ETABLISSEMENT;
  PROCEDURE ELEMENT_TAUX_REGIMES;
  PROCEDURE ELEMENT_PEDAGOGIQUE;
  PROCEDURE EFFECTIFS;
  PROCEDURE DOMAINE_FONCTIONNEL;
  PROCEDURE DEPARTEMENT;
  PROCEDURE CORPS;
  PROCEDURE CHEMIN_PEDAGOGIQUE;
  PROCEDURE CENTRE_COUT_STRUCTURE;
  PROCEDURE CENTRE_COUT;
  PROCEDURE AFFECTATION_RECHERCHE;
  PROCEDURE AFFECTATION;
  PROCEDURE ADRESSE_STRUCTURE;
  PROCEDURE ADRESSE_INTERVENANT;

END UNICAEN_IMPORT_AUTOGEN_PROCS__;
/
---------------------------
--Modifié PACKAGE
--UNICAEN_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."UNICAEN_IMPORT" AS 

  z__SYNC_FILRE__z CLOB DEFAULT '';
  z__IGNORE_UPD_COLS__z CLOB DEFAULT '';

  PROCEDURE set_current_user(p_current_user IN INTEGER);
  FUNCTION get_current_user return INTEGER;

  FUNCTION get_current_annee RETURN INTEGER;
  PROCEDURE set_current_annee (p_current_annee INTEGER);

  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC;
  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL );

  PROCEDURE SYNCHRONISATION( table_name VARCHAR2, SYNC_FILRE CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '' );

  

END UNICAEN_IMPORT;
/
---------------------------
--Modifié PACKAGE
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE "OSE"."OSE_DIVERS" AS 

  PROCEDURE CALCULER_TABLEAUX_BORD;

  FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC;
  FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC;

  FUNCTION INTERVENANT_HAS_PRIVILEGE( intervenant_id NUMERIC, privilege_name VARCHAR2 ) RETURN NUMERIC;

  FUNCTION implode(i_query VARCHAR2, i_seperator VARCHAR2 DEFAULT ',') RETURN VARCHAR2;

  PROCEDURE intervenant_horodatage_service( INTERVENANT_ID NUMERIC, TYPE_VOLUME_HORAIRE_ID NUMERIC, REFERENTIEL NUMERIC, HISTO_MODIFICATEUR_ID NUMERIC, HISTO_MODIFICATION DATE );

  FUNCTION NIVEAU_FORMATION_ID_CALC( gtf_id NUMERIC, gtf_pertinence_niveau NUMERIC, niveau NUMERIC DEFAULT NULL ) RETURN NUMERIC;

  FUNCTION STR_REDUCE( str CLOB ) RETURN CLOB;
  
  FUNCTION STR_FIND( haystack CLOB, needle VARCHAR2 ) RETURN NUMERIC;
  
  FUNCTION LIKED( haystack CLOB, needle CLOB ) RETURN NUMERIC;

  FUNCTION CALCUL_TAUX_FI( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FC( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;
  
  FUNCTION CALCUL_TAUX_FA( eff_fi FLOAT, eff_fc FLOAT, eff_fa FLOAT, fi NUMERIC, fc NUMERIC, fa NUMERIC, arrondi NUMERIC DEFAULT 15 ) RETURN FLOAT;

  FUNCTION STRUCTURE_UNIV_GET_ID RETURN NUMERIC;

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
  
  FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2;
END OSE_DIVERS;
/
---------------------------
--Modifié PACKAGE BODY
--UNICAEN_TBL
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_TBL" AS 

  FUNCTION MAKE_PARAMS(
    c1 VARCHAR2 DEFAULT NULL, v1 VARCHAR2 DEFAULT NULL,
    c2 VARCHAR2 DEFAULT NULL, v2 VARCHAR2 DEFAULT NULL,
    c3 VARCHAR2 DEFAULT NULL, v3 VARCHAR2 DEFAULT NULL,
    c4 VARCHAR2 DEFAULT NULL, v4 VARCHAR2 DEFAULT NULL,
    c5 VARCHAR2 DEFAULT NULL, v5 VARCHAR2 DEFAULT NULL,
    sqlcond CLOB DEFAULT NULL
  ) RETURN t_params IS
    params t_params;
  BEGIN
    IF c1 IS NOT NULL THEN
      params.c1 := c1;
      params.v1 := v1;
    END IF;
    IF c2 IS NOT NULL THEN
      params.c2 := c2;
      params.v2 := v2;
    END IF;
    IF c3 IS NOT NULL THEN
      params.c3 := c3;
      params.v3 := v3;
    END IF;
    IF c4 IS NOT NULL THEN
      params.c4 := c4;
      params.v4 := v4;
    END IF;
    IF c5 IS NOT NULL THEN
      params.c5 := c5;
      params.v5 := v5;
    END IF;
    params.sqlcond := sqlcond;

    RETURN params;
  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2 ) IS
    p t_params;
  BEGIN
    DEMANDE_CALCUL( tbl_name, p );
  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, CONDS CLOB ) IS
    p t_params;
  BEGIN
    p.sqlcond := CONDS;
    DEMANDE_CALCUL( tbl_name, p );
  END;



  PROCEDURE DEMANDE_CALCUL( TBL_NAME VARCHAR2, PARAMS t_params ) IS
  BEGIN
    INSERT INTO tbl_dems (
      ID, 
      TBL_NAME,
      c1, v1,
      c2, v2,
      c3, v3,
      c4, v4,
      c5, v5,
      sqlcond
    ) VALUES (
      TBL_DEMS_ID_SEQ.NEXTVAL, 
      TBL_NAME,
      PARAMS.c1, PARAMS.v1,
      PARAMS.c2, PARAMS.v2,
      PARAMS.c3, PARAMS.v3,
      PARAMS.c4, PARAMS.v4,
      PARAMS.c5, PARAMS.v5,
      PARAMS.sqlcond
    );
  END;



  FUNCTION PARAMS_FROM_DEMS( TBL_NAME VARCHAR2 ) RETURN t_params IS
    res t_params;
    conds CLOB := '';
    cond CLOB;
  BEGIN
    FOR d IN (
      SELECT *
      FROM   tbl_dems
      WHERE  tbl_name = PARAMS_FROM_DEMS.TBL_NAME
    )
    LOOP

      cond := '';

      IF d.c1 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF d.v1 IS NULL THEN
          cond := cond || d.c1 || ' IS NULL';
        ELSE
          cond := cond || d.c1 || '=' || d.v1; 
        END IF;
      END IF;

      IF d.c2 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF d.v2 IS NULL THEN
          cond := cond || d.c2 || ' IS NULL';
        ELSE
          cond := cond || d.c2 || '=' || d.v2;
        END IF;
      END IF;

      IF d.c3 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF d.v3 IS NULL THEN
          cond := cond || d.c3 || ' IS NULL';
        ELSE
          cond := cond || d.c3 || '=' || d.v3; 
        END IF;
      END IF;

      IF d.c4 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF d.v4 IS NULL THEN
          cond := cond || d.c4 || ' IS NULL';
        ELSE
          cond := cond || d.c4 || '=' || d.v4; 
        END IF;
      END IF;

      IF d.c5 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF d.v5 IS NULL THEN
          cond := cond || d.c5 || ' IS NULL';
        ELSE
          cond := cond || d.c5 || '=' || d.v5;
        END IF;
      END IF;

      IF d.sqlcond IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        cond := cond || '(' || d.sqlcond || ')';
      END IF;

      IF conds IS NOT NULL THEN 
        conds := conds || ' OR ';
      END IF;
      conds := conds || '(' || cond || ')';
    END LOOP;

    res.sqlcond := conds;
    DELETE FROM tbl_dems WHERE tbl_name = PARAMS_FROM_DEMS.TBL_NAME;
    RETURN res;
  END;



  FUNCTION PARAMS_TO_CONDS ( PARAMS UNICAEN_TBL.T_PARAMS ) RETURN CLOB IS
    cond CLOB;
  BEGIN
    IF params.c1 IS NOT NULL THEN 
        IF params.v1 IS NULL THEN
          cond := cond || params.c1 || ' IS NULL';
        ELSE
          cond := cond || params.c1 || '=' || params.v1; 
        END IF;
      END IF;

      IF params.c2 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF params.v2 IS NULL THEN
          cond := cond || params.c2 || ' IS NULL';
        ELSE
          cond := cond || params.c2 || '=' || params.v2;
        END IF;
      END IF;

      IF params.c3 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF params.v3 IS NULL THEN
          cond := cond || params.c3 || ' IS NULL';
        ELSE
          cond := cond || params.c3 || '=' || params.v3; 
        END IF;
      END IF;

      IF params.c4 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF params.v4 IS NULL THEN
          cond := cond || params.c4 || ' IS NULL';
        ELSE
          cond := cond || params.c4 || '=' || params.v4; 
        END IF;
      END IF;

      IF params.c5 IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        IF params.v5 IS NULL THEN
          cond := cond || params.c5 || ' IS NULL';
        ELSE
          cond := cond || params.c5 || '=' || params.v5;
        END IF;
      END IF;

      IF params.sqlcond IS NOT NULL THEN 
        IF cond IS NOT NULL THEN cond := cond || ' AND '; END IF; 
        cond := cond || '(' || params.sqlcond || ')';
      END IF;

      IF cond IS NULL THEN cond := '1=1'; END IF;

      RETURN cond;
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2 ) IS
    p t_params;
  BEGIN
    ANNULER_DEMANDES( TBL_NAME );
    CALCULER(TBL_NAME, p);
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, CONDS CLOB ) IS
    p t_params;
  BEGIN
    p.sqlcond := CONDS;
    CALCULER(TBL_NAME, p);
  END;



  PROCEDURE CALCULER( TBL_NAME VARCHAR2, PARAMS t_params ) IS
    calcul_proc varchar2(30);
  BEGIN
    IF NOT UNICAEN_TBL.ACTIV_CALCULS THEN RETURN; END IF;

    SELECT custom_calcul_proc INTO calcul_proc FROM tbl WHERE tbl_name = CALCULER.TBL_NAME;

    UNICAEN_TBL.CALCUL_PROC_PARAMS := PARAMS;
    IF calcul_proc IS NOT NULL THEN
      EXECUTE IMMEDIATE 
        'BEGIN ' || calcul_proc || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
      ;
    ELSE
      EXECUTE IMMEDIATE 
        'BEGIN UNICAEN_TBL.C_' || TBL_NAME || '(UNICAEN_TBL.CALCUL_PROC_PARAMS); END;'
      ;
    END IF;

  END;



  PROCEDURE ANNULER_DEMANDES IS
  BEGIN
    DELETE FROM tbl_dems;
  END;



  PROCEDURE ANNULER_DEMANDES( TBL_NAME VARCHAR2 ) IS
  BEGIN
    DELETE FROM tbl_dems WHERE tbl_name = ANNULER_DEMANDES.tbl_name;
  END;



  FUNCTION HAS_DEMANDES RETURN BOOLEAN IS
    has_dems NUMERIC;
  BEGIN
    SELECT count(*) INTO has_dems from tbl_dems where rownum = 1;

    RETURN has_dems = 1;
  END;



  PROCEDURE CALCULER_DEMANDES IS
    dems t_params;
  BEGIN
    FOR d IN (
      SELECT DISTINCT tbl_name FROM tbl_dems
    ) LOOP
      dems := PARAMS_FROM_DEMS( d.tbl_name );
      calculer( d.tbl_name, dems );
    END LOOP;

    IF HAS_DEMANDES THEN -- pour les boucles !!
      CALCULER_DEMANDES;
    END IF;
  END;



  -- AUTOMATIC GENERATION --

  PROCEDURE C_AGREMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_AGREMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_AGREMENT t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH i_s AS (
          SELECT DISTINCT
            fr.intervenant_id,
            ep.structure_id
          FROM
            formule_resultat fr
            JOIN type_volume_horaire  tvh ON tvh.code = ''PREVU'' AND tvh.id = fr.type_volume_horaire_id
            JOIN etat_volume_horaire  evh ON evh.code = ''valide'' AND evh.id = fr.etat_volume_horaire_id
        
            JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
            JOIN service s ON s.id = frs.service_id
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          WHERE
            frs.total > 0
        )
        SELECT
          i.annee_id              annee_id,
          tas.type_agrement_id    type_agrement_id,
          i.id                    intervenant_id,
          null                    structure_id,
          tas.obligatoire         obligatoire,
          a.id                    agrement_id
        FROM
          type_agrement                  ta
          JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                            AND tas.histo_destruction IS NULL
                                       
          JOIN intervenant                 i ON i.histo_destruction IS NULL
                                            AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                            AND i.statut_id = tas.statut_intervenant_id
                                    
          LEFT JOIN agrement               a ON a.type_agrement_id = ta.id 
                                            AND a.intervenant_id = i.id
                                            AND a.histo_destruction IS NULL
        WHERE
          ta.code = ''CONSEIL_ACADEMIQUE''
        
        UNION ALL
        
        SELECT
          i.annee_id              annee_id,
          tas.type_agrement_id    type_agrement_id,
          i.id                    intervenant_id,
          i_s.structure_id        structure_id,
          tas.obligatoire         obligatoire,
          a.id                    agrement_id
        FROM
          type_agrement                   ta
          JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                            AND tas.histo_destruction IS NULL
        
          JOIN intervenant                 i ON i.histo_destruction IS NULL
                                            AND (tas.premier_recrutement IS NULL OR NVL(i.premier_recrutement,0) = tas.premier_recrutement)
                                            AND i.statut_id = tas.statut_intervenant_id
        
          JOIN                           i_s ON i_s.intervenant_id = i.id
        
          LEFT JOIN agrement               a ON a.type_agrement_id = ta.id 
                                            AND a.intervenant_id = i.id
                                            AND a.structure_id = i_s.structure_id
                                            AND a.histo_destruction IS NULL
        WHERE
          ta.code = ''CONSEIL_RESTREINT'') tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_AGREMENT_ID = v.TYPE_AGREMENT_ID
        AND t.INTERVENANT_ID   = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID         = v.ANNEE_ID,
      OBLIGATOIRE      = v.OBLIGATOIRE,
      AGREMENT_ID      = v.AGREMENT_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_AGREMENT_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      OBLIGATOIRE,
      AGREMENT_ID,
      TO_DELETE

    ) VALUES (

      TBL_AGREMENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_AGREMENT_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.OBLIGATOIRE,
      v.AGREMENT_ID,
      0

    );

    DELETE TBL_AGREMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CHARGENS( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CHARGENS SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_CHARGENS t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
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
               
               JOIN tbl_noeud                       n ON n.noeud_id = sn.noeud_id
                                                  
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
          t) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID                 = v.ANNEE_ID
        AND t.NOEUD_ID                 = v.NOEUD_ID
        AND t.SCENARIO_ID              = v.SCENARIO_ID
        AND t.TYPE_HEURES_ID           = v.TYPE_HEURES_ID
        AND t.TYPE_INTERVENTION_ID     = v.TYPE_INTERVENTION_ID
        AND t.ELEMENT_PEDAGOGIQUE_ID   = v.ELEMENT_PEDAGOGIQUE_ID
        AND t.ETAPE_ID                 = v.ETAPE_ID
        AND t.ETAPE_ENS_ID             = v.ETAPE_ENS_ID
        AND t.STRUCTURE_ID             = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID = v.GROUPE_TYPE_FORMATION_ID

    ) WHEN MATCHED THEN UPDATE SET

      OUVERTURE                = v.OUVERTURE,
      DEDOUBLEMENT             = v.DEDOUBLEMENT,
      ASSIDUITE                = v.ASSIDUITE,
      EFFECTIF                 = v.EFFECTIF,
      HEURES_ENS               = v.HEURES_ENS,
      GROUPES                  = v.GROUPES,
      HEURES                   = v.HEURES,
      HETD                     = v.HETD,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      NOEUD_ID,
      SCENARIO_ID,
      TYPE_HEURES_ID,
      TYPE_INTERVENTION_ID,
      ELEMENT_PEDAGOGIQUE_ID,
      ETAPE_ID,
      ETAPE_ENS_ID,
      STRUCTURE_ID,
      GROUPE_TYPE_FORMATION_ID,
      OUVERTURE,
      DEDOUBLEMENT,
      ASSIDUITE,
      EFFECTIF,
      HEURES_ENS,
      GROUPES,
      HEURES,
      HETD,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.NOEUD_ID,
      v.SCENARIO_ID,
      v.TYPE_HEURES_ID,
      v.TYPE_INTERVENTION_ID,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.ETAPE_ID,
      v.ETAPE_ENS_ID,
      v.STRUCTURE_ID,
      v.GROUPE_TYPE_FORMATION_ID,
      v.OUVERTURE,
      v.DEDOUBLEMENT,
      v.ASSIDUITE,
      v.EFFECTIF,
      v.HEURES_ENS,
      v.GROUPES,
      v.HEURES,
      v.HETD,
      0

    );

    DELETE TBL_CHARGENS WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CHARGENS_SEUILS_DEF( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CHARGENS_SEUILS_DEF SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_CHARGENS_SEUILS_DEF t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          sta.annee_id,
          sta.scenario_id,
          s.structure_id,
          gtf.groupe_type_formation_id,
          sta.type_intervention_id,
          COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement) dedoublement
        FROM
          (SELECT DISTINCT scenario_id, type_intervention_id, annee_id FROM seuil_charge WHERE histo_destruction IS NULL) sta
          JOIN (SELECT DISTINCT structure_id FROM noeud WHERE structure_id IS NOT NULL) s ON 1=1
          JOIN (SELECT id groupe_type_formation_id FROM groupe_type_formation) gtf ON 1=1
          
          LEFT JOIN seuil_charge sc1 ON 
            sc1.histo_destruction            IS NULL
            AND sc1.annee_id                 = sta.annee_id
            AND sc1.scenario_id              = sta.scenario_id
            AND sc1.type_intervention_id     = sta.type_intervention_id
            AND sc1.structure_id             = s.structure_id
            AND sc1.groupe_type_formation_id = gtf.groupe_type_formation_id
            
          LEFT JOIN seuil_charge sc2 ON 
            sc2.histo_destruction            IS NULL
            AND sc2.annee_id                 = sta.annee_id
            AND sc2.scenario_id              = sta.scenario_id
            AND sc2.type_intervention_id     = sta.type_intervention_id
            AND sc2.structure_id             = s.structure_id
            AND sc2.groupe_type_formation_id IS NULL
            
          LEFT JOIN seuil_charge sc3 ON 
            sc3.histo_destruction            IS NULL
            AND sc3.annee_id                 = sta.annee_id
            AND sc3.scenario_id              = sta.scenario_id
            AND sc3.type_intervention_id     = sta.type_intervention_id
            AND sc3.structure_id             IS NULL
            AND sc3.groupe_type_formation_id = gtf.groupe_type_formation_id
            
          LEFT JOIN seuil_charge sc4 ON 
            sc4.histo_destruction            IS NULL
            AND sc4.annee_id                 = sta.annee_id
            AND sc4.scenario_id              = sta.scenario_id
            AND sc4.type_intervention_id     = sta.type_intervention_id
            AND sc4.structure_id             IS NULL
            AND sc4.groupe_type_formation_id IS NULL
        WHERE
          COALESCE(sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement, 1) <> 1) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID                 = v.ANNEE_ID
        AND t.SCENARIO_ID              = v.SCENARIO_ID
        AND t.STRUCTURE_ID             = v.STRUCTURE_ID
        AND t.GROUPE_TYPE_FORMATION_ID = v.GROUPE_TYPE_FORMATION_ID
        AND t.TYPE_INTERVENTION_ID     = v.TYPE_INTERVENTION_ID

    ) WHEN MATCHED THEN UPDATE SET

      DEDOUBLEMENT             = v.DEDOUBLEMENT,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      SCENARIO_ID,
      STRUCTURE_ID,
      GROUPE_TYPE_FORMATION_ID,
      TYPE_INTERVENTION_ID,
      DEDOUBLEMENT,
      TO_DELETE

    ) VALUES (

      TBL_CHARGENS_SEUILS_DEF_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.SCENARIO_ID,
      v.STRUCTURE_ID,
      v.GROUPE_TYPE_FORMATION_ID,
      v.TYPE_INTERVENTION_ID,
      v.DEDOUBLEMENT,
      0

    );

    DELETE TBL_CHARGENS_SEUILS_DEF WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CLOTURE_REALISE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CLOTURE_REALISE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_CLOTURE_REALISE t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
          SELECT
            i.annee_id              annee_id,
            i.id                    intervenant_id,
            si.peut_cloturer_saisie peut_cloturer_saisie,
            CASE WHEN v.id IS NULL THEN 0 ELSE 1 END cloture
          FROM
                      intervenant         i
                 JOIN statut_intervenant si ON si.id = i.statut_id
                 JOIN type_validation    tv ON tv.code = ''CLOTURE_REALISE''
                 
            LEFT JOIN validation          v ON v.intervenant_id = i.id
                                           AND v.type_validation_id = tv.id
                                           AND v.histo_destruction IS NULL
        
          WHERE
            i.histo_destruction IS NULL
        )
        SELECT
          annee_id,
          intervenant_id,
          peut_cloturer_saisie,
          CASE WHEN sum(cloture) = 0 THEN 0 ELSE 1 END cloture
        FROM 
          t
        GROUP BY
          annee_id,
          intervenant_id,
          peut_cloturer_saisie) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      PEUT_CLOTURER_SAISIE = v.PEUT_CLOTURER_SAISIE,
      CLOTURE              = v.CLOTURE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_CLOTURER_SAISIE,
      CLOTURE,
      TO_DELETE

    ) VALUES (

      TBL_CLOTURE_REALISE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_CLOTURER_SAISIE,
      v.CLOTURE,
      0

    );

    DELETE TBL_CLOTURE_REALISE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_CONTRAT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_CONTRAT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_CONTRAT t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
          SELECT 
            i.annee_id                                                                annee_id,
            i.id                                                                      intervenant_id,
            si.peut_avoir_contrat                                                     peut_avoir_contrat,
            NVL(ep.structure_id, i.structure_id)                                      structure_id,
            CASE WHEN evh.code IN (''contrat-edite'',''contrat-signe'') THEN 1 ELSE 0 END edite,
            CASE WHEN evh.code IN (''contrat-signe'')                 THEN 1 ELSE 0 END signe
          FROM
                      intervenant                 i
                      
                 JOIN statut_intervenant         si ON si.id = i.statut_id
                 
                 JOIN service                     s ON s.intervenant_id = i.id
                                                   AND s.histo_destruction IS NULL
                 
                 JOIN type_volume_horaire       tvh ON tvh.code = ''PREVU''
                 
                 JOIN volume_horaire             vh ON vh.service_id = s.id
                                                   AND vh.histo_destruction IS NULL
                                                   AND vh.heures <> 0
                                                   AND vh.type_volume_horaire_id = tvh.id
            
                 JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id
                 
                 JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                                   AND evh.code IN (''valide'', ''contrat-edite'', ''contrat-signe'')
          
                 JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id
            
          WHERE
            i.histo_destruction IS NULL
            AND NOT (si.peut_avoir_contrat = 0 AND evh.code = ''valide'')
            
          UNION ALL
          
          SELECT 
            i.annee_id                                                                annee_id,
            i.id                                                                      intervenant_id,
            si.peut_avoir_contrat                                                     peut_avoir_contrat,
            s.structure_id                                                            structure_id,
            CASE WHEN evh.code IN (''contrat-edite'',''contrat-signe'') THEN 1 ELSE 0 END edite,
            CASE WHEN evh.code IN (''contrat-signe'')                 THEN 1 ELSE 0 END signe
          FROM
                      intervenant                 i
                      
                 JOIN statut_intervenant         si ON si.id = i.statut_id
                 
                 JOIN service_referentiel         s ON s.intervenant_id = i.id
                                                   AND s.histo_destruction IS NULL
                 
                 JOIN type_volume_horaire       tvh ON tvh.code = ''PREVU''
                 
                 JOIN volume_horaire_ref         vh ON vh.service_referentiel_id = s.id
                                                   AND vh.histo_destruction IS NULL
                                                   AND vh.heures <> 0
                                                   AND vh.type_volume_horaire_id = tvh.id
            
                 JOIN v_volume_horaire_ref_etat vhe ON vhe.volume_horaire_ref_id = vh.id
                 
                 JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                                   AND evh.code IN (''valide'', ''contrat-edite'', ''contrat-signe'')
          
          WHERE
            i.histo_destruction IS NULL
            AND NOT (si.peut_avoir_contrat = 0 AND evh.code = ''valide'')
        )
        SELECT
          annee_id,
          intervenant_id,
          peut_avoir_contrat,
          structure_id,
          count(*) as nbvh,
          sum(edite) as edite,
          sum(signe) as signe
        FROM
          t
        GROUP BY
          annee_id,
          intervenant_id,
          peut_avoir_contrat,
          structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID           = v.ANNEE_ID,
      PEUT_AVOIR_CONTRAT = v.PEUT_AVOIR_CONTRAT,
      NBVH               = v.NBVH,
      EDITE              = v.EDITE,
      SIGNE              = v.SIGNE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_AVOIR_CONTRAT,
      STRUCTURE_ID,
      NBVH,
      EDITE,
      SIGNE,
      TO_DELETE

    ) VALUES (

      TBL_CONTRAT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_AVOIR_CONTRAT,
      v.STRUCTURE_ID,
      v.NBVH,
      v.EDITE,
      v.SIGNE,
      0

    );

    DELETE TBL_CONTRAT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_DMEP_LIQUIDATION( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_DMEP_LIQUIDATION SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_DMEP_LIQUIDATION t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
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
        
        ) t1
        GROUP BY
          annee_id, type_ressource_id, structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.ANNEE_ID          = v.ANNEE_ID
        AND t.TYPE_RESSOURCE_ID = v.TYPE_RESSOURCE_ID
        AND t.STRUCTURE_ID      = v.STRUCTURE_ID

    ) WHEN MATCHED THEN UPDATE SET

      HEURES            = v.HEURES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_RESSOURCE_ID,
      STRUCTURE_ID,
      HEURES,
      TO_DELETE

    ) VALUES (

      TBL_DMEP_LIQUIDATION_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_RESSOURCE_ID,
      v.STRUCTURE_ID,
      v.HEURES,
      0

    );

    DELETE TBL_DMEP_LIQUIDATION WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_DOSSIER( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_DOSSIER SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_DOSSIER t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_dossier,
          d.id dossier_id,
          v.id validation_id
        FROM
                    intervenant         i
               JOIN statut_intervenant si ON si.id = i.statut_id
          LEFT JOIN dossier             d ON d.intervenant_id = i.id
                                      AND d.histo_destruction IS NULL
          
               JOIN type_validation tv ON tv.code = ''DONNEES_PERSO_PAR_COMP''
          LEFT JOIN validation       v ON v.intervenant_id = i.id
                                      AND v.type_validation_id = tv.id
                                      AND v.histo_destruction IS NULL
        WHERE
          i.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID            = v.ANNEE_ID,
      PEUT_SAISIR_DOSSIER = v.PEUT_SAISIR_DOSSIER,
      DOSSIER_ID          = v.DOSSIER_ID,
      VALIDATION_ID       = v.VALIDATION_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_DOSSIER,
      DOSSIER_ID,
      VALIDATION_ID,
      TO_DELETE

    ) VALUES (

      TBL_DOSSIER_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_DOSSIER,
      v.DOSSIER_ID,
      v.VALIDATION_ID,
      0

    );

    DELETE TBL_DOSSIER WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PAIEMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PAIEMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_PAIEMENT t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id                                  annee_id,
          frs.id                                      formule_res_service_id,
          null                                        formule_res_service_ref_id,
          i.id                                        intervenant_id,
          COALESCE( ep.structure_id, i.structure_id ) structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
          count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          NVL(mep.heures,0)                           heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
        FROM
                    formule_resultat_service        frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id
        
               JOIN intervenant                       i ON i.id = fr.intervenant_id  
               JOIN service                           s ON s.id = frs.service_id
          LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                                       AND mep.histo_destruction IS NULL
        
        UNION ALL
        
        SELECT
          i.annee_id                                  annee_id,
          null                                        formule_res_service_id,
          frs.id                                      formule_res_service_ref_id,
          i.id                                        intervenant_id,
          s.structure_id                              structure_id,
          mep.id                                      mise_en_paiement_id,
          mep.periode_paiement_id                     periode_paiement_id,
          frs.heures_compl_referentiel                heures_a_payer,
          count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
          NVL(mep.heures,0)                           heures_demandees,
          CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees
        FROM
                    formule_resultat_service_ref    frs
               JOIN type_volume_horaire             tvh ON tvh.code = ''REALISE''
               JOIN etat_volume_horaire             evh ON evh.code = ''valide''
               JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                                       AND fr.type_volume_horaire_id = tvh.id
                                                       AND fr.etat_volume_horaire_id = evh.id
        
               JOIN intervenant                       i ON i.id = fr.intervenant_id  
               JOIN service_referentiel               s ON s.id = frs.service_referentiel_id
          LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                                       AND mep.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID             = v.INTERVENANT_ID
        AND COALESCE(t.MISE_EN_PAIEMENT_ID,0) = COALESCE(v.MISE_EN_PAIEMENT_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_ID,0)
        AND COALESCE(t.FORMULE_RES_SERVICE_REF_ID,0) = COALESCE(v.FORMULE_RES_SERVICE_REF_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                   = v.ANNEE_ID,
      STRUCTURE_ID               = v.STRUCTURE_ID,
      PERIODE_PAIEMENT_ID        = v.PERIODE_PAIEMENT_ID,
      HEURES_A_PAYER             = v.HEURES_A_PAYER,
      HEURES_A_PAYER_POND        = v.HEURES_A_PAYER_POND,
      HEURES_DEMANDEES           = v.HEURES_DEMANDEES,
      HEURES_PAYEES              = v.HEURES_PAYEES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      MISE_EN_PAIEMENT_ID,
      PERIODE_PAIEMENT_ID,
      HEURES_A_PAYER,
      HEURES_A_PAYER_POND,
      HEURES_DEMANDEES,
      HEURES_PAYEES,
      FORMULE_RES_SERVICE_ID,
      FORMULE_RES_SERVICE_REF_ID,
      TO_DELETE

    ) VALUES (

      TBL_PAIEMENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.MISE_EN_PAIEMENT_ID,
      v.PERIODE_PAIEMENT_ID,
      v.HEURES_A_PAYER,
      v.HEURES_A_PAYER_POND,
      v.HEURES_DEMANDEES,
      v.HEURES_PAYEES,
      v.FORMULE_RES_SERVICE_ID,
      v.FORMULE_RES_SERVICE_REF_ID,
      0

    );

    DELETE TBL_PAIEMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PIECE_JOINTE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_PIECE_JOINTE t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH pjf AS (
          SELECT
            pjf.annee_id,
            pjf.type_piece_jointe_id,
            pjf.intervenant_id,
            COUNT(*) count,
            SUM(CASE WHEN validation_id IS NULL THEN 0 ELSE 1 END) validation,
            SUM(CASE WHEN fichier_id IS NULL THEN 0 ELSE 1 END) fichier
          FROM
            tbl_piece_jointe_fournie pjf
          GROUP BY
            pjf.annee_id,
            pjf.type_piece_jointe_id,
            pjf.intervenant_id
        )
        SELECT
          NVL( pjd.annee_id, pjf.annee_id ) annee_id,
          NVL( pjd.type_piece_jointe_id, pjf.type_piece_jointe_id ) type_piece_jointe_id,
          NVL( pjd.intervenant_id, pjf.intervenant_id ) intervenant_id,
          CASE WHEN pjd.intervenant_id IS NULL THEN 0 ELSE 1 END demandee,
          CASE WHEN pjf.fichier = pjf.count THEN 1 ELSE 0 END fournie,
          CASE WHEN pjf.validation = pjf.count THEN 1 ELSE 0 END validee,
          NVL(pjd.heures_pour_seuil,0) heures_pour_seuil
        FROM
          tbl_piece_jointe_demande pjd
          FULL JOIN pjf ON pjf.type_piece_jointe_id = pjd.type_piece_jointe_id AND pjf.intervenant_id = pjd.intervenant_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      DEMANDEE             = v.DEMANDEE,
      FOURNIE              = v.FOURNIE,
      VALIDEE              = v.VALIDEE,
      HEURES_POUR_SEUIL    = v.HEURES_POUR_SEUIL,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      DEMANDEE,
      FOURNIE,
      VALIDEE,
      HEURES_POUR_SEUIL,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.DEMANDEE,
      v.FOURNIE,
      v.VALIDEE,
      v.HEURES_POUR_SEUIL,
      0

    );

    DELETE TBL_PIECE_JOINTE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PIECE_JOINTE_DEMANDE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE_DEMANDE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_PIECE_JOINTE_DEMANDE t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH i_h AS (
          SELECT
            s.intervenant_id,
            sum(vh.heures) heures,
            sum(ep.taux_fc) fc
          FROM
                 service               s
            JOIN type_volume_horaire tvh ON tvh.code = ''PREVU''
            JOIN volume_horaire       vh ON vh.service_id = s.id 
                                        AND vh.type_volume_horaire_id = tvh.id
                                        AND vh.histo_destruction IS NULL
            JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id -- Service sur l''établissement
          WHERE
            s.histo_destruction IS NULL
            AND vh.motif_non_paiement_id IS NULL -- pas de motif de non paiement
          GROUP BY
            s.intervenant_id
        )
        SELECT
          i.annee_id                      annee_id,
          i.id                            intervenant_id,
          tpj.id                          type_piece_jointe_id,
          MAX(COALESCE(i_h.heures, 0))    heures_pour_seuil
        FROM
                    intervenant                 i
        
          LEFT JOIN dossier                     d ON d.intervenant_id = i.id
                                                 AND d.histo_destruction IS NULL
                                                
               JOIN type_piece_jointe_statut tpjs ON tpjs.statut_intervenant_id = i.statut_id
                                                 AND tpjs.histo_destruction IS NULL
                                                 AND i.annee_id BETWEEN COALESCE(tpjs.annee_debut_id,i.annee_id) AND COALESCE(tpjs.annee_fin_id,i.annee_id)
                                                 
               JOIN type_piece_jointe         tpj ON tpj.id = tpjs.type_piece_jointe_id
                                                 AND tpj.histo_destruction IS NULL
                                                 
          LEFT JOIN                           i_h ON i_h.intervenant_id = i.id
        WHERE
          -- Gestion de l''historique
          i.histo_destruction IS NULL
        
          -- Seuil HETD
          AND (COALESCE(i_h.heures,0) > COALESCE(tpjs.seuil_hetd,-1))
        
          -- En fonction du premier recrutement ou non
          AND (tpjs.premier_recrutement = 0 OR COALESCE(i.premier_recrutement,0) = 1)
        
          -- Le RIB n''est demandé QUE s''il est différent!!  
          AND CASE 
                WHEN tpjs.changement_rib = 0 OR d.id IS NULL THEN 1
                ELSE CASE WHEN replace(i.bic, '' '', '''') || ''-'' || replace(i.iban, '' '', '''') = d.rib THEN 0 ELSE 1 END
              END = 1
        
          -- Filtre FC
          AND (tpjs.fc = 0 OR i_h.fc > 0)
        GROUP BY
          i.annee_id,
          i.id,
          tpj.id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      HEURES_POUR_SEUIL    = v.HEURES_POUR_SEUIL,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      HEURES_POUR_SEUIL,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_DEMAND_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.HEURES_POUR_SEUIL,
      0

    );

    DELETE TBL_PIECE_JOINTE_DEMANDE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_PIECE_JOINTE_FOURNIE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_PIECE_JOINTE_FOURNIE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_PIECE_JOINTE_FOURNIE t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT 
          i.annee_id,
          pj.type_piece_jointe_id,
          pj.intervenant_id,
          pj.id piece_jointe_id,
          v.id validation_id,
          f.id fichier_id
        FROM
                    piece_jointe          pj
               JOIN intervenant            i ON i.id = pj.intervenant_id
                                            AND i.histo_destruction IS NULL
               
               JOIN piece_jointe_fichier pjf ON pjf.piece_jointe_id = pj.id
               JOIN fichier                f ON f.id = pjf.fichier_id
                                            AND f.histo_destruction IS NULL
                                            
          LEFT JOIN validation             v ON v.id = pj.validation_id
                                            AND v.histo_destruction IS NULL
        WHERE
          pj.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.TYPE_PIECE_JOINTE_ID = v.TYPE_PIECE_JOINTE_ID
        AND t.INTERVENANT_ID       = v.INTERVENANT_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND COALESCE(t.FICHIER_ID,0) = COALESCE(v.FICHIER_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID             = v.ANNEE_ID,
      PIECE_JOINTE_ID      = v.PIECE_JOINTE_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      TYPE_PIECE_JOINTE_ID,
      INTERVENANT_ID,
      VALIDATION_ID,
      FICHIER_ID,
      PIECE_JOINTE_ID,
      TO_DELETE

    ) VALUES (

      TBL_PIECE_JOINTE_FOURNI_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.TYPE_PIECE_JOINTE_ID,
      v.INTERVENANT_ID,
      v.VALIDATION_ID,
      v.FICHIER_ID,
      v.PIECE_JOINTE_ID,
      0

    );

    DELETE TBL_PIECE_JOINTE_FOURNIE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_SERVICE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_SERVICE t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
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
        
          CASE WHEN v.id IS NULL THEN 0 ELSE 1 END valide
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
        
          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE sum(t.heures) END heures,
          sum(valide) valide
        FROM
          t
          JOIN intervenant                              i ON i.id = t.intervenant_id
          JOIN statut_intervenant                      si ON si.id = i.statut_id
          JOIN type_intervenant                        ti ON ti.id = si.type_intervenant_id
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
          t.etape_histo) tv
      WHERE
        ' || conds || '

    ) v ON (
            COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND t.SERVICE_ID             = v.SERVICE_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                       = v.ANNEE_ID,
      INTERVENANT_ID                 = v.INTERVENANT_ID,
      PEUT_SAISIR_SERVICE            = v.PEUT_SAISIR_SERVICE,
      STRUCTURE_ID                   = v.STRUCTURE_ID,
      NBVH                           = v.NBVH,
      VALIDE                         = v.VALIDE,
      ELEMENT_PEDAGOGIQUE_ID         = v.ELEMENT_PEDAGOGIQUE_ID,
      ELEMENT_PEDAGOGIQUE_PERIODE_ID = v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      ETAPE_ID                       = v.ETAPE_ID,
      ELEMENT_PEDAGOGIQUE_HISTO      = v.ELEMENT_PEDAGOGIQUE_HISTO,
      ETAPE_HISTO                    = v.ETAPE_HISTO,
      HAS_HEURES_MAUVAISE_PERIODE    = v.HAS_HEURES_MAUVAISE_PERIODE,
      INTERVENANT_STRUCTURE_ID       = v.INTERVENANT_STRUCTURE_ID,
      TYPE_INTERVENANT_ID            = v.TYPE_INTERVENANT_ID,
      TYPE_INTERVENANT_CODE          = v.TYPE_INTERVENANT_CODE,
      TYPE_VOLUME_HORAIRE_CODE       = v.TYPE_VOLUME_HORAIRE_CODE,
      HEURES                         = v.HEURES,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      TYPE_VOLUME_HORAIRE_ID,
      STRUCTURE_ID,
      NBVH,
      VALIDE,
      ELEMENT_PEDAGOGIQUE_ID,
      ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      ETAPE_ID,
      ELEMENT_PEDAGOGIQUE_HISTO,
      ETAPE_HISTO,
      HAS_HEURES_MAUVAISE_PERIODE,
      SERVICE_ID,
      INTERVENANT_STRUCTURE_ID,
      TYPE_INTERVENANT_ID,
      TYPE_INTERVENANT_CODE,
      TYPE_VOLUME_HORAIRE_CODE,
      HEURES,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.STRUCTURE_ID,
      v.NBVH,
      v.VALIDE,
      v.ELEMENT_PEDAGOGIQUE_ID,
      v.ELEMENT_PEDAGOGIQUE_PERIODE_ID,
      v.ETAPE_ID,
      v.ELEMENT_PEDAGOGIQUE_HISTO,
      v.ETAPE_HISTO,
      v.HAS_HEURES_MAUVAISE_PERIODE,
      v.SERVICE_ID,
      v.INTERVENANT_STRUCTURE_ID,
      v.TYPE_INTERVENANT_ID,
      v.TYPE_INTERVENANT_CODE,
      v.TYPE_VOLUME_HORAIRE_CODE,
      v.HEURES,
      0

    );

    DELETE TBL_SERVICE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_SERVICE_REFERENTIEL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE_REFERENTIEL SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_SERVICE_REFERENTIEL t 
    USING (

      SELECT
        tv.*
      FROM
        (WITH t AS (
        
          SELECT
            i.annee_id,
            i.id intervenant_id,
            si.peut_saisir_referentiel peut_saisir_service,
            vh.type_volume_horaire_id,
            s.structure_id,
            CASE WHEN v.id IS NULL THEN 0 ELSE 1 END valide
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
        
        )  
        SELECT
          annee_id,
          intervenant_id,
          peut_saisir_service,
          type_volume_horaire_id,
          structure_id,
          CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END nbvh,
          sum(valide) valide
        FROM
          t
        WHERE 
          NOT (structure_id IS NOT NULL AND type_volume_horaire_id IS NULL)
        GROUP BY
          annee_id,
          intervenant_id,
          peut_saisir_service,
          type_volume_horaire_id,
          structure_id) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND COALESCE(t.TYPE_VOLUME_HORAIRE_ID,0) = COALESCE(v.TYPE_VOLUME_HORAIRE_ID,0)
        AND COALESCE(t.STRUCTURE_ID,0) = COALESCE(v.STRUCTURE_ID,0)

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      PEUT_SAISIR_SERVICE    = v.PEUT_SAISIR_SERVICE,
      NBVH                   = v.NBVH,
      VALIDE                 = v.VALIDE,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      TYPE_VOLUME_HORAIRE_ID,
      STRUCTURE_ID,
      NBVH,
      VALIDE,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_REFERENTIEL_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.STRUCTURE_ID,
      v.NBVH,
      v.VALIDE,
      0

    );

    DELETE TBL_SERVICE_REFERENTIEL WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_SERVICE_SAISIE( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_SERVICE_SAISIE SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_SERVICE_SAISIE t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT
          i.annee_id,
          i.id intervenant_id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel,
          SUM( CASE WHEN tvhs.code = ''PREVU''   THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_prev,
          SUM( CASE WHEN tvhs.code = ''PREVU''   THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_prev,
          SUM( CASE WHEN tvhs.code = ''REALISE'' THEN NVL(vh .heures,0) ELSE 0 END ) heures_service_real,
          SUM( CASE WHEN tvhs.code = ''REALISE'' THEN NVL(vhr.heures,0) ELSE 0 END ) heures_referentiel_real
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
        GROUP BY
          i.annee_id,
          i.id,
          si.peut_saisir_service,
          si.peut_saisir_referentiel) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID = v.INTERVENANT_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID                = v.ANNEE_ID,
      PEUT_SAISIR_SERVICE     = v.PEUT_SAISIR_SERVICE,
      PEUT_SAISIR_REFERENTIEL = v.PEUT_SAISIR_REFERENTIEL,
      HEURES_SERVICE_PREV     = v.HEURES_SERVICE_PREV,
      HEURES_REFERENTIEL_PREV = v.HEURES_REFERENTIEL_PREV,
      HEURES_SERVICE_REAL     = v.HEURES_SERVICE_REAL,
      HEURES_REFERENTIEL_REAL = v.HEURES_REFERENTIEL_REAL,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      PEUT_SAISIR_SERVICE,
      PEUT_SAISIR_REFERENTIEL,
      HEURES_SERVICE_PREV,
      HEURES_REFERENTIEL_PREV,
      HEURES_SERVICE_REAL,
      HEURES_REFERENTIEL_REAL,
      TO_DELETE

    ) VALUES (

      TBL_SERVICE_SAISIE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.PEUT_SAISIR_SERVICE,
      v.PEUT_SAISIR_REFERENTIEL,
      v.HEURES_SERVICE_PREV,
      v.HEURES_REFERENTIEL_PREV,
      v.HEURES_SERVICE_REAL,
      v.HEURES_REFERENTIEL_REAL,
      0

    );

    DELETE TBL_SERVICE_SAISIE WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_VALIDATION_ENSEIGNEMENT( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_VALIDATION_ENSEIGNEMENT SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_VALIDATION_ENSEIGNEMENT t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT DISTINCT
          i.annee_id,
          i.id intervenant_id,
          CASE WHEN rsv.priorite = ''affectation'' THEN
            COALESCE( i.structure_id, ep.structure_id, str.id )
          ELSE
            COALESCE( ep.structure_id, i.structure_id, str.id )
          END structure_id,
          vh.type_volume_horaire_id,
          s.id service_id,
          vh.id volume_horaire_id,
          v.id validation_id
        FROM
          service s
          JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
          JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut_intervenant si ON si.id = i.statut_id
          JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
          LEFT JOIN structure str ON str.niveau = 1 AND str.histo_destruction IS NULL
          LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
          LEFT JOIN validation_vol_horaire vvh ON vvh.volume_horaire_id = vh.id
          LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND t.STRUCTURE_ID           = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_ID             = v.SERVICE_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND t.VOLUME_HORAIRE_ID      = v.VOLUME_HORAIRE_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_ID,
      VALIDATION_ID,
      VOLUME_HORAIRE_ID,
      TO_DELETE

    ) VALUES (

      TBL_VALIDATION_ENSEIGNE_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_ID,
      v.VALIDATION_ID,
      v.VOLUME_HORAIRE_ID,
      0

    );

    DELETE TBL_VALIDATION_ENSEIGNEMENT WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;



  PROCEDURE C_VALIDATION_REFERENTIEL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    conds CLOB;    
  BEGIN
    conds := params_to_conds( params );

    EXECUTE IMMEDIATE 'BEGIN

    UPDATE TBL_VALIDATION_REFERENTIEL SET to_delete = 1 WHERE ' || conds || ';

    MERGE INTO 
      TBL_VALIDATION_REFERENTIEL t 
    USING (

      SELECT
        tv.*
      FROM
        (SELECT DISTINCT
          i.annee_id,
          i.id intervenant_id,
          CASE WHEN rsv.priorite = ''affectation'' THEN
            COALESCE( i.structure_id, s.structure_id, str.id )
          ELSE
            COALESCE( s.structure_id, i.structure_id, str.id )
          END structure_id,
          vh.type_volume_horaire_id,
          s.id service_referentiel_id,
          vh.id volume_horaire_ref_id,
          v.id validation_id
        FROM
          service_referentiel s
          JOIN volume_horaire_ref vh ON vh.service_referentiel_id = s.id AND vh.histo_destruction IS NULL
          JOIN intervenant i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut_intervenant si ON si.id = i.statut_id
          JOIN regle_structure_validation rsv ON rsv.type_intervenant_id = si.type_intervenant_id AND rsv.type_volume_horaire_id = vh.type_volume_horaire_id
          LEFT JOIN structure str ON str.niveau = 1 AND str.histo_destruction IS NULL
          LEFT JOIN validation_vol_horaire_ref vvh ON vvh.volume_horaire_ref_id = vh.id
          LEFT JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL
        WHERE
          s.histo_destruction IS NULL) tv
      WHERE
        ' || conds || '

    ) v ON (
            t.INTERVENANT_ID         = v.INTERVENANT_ID
        AND t.STRUCTURE_ID           = v.STRUCTURE_ID
        AND t.TYPE_VOLUME_HORAIRE_ID = v.TYPE_VOLUME_HORAIRE_ID
        AND t.SERVICE_REFERENTIEL_ID = v.SERVICE_REFERENTIEL_ID
        AND COALESCE(t.VALIDATION_ID,0) = COALESCE(v.VALIDATION_ID,0)
        AND t.VOLUME_HORAIRE_REF_ID  = v.VOLUME_HORAIRE_REF_ID

    ) WHEN MATCHED THEN UPDATE SET

      ANNEE_ID               = v.ANNEE_ID,
      to_delete = 0

    WHEN NOT MATCHED THEN INSERT (

      ID,
      ANNEE_ID,
      INTERVENANT_ID,
      STRUCTURE_ID,
      TYPE_VOLUME_HORAIRE_ID,
      SERVICE_REFERENTIEL_ID,
      VALIDATION_ID,
      VOLUME_HORAIRE_REF_ID,
      TO_DELETE

    ) VALUES (

      TBL_VALIDATION_REFERENT_ID_SEQ.NEXTVAL,
      v.ANNEE_ID,
      v.INTERVENANT_ID,
      v.STRUCTURE_ID,
      v.TYPE_VOLUME_HORAIRE_ID,
      v.SERVICE_REFERENTIEL_ID,
      v.VALIDATION_ID,
      v.VOLUME_HORAIRE_REF_ID,
      0

    );

    DELETE TBL_VALIDATION_REFERENTIEL WHERE to_delete = 1 AND ' || conds || ';

    END;';

  END;

  -- END OF AUTOMATIC GENERATION --

END UNICAEN_TBL;
/
---------------------------
--Nouveau PACKAGE BODY
--UNICAEN_IMPORT_AUTOGEN_PROCS__
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_IMPORT_AUTOGEN_PROCS__" IS

  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;


  PROCEDURE VOLUME_HORAIRE_ENS IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_VOLUME_HORAIRE_ENS%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'VOLUME_HORAIRE_ENS';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_VOLUME_HORAIRE_ENS.* FROM V_DIFF_VOLUME_HORAIRE_ENS ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO VOLUME_HORAIRE_ENS
              ( id, ELEMENT_PEDAGOGIQUE_ID,GROUPES,HEURES,TYPE_INTERVENTION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,VOLUME_HORAIRE_ENS_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.GROUPES,diff_row.HEURES,diff_row.TYPE_INTERVENTION_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GROUPES = 1 AND IN_COLUMN_LIST('GROUPES',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET GROUPES = diff_row.GROUPES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE VOLUME_HORAIRE_ENS SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GROUPES = 1 AND IN_COLUMN_LIST('GROUPES',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET GROUPES = diff_row.GROUPES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_HEURES = 1 AND IN_COLUMN_LIST('HEURES',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET HEURES = diff_row.HEURES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE VOLUME_HORAIRE_ENS SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
            UPDATE VOLUME_HORAIRE_ENS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'VOLUME_HORAIRE_ENS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END VOLUME_HORAIRE_ENS;



  PROCEDURE TYPE_MODULATEUR_EP IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_TYPE_MODULATEUR_EP%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'TYPE_MODULATEUR_EP';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_TYPE_MODULATEUR_EP.* FROM V_DIFF_TYPE_MODULATEUR_EP ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO TYPE_MODULATEUR_EP
              ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_MODULATEUR_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_MODULATEUR_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_MODULATEUR_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE TYPE_MODULATEUR_EP SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_MODULATEUR_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_MODULATEUR_ID = 1 AND IN_COLUMN_LIST('TYPE_MODULATEUR_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_MODULATEUR_EP SET TYPE_MODULATEUR_ID = diff_row.TYPE_MODULATEUR_ID WHERE ID = diff_row.id; END IF;
            UPDATE TYPE_MODULATEUR_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_MODULATEUR_EP', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END TYPE_MODULATEUR_EP;



  PROCEDURE TYPE_INTERVENTION_EP IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_TYPE_INTERVENTION_EP%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'TYPE_INTERVENTION_EP';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_TYPE_INTERVENTION_EP.* FROM V_DIFF_TYPE_INTERVENTION_EP ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO TYPE_INTERVENTION_EP
              ( id, ELEMENT_PEDAGOGIQUE_ID,TYPE_INTERVENTION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_INTERVENTION_EP_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TYPE_INTERVENTION_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE TYPE_INTERVENTION_EP SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_INTERVENTION_EP SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_INTERVENTION_ID = 1 AND IN_COLUMN_LIST('TYPE_INTERVENTION_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_INTERVENTION_EP SET TYPE_INTERVENTION_ID = diff_row.TYPE_INTERVENTION_ID WHERE ID = diff_row.id; END IF;
            UPDATE TYPE_INTERVENTION_EP SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_INTERVENTION_EP', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END TYPE_INTERVENTION_EP;



  PROCEDURE TYPE_FORMATION IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_TYPE_FORMATION%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'TYPE_FORMATION';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_TYPE_FORMATION.* FROM V_DIFF_TYPE_FORMATION ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO TYPE_FORMATION
              ( id, GROUPE_ID,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.GROUPE_ID,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_GROUPE_ID = 1 AND IN_COLUMN_LIST('GROUPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_FORMATION SET GROUPE_ID = diff_row.GROUPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'TYPE_FORMATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END TYPE_FORMATION;



  PROCEDURE STRUCTURE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_STRUCTURE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'STRUCTURE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_STRUCTURE.* FROM V_DIFF_STRUCTURE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO STRUCTURE
              ( id, CODE,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CODE,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE STRUCTURE SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE STRUCTURE SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE STRUCTURE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE STRUCTURE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END STRUCTURE;



  PROCEDURE SCENARIO_LIEN IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_SCENARIO_LIEN%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'SCENARIO_LIEN';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_SCENARIO_LIEN.* FROM V_DIFF_SCENARIO_LIEN ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO SCENARIO_LIEN
              ( id, ACTIF,CHOIX_MAXIMUM,CHOIX_MINIMUM,LIEN_ID,POIDS,SCENARIO_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,SCENARIO_LIEN_ID_SEQ.NEXTVAL), diff_row.ACTIF,diff_row.CHOIX_MAXIMUM,diff_row.CHOIX_MINIMUM,diff_row.LIEN_ID,diff_row.POIDS,diff_row.SCENARIO_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ACTIF = 1 AND IN_COLUMN_LIST('ACTIF',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET ACTIF = diff_row.ACTIF WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CHOIX_MAXIMUM = 1 AND IN_COLUMN_LIST('CHOIX_MAXIMUM',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET CHOIX_MAXIMUM = diff_row.CHOIX_MAXIMUM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CHOIX_MINIMUM = 1 AND IN_COLUMN_LIST('CHOIX_MINIMUM',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET CHOIX_MINIMUM = diff_row.CHOIX_MINIMUM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIEN_ID = 1 AND IN_COLUMN_LIST('LIEN_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET LIEN_ID = diff_row.LIEN_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_POIDS = 1 AND IN_COLUMN_LIST('POIDS',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET POIDS = diff_row.POIDS WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SCENARIO_ID = 1 AND IN_COLUMN_LIST('SCENARIO_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET SCENARIO_ID = diff_row.SCENARIO_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE SCENARIO_LIEN SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ACTIF = 1 AND IN_COLUMN_LIST('ACTIF',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET ACTIF = diff_row.ACTIF WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CHOIX_MAXIMUM = 1 AND IN_COLUMN_LIST('CHOIX_MAXIMUM',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET CHOIX_MAXIMUM = diff_row.CHOIX_MAXIMUM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CHOIX_MINIMUM = 1 AND IN_COLUMN_LIST('CHOIX_MINIMUM',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET CHOIX_MINIMUM = diff_row.CHOIX_MINIMUM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIEN_ID = 1 AND IN_COLUMN_LIST('LIEN_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET LIEN_ID = diff_row.LIEN_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_POIDS = 1 AND IN_COLUMN_LIST('POIDS',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET POIDS = diff_row.POIDS WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SCENARIO_ID = 1 AND IN_COLUMN_LIST('SCENARIO_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE SCENARIO_LIEN SET SCENARIO_ID = diff_row.SCENARIO_ID WHERE ID = diff_row.id; END IF;
            UPDATE SCENARIO_LIEN SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'SCENARIO_LIEN', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END SCENARIO_LIEN;



  PROCEDURE PAYS IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_PAYS%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'PAYS';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_PAYS.* FROM V_DIFF_PAYS ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO PAYS
              ( id, LIBELLE_COURT,LIBELLE_LONG,TEMOIN_UE,VALIDITE_DEBUT,VALIDITE_FIN, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,PAYS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.TEMOIN_UE,diff_row.VALIDITE_DEBUT,diff_row.VALIDITE_FIN, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEMOIN_UE = 1 AND IN_COLUMN_LIST('TEMOIN_UE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET TEMOIN_UE = diff_row.TEMOIN_UE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE PAYS SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEMOIN_UE = 1 AND IN_COLUMN_LIST('TEMOIN_UE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET TEMOIN_UE = diff_row.TEMOIN_UE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_DEBUT = 1 AND IN_COLUMN_LIST('VALIDITE_DEBUT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET VALIDITE_DEBUT = diff_row.VALIDITE_DEBUT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VALIDITE_FIN = 1 AND IN_COLUMN_LIST('VALIDITE_FIN',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE PAYS SET VALIDITE_FIN = diff_row.VALIDITE_FIN WHERE ID = diff_row.id; END IF;
            UPDATE PAYS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'PAYS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END PAYS;



  PROCEDURE NOEUD IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_NOEUD%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'NOEUD';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_NOEUD.* FROM V_DIFF_NOEUD ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO NOEUD
              ( id, ANNEE_ID,CODE,ELEMENT_PEDAGOGIQUE_ID,ETAPE_ID,LIBELLE,LISTE,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,NOEUD_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.CODE,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.ETAPE_ID,diff_row.LIBELLE,diff_row.LISTE,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LISTE = 1 AND IN_COLUMN_LIST('LISTE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET LISTE = diff_row.LISTE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE NOEUD SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LISTE = 1 AND IN_COLUMN_LIST('LISTE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET LISTE = diff_row.LISTE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE NOEUD SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE NOEUD SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'NOEUD', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END NOEUD;



  PROCEDURE LIEN IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_LIEN%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'LIEN';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_LIEN.* FROM V_DIFF_LIEN ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO LIEN
              ( id, NOEUD_INF_ID,NOEUD_SUP_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,LIEN_ID_SEQ.NEXTVAL), diff_row.NOEUD_INF_ID,diff_row.NOEUD_SUP_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_NOEUD_INF_ID = 1 AND IN_COLUMN_LIST('NOEUD_INF_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE LIEN SET NOEUD_INF_ID = diff_row.NOEUD_INF_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOEUD_SUP_ID = 1 AND IN_COLUMN_LIST('NOEUD_SUP_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE LIEN SET NOEUD_SUP_ID = diff_row.NOEUD_SUP_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE LIEN SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE LIEN SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_NOEUD_INF_ID = 1 AND IN_COLUMN_LIST('NOEUD_INF_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE LIEN SET NOEUD_INF_ID = diff_row.NOEUD_INF_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOEUD_SUP_ID = 1 AND IN_COLUMN_LIST('NOEUD_SUP_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE LIEN SET NOEUD_SUP_ID = diff_row.NOEUD_SUP_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE LIEN SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE LIEN SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'LIEN', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END LIEN;



  PROCEDURE INTERVENANT IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_INTERVENANT%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'INTERVENANT';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_INTERVENANT.* FROM V_DIFF_INTERVENANT ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO INTERVENANT
              ( id, ANNEE_ID,BIC,CIVILITE_ID,CODE,CRITERE_RECHERCHE,DATE_NAISSANCE,DEP_NAISSANCE_ID,DISCIPLINE_ID,EMAIL,GRADE_ID,IBAN,NOM_PATRONYMIQUE,NOM_USUEL,NUMERO_INSEE,NUMERO_INSEE_CLE,NUMERO_INSEE_PROVISOIRE,PAYS_NAISSANCE_ID,PAYS_NATIONALITE_ID,PRENOM,STATUT_ID,STRUCTURE_ID,TEL_MOBILE,TEL_PRO,UTILISATEUR_CODE,VILLE_NAISSANCE_CODE_INSEE,VILLE_NAISSANCE_LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,INTERVENANT_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.BIC,diff_row.CIVILITE_ID,diff_row.CODE,diff_row.CRITERE_RECHERCHE,diff_row.DATE_NAISSANCE,diff_row.DEP_NAISSANCE_ID,diff_row.DISCIPLINE_ID,diff_row.EMAIL,diff_row.GRADE_ID,diff_row.IBAN,diff_row.NOM_PATRONYMIQUE,diff_row.NOM_USUEL,diff_row.NUMERO_INSEE,diff_row.NUMERO_INSEE_CLE,diff_row.NUMERO_INSEE_PROVISOIRE,diff_row.PAYS_NAISSANCE_ID,diff_row.PAYS_NATIONALITE_ID,diff_row.PRENOM,diff_row.STATUT_ID,diff_row.STRUCTURE_ID,diff_row.TEL_MOBILE,diff_row.TEL_PRO,diff_row.UTILISATEUR_CODE,diff_row.VILLE_NAISSANCE_CODE_INSEE,diff_row.VILLE_NAISSANCE_LIBELLE, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CRITERE_RECHERCHE = 1 AND IN_COLUMN_LIST('CRITERE_RECHERCHE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET CRITERE_RECHERCHE = diff_row.CRITERE_RECHERCHE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_ID = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET DEP_NAISSANCE_ID = diff_row.DEP_NAISSANCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GRADE_ID = 1 AND IN_COLUMN_LIST('GRADE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET GRADE_ID = diff_row.GRADE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_ID = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET PAYS_NAISSANCE_ID = diff_row.PAYS_NAISSANCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_ID = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET PAYS_NATIONALITE_ID = diff_row.PAYS_NATIONALITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UTILISATEUR_CODE = 1 AND IN_COLUMN_LIST('UTILISATEUR_CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET UTILISATEUR_CODE = diff_row.UTILISATEUR_CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_BIC = 1 AND IN_COLUMN_LIST('BIC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET BIC = diff_row.BIC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CIVILITE_ID = 1 AND IN_COLUMN_LIST('CIVILITE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET CIVILITE_ID = diff_row.CIVILITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CRITERE_RECHERCHE = 1 AND IN_COLUMN_LIST('CRITERE_RECHERCHE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET CRITERE_RECHERCHE = diff_row.CRITERE_RECHERCHE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DATE_NAISSANCE = 1 AND IN_COLUMN_LIST('DATE_NAISSANCE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET DATE_NAISSANCE = diff_row.DATE_NAISSANCE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DEP_NAISSANCE_ID = 1 AND IN_COLUMN_LIST('DEP_NAISSANCE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET DEP_NAISSANCE_ID = diff_row.DEP_NAISSANCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_EMAIL = 1 AND IN_COLUMN_LIST('EMAIL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET EMAIL = diff_row.EMAIL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_GRADE_ID = 1 AND IN_COLUMN_LIST('GRADE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET GRADE_ID = diff_row.GRADE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_IBAN = 1 AND IN_COLUMN_LIST('IBAN',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET IBAN = diff_row.IBAN WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_PATRONYMIQUE = 1 AND IN_COLUMN_LIST('NOM_PATRONYMIQUE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NOM_PATRONYMIQUE = diff_row.NOM_PATRONYMIQUE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_USUEL = 1 AND IN_COLUMN_LIST('NOM_USUEL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NOM_USUEL = diff_row.NOM_USUEL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NUMERO_INSEE = diff_row.NUMERO_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_CLE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_CLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NUMERO_INSEE_CLE = diff_row.NUMERO_INSEE_CLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NUMERO_INSEE_PROVISOIRE = 1 AND IN_COLUMN_LIST('NUMERO_INSEE_PROVISOIRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET NUMERO_INSEE_PROVISOIRE = diff_row.NUMERO_INSEE_PROVISOIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NAISSANCE_ID = 1 AND IN_COLUMN_LIST('PAYS_NAISSANCE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET PAYS_NAISSANCE_ID = diff_row.PAYS_NAISSANCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_NATIONALITE_ID = 1 AND IN_COLUMN_LIST('PAYS_NATIONALITE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET PAYS_NATIONALITE_ID = diff_row.PAYS_NATIONALITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRENOM = 1 AND IN_COLUMN_LIST('PRENOM',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET PRENOM = diff_row.PRENOM WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STATUT_ID = 1 AND IN_COLUMN_LIST('STATUT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET STATUT_ID = diff_row.STATUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_MOBILE = 1 AND IN_COLUMN_LIST('TEL_MOBILE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET TEL_MOBILE = diff_row.TEL_MOBILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_PRO = 1 AND IN_COLUMN_LIST('TEL_PRO',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET TEL_PRO = diff_row.TEL_PRO WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UTILISATEUR_CODE = 1 AND IN_COLUMN_LIST('UTILISATEUR_CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET UTILISATEUR_CODE = diff_row.UTILISATEUR_CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_CODE_INSEE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_CODE_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET VILLE_NAISSANCE_CODE_INSEE = diff_row.VILLE_NAISSANCE_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE_NAISSANCE_LIBELLE = 1 AND IN_COLUMN_LIST('VILLE_NAISSANCE_LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE INTERVENANT SET VILLE_NAISSANCE_LIBELLE = diff_row.VILLE_NAISSANCE_LIBELLE WHERE ID = diff_row.id; END IF;
            UPDATE INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'INTERVENANT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END INTERVENANT;



  PROCEDURE GROUPE_TYPE_FORMATION IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_GROUPE_TYPE_FORMATION%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'GROUPE_TYPE_FORMATION';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_GROUPE_TYPE_FORMATION.* FROM V_DIFF_GROUPE_TYPE_FORMATION ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO GROUPE_TYPE_FORMATION
              ( id, LIBELLE_COURT,LIBELLE_LONG,ORDRE,PERTINENCE_NIVEAU, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,GROUPE_TYPE_FORMATION_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG,diff_row.ORDRE,diff_row.PERTINENCE_NIVEAU, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE GROUPE_TYPE_FORMATION SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERTINENCE_NIVEAU = 1 AND IN_COLUMN_LIST('PERTINENCE_NIVEAU',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GROUPE_TYPE_FORMATION SET PERTINENCE_NIVEAU = diff_row.PERTINENCE_NIVEAU WHERE ID = diff_row.id; END IF;
            UPDATE GROUPE_TYPE_FORMATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'GROUPE_TYPE_FORMATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END GROUPE_TYPE_FORMATION;



  PROCEDURE GRADE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_GRADE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'GRADE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_GRADE.* FROM V_DIFF_GRADE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO GRADE
              ( id, CORPS_ID,ECHELLE,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,GRADE_ID_SEQ.NEXTVAL), diff_row.CORPS_ID,diff_row.ECHELLE,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ECHELLE = 1 AND IN_COLUMN_LIST('ECHELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET ECHELLE = diff_row.ECHELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE GRADE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CORPS_ID = 1 AND IN_COLUMN_LIST('CORPS_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET CORPS_ID = diff_row.CORPS_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ECHELLE = 1 AND IN_COLUMN_LIST('ECHELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET ECHELLE = diff_row.ECHELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE GRADE SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE GRADE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'GRADE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END GRADE;



  PROCEDURE ETAPE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_ETAPE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'ETAPE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_ETAPE.* FROM V_DIFF_ETAPE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO ETAPE
              ( id, ANNEE_ID,CODE,DOMAINE_FONCTIONNEL_ID,LIBELLE,NIVEAU,SPECIFIQUE_ECHANGES,STRUCTURE_ID,TYPE_FORMATION_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ETAPE_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.CODE,diff_row.DOMAINE_FONCTIONNEL_ID,diff_row.LIBELLE,diff_row.NIVEAU,diff_row.SPECIFIQUE_ECHANGES,diff_row.STRUCTURE_ID,diff_row.TYPE_FORMATION_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DOMAINE_FONCTIONNEL_ID = 1 AND IN_COLUMN_LIST('DOMAINE_FONCTIONNEL_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET DOMAINE_FONCTIONNEL_ID = diff_row.DOMAINE_FONCTIONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE ETAPE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DOMAINE_FONCTIONNEL_ID = 1 AND IN_COLUMN_LIST('DOMAINE_FONCTIONNEL_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET DOMAINE_FONCTIONNEL_ID = diff_row.DOMAINE_FONCTIONNEL_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NIVEAU = 1 AND IN_COLUMN_LIST('NIVEAU',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET NIVEAU = diff_row.NIVEAU WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_SPECIFIQUE_ECHANGES = 1 AND IN_COLUMN_LIST('SPECIFIQUE_ECHANGES',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET SPECIFIQUE_ECHANGES = diff_row.SPECIFIQUE_ECHANGES WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_FORMATION_ID = 1 AND IN_COLUMN_LIST('TYPE_FORMATION_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETAPE SET TYPE_FORMATION_ID = diff_row.TYPE_FORMATION_ID WHERE ID = diff_row.id; END IF;
            UPDATE ETAPE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'ETAPE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END ETAPE;



  PROCEDURE ETABLISSEMENT IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_ETABLISSEMENT%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'ETABLISSEMENT';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_ETABLISSEMENT.* FROM V_DIFF_ETABLISSEMENT ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO ETABLISSEMENT
              ( id, DEPARTEMENT,LIBELLE,LOCALISATION, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ETABLISSEMENT_ID_SEQ.NEXTVAL), diff_row.DEPARTEMENT,diff_row.LIBELLE,diff_row.LOCALISATION, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE ETABLISSEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_DEPARTEMENT = 1 AND IN_COLUMN_LIST('DEPARTEMENT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETABLISSEMENT SET DEPARTEMENT = diff_row.DEPARTEMENT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETABLISSEMENT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALISATION = 1 AND IN_COLUMN_LIST('LOCALISATION',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ETABLISSEMENT SET LOCALISATION = diff_row.LOCALISATION WHERE ID = diff_row.id; END IF;
            UPDATE ETABLISSEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'ETABLISSEMENT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END ETABLISSEMENT;



  PROCEDURE ELEMENT_TAUX_REGIMES IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_ELEMENT_TAUX_REGIMES%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'ELEMENT_TAUX_REGIMES';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_ELEMENT_TAUX_REGIMES.* FROM V_DIFF_ELEMENT_TAUX_REGIMES ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO ELEMENT_TAUX_REGIMES
              ( id, ELEMENT_PEDAGOGIQUE_ID,TAUX_FA,TAUX_FC,TAUX_FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ELEMENT_TAUX_REGIMES_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE ELEMENT_TAUX_REGIMES SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_TAUX_REGIMES SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
            UPDATE ELEMENT_TAUX_REGIMES SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'ELEMENT_TAUX_REGIMES', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END ELEMENT_TAUX_REGIMES;



  PROCEDURE ELEMENT_PEDAGOGIQUE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_ELEMENT_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'ELEMENT_PEDAGOGIQUE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_ELEMENT_PEDAGOGIQUE.* FROM V_DIFF_ELEMENT_PEDAGOGIQUE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO ELEMENT_PEDAGOGIQUE
              ( id, ANNEE_ID,CODE,DISCIPLINE_ID,ETAPE_ID,FA,FC,FI,LIBELLE,PERIODE_ID,STRUCTURE_ID,TAUX_FA,TAUX_FC,TAUX_FI,TAUX_FOAD, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ELEMENT_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.CODE,diff_row.DISCIPLINE_ID,diff_row.ETAPE_ID,diff_row.FA,diff_row.FC,diff_row.FI,diff_row.LIBELLE,diff_row.PERIODE_ID,diff_row.STRUCTURE_ID,diff_row.TAUX_FA,diff_row.TAUX_FC,diff_row.TAUX_FI,diff_row.TAUX_FOAD, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE ELEMENT_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_DISCIPLINE_ID = 1 AND IN_COLUMN_LIST('DISCIPLINE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET DISCIPLINE_ID = diff_row.DISCIPLINE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PERIODE_ID = 1 AND IN_COLUMN_LIST('PERIODE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET PERIODE_ID = diff_row.PERIODE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FA = 1 AND IN_COLUMN_LIST('TAUX_FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FA = diff_row.TAUX_FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FC = 1 AND IN_COLUMN_LIST('TAUX_FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FC = diff_row.TAUX_FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FI = 1 AND IN_COLUMN_LIST('TAUX_FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FI = diff_row.TAUX_FI WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TAUX_FOAD = 1 AND IN_COLUMN_LIST('TAUX_FOAD',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ELEMENT_PEDAGOGIQUE SET TAUX_FOAD = diff_row.TAUX_FOAD WHERE ID = diff_row.id; END IF;
            UPDATE ELEMENT_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'ELEMENT_PEDAGOGIQUE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END ELEMENT_PEDAGOGIQUE;



  PROCEDURE EFFECTIFS IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_EFFECTIFS%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'EFFECTIFS';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_EFFECTIFS.* FROM V_DIFF_EFFECTIFS ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO EFFECTIFS
              ( id, ANNEE_ID,ELEMENT_PEDAGOGIQUE_ID,FA,FC,FI, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,EFFECTIFS_ID_SEQ.NEXTVAL), diff_row.ANNEE_ID,diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.FA,diff_row.FC,diff_row.FI, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE EFFECTIFS SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ANNEE_ID = 1 AND IN_COLUMN_LIST('ANNEE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET ANNEE_ID = diff_row.ANNEE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FA = 1 AND IN_COLUMN_LIST('FA',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET FA = diff_row.FA WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FC = 1 AND IN_COLUMN_LIST('FC',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET FC = diff_row.FC WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_FI = 1 AND IN_COLUMN_LIST('FI',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE EFFECTIFS SET FI = diff_row.FI WHERE ID = diff_row.id; END IF;
            UPDATE EFFECTIFS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'EFFECTIFS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END EFFECTIFS;



  PROCEDURE DOMAINE_FONCTIONNEL IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_DOMAINE_FONCTIONNEL%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'DOMAINE_FONCTIONNEL';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_DOMAINE_FONCTIONNEL.* FROM V_DIFF_DOMAINE_FONCTIONNEL ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO DOMAINE_FONCTIONNEL
              ( id, LIBELLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,DOMAINE_FONCTIONNEL_ID_SEQ.NEXTVAL), diff_row.LIBELLE, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DOMAINE_FONCTIONNEL SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE DOMAINE_FONCTIONNEL SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DOMAINE_FONCTIONNEL SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
            UPDATE DOMAINE_FONCTIONNEL SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'DOMAINE_FONCTIONNEL', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END DOMAINE_FONCTIONNEL;



  PROCEDURE DEPARTEMENT IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_DEPARTEMENT%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'DEPARTEMENT';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_DEPARTEMENT.* FROM V_DIFF_DEPARTEMENT ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO DEPARTEMENT
              ( id, CODE,LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,DEPARTEMENT_ID_SEQ.NEXTVAL), diff_row.CODE,diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DEPARTEMENT SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DEPARTEMENT SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DEPARTEMENT SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE DEPARTEMENT SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DEPARTEMENT SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DEPARTEMENT SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE DEPARTEMENT SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE DEPARTEMENT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'DEPARTEMENT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END DEPARTEMENT;



  PROCEDURE CORPS IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_CORPS%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'CORPS';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_CORPS.* FROM V_DIFF_CORPS ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO CORPS
              ( id, LIBELLE_COURT,LIBELLE_LONG, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CORPS_ID_SEQ.NEXTVAL), diff_row.LIBELLE_COURT,diff_row.LIBELLE_LONG, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE CORPS SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_LIBELLE_COURT = 1 AND IN_COLUMN_LIST('LIBELLE_COURT',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CORPS SET LIBELLE_COURT = diff_row.LIBELLE_COURT WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE_LONG = 1 AND IN_COLUMN_LIST('LIBELLE_LONG',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CORPS SET LIBELLE_LONG = diff_row.LIBELLE_LONG WHERE ID = diff_row.id; END IF;
            UPDATE CORPS SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'CORPS', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END CORPS;



  PROCEDURE CHEMIN_PEDAGOGIQUE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_CHEMIN_PEDAGOGIQUE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'CHEMIN_PEDAGOGIQUE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_CHEMIN_PEDAGOGIQUE.* FROM V_DIFF_CHEMIN_PEDAGOGIQUE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO CHEMIN_PEDAGOGIQUE
              ( id, ELEMENT_PEDAGOGIQUE_ID,ETAPE_ID,ORDRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CHEMIN_PEDAGOGIQUE_ID_SEQ.NEXTVAL), diff_row.ELEMENT_PEDAGOGIQUE_ID,diff_row.ETAPE_ID,diff_row.ORDRE, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE CHEMIN_PEDAGOGIQUE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ELEMENT_PEDAGOGIQUE_ID = 1 AND IN_COLUMN_LIST('ELEMENT_PEDAGOGIQUE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CHEMIN_PEDAGOGIQUE SET ELEMENT_PEDAGOGIQUE_ID = diff_row.ELEMENT_PEDAGOGIQUE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ETAPE_ID = 1 AND IN_COLUMN_LIST('ETAPE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CHEMIN_PEDAGOGIQUE SET ETAPE_ID = diff_row.ETAPE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_ORDRE = 1 AND IN_COLUMN_LIST('ORDRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CHEMIN_PEDAGOGIQUE SET ORDRE = diff_row.ORDRE WHERE ID = diff_row.id; END IF;
            UPDATE CHEMIN_PEDAGOGIQUE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'CHEMIN_PEDAGOGIQUE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END CHEMIN_PEDAGOGIQUE;



  PROCEDURE CENTRE_COUT_STRUCTURE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_CENTRE_COUT_STRUCTURE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'CENTRE_COUT_STRUCTURE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_CENTRE_COUT_STRUCTURE.* FROM V_DIFF_CENTRE_COUT_STRUCTURE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO CENTRE_COUT_STRUCTURE
              ( id, CENTRE_COUT_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CENTRE_COUT_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CENTRE_COUT_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CENTRE_COUT_ID = 1 AND IN_COLUMN_LIST('CENTRE_COUT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT_STRUCTURE SET CENTRE_COUT_ID = diff_row.CENTRE_COUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE CENTRE_COUT_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CENTRE_COUT_ID = 1 AND IN_COLUMN_LIST('CENTRE_COUT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT_STRUCTURE SET CENTRE_COUT_ID = diff_row.CENTRE_COUT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE CENTRE_COUT_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'CENTRE_COUT_STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END CENTRE_COUT_STRUCTURE;



  PROCEDURE CENTRE_COUT IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_CENTRE_COUT%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'CENTRE_COUT';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_CENTRE_COUT.* FROM V_DIFF_CENTRE_COUT ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO CENTRE_COUT
              ( id, ACTIVITE_ID,CODE,LIBELLE,PARENT_ID,TYPE_RESSOURCE_ID,UNITE_BUDGETAIRE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,CENTRE_COUT_ID_SEQ.NEXTVAL), diff_row.ACTIVITE_ID,diff_row.CODE,diff_row.LIBELLE,diff_row.PARENT_ID,diff_row.TYPE_RESSOURCE_ID,diff_row.UNITE_BUDGETAIRE, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ACTIVITE_ID = 1 AND IN_COLUMN_LIST('ACTIVITE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET ACTIVITE_ID = diff_row.ACTIVITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENT_ID = 1 AND IN_COLUMN_LIST('PARENT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET PARENT_ID = diff_row.PARENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_RESSOURCE_ID = 1 AND IN_COLUMN_LIST('TYPE_RESSOURCE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET TYPE_RESSOURCE_ID = diff_row.TYPE_RESSOURCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UNITE_BUDGETAIRE = 1 AND IN_COLUMN_LIST('UNITE_BUDGETAIRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET UNITE_BUDGETAIRE = diff_row.UNITE_BUDGETAIRE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE CENTRE_COUT SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ACTIVITE_ID = 1 AND IN_COLUMN_LIST('ACTIVITE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET ACTIVITE_ID = diff_row.ACTIVITE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_CODE = 1 AND IN_COLUMN_LIST('CODE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET CODE = diff_row.CODE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LIBELLE = 1 AND IN_COLUMN_LIST('LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET LIBELLE = diff_row.LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PARENT_ID = 1 AND IN_COLUMN_LIST('PARENT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET PARENT_ID = diff_row.PARENT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TYPE_RESSOURCE_ID = 1 AND IN_COLUMN_LIST('TYPE_RESSOURCE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET TYPE_RESSOURCE_ID = diff_row.TYPE_RESSOURCE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UNITE_BUDGETAIRE = 1 AND IN_COLUMN_LIST('UNITE_BUDGETAIRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE CENTRE_COUT SET UNITE_BUDGETAIRE = diff_row.UNITE_BUDGETAIRE WHERE ID = diff_row.id; END IF;
            UPDATE CENTRE_COUT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'CENTRE_COUT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END CENTRE_COUT;



  PROCEDURE AFFECTATION_RECHERCHE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_AFFECTATION_RECHERCHE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'AFFECTATION_RECHERCHE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_AFFECTATION_RECHERCHE.* FROM V_DIFF_AFFECTATION_RECHERCHE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO AFFECTATION_RECHERCHE
              ( id, INTERVENANT_ID,STRUCTURE_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,AFFECTATION_RECHERCHE_ID_SEQ.NEXTVAL), diff_row.INTERVENANT_ID,diff_row.STRUCTURE_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE AFFECTATION_RECHERCHE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION_RECHERCHE SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION_RECHERCHE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
            UPDATE AFFECTATION_RECHERCHE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'AFFECTATION_RECHERCHE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END AFFECTATION_RECHERCHE;



  PROCEDURE AFFECTATION IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_AFFECTATION%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'AFFECTATION';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_AFFECTATION.* FROM V_DIFF_AFFECTATION ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO AFFECTATION
              ( id, ROLE_ID,STRUCTURE_ID,UTILISATEUR_ID, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,AFFECTATION_ID_SEQ.NEXTVAL), diff_row.ROLE_ID,diff_row.STRUCTURE_ID,diff_row.UTILISATEUR_ID, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_ROLE_ID = 1 AND IN_COLUMN_LIST('ROLE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION SET ROLE_ID = diff_row.ROLE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UTILISATEUR_ID = 1 AND IN_COLUMN_LIST('UTILISATEUR_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION SET UTILISATEUR_ID = diff_row.UTILISATEUR_ID WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE AFFECTATION SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_ROLE_ID = 1 AND IN_COLUMN_LIST('ROLE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION SET ROLE_ID = diff_row.ROLE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_UTILISATEUR_ID = 1 AND IN_COLUMN_LIST('UTILISATEUR_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE AFFECTATION SET UTILISATEUR_ID = diff_row.UTILISATEUR_ID WHERE ID = diff_row.id; END IF;
            UPDATE AFFECTATION SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'AFFECTATION', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END AFFECTATION;



  PROCEDURE ADRESSE_STRUCTURE IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_ADRESSE_STRUCTURE%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'ADRESSE_STRUCTURE';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_ADRESSE_STRUCTURE.* FROM V_DIFF_ADRESSE_STRUCTURE ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO ADRESSE_STRUCTURE
              ( id, CODE_POSTAL,LOCALITE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,PRINCIPALE,STRUCTURE_ID,TELEPHONE,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ADRESSE_STRUCTURE_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.LOCALITE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.PRINCIPALE,diff_row.STRUCTURE_ID,diff_row.TELEPHONE,diff_row.VILLE, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE ADRESSE_STRUCTURE SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PRINCIPALE = 1 AND IN_COLUMN_LIST('PRINCIPALE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET PRINCIPALE = diff_row.PRINCIPALE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_STRUCTURE_ID = 1 AND IN_COLUMN_LIST('STRUCTURE_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET STRUCTURE_ID = diff_row.STRUCTURE_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TELEPHONE = 1 AND IN_COLUMN_LIST('TELEPHONE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET TELEPHONE = diff_row.TELEPHONE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_STRUCTURE SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
            UPDATE ADRESSE_STRUCTURE SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'ADRESSE_STRUCTURE', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END ADRESSE_STRUCTURE;



  PROCEDURE ADRESSE_INTERVENANT IS
    TYPE r_cursor IS REF CURSOR;
    sync_filtre VARCHAR2(2000) DEFAULT '';
    sql_query   CLOB;
    diff_cur    r_cursor;
    diff_row    V_DIFF_ADRESSE_INTERVENANT%ROWTYPE;
  BEGIN
    IF UNICAEN_IMPORT.z__SYNC_FILRE__z IS NULL THEN
      BEGIN
        SELECT sync_filtre INTO sync_filtre FROM import_tables WHERE table_name = 'ADRESSE_INTERVENANT';
      EXCEPTION WHEN NO_DATA_FOUND THEN
        sync_filtre := '';
      END;
    END IF;
  
    sql_query := 'SELECT V_DIFF_ADRESSE_INTERVENANT.* FROM V_DIFF_ADRESSE_INTERVENANT ' || COALESCE(UNICAEN_IMPORT.z__SYNC_FILRE__z,sync_filtre);
    OPEN diff_cur FOR sql_query;
    LOOP
      FETCH diff_cur INTO diff_row; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN

        CASE diff_row.import_action
          WHEN 'insert' THEN
            INSERT INTO ADRESSE_INTERVENANT
              ( id, CODE_POSTAL,INTERVENANT_ID,LOCALITE,MENTION_COMPLEMENTAIRE,NOM_VOIE,NO_VOIE,PAYS_CODE_INSEE,PAYS_LIBELLE,TEL_DOMICILE,VILLE, source_id, source_code, histo_createur_id, histo_modificateur_id )
            VALUES
              ( COALESCE(diff_row.id,ADRESSE_INTERVENANT_ID_SEQ.NEXTVAL), diff_row.CODE_POSTAL,diff_row.INTERVENANT_ID,diff_row.LOCALITE,diff_row.MENTION_COMPLEMENTAIRE,diff_row.NOM_VOIE,diff_row.NO_VOIE,diff_row.PAYS_CODE_INSEE,diff_row.PAYS_LIBELLE,diff_row.TEL_DOMICILE,diff_row.VILLE, diff_row.source_id, diff_row.source_code, unicaen_import.get_current_user, unicaen_import.get_current_user );

          WHEN 'update' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;

          WHEN 'delete' THEN
            UPDATE ADRESSE_INTERVENANT SET histo_destruction = SYSDATE, histo_destructeur_id = unicaen_import.get_current_user WHERE ID = diff_row.id;

          WHEN 'undelete' THEN
            IF (diff_row.u_CODE_POSTAL = 1 AND IN_COLUMN_LIST('CODE_POSTAL',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET CODE_POSTAL = diff_row.CODE_POSTAL WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_INTERVENANT_ID = 1 AND IN_COLUMN_LIST('INTERVENANT_ID',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET INTERVENANT_ID = diff_row.INTERVENANT_ID WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_LOCALITE = 1 AND IN_COLUMN_LIST('LOCALITE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET LOCALITE = diff_row.LOCALITE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_MENTION_COMPLEMENTAIRE = 1 AND IN_COLUMN_LIST('MENTION_COMPLEMENTAIRE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET MENTION_COMPLEMENTAIRE = diff_row.MENTION_COMPLEMENTAIRE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NOM_VOIE = 1 AND IN_COLUMN_LIST('NOM_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET NOM_VOIE = diff_row.NOM_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_NO_VOIE = 1 AND IN_COLUMN_LIST('NO_VOIE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET NO_VOIE = diff_row.NO_VOIE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_CODE_INSEE = 1 AND IN_COLUMN_LIST('PAYS_CODE_INSEE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET PAYS_CODE_INSEE = diff_row.PAYS_CODE_INSEE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_PAYS_LIBELLE = 1 AND IN_COLUMN_LIST('PAYS_LIBELLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET PAYS_LIBELLE = diff_row.PAYS_LIBELLE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_TEL_DOMICILE = 1 AND IN_COLUMN_LIST('TEL_DOMICILE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET TEL_DOMICILE = diff_row.TEL_DOMICILE WHERE ID = diff_row.id; END IF;
          IF (diff_row.u_VILLE = 1 AND IN_COLUMN_LIST('VILLE',UNICAEN_IMPORT.z__IGNORE_UPD_COLS__z) = 0) THEN UPDATE ADRESSE_INTERVENANT SET VILLE = diff_row.VILLE WHERE ID = diff_row.id; END IF;
            UPDATE ADRESSE_INTERVENANT SET histo_destruction = NULL, histo_destructeur_id = NULL WHERE ID = diff_row.id;

        END CASE;

      EXCEPTION WHEN OTHERS THEN
        UNICAEN_IMPORT.SYNC_LOG( SQLERRM, 'ADRESSE_INTERVENANT', diff_row.source_code );
      END;
    END LOOP;
    CLOSE diff_cur;

  END ADRESSE_INTERVENANT;

END UNICAEN_IMPORT_AUTOGEN_PROCS__;
/
---------------------------
--Modifié PACKAGE BODY
--UNICAEN_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."UNICAEN_IMPORT" AS

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



  PROCEDURE SYNCHRONISATION( table_name VARCHAR2, SYNC_FILRE CLOB DEFAULT '', IGNORE_UPD_COLS CLOB DEFAULT '' ) IS
    ok NUMERIC(1);
  BEGIN
    SELECT COUNT(*) INTO ok FROM import_tables it WHERE it.table_name = SYNCHRONISATION.table_name AND it.sync_enabled = 1 AND rownum = 1;

    IF 1 = ok THEN
      z__SYNC_FILRE__z      := SYNCHRONISATION.SYNC_FILRE;
      z__IGNORE_UPD_COLS__z := SYNCHRONISATION.IGNORE_UPD_COLS;
      EXECUTE IMMEDIATE 'BEGIN UNICAEN_IMPORT_AUTOGEN_PROCS__.' || table_name || '(); END;';
    END IF;
  END;



  PROCEDURE SYNC_LOG( message CLOB, table_name VARCHAR2 DEFAULT NULL, source_code VARCHAR2 DEFAULT NULL ) IS
  BEGIN
    INSERT INTO SYNC_LOG("ID","DATE_SYNC","MESSAGE","TABLE_NAME","SOURCE_CODE") VALUES (SYNC_LOG_ID_SEQ.NEXTVAL, SYSDATE, message,table_name,source_code);
  END SYNC_LOG;



  FUNCTION IN_COLUMN_LIST( VALEUR VARCHAR2, CHAMPS CLOB ) RETURN NUMERIC IS
  BEGIN
    IF REGEXP_LIKE(CHAMPS, '(^|,)[ \t\r\n\v\f]*' || VALEUR || '[ \t\r\n\v\f]*(,|$)') THEN RETURN 1; END IF;
    RETURN 0;
  END;

END UNICAEN_IMPORT;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_IMPORT
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_IMPORT" IS

  PROCEDURE REFRESH_MV( mview_name varchar2 ) IS
  BEGIN
    DBMS_MVIEW.REFRESH(mview_name, 'C');
  EXCEPTION WHEN OTHERS THEN
    UNICAEN_IMPORT.SYNC_LOG( SQLERRM, mview_name );
  END;

  PROCEDURE REFRESH_MVS IS
  BEGIN
    -- Mise à jour des vues matérialisées
    -- procédure à adapter aux besoins de chaque établissement
    
    REFRESH_MV('MV_UNICAEN_STRUCTURE_CODES');
    REFRESH_MV('MV_AFFECTATION');
    REFRESH_MV('MV_INTERVENANT');
  END;

  PROCEDURE SYNC_TABLES IS
  BEGIN
    -- procédure à adapter aux besoins de chaque établissement
    
    UNICAEN_IMPORT.SYNCHRONISATION('PAYS');
    UNICAEN_IMPORT.SYNCHRONISATION('DEPARTEMENT');

    UNICAEN_IMPORT.SYNCHRONISATION('ETABLISSEMENT');
    UNICAEN_IMPORT.SYNCHRONISATION('STRUCTURE');
    UNICAEN_IMPORT.SYNCHRONISATION('ADRESSE_STRUCTURE');

    UNICAEN_IMPORT.SYNCHRONISATION('DOMAINE_FONCTIONNEL');
    UNICAEN_IMPORT.SYNCHRONISATION('CENTRE_COUT');
    UNICAEN_IMPORT.SYNCHRONISATION('CENTRE_COUT_STRUCTURE');

    /* Import automatique des users des nouveaux directeurs */
    INSERT INTO utilisateur (
      id, display_name, email, password, state, username
    )
    SELECT
      utilisateur_id_seq.nextval id, 
      display_name, 
      email, 
      password, 
      state, 
      username 
    FROM 
      mv_affectation 
    WHERE 
      username not in (select username from utilisateur);

    UNICAEN_IMPORT.SYNCHRONISATION('AFFECTATION');

    UNICAEN_IMPORT.SYNCHRONISATION('CORPS');
    UNICAEN_IMPORT.SYNCHRONISATION('GRADE');

    UNICAEN_IMPORT.SYNCHRONISATION('INTERVENANT');
    UNICAEN_IMPORT.SYNCHRONISATION('AFFECTATION_RECHERCHE');
    UNICAEN_IMPORT.SYNCHRONISATION('ADRESSE_INTERVENANT');

    UNICAEN_IMPORT.SYNCHRONISATION('GROUPE_TYPE_FORMATION');
    UNICAEN_IMPORT.SYNCHRONISATION('TYPE_FORMATION');
    UNICAEN_IMPORT.SYNCHRONISATION('ETAPE');
    UNICAEN_IMPORT.SYNCHRONISATION('ELEMENT_PEDAGOGIQUE');
    UNICAEN_IMPORT.SYNCHRONISATION('EFFECTIFS');
    --UNICAEN_IMPORT.SYNCHRONISATION('ELEMENT_TAUX_REGIMES');
    UNICAEN_IMPORT.SYNCHRONISATION('CHEMIN_PEDAGOGIQUE');

    UNICAEN_IMPORT.SYNCHRONISATION('VOLUME_HORAIRE_ENS');
    UNICAEN_IMPORT.SYNCHRONISATION('NOEUD');
    UNICAEN_IMPORT.SYNCHRONISATION('LIEN');
    UNICAEN_IMPORT.SYNCHRONISATION('SCENARIO_LIEN');

    REFRESH_MV('TBL_NOEUD');
    UNICAEN_TBL.CALCULER('chargens');

    -- Mise à jour des sources calculées en dernier
    UNICAEN_IMPORT.SYNCHRONISATION('TYPE_INTERVENTION_EP');
    UNICAEN_IMPORT.SYNCHRONISATION('TYPE_MODULATEUR_EP');
  END;

  PROCEDURE SYNCHRONISATION IS
  BEGIN
    REFRESH_MVS;
    SYNC_TABLES;
  END SYNCHRONISATION;

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

  INTERVENANT_ID NUMERIC DEFAULT NULL;

  FUNCTION GET_INTERVENANT_ID RETURN NUMERIC IS
  BEGIN
    RETURN INTERVENANT_ID;
  END;

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
    SELECT valeur INTO taux_hetd 
    FROM taux_horaire_hetd t 
    WHERE 
      DATE_OBS BETWEEN t.histo_creation AND COALESCE(t.histo_destruction,GREATEST(SYSDATE,DATE_OBS))
      AND rownum = 1
    ORDER BY
      histo_creation DESC;
    RETURN taux_hetd;
  END;

  PROCEDURE UPDATE_ANNEE_TAUX_HETD IS
  BEGIN
    UPDATE annee SET taux_hetd = GET_TAUX_HORAIRE_HETD(date_fin);
  END;
  
  

  PROCEDURE CALCULER_TOUT( ANNEE_ID NUMERIC DEFAULT NULL ) IS
    a_id NUMERIC;
  BEGIN
    a_id := NVL(CALCULER_TOUT.ANNEE_ID, OSE_PARAMETRE.GET_ANNEE);
    FOR mp IN (
      SELECT DISTINCT
        intervenant_id 
      FROM 
        service s
        JOIN intervenant i ON i.id = s.intervenant_id
      WHERE
        s.histo_destruction IS NULL
        AND i.annee_id = a_id

      UNION

      SELECT DISTINCT
        intervenant_id
      FROM
        service_referentiel sr
        JOIN intervenant i ON i.id = sr.intervenant_id
      WHERE
        sr.histo_destruction IS NULL
        AND i.annee_id = a_id

    )
    LOOP
      CALCULER( mp.intervenant_id );
    END LOOP;
  END;



  FUNCTION ENREGISTRER_RESULTAT( fr formule_resultat%rowtype ) RETURN NUMERIC IS
    id NUMERIC;
    ti_code VARCHAR(5);
  BEGIN

    SELECT
      ti.code INTO ti_code 
    FROM 
      type_intervenant        ti 
      JOIN statut_intervenant si ON si.type_intervenant_id = ti.id 
      JOIN intervenant         i ON i.statut_id = si.id 
    WHERE 
      i.id = fr.intervenant_id;



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
      TO_DELETE,
      type_intervenant_code

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
      0,
      ti_code
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
      annee_id,
      heures_service_statutaire,
      depassement_service_du_sans_hc
    INTO
      d_intervenant.structure_id,
      d_intervenant.annee_id,
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
    d_intervenant.annee_id := null;
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
    OSE_FORMULE.INTERVENANT_ID := POPULATE.INTERVENANT_ID;

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
    OSE_EVENT.ON_BEFORE_FORMULE_RES_DELETE( CALCULER.INTERVENANT_ID );

    DELETE FROM FORMULE_RESULTAT_SERVICE_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_SERVICE WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH_REF WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT_VH WHERE TO_DELETE = 1 AND formule_resultat_id IN (SELECT id FROM formule_resultat WHERE intervenant_id = CALCULER.INTERVENANT_ID);
    DELETE FROM FORMULE_RESULTAT WHERE TO_DELETE = 1 AND intervenant_id = CALCULER.INTERVENANT_ID;

    OSE_EVENT.ON_AFTER_FORMULE_CALC( CALCULER.INTERVENANT_ID );
  END;



  PROCEDURE CALCULER_TBL( PARAMS UNICAEN_TBL.T_PARAMS ) IS
    intervenant_id NUMERIC;
    TYPE r_cursor IS REF CURSOR;
    diff_cur r_cursor;
  BEGIN
    OPEN diff_cur FOR 'WITH interv AS (SELECT id intervenant_id, intervenant.* FROM intervenant)
    SELECT intervenant_id FROM interv WHERE ' || unicaen_tbl.PARAMS_TO_CONDS( params );
    LOOP
      FETCH diff_cur INTO intervenant_id; EXIT WHEN diff_cur%NOTFOUND;
      BEGIN
        CALCULER( intervenant_id );
      END;
    END LOOP;
    CLOSE diff_cur;
  END; 



  FUNCTION GET_INTERVENANT RETURN NUMERIC IS
  BEGIN
    RETURN OSE_FORMULE.INTERVENANT_ID;
  END;

  PROCEDURE SET_INTERVENANT( INTERVENANT_ID NUMERIC DEFAULT NULL) IS
  BEGIN
    IF SET_INTERVENANT.INTERVENANT_ID = -1 THEN
      OSE_FORMULE.INTERVENANT_ID := NULL;
    ELSE
      OSE_FORMULE.INTERVENANT_ID := SET_INTERVENANT.INTERVENANT_ID;
    END IF;
  END;

  FUNCTION MATCH_INTERVENANT(INTERVENANT_ID NUMERIC DEFAULT NULL) RETURN NUMERIC IS
  BEGIN
    IF OSE_FORMULE.INTERVENANT_ID IS NULL OR OSE_FORMULE.INTERVENANT_ID = MATCH_INTERVENANT.INTERVENANT_ID THEN
      RETURN 1;
    ELSE
      RETURN 0;
    END IF;
  END;
END OSE_FORMULE;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_DIVERS
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_DIVERS" AS
  OSE_UTILISATEUR_ID NUMERIC;
  OSE_SOURCE_ID NUMERIC;




PROCEDURE CALCULER_TABLEAUX_BORD IS
BEGIN
  FOR d IN (
    SELECT tbl_name
    FROM tbl
    WHERE tbl_name <> 'formule' -- TROP LONG !!
    ORDER BY ordre
  )
  LOOP
    UNICAEN_TBL.CALCULER(d.tbl_name);
    dbms_output.put_line('Calcul du tableau de bord "' || d.tbl_name || '" effectué');
    COMMIT;
  END LOOP;
END;



FUNCTION GET_OSE_UTILISATEUR_ID RETURN NUMERIC IS
BEGIN
  IF OSE_DIVERS.OSE_UTILISATEUR_ID IS NULL THEN
    SELECT
      to_number(valeur) INTO OSE_DIVERS.OSE_UTILISATEUR_ID
    FROM
      parametre
    WHERE
      nom = 'oseuser';
  END IF;

  RETURN OSE_DIVERS.OSE_UTILISATEUR_ID;
END;



FUNCTION GET_OSE_SOURCE_ID RETURN NUMERIC IS
BEGIN
  IF OSE_DIVERS.OSE_SOURCE_ID IS NULL THEN
    SELECT
      id INTO OSE_DIVERS.OSE_SOURCE_ID
    FROM
      source
    WHERE
      code = 'OSE';
  END IF;

  RETURN OSE_DIVERS.OSE_SOURCE_ID;
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

PROCEDURE DO_NOTHING IS
BEGIN
  RETURN;
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
  SELECT id INTO res FROM structure WHERE source_code = 'UNIV';
  RETURN res;
END;

PROCEDURE SYNC_LOG( msg CLOB ) IS
BEGIN
  INSERT INTO SYNC_LOG( id, date_sync, message ) VALUES ( sync_log_id_seq.nextval, systimestamp, msg );
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



FUNCTION GET_TRIGGER_BODY( TRIGGER_NAME VARCHAR2 ) RETURN VARCHAR2 IS
  vlong long;
BEGIN
  SELECT trigger_body INTO vlong FROM all_triggers WHERE trigger_name = GET_TRIGGER_BODY.TRIGGER_NAME;

  RETURN substr(vlong, 1, 32767);
END;

END OSE_DIVERS;
/
---------------------------
--Modifié PACKAGE BODY
--OSE_CHARGENS
---------------------------
CREATE OR REPLACE PACKAGE BODY "OSE"."OSE_CHARGENS" AS
  SCENARIO NUMERIC;
  NOEUD NUMERIC;
  old_enable BOOLEAN DEFAULT TRUE;

  TYPE T_PRECALC_HEURES_PARAMS IS RECORD (
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_ids                      tnoeud_ids DEFAULT NULL
  );

  PRECALC_HEURES_PARAMS T_PRECALC_HEURES_PARAMS;


  FUNCTION GET_SCENARIO RETURN NUMERIC IS
  BEGIN
    RETURN OSE_CHARGENS.SCENARIO;
  END;

  PROCEDURE SET_SCENARIO( SCENARIO NUMERIC ) IS
  BEGIN
    OSE_CHARGENS.SCENARIO := SET_SCENARIO.SCENARIO;
  END;



  FUNCTION GET_NOEUD RETURN NUMERIC IS
  BEGIN
    RETURN OSE_CHARGENS.NOEUD;
  END;

  PROCEDURE SET_NOEUD( NOEUD NUMERIC ) IS
  BEGIN
    OSE_CHARGENS.NOEUD := SET_NOEUD.NOEUD;
  END;





  FUNCTION CALC_COEF( choix_min NUMERIC, choix_max NUMERIC, poids NUMERIC, max_poids NUMERIC, total_poids NUMERIC, nb_choix NUMERIC ) RETURN FLOAT IS
    cmin NUMERIC;
    cmax NUMERIC;
    coef_choix FLOAT;
    coef_poids FLOAT;
    max_coef_poids FLOAT;
    correcteur FLOAT DEFAULT 1;
    res FLOAT;
  BEGIN
    cmin := choix_min;
    cmax := choix_max;

    IF total_poids = 0 THEN RETURN 0; END IF;

    IF cmax IS NULL OR cmax > nb_choix THEN
      cmax := nb_choix;
    END IF;
    IF cmin IS NULL THEN
      cmin := nb_choix;
    ELSIF cmin > cmax THEN
      cmin := cmax;
    END IF;

      coef_choix := (cmin + cmax) / 2 / nb_choix;

      coef_poids := poids / total_poids;

      max_coef_poids := max_poids / total_poids;

      IF (coef_choix * nb_choix * max_coef_poids) <= 1 THEN
        res := coef_choix * nb_choix * coef_poids;
      ELSE
        correcteur := 1;
        res := coef_choix * nb_choix * (coef_poids + (((1/nb_choix)-coef_poids)*correcteur));
      END IF;

      --ose_test.echo('choix_min= ' || cmin || ', choix_max= ' || cmax || ', poids = ' || poids || ', max_poids = ' || max_poids || ', total_poids = ' || total_poids || ', nb_choix = ' || nb_choix || ', RES = ' || res);
      RETURN res;
  END;


  PROCEDURE DEM_CALC_SUB_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT ) IS
  BEGIN
    INSERT INTO TMP_scenario_noeud_effectif(
      scenario_noeud_id, type_heures_id, etape_id, effectif
    ) VALUES(
      scenario_noeud_id, type_heures_id, etape_id, effectif
    );
  END;



  PROCEDURE CALC_SUB_EFFECTIF_DEM IS
  BEGIN
    DELETE FROM TMP_scenario_noeud_effectif;
  END;


  PROCEDURE CALC_ALL_EFFECTIFS IS
  BEGIN
    FOR p IN (

      SELECT 
        sn.noeud_id,
        sn.scenario_id,
        sne.type_heures_id,
        sne.etape_id
      FROM 
        scenario_noeud_effectif sne
        JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
        JOIN noeud n ON n.id = sn.noeud_id
      WHERE
        n.etape_id IS NOT NULL

    ) LOOP

      CALC_SUB_EFFECTIF2( p.noeud_id, p.scenario_id, p.type_heures_id, p.etape_id );
    END LOOP;

  END;



  PROCEDURE CALC_EFFECTIF( 
    noeud_id       NUMERIC,
    scenario_id    NUMERIC,
    type_heures_id NUMERIC DEFAULT NULL,
    etape_id       NUMERIC DEFAULT NULL
  ) IS
    snid  NUMERIC;
  BEGIN
    UPDATE scenario_noeud_effectif SET effectif = 0 
    WHERE 
      scenario_noeud_id = (
        SELECT id FROM scenario_noeud WHERE noeud_id = CALC_EFFECTIF.noeud_id AND scenario_id = CALC_EFFECTIF.scenario_id
      )
      AND (type_heures_id = CALC_EFFECTIF.type_heures_id OR CALC_EFFECTIF.type_heures_id IS NULL)
      AND (etape_id = CALC_EFFECTIF.etape_id OR CALC_EFFECTIF.etape_id IS NULL)
    ;

    FOR p IN (

      SELECT 
        * 
      FROM 
        v_chargens_calc_effectif cce
      WHERE 
        cce.noeud_id = CALC_EFFECTIF.noeud_id
        AND cce.scenario_id = CALC_EFFECTIF.scenario_id
        AND (cce.type_heures_id = CALC_EFFECTIF.type_heures_id OR CALC_EFFECTIF.type_heures_id IS NULL)
        AND (cce.etape_id = CALC_EFFECTIF.etape_id OR CALC_EFFECTIF.etape_id IS NULL)

    ) LOOP
      snid := OSE_CHARGENS.GET_SCENARIO_NOEUD_ID( p.scenario_id, p.noeud_id );
      IF snid IS NULL THEN
        snid := OSE_CHARGENS.CREER_SCENARIO_NOEUD( p.scenario_id, p.noeud_id );
      END IF;
      ADD_SCENARIO_NOEUD_EFFECTIF( snid, p.type_heures_id, p.etape_id, p.effectif );
    END LOOP;
    CALC_SUB_EFFECTIF2( noeud_id, scenario_id, type_heures_id, etape_id );
  END;



  PROCEDURE CALC_SUB_EFFECTIF2( noeud_id NUMERIC, scenario_id NUMERIC, type_heures_id NUMERIC DEFAULT NULL, etape_id NUMERIC DEFAULT NULL) IS
  BEGIN
    FOR p IN (

      SELECT * 
      FROM   V_CHARGENS_GRANDS_LIENS cgl 
      WHERE  cgl.noeud_sup_id = CALC_SUB_EFFECTIF2.noeud_id

    ) LOOP
      CALC_EFFECTIF( p.noeud_inf_id, scenario_id, type_heures_id, etape_id );
    END LOOP;
  END;



  PROCEDURE DUPLIQUER( source_id NUMERIC, destination_id NUMERIC, utilisateur_id NUMERIC, structure_id NUMERIC, noeuds VARCHAR2 DEFAULT '', liens VARCHAR2 DEFAULT '' ) IS
  BEGIN

    /* Destruction de tous les liens antérieurs de la destination */
    DELETE FROM 
      scenario_lien 
    WHERE 
      scenario_id = DUPLIQUER.destination_id 
      AND histo_destruction IS NULL
      AND (DUPLIQUER.LIENS IS NULL OR DUPLIQUER.LIENS LIKE '%,' || lien_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR lien_id IN (
        SELECT id FROM lien WHERE lien.structure_id = DUPLIQUER.STRUCTURE_ID
      ))
    ;

    /* Duplication des liens */
    INSERT INTO scenario_lien (
      id, 
      scenario_id, lien_id, 
      actif, poids, 
      choix_minimum, choix_maximum, 
      source_id, source_code, 
      histo_creation, histo_createur_id,
      histo_modification, histo_modificateur_id
    ) SELECT
      scenario_lien_id_seq.nextval,
      DUPLIQUER.destination_id, sl.lien_id,
      sl.actif, sl.poids,
      sl.choix_minimum, sl.choix_maximum,
      source.id, 'dupli_' || sl.id || '_' || sl.lien_id || '_' || trunc(dbms_random.value(1,10000000000000)),
      sysdate, DUPLIQUER.utilisateur_id,
      sysdate, DUPLIQUER.utilisateur_id
    FROM
      scenario_lien sl
      JOIN lien l ON l.id = sl.lien_id
      JOIN source ON source.code = 'OSE'
    WHERE
      sl.scenario_id = DUPLIQUER.source_id
      AND sl.histo_destruction IS NULL
      AND (DUPLIQUER.LIENS IS NULL OR DUPLIQUER.LIENS LIKE '%,' || lien_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR l.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;


    /* Destruction de tous les noeuds antérieurs de la destination */
    DELETE FROM 
      scenario_noeud
    WHERE 
      scenario_id = DUPLIQUER.destination_id 
      AND histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR scenario_noeud.noeud_id IN (
        SELECT id FROM noeud WHERE noeud.structure_id = DUPLIQUER.STRUCTURE_ID
      ))
    ;

    /* Duplication des noeuds */
    INSERT INTO scenario_noeud (
      id, 
      scenario_id, noeud_id, 
      assiduite, 
      source_id, source_code, 
      histo_creation, histo_createur_id,
      histo_modification, histo_modificateur_id
    ) SELECT
      scenario_noeud_id_seq.nextval,
      DUPLIQUER.destination_id, sn.noeud_id,
      sn.assiduite,
      source.id, 'dupli_' || sn.id || '_' || sn.noeud_id || '_' || trunc(dbms_random.value(1,10000000000000)),
      sysdate, DUPLIQUER.utilisateur_id,
      sysdate, DUPLIQUER.utilisateur_id
    FROM
      scenario_noeud sn
      JOIN noeud n ON n.id = sn.noeud_id
      JOIN source ON source.code = 'OSE'
    WHERE
      sn.scenario_id = DUPLIQUER.source_id
      AND sn.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;

    /* Duplication des effectifs */
    INSERT INTO scenario_noeud_effectif (
      id,
      scenario_noeud_id,
      type_heures_id,
      effectif,
      etape_id
    ) SELECT
      scenario_noeud_effectif_id_seq.nextval,
      sn_dst.id,
      sne.type_heures_id,
      sne.effectif,
      sne.etape_id
    FROM
      scenario_noeud_effectif sne
      JOIN scenario_noeud sn_src ON sn_src.id = sne.scenario_noeud_id
      JOIN scenario_noeud sn_dst ON sn_dst.scenario_id = DUPLIQUER.destination_id AND sn_dst.noeud_id = sn_src.noeud_id
      JOIN noeud n ON n.id = sn_src.noeud_id
    WHERE
      sn_src.scenario_id = DUPLIQUER.source_id
      AND sn_src.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || sn_src.noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;

    /* Duplication des seuils */
    INSERT INTO scenario_noeud_seuil (
      id,
      scenario_noeud_id,
      type_intervention_id,
      ouverture,
      dedoublement
    ) SELECT
      scenario_noeud_seuil_id_seq.nextval,
      sn_dst.id,
      sns.type_intervention_id,
      sns.ouverture,
      sns.dedoublement
    FROM
      scenario_noeud_seuil sns
      JOIN scenario_noeud sn_src ON sn_src.id = sns.scenario_noeud_id
      JOIN scenario_noeud sn_dst ON sn_dst.scenario_id = DUPLIQUER.destination_id AND sn_dst.noeud_id = sn_src.noeud_id
      JOIN noeud n ON n.id = sn_src.noeud_id
    WHERE
      sn_src.scenario_id = DUPLIQUER.source_id
      AND sn_src.histo_destruction IS NULL
      AND (DUPLIQUER.NOEUDS IS NULL OR DUPLIQUER.NOEUDS LIKE '%,' || sn_src.noeud_id || ',%' )
      AND (DUPLIQUER.STRUCTURE_ID IS NULL OR n.structure_id = DUPLIQUER.STRUCTURE_ID)
    ;
  END;



  PROCEDURE CONTROLE_SEUIL( ouverture NUMERIC, dedoublement NUMERIC ) IS
  BEGIN
    IF ouverture IS NOT NULL THEN
      IF ouverture < 1 THEN
        raise_application_error(-20101, 'Le seuil d''ouverture doit être supérieur ou égal à 1');
      END IF;
    END IF;

    IF dedoublement IS NOT NULL THEN
      IF dedoublement < 1 THEN
        raise_application_error(-20101, 'Le seuil de dédoublement doit être supérieur ou égal à 1');
      END IF;
    END IF;

    IF ouverture IS NOT NULL AND dedoublement IS NOT NULL THEN
      IF dedoublement < ouverture THEN
        raise_application_error(-20101, 'Le seuil de dédoublement doit être supérieur ou égal au seuil d''ouverture');
      END IF;
    END IF;
  END;


  FUNCTION CREER_SCENARIO_NOEUD( scenario_id NUMERIC, noeud_id NUMERIC, assiduite FLOAT DEFAULT 1 ) RETURN NUMERIC IS
    new_id NUMERIC;
  BEGIN
    new_id := SCENARIO_NOEUD_ID_SEQ.NEXTVAL;
--ose_test.echo(scenario_id || '-' || noeud_id);
    INSERT INTO SCENARIO_NOEUD(
      ID,
      SCENARIO_ID,
      NOEUD_ID,
      ASSIDUITE,
      SOURCE_ID,
      SOURCE_CODE,
      HEURES,
      HISTO_CREATION,
      HISTO_CREATEUR_ID,
      HISTO_MODIFICATION,
      HISTO_MODIFICATEUR_ID
    ) VALUES (
      new_id,
      CREER_SCENARIO_NOEUD.scenario_id,
      CREER_SCENARIO_NOEUD.noeud_id,
      CREER_SCENARIO_NOEUD.assiduite,
      OSE_DIVERS.GET_OSE_SOURCE_ID,
      'OSE_NEW_SN_' || new_id,
      null,
      SYSDATE,
      OSE_DIVERS.GET_OSE_UTILISATEUR_ID,
      SYSDATE,
      OSE_DIVERS.GET_OSE_UTILISATEUR_ID
    );
    RETURN new_id;
  END;


  FUNCTION GET_SCENARIO_NOEUD_ID(scenario_id NUMERIC, noeud_id NUMERIC) RETURN NUMERIC IS
    res NUMERIC;
  BEGIN
    SELECT
      sn.id INTO res
    FROM
      scenario_noeud sn
    WHERE
      sn.noeud_id = GET_SCENARIO_NOEUD_ID.noeud_id
      AND sn.scenario_id = GET_SCENARIO_NOEUD_ID.scenario_id
      AND sn.histo_destruction IS NULL;

    RETURN res;

  EXCEPTION WHEN NO_DATA_FOUND THEN
    RETURN NULL;
  END;


  PROCEDURE ADD_SCENARIO_NOEUD_EFFECTIF( scenario_noeud_id NUMERIC, type_heures_id NUMERIC, etape_id NUMERIC, effectif FLOAT ) IS
    old_enable BOOLEAN;
  BEGIN
    old_enable := ose_chargens.ENABLE_TRIGGER_EFFECTIFS;
    ose_chargens.ENABLE_TRIGGER_EFFECTIFS := false;

    MERGE INTO scenario_noeud_effectif sne USING dual ON (

          sne.scenario_noeud_id = ADD_SCENARIO_NOEUD_EFFECTIF.scenario_noeud_id
      AND sne.type_heures_id = ADD_SCENARIO_NOEUD_EFFECTIF.type_heures_id
      AND sne.etape_id = ADD_SCENARIO_NOEUD_EFFECTIF.etape_id

    ) WHEN MATCHED THEN UPDATE SET

      effectif = effectif + ADD_SCENARIO_NOEUD_EFFECTIF.effectif

    WHEN NOT MATCHED THEN INSERT (

      ID,
      SCENARIO_NOEUD_ID,
      TYPE_HEURES_ID,
      ETAPE_ID,
      EFFECTIF

    ) VALUES (

      SCENARIO_NOEUD_EFFECTIF_ID_SEQ.NEXTVAL,
      ADD_SCENARIO_NOEUD_EFFECTIF.scenario_noeud_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.type_heures_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.etape_id,
      ADD_SCENARIO_NOEUD_EFFECTIF.effectif

    );

    DELETE FROM scenario_noeud_effectif WHERE effectif = 0;

    ose_chargens.ENABLE_TRIGGER_EFFECTIFS := old_enable;
  END;



  PROCEDURE INIT_SCENARIO_NOEUD_EFFECTIF( 
    etape_id NUMERIC, 
    scenario_id NUMERIC, 
    type_heures_id NUMERIC, 
    effectif FLOAT, 
    surcharge BOOLEAN DEFAULT FALSE 
  ) IS
    noeud_id NUMERIC;
    scenario_noeud_id NUMERIC;
    scenario_noeud_effectif_id NUMERIC;
  BEGIN
    SELECT 
      n.id, sn.id, sne.id
    INTO 
      noeud_id, scenario_noeud_id, scenario_noeud_effectif_id
    FROM 
                noeud                     n
      LEFT JOIN scenario_noeud           sn ON sn.noeud_id = n.id
                                           AND sn.histo_destruction IS NULL
                                           AND sn.scenario_id = INIT_SCENARIO_NOEUD_EFFECTIF.scenario_id

      LEFT JOIN scenario_noeud_effectif sne ON sne.scenario_noeud_id = sn.id
                                           AND sne.type_heures_id = INIT_SCENARIO_NOEUD_EFFECTIF.type_heures_id
    WHERE 
      n.etape_id = INIT_SCENARIO_NOEUD_EFFECTIF.etape_id 
      AND n.histo_destruction IS NULL
    ;

    IF noeud_id IS NULL THEN RETURN; END IF;

    IF scenario_noeud_id IS NULL THEN
      scenario_noeud_id := CREER_SCENARIO_NOEUD( scenario_id, noeud_id );
    END IF;

    IF scenario_noeud_effectif_id IS NULL THEN
      scenario_noeud_effectif_id := SCENARIO_NOEUD_EFFECTIF_ID_SEQ.NEXTVAL;
      INSERT INTO scenario_noeud_effectif (
        id, 
        scenario_noeud_id, 
        type_heures_id, 
        effectif, 
        etape_id
      ) VALUES (
        scenario_noeud_effectif_id,
        scenario_noeud_id,
        INIT_SCENARIO_NOEUD_EFFECTIF.type_heures_id,
        INIT_SCENARIO_NOEUD_EFFECTIF.effectif,
        INIT_SCENARIO_NOEUD_EFFECTIF.etape_id
      );
    ELSIF surcharge THEN
      UPDATE scenario_noeud_effectif SET effectif = INIT_SCENARIO_NOEUD_EFFECTIF.effectif WHERE id = scenario_noeud_effectif_id;
    END IF;

    CALC_SUB_EFFECTIF2( noeud_id, scenario_id, type_heures_id, etape_id );

  EXCEPTION WHEN NO_DATA_FOUND THEN
    RETURN;
  END;



  PROCEDURE SET_PRECALC_HEURES_PARAMS( 
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_ids                      tnoeud_ids DEFAULT NULL
  ) IS
  BEGIN
    PRECALC_HEURES_PARAMS.ANNEE_ID       := ANNEE_ID;
    PRECALC_HEURES_PARAMS.STRUCTURE_ID   := STRUCTURE_ID;
    PRECALC_HEURES_PARAMS.SCENARIO_ID    := SCENARIO_ID;
    PRECALC_HEURES_PARAMS.TYPE_HEURES_ID := TYPE_HEURES_ID;
    PRECALC_HEURES_PARAMS.ETAPE_ID       := ETAPE_ID;
    PRECALC_HEURES_PARAMS.NOEUD_IDS      := noeud_ids;
  END;



  FUNCTION MATCH_PRECALC_HEURES_PARAMS( 
    annee_id                       NUMERIC DEFAULT NULL,
    structure_id                   NUMERIC DEFAULT NULL,
    scenario_id                    NUMERIC DEFAULT NULL,
    type_heures_id                 NUMERIC DEFAULT NULL,
    etape_id                       NUMERIC DEFAULT NULL,
    noeud_id                       NUMERIC DEFAULT NULL
  ) RETURN NUMERIC IS
  BEGIN

    IF PRECALC_HEURES_PARAMS.noeud_ids IS NOT NULL THEN
      IF NOT (noeud_id MEMBER OF PRECALC_HEURES_PARAMS.noeud_ids) THEN
        RETURN 0;
      END IF;
    END IF;

    IF annee_id <> COALESCE(PRECALC_HEURES_PARAMS.annee_id, annee_id) THEN
      RETURN 0;
    END IF;

    IF structure_id <> COALESCE(PRECALC_HEURES_PARAMS.structure_id, structure_id) THEN
      RETURN 0;
    END IF;

    IF scenario_id <> COALESCE(PRECALC_HEURES_PARAMS.scenario_id, scenario_id) THEN
      RETURN 0;
    END IF;

    IF type_heures_id <> COALESCE(PRECALC_HEURES_PARAMS.type_heures_id, type_heures_id) THEN
      RETURN 0;
    END IF;

    IF etape_id <> COALESCE(PRECALC_HEURES_PARAMS.etape_id, etape_id) THEN
      RETURN 0;
    END IF;

    RETURN 1;
  END;


  FUNCTION GET_PRECALC_HEURES_ANNEE RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.ANNEE_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_STRUCTURE RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.STRUCTURE_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_SCENARIO RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.SCENARIO_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_TYPE_HEURES RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.TYPE_HEURES_ID;
  END;



  FUNCTION GET_PRECALC_HEURES_ETAPE RETURN NUMERIC IS
  BEGIN
    RETURN PRECALC_HEURES_PARAMS.ETAPE_ID;
  END;

--  FUNCTION GET_PRECALC_HEURES_NOEUD RETURN NUMERIC IS
--  BEGIN

--  END;

END OSE_CHARGENS;
/





REM INSERTING into IMPORT_TABLES
SET DEFINE OFF;
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('INTERVENANT','WHERE (IMPORT_ACTION IN (''delete'',''update'',''undelete'') OR STATUT_ID IN (SELECT si.id FROM statut_intervenant si JOIN type_intervenant ti ON ti.id = si.type_intervenant_id WHERE ti.code = ''P''))','0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('AFFECTATION_RECHERCHE','WHERE INTERVENANT_ID IS NOT NULL','0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('ADRESSE_INTERVENANT','WHERE INTERVENANT_ID IS NOT NULL','0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('ELEMENT_TAUX_REGIMES','WHERE IMPORT_ACTION IN (''delete'',''insert'',''undelete'')','0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('VOLUME_HORAIRE_ENS',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('TYPE_MODULATEUR_EP',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('TYPE_INTERVENTION_EP',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('TYPE_FORMATION',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('STRUCTURE',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('SCENARIO_LIEN',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('PAYS',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('NOEUD',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('LIEN',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('GROUPE_TYPE_FORMATION',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('GRADE',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('ETAPE',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('ETABLISSEMENT',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('ELEMENT_PEDAGOGIQUE',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('EFFECTIFS',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('DOMAINE_FONCTIONNEL',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('DEPARTEMENT',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('CORPS',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('CHEMIN_PEDAGOGIQUE',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('CENTRE_COUT_STRUCTURE',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('CENTRE_COUT',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('AFFECTATION',null,'0');
Insert into IMPORT_TABLES (TABLE_NAME,SYNC_FILTRE,SYNC_ENABLED) values ('ADRESSE_STRUCTURE',null,'0');

update intervenant set utilisateur_code = lpad(code, 8, '0');


DROP TABLE ELEMENT_TAUX_REGIMES_SAVE;
DROP TABLE TYPE_STRUCTURE;
DROP TABLE "OSE"."PERSONNEL";
DROP TABLE "OSE"."MESSAGE";

DROP OR REPLACE FORCE VIEW "OSE"."SRC_PERSONNEL" ;
DROP OR REPLACE FORCE VIEW "OSE"."V_HARP_INTERVENANT_STATUT" ;
DROP OR REPLACE FORCE VIEW "OSE"."V_PLAFOND_FC_MAJ" ;
DROP OR REPLACE FORCE VIEW "OSE"."V_DIFF_PERSONNEL" ;
DROP OR REPLACE FORCE VIEW "OSE"."V_PLAFOND" ;

DROP MATERIALIZED VIEW "OSE"."MV_STRUCTURE";
DROP MATERIALIZED VIEW "OSE"."MV_PERSONNEL";
DROP MATERIALIZED VIEW "OSE"."MV_INTERVENANT_RECHERCHE";
DROP MATERIALIZED VIEW "OSE"."MV_DOMAINE_FONCTIONNEL";
DROP MATERIALIZED VIEW "OSE"."MV_CENTRE_COUT";
DROP MATERIALIZED VIEW "OSE"."MV_AFFECTATION_RECHERCHE";
DROP MATERIALIZED VIEW "OSE"."MV_ADRESSE_STRUCTURE";
DROP MATERIALIZED VIEW "OSE"."MV_ADRESSE_INTERVENANT";

DROP INDEX MV_PERSONNEL_PK;
DROP INDEX PERSONNEL_SOURCE__UN;
DROP INDEX MV_ADRESSE_STRUCTURE_PK;
DROP INDEX STRUCTURE_SOURCE_FK_IDX;
DROP INDEX MESSAGES__UN;
DROP INDEX PERSONNEL_SUPANN_UN;
DROP INDEX PERSONNEL_HMFK_IDX;
DROP INDEX WF_ETAPE_DEP_PK1_IDX;
DROP INDEX TYPE_STRUCTURE_HCFK_IDX;
DROP INDEX PERSONNEL_PK;
DROP INDEX TYPE_STRUCTURE_HMFK_IDX;
DROP INDEX PERSONNEL_CODE_UN;
DROP INDEX RSV_UN_IDX;
DROP INDEX TYPE_VOLUME_HORAIRE__UN;
DROP INDEX MV_ADRESSE_INTERVENANT_PK;
DROP INDEX MV_AFFECTATION_RECHERCHE_PK;
DROP INDEX PERSONNEL_CIVILITE_FK_IDX;
DROP INDEX TBL_SERVICE_REF_INTERV_FK_IDX;
DROP INDEX MV_STRUCTURE_PK;
DROP INDEX AFFECTATION_PERSONNEL_FK_IDX;
DROP INDEX RSV_PK_IDX;
DROP INDEX TBL_VALIDATION_REFERENTIEL__UN;
DROP INDEX STRUCTURE_SOURCE_ID_UN;
DROP INDEX PERSONNEL_HCFK_IDX;
DROP INDEX TYPE_STRUCTURE_PK;
DROP INDEX PERSONNEL_STRUCTURE_FK_IDX;
DROP INDEX MESSAGES_PK;
DROP INDEX TYPE_STRUCTURE_CODE_UN;
DROP INDEX TBL_SERVICE_SAISIE_ANN_FK_IDX;
DROP INDEX TYPE_STRUCTURE_HDFK_IDX;
DROP INDEX PERSONNEL_HDFK_IDX;
DROP INDEX PERSONNEL_SOURCE_FK_IDX;

DROP OR REPLACE TRIGGER "OSE"."PFM_VOLUME_HORAIRE";
DROP OR REPLACE PACKAGE "OSE"."PTBL_LIEN" ;
DROP OR REPLACE PACKAGE "OSE"."OSE_SERVICE" ;
DROP OR REPLACE PACKAGE BODY "OSE"."PTBL_LIEN" ;
DROP OR REPLACE PACKAGE BODY "OSE"."OSE_SERVICE";

