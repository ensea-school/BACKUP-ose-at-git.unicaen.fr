CREATE OR REPLACE FORCE VIEW V_HAS_DMEP_A_FAIRE AS
SELECT
  intervenant_id,
  structure_id,
  CASE WHEN
    SUM(CASE WHEN heures_dmep > heures_compl THEN heures_compl ELSE heures_dmep END) < SUM(heures_compl)
  THEN 1 ELSE 0 END has_dmep_a_faire
FROM
  (
  SELECT
    fr.intervenant_id intervenant_id,
    NVL( ep.structure_id, i.structure_id ) structure_id,
    frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees heures_compl,
    SUM( NVL(mep.heures,0) ) OVER (PARTITION BY frs.id) heures_dmep,
    SUM( NVL(CASE WHEN mep.periode_paiement_id IS NOT NULL THEN mep.heures ELSE 0 END,0) ) OVER (PARTITION BY frs.id) heures_mep,
    ROW_NUMBER() OVER (PARTITION BY frs.id ORDER BY 1) index__
  FROM
    formule_resultat_service frs
    JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
    JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code = 'REALISE'
    JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id AND evh.code = 'valide'
    JOIN intervenant i on i.id = fr.intervenant_id
    JOIN service s ON s.id = frs.service_id
    LEFT JOIN element_pedagogique ep on ep.id = s.element_pedagogique_id
    LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_id = frs.id AND mep.histo_destruction IS NULL

  UNION

    SELECT
    fr.intervenant_id intervenant_id,
    NVL( s.structure_id, i.structure_id ) structure_id,
    frs.heures_compl_referentiel heures_compl,
    SUM( NVL(mep.heures,0) ) OVER (PARTITION BY frs.id) heures_dmep,
    SUM( NVL(CASE WHEN mep.periode_paiement_id IS NOT NULL THEN mep.heures ELSE 0 END,0) ) OVER (PARTITION BY frs.id) heures_mep,
    ROW_NUMBER() OVER (PARTITION BY frs.id ORDER BY 1) index__
  FROM
    formule_resultat_service_ref frs
    JOIN formule_resultat fr ON fr.id = frs.formule_resultat_id
    JOIN type_volume_horaire tvh ON tvh.id = fr.type_volume_horaire_id AND tvh.code = 'REALISE'
    JOIN etat_volume_horaire evh ON evh.id = fr.etat_volume_horaire_id AND evh.code = 'valide'
    JOIN intervenant i on i.id = fr.intervenant_id
    JOIN service_referentiel s ON s.id = frs.service_referentiel_id
    LEFT JOIN mise_en_paiement mep ON mep.formule_res_service_ref_id = frs.id AND mep.histo_destruction IS NULL
  )mep
WHERE
  index__ = 1
GROUP BY
  intervenant_id,
  structure_id