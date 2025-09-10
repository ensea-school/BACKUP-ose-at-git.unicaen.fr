CREATE OR REPLACE FORCE VIEW V_TBL_SERVICE AS
WITH t AS (
SELECT
  s.id                                                                                      service_id,
  s.intervenant_id                                                                          intervenant_id,
  ep.structure_id                                                                           structure_id,
  ep.id                                                                                     element_pedagogique_id,
  ep.periode_id                                                                             element_pedagogique_periode_id,
  etp.id                                                                                    etape_id,

  vh.type_volume_horaire_id                                                                 type_volume_horaire_id,
  vh.heures                                                                                 heures,
  tvh.code                                                                                  type_volume_horaire_code,

  CASE WHEN ep.histo_destruction IS NULL THEN 1 ELSE 0 END                                  element_pedagogique_histo,
  CASE WHEN etp.histo_destruction IS NULL OR cp.id IS NOT NULL THEN 1 ELSE 0 END            etape_histo,

  CASE WHEN ep.periode_id IS NOT NULL THEN
    SUM( CASE WHEN vh.periode_id <> ep.periode_id THEN 1 ELSE 0 END ) OVER( PARTITION BY vh.service_id, vh.periode_id, vh.type_volume_horaire_id, vh.type_intervention_id )
  ELSE 0 END has_heures_mauvaise_periode,

  CASE WHEN v.id IS NULL AND vh.auto_validation=0 THEN 0 ELSE 1 END valide
FROM
  service                                       s
  LEFT JOIN element_pedagogique                ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             etp ON etp.id = ep.etape_id
  LEFT JOIN chemin_pedagogique                 cp ON cp.etape_id = etp.id
                                                 AND cp.element_pedagogique_id = ep.id
                                                 AND cp.histo_destruction IS NULL

       JOIN volume_horaire                     vh ON vh.service_id = s.id
                                                 AND vh.histo_destruction IS NULL

       JOIN type_volume_horaire               tvh ON tvh.id = vh.type_volume_horaire_id

  LEFT JOIN validation_vol_horaire            vvh ON vvh.volume_horaire_id = vh.id

  LEFT JOIN validation                          v ON v.id = vvh.validation_id
                                                 AND v.histo_destruction IS NULL
WHERE
  s.histo_destruction IS NULL
  /*@intervenant_id=s.intervenant_id*/
)
SELECT
  i.annee_id                                                             annee_id,
  i.id                                                                   intervenant_id,
  CASE WHEN t.type_volume_horaire_code = 'PREVU'
    THEN si.service_prevu
    ELSE si.service_realise
  END                                                                    actif,
  t.service_id                                                           service_id,
  t.element_pedagogique_id                                               element_pedagogique_id,
  ti.id                                                                  type_intervenant_id,
  ti.code                                                                type_intervenant_code,
  NVL( t.structure_id, i.structure_id )                                  structure_id,
  i.structure_id                                                         intervenant_structure_id,
  t.element_pedagogique_periode_id                                       element_pedagogique_periode_id,
  t.etape_id                                                             etape_id,
  t.type_volume_horaire_id                                               type_volume_horaire_id,
  t.type_volume_horaire_code                                             type_volume_horaire_code,
  t.element_pedagogique_histo                                            element_pedagogique_histo,
  t.etape_histo                                                          etape_histo,
  CASE WHEN SUM(t.has_heures_mauvaise_periode) > 0 THEN 1 ELSE 0 END     has_heures_mauvaise_periode,
  CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE count(*) END      nbvh,
  CASE WHEN type_volume_horaire_id IS NULL THEN 0 ELSE sum(t.heures) END heures,
  sum(valide)                                                            valide
FROM
  t
  JOIN intervenant                  i ON i.id = t.intervenant_id
  JOIN statut                      si ON si.id = i.statut_id
  JOIN type_intervenant            ti ON ti.id = si.type_intervenant_id
WHERE
  1=1
  /*@intervenant_id=i.id*/
  /*@annee_id=i.annee_id*/
GROUP BY
  i.annee_id,
  i.id,
  i.structure_id,
  t.structure_id,
  i.structure_id,
  ti.id,
  ti.code,
  si.service_prevu,
  si.service_realise,
  t.element_pedagogique_id,
  t.service_id,
  t.element_pedagogique_periode_id,
  t.etape_id,
  t.type_volume_horaire_id,
  t.type_volume_horaire_code,
  t.element_pedagogique_histo,
  t.etape_histo