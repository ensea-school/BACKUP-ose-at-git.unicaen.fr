CREATE OR REPLACE FORCE VIEW V_INDICATEUR_920 AS
SELECT
  w.intervenant_id,
  w.structure_id,
  Max(v.HISTO_CREATION) AS dateModif
FROM
  tbl_workflow w
  JOIN VALIDATION v ON v.INTERVENANT_ID = w.INTERVENANT_ID
  JOIN type_volume_horaire tvh ON tvh.code = 'REALISE'
  LEFT JOIN VALIDATION_VOL_HORAIRE vvh ON vvh.VALIDATION_ID = v.ID
  LEFT JOIN VALIDATION_VOL_HORAIRE_REF vvhr ON vvhr.VALIDATION_ID = v.ID
  LEFT JOIN VOLUME_HORAIRE vh ON vh.ID = vvh.VOLUME_HORAIRE_ID
  LEFT JOIN VOLUME_HORAIRE_REF vhr ON vhr.ID = vvhr.VOLUME_HORAIRE_REF_ID
WHERE
  w.etape_code = 'SAISIE_MEP'
  AND w.type_intervenant_code = 'E'
  AND w.atteignable = 1
  AND w.objectif > w.realisation
  AND (vhr.TYPE_VOLUME_HORAIRE_ID = tvh.id OR vh.TYPE_VOLUME_HORAIRE_ID = tvh.id)
GROUP BY
  w.intervenant_id, w.structure_id