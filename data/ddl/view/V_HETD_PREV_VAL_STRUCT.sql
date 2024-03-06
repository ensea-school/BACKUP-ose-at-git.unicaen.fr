CREATE OR REPLACE FORCE VIEW V_HETD_PREV_VAL_STRUCT AS
SELECT
  annee_id,
  structure_id,
  SUM(heures) heures

FROM (
    SELECT
      i.annee_id,
      coalesce(ep.structure_id, i.structure_id)                                                structure_id,
      frvh.heures_compl_fi + frvh.heures_compl_fa + frvh.heures_compl_fc + frvh.heures_primes  heures
    FROM
      formule_resultat_volume_horaire frvh
      JOIN formule_resultat_intervenant fr ON fr.id = frvh.formule_resultat_intervenant_id
      JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
      JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
      JOIN intervenant i ON i.id = fr.intervenant_id
      JOIN service s ON s.id = frvh.service_id
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
    WHERE
      tvh.code = 'PREVU'
      AND evh.code = 'valide'
  ) t1
GROUP BY
  annee_id, structure_id