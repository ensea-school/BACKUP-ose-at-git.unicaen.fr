CREATE OR REPLACE FORCE VIEW V_INDICATEUR_550 AS
SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond,
  fr.heures_compl_fc_majorees         heures
FROM
       intervenant                i
  JOIN annee                      a ON a.id = i.annee_id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
WHERE
  fr.heures_compl_fc_majorees > ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 )
  AND tvh.code = 'REALISE'