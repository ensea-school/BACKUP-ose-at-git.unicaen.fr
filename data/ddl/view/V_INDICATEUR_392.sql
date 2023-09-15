CREATE OR REPLACE FORCE VIEW V_INDICATEUR_392 AS
SELECT
i.id                intervenant_id,
i.structure_id      structure_id
from
mission m
JOIN contrat c ON c.mission_id = m.id AND c.histo_destruction IS NULL
JOIN intervenant i ON i.id = m.intervenant_id
WHERE c.date_retour_signe IS NOT NULL
GROUP BY
i.id,
i.structure_id
HAVING SUM(m.prime_id) IS NULL