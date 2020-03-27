CREATE OR REPLACE FORCE VIEW V_INDICATEUR_680 AS
SELECT
  fr.id id,
  i.annee_id annee_id,
  i.id intervenant_id,
  i.structure_id structure_id,
  si.plafond_referentiel plafond,
  fr.heures_compl_referentiel heures
FROM
  formule_resultat fr
  JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id
  JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN statut_intervenant si ON si.id = i.statut_id
WHERE
  tvh.code = 'PREVU'
  AND evh.code = 'saisi'
  AND si.plafond_referentiel < fr.service_referentiel + fr.heures_compl_referentiel