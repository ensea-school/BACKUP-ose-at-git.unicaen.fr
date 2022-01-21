SELECT
  i.annee_id                             annee_id,
  fr.type_volume_horaire_id              type_volume_horaire_id,
  i.id                                   intervenant_id,
  fr.total - fr.heures_compl_fc_majorees heures,
  si.maximum_hetd                        plafond
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id