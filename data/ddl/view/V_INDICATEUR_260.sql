CREATE OR REPLACE FORCE VIEW V_INDICATEUR_260 AS
SELECT DISTINCT
  w.intervenant_id,
  i.structure_id,
  MAX(pj.HISTO_MODIFICATION) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id
  JOIN PIECE_JOINTE pj ON i.id = pj.intervenant_id
WHERE
  w.etape_code = 'pj_validation'
  AND wc.etape_code = 'pj_saisie'
  AND w.type_intervenant_code = 'S'
  AND wc.objectif = wc.realisation
  AND w.atteignable = 1
  AND w.objectif > w.realisation
GROUP BY (w.intervenant_id, i.STRUCTURE_ID)