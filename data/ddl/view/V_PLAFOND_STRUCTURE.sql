CREATE OR REPLACE FORCE VIEW V_PLAFOND_STRUCTURE AS
SELECT
  id,
  structure_id,
  plafond_id,
  a_id annee_id,
  heures,
  histo_modification,
  histo_modificateur_id
FROM
(
  SELECT
    tbl.*,
    a.id a_id,
    MAX(tbl.annee_id) OVER (partition by a.id, structure_id, plafond_id) annee_max_id
  FROM
    annee a
    JOIN plafond_structure tbl ON tbl.annee_id <= a.id
  WHERE
    a.active = 1
) t
WHERE
  annee_max_id = annee_id