SELECT
  i.annee_id                          annee_id,
  fr.type_volume_horaire_id           type_volume_horaire_id,
  fr.intervenant_id                   intervenant_id,
  fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel heures
FROM
       intervenant                   i
  JOIN statut                       si ON si.id = i.statut_id
  JOIN etat_volume_horaire         evh ON evh.code = 'saisi'
  JOIN formule_resultat_intervenant fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id