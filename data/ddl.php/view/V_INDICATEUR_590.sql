CREATE OR REPLACE FORCE VIEW V_INDICATEUR_590 AS
SELECT
  rownum                              id,
  t.annee_id,
  t.intervenant_id,
  t.structure_id,
  si.plafond_hc_fi_hors_ead           plafond,
  t.heures
FROM
  (
    SELECT
      fr.type_volume_horaire_id           type_volume_horaire_id,
      i.annee_id                          annee_id,
      i.id                                intervenant_id,
      i.structure_id                      structure_id,
      i.statut_id                         statut_intervenant_id,
      si.plafond_hc_fi_hors_ead           plafond,
      SUM(frvh.heures_compl_fi)           heures
    FROM
      intervenant                     i
      JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
      JOIN type_volume_horaire      tvh ON tvh.code= 'REALISE'
      JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id AND fr.type_volume_horaire_id = tvh.id
      JOIN formule_resultat_vh     frvh ON frvh.formule_resultat_id = fr.id
      JOIN volume_horaire            vh ON vh.id = frvh.volume_horaire_id
      JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
      JOIN statut_intervenant        si ON si.id = i.statut_id
    WHERE
      ti.regle_foad = 0
    GROUP BY
      fr.type_volume_horaire_id,
      i.annee_id,
      i.id,
      i.structure_id,
      i.statut_id,
      si.plafond_hc_fi_hors_ead
  ) t
    JOIN statut_intervenant si ON si.id = t.statut_intervenant_id
WHERE
  t.heures > si.plafond_hc_fi_hors_ead