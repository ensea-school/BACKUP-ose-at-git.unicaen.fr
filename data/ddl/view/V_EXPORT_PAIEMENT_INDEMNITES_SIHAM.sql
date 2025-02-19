CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_INDEMNITES_SIHAM AS
WITH heures_paie_mission AS(
SELECT
		tp.mission_id mission_id,
		SUM((tp.heures_payees_aa + tp.heures_payees_ac)* tp.taux_horaire * tp.taux_conges_payes) total_paie,
		SUM(tp.heures_a_payer_aa + tp.heures_a_payer_ac) total_heures_a_payer,
		SUM(tp.heures_payees_aa + tp.heures_payees_ac) total_heures_payees
FROM
		tbl_paiement tp
WHERE
		tp.mission_id IS NOT NULL
GROUP BY
		tp.mission_id
)
SELECT
	t.annee_id 															                            annee_id,
	ti.id 																                              type_intervenant,
	str.id 																                              structure_id,
	str.ids 															                              structure_ids,
	t.periode_id 														                            periode_id,
	t.periode_code 														                          periode_code,
	s.type_paie_prime 													                        type,
	i.code_rh 															                            matricule,
	s.code_indemnite_prime 												                      code_indemnite_retenu,
	TO_CHAR(TRUNC(ADD_MONTHS(t.date_fin, 1) , 'MONTH'), 'YYYY-MM') 	    du_mois,
	TO_CHAR(ADD_MONTHS(t.date_fin, 1), 'YY') 							              annee_de_paye,
	'="' || TO_CHAR(ADD_MONTHS(t.date_fin, 1), 'MM') || '"' 			      mois_de_paye,
	'="01"' 															                              numero_de_remise,
	'G' 																                                tg_specifique,
	'A definir' 														                            dossier_de_paye,
	TO_CHAR(TRUNC(ADD_MONTHS(t.date_fin, 1) , 'MONTH'), 'DD/MM/YYYY')   date_pecuniaire,
	'0' 																                                nombre_d_unites,
	t.montant_prime 													                          montant,
	'INDEMNITE FIN CONTRAT' 											                      libelle,
	s.mode_calcul_prime													                        mode_de_calcul,
	'1' 															                                  code_origine,
	i.id 																                                intervenant_id,
	i.nom_usuel || ',' || i.prenom 										                  nom,
	t.prime_id 															                            prime_id,
	t.date_declaration                                                  date_declaration
FROM
	(
	SELECT
		mp.id 									              prime_id,
		MAX(m.date_fin) 						          date_fin_mission_max,
		MAX(m.intervenant_id) 					      intervenant_id,
		MAX(i.annee_id) 						          annee_id,
		max(p.code) 							            periode_code,
		max(p.id) 								            periode_id,
		MAX(m.date_fin) 						          date_fin,
		SUM(hpm.total_paie) 					        paie_mission,
		SUM(hpm.total_heures_a_payer) 		  	total_heures_a_payer,
		SUM(hpm.total_heures_payees) 			    total_heures_payees,
		SUM(round(hpm.total_paie * 0.1, 2)) 	montant_prime,
  	MAX(f.histo_creation)                 date_declaration
	FROM
		mission_prime mp
	JOIN mission m ON
		mp.id = m.prime_id
	JOIN intervenant i ON
		i.id = m.intervenant_id
	JOIN statut s ON
		s.id = i.statut_id
	JOIN heures_paie_mission hpm ON
		hpm.mission_id = m.id
	JOIN annee a ON
		a.id = i.annee_id
		--On regarde la date de fin la plus éloignée des missions composants la prime, pour en déduire la période de paie au mois suivant cette date
		-- on ajoute 0.5 pour être sûre d'arrondir à l'entier supérieur et on filtre sur les périodes de paiement uniquement
	JOIN periode p ON
		p.ecart_mois = ROUND(MONTHS_BETWEEN(m.date_fin, a.date_debut)+ 0.5)
			AND p.enseignement = 0
  JOIN fichier f ON f.id = mp.declaration_id and f.histo_destruction IS NULL
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
JOIN type_intervenant ti ON	ti.id = s.type_intervenant_id
LEFT JOIN STRUCTURE str ON str.id = i.structure_id