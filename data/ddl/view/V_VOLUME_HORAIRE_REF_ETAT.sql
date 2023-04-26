CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_REF_ETAT AS
SELECT
  vhr.id volume_horaire_ref_id,
  evh.id etat_volume_horaire_id
FROM
  volume_horaire_ref vhr
  LEFT JOIN validation_vol_horaire_ref vvhr ON vvhr.volume_horaire_ref_id = vhr.id
  JOIN validation v ON v.id = vvhr.validation_id AND v.histo_destruction IS NULL
  JOIN etat_volume_horaire evh ON evh.code = CASE WHEN vhr.auto_validation = 1 OR v.id IS NOT NULL THEN 'valide' ELSE 'saisi' END