CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_REF_ETAT AS
SELECT
  vhr.id volume_horaire_ref_id, evh.id etat_volume_horaire_id
FROM
  volume_horaire_ref vhr
  LEFT JOIN contrat c ON c.id = vhr.contrat_id AND c.histo_destruction IS NULL
  LEFT JOIN validation cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
  LEFT JOIN (
    SELECT
      vvhr.volume_horaire_ref_id
    FROM
      type_validation tv
      JOIN validation v ON v.type_validation_id = tv.id AND v.histo_destruction IS NULL
      JOIN validation_vol_horaire_ref vvhr ON vvhr.validation_id = v.id
    WHERE
      tv.code = 'REFERENTIEL'
  ) t ON t.volume_horaire_ref_id = vhr.id
  JOIN etat_volume_horaire evh ON evh.code = CASE
    WHEN c.date_retour_signe IS NOT NULL THEN 'contrat-signe'
    WHEN cv.id IS NOT NULL THEN 'contrat-edite'
    WHEN vhr.auto_validation = 1 OR t.volume_horaire_ref_id IS NOT NULL THEN 'valide'
    ELSE 'saisi'
  END