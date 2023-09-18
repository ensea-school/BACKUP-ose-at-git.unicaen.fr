CREATE OR REPLACE FORCE VIEW V_TBL_MISSION_PRIME AS
SELECT DISTINCT
i.annee_id 			annee_id,
i.id 			    intervenant_id,
i.structure_id      structure_id,
'1' 				actif,
CASE WHEN count(mp.id) = 0 THEN 1 ELSE count(mp.id) END		    prime,
SUM(CASE WHEN mp.declaration_id IS NOT NULL THEN 1 ELSE 0 END)   declaration,
SUM(CASE WHEN mp.validation_id IS NOT NULL THEN 1 ELSE 0 END)   validation,
SUM(CASE WHEN mp.date_refus IS NOT NULL THEN 1 ELSE 0 END)   refus
FROM
intervenant i
LEFT JOIN mission_prime mp ON i.id = mp.intervenant_id AND mp.histo_destruction IS null
WHERE mp.histo_destruction IS NULL
AND i.histo_destruction IS NULL
/*@INTERVENANT_ID=i.id*/
/*@ANNEE_ID=i.annee_id*/
GROUP BY
i.id,
i.annee_id,
i.structure_id