CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_INDEMNITES AS
WITH heures_paie_mission AS(
	SELECT
		tp.mission_id       																 mission_id,
		SUM((tp.heures_payees_aa+tp.heures_payees_ac)*tp.taux_horaire*tp.taux_conges_payes)  total_paie,
		SUM(tp.heures_a_payer_aa+tp.heures_a_payer_ac)         									 total_heures_a_payer,
		SUM(tp.heures_payees_aa+tp.heures_payees_ac)                                             total_heures_payees
	FROM
		tbl_paiement tp
	WHERE
		tp.mission_id IS NOT NULL
	GROUP BY
		tp.mission_id
)
SELECT
	t.annee_id           				annee_id,
	ti.id				 				type_intervenant,
	i.structure_id		 				structure_id,
	t.periode_id         				periode_id,
	t.periode_code						periode_code,
	i.id				 				intervenant_id,
	CASE
     WHEN i.numero_insee IS NULL
     THEN '''' || TRIM(d.numero_insee)
     ELSE
         '''' || TRIM(i.numero_insee)
     END								insee,
  i.nom_usuel || ',' ||i.prenom 		nom,
	s.type_paie_prime				    carte,
	--Voir comment répartir le paiement prime aa/ac
	2									code_origine,
	s.code_indemnite_prime				retenue,
	'0'                                 sens,
	s.mode_calcul_prime					mc,
	''									nbu,
	t.montant_prime						montant,
	'INDEMNITE FIN CONTRAT'				libelle,
	t.prime_id							prime_id

FROM (
	SELECT
		mp.id                               prime_id,
		MAX(m.date_fin)                     date_fin_mission_max,
		MAX(m.intervenant_id)        	    intervenant_id,
		MAX(i.annee_id)              	    annee_id,
		max(p.code) 						periode_code,
		max(p.id) 							periode_id,
		MAX(hpm.total_paie)                 paie_mission,
		MAX(hpm.total_heures_a_payer)       total_heures_a_payer,
		MAX(hpm.total_heures_payees)        total_heures_payees,
		MAX(round(hpm.total_paie * 0.1,2))  montant_prime
	FROM mission_prime mp
	JOIN mission m ON mp.id = m.prime_id
	JOIN intervenant i ON i.id = m.intervenant_id
	JOIN statut s ON s.id = i.statut_id
	JOIN heures_paie_mission hpm ON hpm.mission_id = m.id
	JOIN annee a ON a.id = i.annee_id
	--On regarde la date de fin la plus éloignée des missions composants la prime, pour en déduire la période de paie au mois suivant cette date
	-- on ajoute 0.5 pour être sûre d'arrondir à l'entier supérieur et on filtre sur les périodes de paiement uniquement
	JOIN periode p ON p.ecart_mois = ROUND(MONTHS_BETWEEN(m.date_fin,a.date_debut)+0.5) and p.enseignement = 0
	WHERE
	--Il faut impérativement une prime validée
	mp.declaration_id IS NOT NULL
	AND mp.validation_id IS NOT NULL
	--Il faut payer les primes uniquement pour les missions payées en totalité
	AND hpm.total_heures_a_payer = hpm.total_heures_payees
	GROUP BY
		mp.id
) t
JOIN intervenant i ON t.intervenant_id = i.id
LEFT JOIN intervenant_dossier d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
JOIN statut s ON s.id = i.statut_id
JOIN type_intervenant ti ON ti.id = s.type_intervenant_id