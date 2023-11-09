CREATE OR REPLACE FORCE VIEW V_INDICATEUR_392 AS
SELECT
    tp.intervenant_id,
    tp.structure_id
FROM
    tbl_mission_prime tp
WHERE
        tp.prime > tp.declaration
GROUP BY
    tp.intervenant_id,
    tp.annee_id,
    tp.structure_id