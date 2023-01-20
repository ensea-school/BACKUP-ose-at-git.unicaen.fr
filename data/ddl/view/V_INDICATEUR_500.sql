CREATE OR REPLACE FORCE VIEW V_INDICATEUR_500 AS
SELECT DISTINCT
i.id AS intervenant_id,
i.structure_id
FROM
  intervenant i
  JOIN V_FORMULE_INTERVENANT vfi ON vfi.intervenant_id = i.id
  LEFT JOIN (
    SELECT ts.intervenant_id, SUM(ts.heures) heures
    FROM tbl_service ts
    WHERE ts.type_volume_horaire_code = 'PREVU'
    GROUP BY ts.intervenant_id
  ) ts ON ts.intervenant_id = i.id
  LEFT JOIN (
    SELECT tr.intervenant_id, SUM(tr.heures) heures
    FROM tbl_referentiel tr
    WHERE tr.type_volume_horaire_code = 'PREVU'
    GROUP BY tr.intervenant_id
  ) tr ON tr.intervenant_id = i.id
WHERE
  i.histo_destruction IS NULL
  AND vfi.type_intervenant_code = 'P'
  AND vfi.heures_service_statutaire + heures_service_modifie > 0
  AND COALESCE(ts.heures,0) + COALESCE(tr.heures,0) = 0