CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_INDEMNITES AS
WITH heures_paie_mission AS(
SELECT
tp.mission_id       																 mission_id,
SUM((tp.heures_payees_aa+tp.heures_payees_ac)*tp.taux_horaire*tp.taux_conges_payes)  total_paie,
SUM(tp.heures_a_payer_aa+tp.heures_a_payer_ac)         									 total_heures_a_payer,
SUM(tp.heures_payees_aa+tp.heures_payees_ac)                                             total_heures_payees 
FROM tbl_paiement tp 
WHERE tp.mission_id IS NOT NULL
GROUP BY tp.mission_id
)
SELECT 
mp.id                            prime_id, 
MAX(m.intervenant_id)        	 intervenant_id,
MAX(i.annee_id)              	 annee_id,
MAX(hpm.total_paie)              paie_mission,
MAX(hpm.total_heures_a_payer)    total_heures_a_payer,
MAX(hpm.total_heures_payees)     total_heures_payees,
MAX(hpm.total_paie * 0.1)        montant_prime
from mission_prime mp 
JOIN mission m ON mp.id = m.prime_id 
JOIN intervenant i ON i.id = m.intervenant_id 
JOIN heures_paie_mission hpm ON hpm.mission_id = m.id
WHERE 
--Il faut impérativement une prime validée
mp.declaration_id IS NOT NULL 
AND mp.validation_id IS NOT NULL
--Il faut payer les primes uniquement pour les missions payées en totalité
AND hpm.total_heures_a_payer = hpm.total_heures_payees
GROUP BY mp.id