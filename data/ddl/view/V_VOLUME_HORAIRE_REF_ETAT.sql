CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_REF_ETAT AS
SELECT
  vhr.id volume_horaire_ref_id,
  evh.id etat_volume_horaire_id
FROM
  volume_horaire_ref vhr
  JOIN etat_volume_horaire evh ON evh.code = CASE
    WHEN vhr.auto_validation = 1 OR EXISTS(
      SELECT * FROM validation v JOIN validation_vol_horaire_ref vvhr ON vvhr.validation_id = v.id
      WHERE vvhr.volume_horaire_ref_id = vhr.id AND v.histo_destruction IS NULL
    ) THEN 'valide'
    ELSE 'saisi'
  END