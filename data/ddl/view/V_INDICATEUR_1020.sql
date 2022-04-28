CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1020 AS
SELECT DISTINCT
  w.intervenant_id,
  i.structure_id,
  TO_CHAR(MAX( pj.HISTO_MODIFICATION),'YYYY-MM-DD HH24:MI:SS') AS "Date modif"
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id
  JOIN PIECE_JOINTE pj ON i.id = pj.intervenant_id
WHERE
  w.etape_code = 'PJ_VALIDATION'
  AND wc.etape_code = 'PJ_SAISIE'
  AND w.type_intervenant_code = 'E'
  AND wc.objectif = wc.realisation
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY (w.intervenant_id, i.STRUCTURE_ID)