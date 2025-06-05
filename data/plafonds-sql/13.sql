SELECT
  i.annee_id                annee_id,
  fr.type_volume_horaire_id type_volume_horaire_id,
  i.id                      intervenant_id,
  SUM(frvh.heures_compl_fi) heures
FROM
  intervenant                             i
  JOIN etat_volume_horaire              evh ON evh.code = 'saisi'
  JOIN formule_resultat_intervenant      fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN formule_resultat_volume_horaire frvh ON frvh.formule_resultat_intervenant_id = fr.id
  JOIN volume_horaire                    vh ON vh.id = frvh.volume_horaire_id
  JOIN type_intervention                 ti ON ti.id = vh.type_intervention_id
  JOIN statut                            si ON si.id = i.statut_id
WHERE
  ti.regle_foad = 0
GROUP BY
  fr.type_volume_horaire_id,
  i.annee_id,
  i.id,
  i.statut_id