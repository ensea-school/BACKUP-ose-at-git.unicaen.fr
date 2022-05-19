CREATE OR REPLACE FORCE VIEW V_INDICATEUR_610 AS
SELECT DISTINCT
  s.intervenant_id,
  s.structure_id,
  ep.source_code || ' - ' || ep.libelle "Enseignements concernés"
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