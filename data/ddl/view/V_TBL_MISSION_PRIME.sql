CREATE OR REPLACE FORCE VIEW V_TBL_MISSION_PRIME AS
SELECT DISTINCT
    i.annee_id 			annee_id,
    i.id 			    intervenant_id,
    i.structure_id      structure_id,
    '1' 				actif,
    count(mp.id)        prime,
    SUM(CASE WHEN mp.declaration_id IS NOT NULL THEN 1 ELSE 0 END)   declaration,
    SUM(CASE WHEN mp.validation_id IS NOT NULL THEN 1 ELSE 0 END)   validation,
    SUM(CASE WHEN mp.date_refus IS NOT NULL THEN 1 ELSE 0 END)   refus
FROM
    intervenant i
    JOIN mission m ON m.intervenant_id = i.id
    JOIN contrat c ON c.mission_id = m.id AND c.histo_destruction IS NULL
    LEFT JOIN mission_prime mp ON m.prime_id = mp.id AND mp.histo_destruction IS null
WHERE i.histo_destruction IS NULL
  AND c.date_retour_signe IS NOT NULL
/*@INTERVENANT_ID=i.id*/
/*@ANNEE_ID=i.annee_id*/
GROUP BY
    i.id,
    i.annee_id,
    i.structure_id

