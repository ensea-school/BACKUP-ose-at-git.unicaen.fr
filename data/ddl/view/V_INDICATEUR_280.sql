CREATE OR REPLACE FORCE VIEW V_INDICATEUR_280 AS
SELECT
    c.intervenant_id,
    i.structure_id
FROM
    candidature c
    JOIN intervenant i ON i.id = c.intervenant_id
    LEFT JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
WHERE
  c.histo_destruction IS null
  AND c.motif IS null
  AND v.id IS NULL
GROUP BY
  c.intervenant_id,
  i.annee_id,
  i.structure_id