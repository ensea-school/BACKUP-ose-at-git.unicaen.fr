CREATE OR REPLACE FORCE VIEW V_TBL_MISSION_PRIME AS
SELECT DISTINCT
i.annee_id 			annee_id,
i.id 			    intervenant_id,
i.structure_id      structure_id,
'1' 				actif,
count(*)		    prime,
SUM(CASE WHEN mp.declaration_id IS NOT NULL THEN 1 ELSE 0 END)   declaration,
SUM(CASE WHEN mp.validation_id IS NOT NULL THEN 1 ELSE 0 END)   validation,
SUM(CASE WHEN mp.date_refus IS NOT NULL THEN 1 ELSE 0 END)   refus
FROM
mission_prime mp
JOIN intervenant i ON i.id = mp.intervenant_id
WHERE mp.histo_destruction IS NULL
/*@INTERVENANT_ID=i.id*/
/*@ANNEE_ID=i.annee_id*/
GROUP BY
i.id,
i.annee_id,
i.structure_id