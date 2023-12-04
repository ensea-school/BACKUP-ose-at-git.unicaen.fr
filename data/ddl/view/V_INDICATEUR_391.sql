CREATE OR REPLACE FORCE VIEW V_INDICATEUR_391 AS
SELECT
    tmp.intervenant_id                intervenant_id,
    tmp.structure_id      structure_id
FROM
    tbl_mission_prime tmp
WHERE tmp.prime = 0 and tmp.actif = 1
GROUP BY
    tmp.intervenant_id,
    tmp.structure_id