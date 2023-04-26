CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_MISSION_ETAT AS
SELECT
  vhm.id volume_horaire_mission_id,
  evh.id etat_volume_horaire_id,
  v.id,
  vvhm.validation_id
FROM
  volume_horaire_mission vhm
  LEFT JOIN contrat c ON c.id = vhm.contrat_id AND c.histo_destruction IS NULL
  LEFT JOIN validation cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
  LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
  JOIN validation v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
  JOIN etat_volume_horaire evh ON evh.code =
  CASE
    WHEN c.date_retour_signe IS NOT NULL THEN 'contrat-signe'
    WHEN cv.id IS NOT NULL THEN 'contrat-edite'
    WHEN vhm.auto_validation = 1 OR v.id IS NOT NULL THEN 'valide'
    ELSE 'saisi'
  END