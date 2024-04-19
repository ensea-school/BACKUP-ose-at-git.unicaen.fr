CREATE OR REPLACE FORCE VIEW V_INDICATEUR_391 AS
SELECT
i.id                intervenant_id,
i.structure_id      structure_id
FROM
mission m
JOIN contrat c ON c.mission_id = m.id AND c.histo_destruction IS NULL
JOIN intervenant i ON i.id = m.intervenant_id
JOIN statut s ON s.id = i.statut_id
WHERE c.date_retour_signe IS NOT NULL
AND s.mission_indemnitees = 1
GROUP BY
i.id,
i.structure_id
HAVING SUM(m.prime_id) IS NULL

