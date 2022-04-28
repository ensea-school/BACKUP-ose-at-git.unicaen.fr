CREATE OR REPLACE FORCE VIEW V_INDICATEUR_320 AS
SELECT DISTINCT
  w.intervenant_id,
  w.structure_id,
  TO_CHAR(MAX( a.HISTO_MODIFICATION),'YYYY-MM-DD HH24:MI:SS') AS "Date modif"
FROM
  tbl_workflow w
  JOIN intervenant  i ON w.intervenant_id = i.id
  JOIN statut      si ON si.id = i.statut_id
  JOIN AGREMENT a ON i.id = a.INTERVENANT_ID
  LEFT JOIN contrat c ON c.intervenant_id = w.intervenant_id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > 0
  AND w.realisation = 0
  AND c.histo_destruction IS NULL
  AND i.histo_destruction IS NULL
  AND si.histo_destruction IS NULL
  AND c.id IS NULL
  AND si.contrat = 1
 GROUP BY (w.INTERVENANT_ID , w.STRUCTURE_ID )