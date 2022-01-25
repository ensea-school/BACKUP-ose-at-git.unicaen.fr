CREATE OR REPLACE FORCE VIEW V_TBL_VOLUME_HORAIRE AS
WITH has_cp AS (
SELECT
  etape_id
FROM
  chemin_pedagogique cp
WHERE
  cp.histo_destruction IS NULL
GROUP BY
  etape_id
)
SELECT
  i.annee_id                                                                                annee_id,
  i.id                                                                                      intervenant_id,
  i.structure_id                                                                            intervenant_structure_id,
  NVL(ep.structure_id, i.structure_id)                                                      structure_id,
  ti.id                                                                                     type_intervenant_id,
  s.id                                                                                      service_id,
  vh.id                                                                                     volume_horaire_id,
  vh.type_intervention_id                                                                   type_intervention_id,
  vh.motif_non_paiement_id                                                                  motif_non_paiement_id,
  vh.periode_id                                                                             volume_horaire_periode_id,
  tvh.id                                                                                    type_volume_horaire_id,
  evh.id                                                                                    etat_volume_horaire_id,
  ep.id                                                                                     element_pedagogique_id,
  ep.periode_id                                                                             element_pedagogique_periode_id,
  etp.id                                                                                    etape_id,

  ti.code                                                                                   type_intervenant_code,
  tvh.code                                                                                  type_volume_horaire_code,
  evh.code                                                                                  etat_volume_horaire_code,
  si.peut_saisir_service                                                                    peut_saisir_service,
  vh.heures                                                                                 heures,

  CASE WHEN ep.histo_destruction IS NULL THEN 1 ELSE 0 END                                  element_pedagogique_histo,
  CASE WHEN etp.histo_destruction IS NULL OR has_cp.etape_id IS NOT NULL THEN 1 ELSE 0 END  etape_histo,
  CASE WHEN ep.periode_id IS NOT NULL AND vh.periode_id <> ep.periode_id THEN 0 ELSE 1 END  periode_corresp

FROM
  intervenant                                   i
  JOIN statut                                  si ON si.id = i.statut_id
  JOIN type_intervenant                        ti ON ti.id = si.type_intervenant_id
  JOIN service                                  s ON s.intervenant_id = i.id
                                                 AND s.histo_destruction IS NULL
  JOIN element_pedagogique                     ep ON ep.id = s.element_pedagogique_id
  JOIN etape                                  etp ON etp.id = ep.etape_id
  JOIN volume_horaire                          vh ON vh.service_id = s.id
                                                 AND vh.histo_destruction IS NULL
  JOIN type_volume_horaire                    tvh ON tvh.id = vh.type_volume_horaire_id
  JOIN v_volume_horaire_etat                  vhe ON vhe.volume_horaire_id = vh.id
  JOIN etat_volume_horaire                    evh ON evh.id = vhe.etat_volume_horaire_id
  LEFT JOIN has_cp                                ON has_cp.etape_id = etp.id
WHERE
  i.histo_destruction IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/