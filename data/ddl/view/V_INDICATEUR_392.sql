CREATE OR REPLACE FORCE VIEW V_INDICATEUR_392 AS
SELECT
    tp.intervenant_id,
    tp.structure_id
FROM
    tbl_mission_prime tp
    JOIN intervenant i on tp.intervenant_id = i.id
    JOIN statut s on i.statut_id = s.id
WHERE
        tp.prime > tp.declaration+tp.refus
        AND s.mission_indemnitees = 1
GROUP BY
    tp.intervenant_id,
    tp.annee_id,
    tp.structure_id