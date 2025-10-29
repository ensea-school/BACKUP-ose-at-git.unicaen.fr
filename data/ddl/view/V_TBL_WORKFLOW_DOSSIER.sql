CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_DOSSIER AS
SELECT
  'donnees_perso_saisie'                              etape_code,
  d.intervenant_id                                    intervenant_id,
  null                                                structure_id,
  d.avant_recrutement_attendue                        objectif,
  d.avant_recrutement_realisee                        partiel,
  d.avant_recrutement_realisee                        realisation
FROM
  tbl_dossier d
WHERE
  d.actif = 1
  AND d.avant_recrutement_attendue > 0
  /*@intervenant_id=d.intervenant_id*/
  /*@annee_id=d.annee_id*/

UNION ALL

SELECT
  'donnees_perso_compl_saisie'                        etape_code,
  d.intervenant_id                                    intervenant_id,
  null                                                structure_id,
  d.apres_recrutement_attendue                        objectif,
  d.apres_recrutement_realisee                        partiel,
  d.apres_recrutement_realisee                        realisation
FROM
  tbl_dossier d
WHERE
  d.actif = 1
  AND d.apres_recrutement_attendue > 0
  /*@intervenant_id=d.intervenant_id*/
  /*@annee_id=d.annee_id*/

UNION ALL

SELECT
  'donnees_perso_validation'                          etape_code,
  d.intervenant_id                                    intervenant_id,
  null                                                structure_id,
  1                                                   objectif,
  CASE WHEN d.validation_id IS NULL THEN 0 ELSE 1 END partiel,
  CASE WHEN d.validation_id IS NULL THEN 0 ELSE 1 END realisation
FROM
  tbl_dossier d
WHERE
  d.actif = 1
  AND d.avant_recrutement_attendue > 0
  /*@intervenant_id=d.intervenant_id*/
  /*@annee_id=d.annee_id*/

UNION ALL

SELECT
  'donnees_perso_compl_validation'                    etape_code,
  d.intervenant_id                                    intervenant_id,
  null                                                structure_id,
  1                                                   objectif,
  CASE WHEN d.validation_complementaire_id IS NULL THEN 0 ELSE 1 END partiel,
  CASE WHEN d.validation_complementaire_id IS NULL THEN 0 ELSE 1 END realisation
FROM
  tbl_dossier d
WHERE
  d.actif = 1
  AND d.apres_recrutement_attendue > 0
  /*@intervenant_id=d.intervenant_id*/
  /*@annee_id=d.annee_id*/