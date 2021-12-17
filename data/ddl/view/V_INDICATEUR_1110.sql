CREATE OR REPLACE FORCE VIEW V_INDICATEUR_1110 AS
SELECT DISTINCT
  idc.intervenant_id,
  idc.structure_id
FROM
            v_indic_depass_charges  idc
       JOIN type_volume_horaire     tvh ON tvh.id = idc.type_volume_horaire_id
  LEFT JOIN periode                 p ON p.id = idc.periode_id
WHERE
  (p.code = 'S1' OR p.id IS NULL)
  AND tvh.code = 'PREVU'