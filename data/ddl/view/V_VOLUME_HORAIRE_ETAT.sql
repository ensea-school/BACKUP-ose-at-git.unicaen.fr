CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_ETAT AS
SELECT
  vh.id volume_horaire_id, evh.id etat_volume_horaire_id
FROM
  volume_horaire vh
  LEFT JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
  LEFT JOIN validation cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
  LEFT JOIN (
    SELECT
      vvh.volume_horaire_id
    FROM
      type_validation tv
      JOIN validation v ON v.type_validation_id = tv.id AND v.histo_destruction IS NULL
      JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
    WHERE
      tv.code = 'SERVICES_PAR_COMP'
  ) t ON t.volume_horaire_id = vh.id
  JOIN etat_volume_horaire evh ON evh.code = CASE
    WHEN c.date_retour_signe IS NOT NULL THEN 'contrat-signe'
    WHEN cv.id IS NOT NULL THEN 'contrat-edite'
    WHEN vh.auto_validation = 1 OR t.volume_horaire_id IS NOT NULL THEN 'valide'
    ELSE 'saisi'
  END