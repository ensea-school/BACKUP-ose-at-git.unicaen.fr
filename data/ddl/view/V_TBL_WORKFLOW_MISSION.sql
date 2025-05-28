CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_MISSION AS
SELECT
  'mission_saisie'                                                  etape_code,
  m.intervenant_id                                                  intervenant_id,
  NULL                                                              structure_id,
  SUM(1)                                                            objectif,
  SUM(CASE WHEN m.mission_id IS NULL THEN 0 ELSE 1 END)             partiel,
  SUM(CASE WHEN m.mission_id IS NULL THEN 0 ELSE 1 END)             realisation
FROM
  tbl_mission m
WHERE
  m.actif = 1
  /*@INTERVENANT_ID=m.intervenant_id*/
  /*@ANNEE_ID=m.annee_id*/
GROUP BY
  m.intervenant_id, m.structure_id, m.intervenant_structure_id

UNION ALL

SELECT
  'mission_validation'                                                                                   etape_code,
  m.intervenant_id                                                                                       intervenant_id,
  m.structure_id                                                                                         structure_id,
  SUM(1)                                                                                                 objectif,
  SUM(CASE WHEN heures_realisees_validees = m.heures_realisees_saisies THEN 1 ELSE 0 END)                partiel,
  SUM(CASE WHEN m.valide = 1 AND m.heures_prevues_validees = m.heures_prevues_saisies THEN 1 ELSE 0 END) realisation
FROM
  tbl_mission m
WHERE
  m.actif = 1
  /*@INTERVENANT_ID=m.intervenant_id*/
  /*@ANNEE_ID=m.annee_id*/
GROUP BY
  m.intervenant_id, m.structure_id

UNION ALL

SELECT
  'mission_saisie_realise'                                              etape_code,
  m.intervenant_id                                                      intervenant_id,
  m.structure_id                                                        structure_id,
  SUM(GREATEST(m.heures_prevues_validees,m.heures_realisees_saisies))   objectif,
  CASE WHEN SUM(m.heures_realisees_saisies) > 0 THEN 1 ELSE 0 END       partiel,
  SUM(m.heures_realisees_saisies)                                       realisation
FROM
  tbl_mission m
WHERE
  m.actif = 1
  /*@INTERVENANT_ID=m.intervenant_id*/
  /*@ANNEE_ID=m.annee_id*/
GROUP BY
  m.intervenant_id,
  m.structure_id

UNION ALL

SELECT
  'mission_validation_realise'                         etape_code,
  m.intervenant_id                                     intervenant_id,
  m.structure_id                                       structure_id,
  SUM(m.heures_realisees_saisies)                      objectif,
  SUM(CASE WHEN heures_realisees_validees = m.heures_realisees_saisies THEN 1 ELSE 0 END) partiel,
  SUM(m.heures_realisees_validees)                     realisation
FROM
  tbl_mission m
WHERE
  m.actif = 1
  /*@INTERVENANT_ID=m.intervenant_id*/
  /*@ANNEE_ID=m.annee_id*/
GROUP BY
  m.intervenant_id,
  m.structure_id