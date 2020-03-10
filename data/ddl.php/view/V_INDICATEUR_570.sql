CREATE OR REPLACE FORCE VIEW V_INDICATEUR_570 AS
SELECT
  rownum                              id,
  i.annee_id                          annee_id,
  i.id                                intervenant_id,
  i.structure_id                      structure_id,
  si.maximum_hetd                     plafond,
  fr.total                            heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
  JOIN statut_intervenant        si ON si.id = i.statut_id
  JOIN type_volume_horaire      tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code= 'REALISE'
WHERE
  fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd