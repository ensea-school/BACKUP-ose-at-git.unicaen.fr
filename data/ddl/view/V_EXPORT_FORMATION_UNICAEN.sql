CREATE OR REPLACE FORCE VIEW V_EXPORT_FORMATION_UNICAEN AS
SELECT
	MAX(e.annee_id) 															                              annee_id,
	MAX(s.id) 																	                                structure_id,
	MAX(s.ids) 																	                                structure_ids,
	MAX(s.libelle_court) 														                            STRUCTURE,
	MAX(e.code) 																                                code_formation,
	MAX(e.libelle) 																                              libelle_formation,
	MAX(e.id) 																	                                etape_id,
	MAX(CONCAT(gtf.libelle_court, e.niveau)) 									                  niveau,
	MAX(ep.code) 																                                code_enseignement,
	MAX(ep.libelle) 															                              libelle_enseignement,
	MAX(d.source_code) 															                            code_discipline,
	MAX(d.libelle_long) 														                            libelle_discipline,
	MAX(p.libelle_long) 														                            periode,
	MAX(ep.taux_foad) 															                            foad,
	MAX(ep.fi) 																	                                fi,
	MAX(ep.fa) 																	                                fa,
	MAX(ep.fc) 																	                                fc,
	COALESCE(MAX(ef.fi), 0) 													                          effectif_fi,
	COALESCE(MAX(ef.fa), 0) 													                          effectif_fa,
	COALESCE(MAX(ef.fc), 0) 		                          											effectif_fc,
	MAX(CASE WHEN ep.fi = 1 THEN
		CASE WHEN cc.code IS NOT NULL AND th.code = 'fi'
			 THEN cc.code ELSE 'manquant' END
		ELSE NULL END) 															                              centre_cout_fi,
	MAX(CASE WHEN ep.fa = 1 THEN
		CASE WHEN cc.code IS NOT NULL AND th.code = 'fa'
			 THEN cc.code ELSE 'manquant' END
		ELSE NULL END) 															                              centre_cout_fa,
	MAX(CASE WHEN ep.fc = 1 THEN
		CASE WHEN cc.code IS NOT NULL AND th.code = 'fc'
			 THEN cc.code ELSE 'manquant' END
		ELSE NULL END) 															                              centre_cout_fc,
	COALESCE(SUM(CASE WHEN ti.code = 'CM' THEN vhe.heures END), 0) 				      heures_cm,
	COALESCE(SUM(CASE WHEN ti.code = 'CM' THEN vhe.groupes END), 0) 			      nb_groupe_cm,
	COALESCE(SUM(CASE WHEN ti.code = 'TD' THEN vhe.heures END), 0) 				      heures_td,
	COALESCE(SUM(CASE WHEN ti.code = 'TD' THEN vhe.groupes END), 0) 			      nb_groupe_td,
	COALESCE(SUM(CASE WHEN ti.code = 'TP' THEN vhe.heures END), 0) 				      heures_tp,
	COALESCE(SUM(CASE WHEN ti.code = 'TP' THEN vhe.groupes END), 0) 			      nb_groupe_tp,
	COALESCE(SUM(CASE WHEN ti.code = 'Accompagnement' THEN vhe.heures END), 0)  heures_accompagnement,
	COALESCE(SUM(CASE WHEN ti.code = 'Accompagnement' THEN vhe.groupes END), 0) nb_groupe_accompagnement
FROM
	etape e
LEFT JOIN type_formation tf ON e.type_formation_id = tf.id
LEFT JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id
LEFT JOIN STRUCTURE s ON	s.id = e.structure_id
LEFT JOIN element_pedagogique ep ON	ep.etape_id = e.id
	AND ep.histo_destruction IS NULL
LEFT JOIN discipline d ON	d.id = ep.discipline_id
LEFT JOIN periode p ON	p.id = ep.periode_id
LEFT JOIN effectifs ef ON	ef.element_pedagogique_id = ep.id
LEFT JOIN volume_horaire_ens vhe ON	vhe.element_pedagogique_id = ep.id
LEFT JOIN type_intervention ti ON	ti.id = vhe.type_intervention_id
LEFT JOIN centre_cout_ep cce ON	cce.element_pedagogique_id = ep.id
	AND cce.histo_destruction IS NULL
LEFT JOIN centre_cout cc ON	cc.id = cce.centre_cout_id
	AND cc.histo_destruction IS NULL
LEFT JOIN type_heures th ON	th.id = cce.type_heures_id
WHERE
	e.histo_destruction IS NULL
	AND e.annee_id = 2025
	AND e.structure_id = 495
GROUP BY
	ep.id
ORDER BY
	MAX(e.annee_id),
	MAX(s.id),
	MAX(e.code),
	MAX(ep.code)