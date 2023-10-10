CREATE OR REPLACE FORCE VIEW V_VOLUME_HORAIRE_REF_ETAT AS
SELECT
  vh.id volume_horaire_ref_id,
  evh.id etat_volume_horaire_id
FROM
  volume_horaire_ref vh
  LEFT JOIN contrat c ON c.id = vh.contrat_id AND c.histo_destruction IS NULL
  LEFT JOIN validation cv ON cv.id = c.validation_id AND cv.histo_destruction IS NULL
  JOIN etat_volume_horaire evh ON evh.code = CASE
    WHEN c.date_retour_signe IS NOT NULL THEN 'contrat-signe'
    WHEN cv.id IS NOT NULL THEN 'contrat-edite'
    WHEN vh.auto_validation = 1 OR EXISTS(
      SELECT * FROM validation v JOIN validation_vol_horaire_ref vvh ON vvh.validation_id = v.id
      WHERE vvh.volume_horaire_ref_id = vh.id AND v.histo_destruction IS NULL
    ) THEN 'valide'
    ELSE 'saisi'
    END