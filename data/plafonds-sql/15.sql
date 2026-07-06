SELECT
  i.annee_id,
  fr.type_volume_horaire_id         type_volume_horaire_id,
  sr.structure_id                   structure_id,
  sum(frvr.total)                        heures
FROM
  formule_resultat_volume_horaire frvr
    JOIN formule_resultat_intervenant fr ON fr.id = frvr.formule_resultat_intervenant_id
    JOIN volume_horaire_ref          vhr ON vhr.id =  frvr.volume_horaire_ref_id AND vhr.histo_destruction IS NULL
    JOIN service_referentiel          sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.histo_destruction IS NULL
    JOIN intervenant                   i ON i.id = fr.intervenant_id
    JOIN etat_volume_horaire         evh ON evh.code = 'saisi' AND evh.id = fr.etat_volume_horaire_id
GROUP BY i.annee_id, fr.type_volume_horaire_id, sr.structure_id
