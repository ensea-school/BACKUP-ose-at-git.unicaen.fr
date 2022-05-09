CREATE OR REPLACE FORCE VIEW V_INDICATEUR_310 AS
WITH caok AS (
  SELECT
    w.intervenant_id
  FROM
    tbl_workflow w
  WHERE
    w.objectif > 0
    AND w.etape_code = 'CONSEIL_ACADEMIQUE'
    AND w.realisation = w.objectif
)
SELECT
  w.intervenant_id,
  w.structure_id,
  TO_CHAR(MAX( a.HISTO_MODIFICATION),'YYYY-MM-DD HH24:MI:SS') AS "Date modif"
FROM
  tbl_workflow w
  JOIN TYPE_AGREMENT ta ON ta.code = 'CONSEIL_ACADEMIQUE'
  JOIN caok ON caok.intervenant_id = w.intervenant_id
  LEFT JOIN AGREMENT a ON a.intervenant_id = w.intervenant_id

WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation < 1
  AND a.TYPE_AGREMENT_ID = ta.id
GROUP BY (  w.intervenant_id, w.structure_id)