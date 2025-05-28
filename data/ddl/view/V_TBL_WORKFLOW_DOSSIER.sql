CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_DOSSIER AS
SELECT
  'donnees_perso_saisie'                              etape_code,
  d.intervenant_id                                    intervenant_id,
  null                                                structure_id,
  8                                                   objectif,
  d.completude_statut + d.completude_identite + d.completude_identite_comp + d.completude_contact + d.completude_adresse + d.completude_insee + d.completude_banque + d.completude_employeur partiel,
  d.completude_statut + d.completude_identite + d.completude_identite_comp + d.completude_contact + d.completude_adresse + d.completude_insee + d.completude_banque + d.completude_employeur realisation
FROM
  tbl_dossier d
WHERE
  d.actif = 1
  /*@INTERVENANT_ID=d.intervenant_id*/
  /*@ANNEE_ID=d.annee_id*/

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
  /*@INTERVENANT_ID=d.intervenant_id*/
  /*@ANNEE_ID=d.annee_id*/