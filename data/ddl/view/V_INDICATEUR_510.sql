CREATE OR REPLACE FORCE VIEW V_INDICATEUR_510 AS
WITH t AS (
SELECT
  s.intervenant_id,
  s.annee_id,
  s.structure_id,
  listagg( ep.source_code || ' - ' || ep.libelle, '||') WITHIN GROUP (ORDER BY ep.libelle) elements
FROM
  tbl_service s
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
WHERE
  (
    s.has_heures_mauvaise_periode = 1
    OR s.etape_histo = 0
    OR s.element_pedagogique_histo = 0
  )
  AND s.heures > 0
GROUP BY
  s.intervenant_id,
  s.annee_id,
  s.structure_id
)
SELECT
  rownum id, t."INTERVENANT_ID",t."ANNEE_ID",t."STRUCTURE_ID", t.elements
FROM t