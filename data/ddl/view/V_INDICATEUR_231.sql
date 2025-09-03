CREATE OR REPLACE FORCE VIEW V_INDICATEUR_231 AS
SELECT DISTINCT
  MAX(i.NOM_USUEL),
  MAX(i.PRENOM),
  w.intervenant_id,
  i.structure_id,
  MAX(pj.histo_modification) AS "Date de modification"
FROM
  tbl_workflow w
  JOIN tbl_workflow wc ON wc.intervenant_id = w.intervenant_id
  JOIN intervenant i ON i.id = w.intervenant_id AND i.histo_destruction IS NULL
  JOIN tbl_piece_jointe tpj ON i.id = tpj.intervenant_id AND tpj.demandee = 0
  LEFT JOIN piece_jointe pj ON i.id = pj.intervenant_id
WHERE
  w.etape_code = 'PJ_VALIDATION'
  AND wc.etape_code = 'PJ_SAISIE'
  AND w.type_intervenant_code = 'E'
  AND w.atteignable = 1
  AND tpj.fournie = 1
  AND tpj.validee = 0
GROUP BY (w.intervenant_id, i.structure_id)