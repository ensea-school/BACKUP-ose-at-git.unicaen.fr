SELECT
  i.annee_id                          annee_id,
  fr.type_volume_horaire_id           type_volume_horaire_id,
  i.id                                intervenant_id,
  fr.heures_primes                    heures
  /*ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond*/

FROM
       intervenant                   i
  JOIN annee                         a ON a.id = i.annee_id
  JOIN statut                       si ON si.id = i.statut_id
  JOIN etat_volume_horaire         evh ON evh.code = 'saisi'
  JOIN formule_resultat_intervenant fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id