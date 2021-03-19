CREATE OR REPLACE FORCE VIEW SRC_CENTRE_COUT_STRUCTURE AS
WITH cc AS (
  SELECT
    cc.id id,
    cc.source_code source_code,
    cc.source_code ori_source_code
  FROM
    centre_cout cc
    LEFT JOIN centre_cout pcc ON pcc.id = cc.parent_id
  WHERE
    pcc.id IS NULL

  UNION ALL

  SELECT
    cc.id id,
    pcc.source_code source_code,
    cc.source_code ori_source_code
  FROM
         centre_cout  cc
    JOIN centre_cout pcc ON pcc.id = cc.parent_id
)
SELECT
  cc.id                                       centre_cout_id,
  s.id                                        structure_id,
  (SELECT id FROM source WHERE code='Calcul') source_id,
  cc.ori_source_code || '_' || s.source_code  source_code
FROM
  unicaen_corresp_structure_cc ucs
  JOIN cc ON substr( cc.source_code, 2, 3 ) = ucs.code_sifac
  JOIN structure s ON s.source_code = CASE
    WHEN cc.source_code = 'P950DRRA' THEN 'ECODOCT'
    WHEN cc.source_code = 'P950FCFCR' THEN 'drh-formation'
    WHEN cc.source_code = 'P950FCFFR' THEN 'drh-formation'
    ELSE ucs.code_harpege
  END;
