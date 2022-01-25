CREATE OR REPLACE FORCE VIEW V_EXPORT_DEPASS_CHARGES AS
WITH c AS (
  SELECT
    vhe.element_pedagogique_id,
    vhe.type_intervention_id,
    CASE WHEN MAX(vhe.groupes) IS NULL THEN
      'Charges OSE' ELSE s.libelle END source,
    MAX(vhe.heures) heures,
    COALESCE( MAX(vhe.groupes), ROUND(SUM(t.groupes),10) ) groupes

  FROM
    volume_horaire_ens     vhe
         JOIN parametre p ON p.nom = 'scenario_charges_services'
         JOIN source    s ON s.id = vhe.source_id
    LEFT JOIN tbl_chargens   t ON t.element_pedagogique_id = vhe.element_pedagogique_id
                              AND t.type_intervention_id = vhe.type_intervention_id
                              AND t.scenario_id = to_number(p.valeur)
  WHERE
    vhe.histo_destruction IS NULL
  GROUP BY
    vhe.element_pedagogique_id,
    vhe.type_intervention_id,
    s.libelle
), s AS (
  SELECT
    i.annee_id,
    vh.type_volume_horaire_id,
    s.intervenant_id,
    s.element_pedagogique_id,
    vh.type_intervention_id,
    SUM(vh.heures) heures
  FROM
    volume_horaire vh
    JOIN service     s ON s.id = vh.service_id
                      AND s.element_pedagogique_id IS NOT NULL
                      AND s.histo_destruction IS NULL
    JOIN intervenant i ON i.id = s.intervenant_id
                      AND i.histo_destruction IS NULL
  WHERE
    vh.histo_destruction IS NULL
  GROUP BY
    i.annee_id,
    vh.type_volume_horaire_id,
    s.intervenant_id,
    s.element_pedagogique_id,
    vh.type_intervention_id
)
SELECT
  s.annee_id                                  annee_id,
  sens.id                                     structure_id,
  tiv.id                                      type_intervention_id,

  a.libelle                                   annee,
  tvh.libelle                                 type_volume_horaire_code,
  i.source_code                               intervenant_code,
  i.nom_usuel || ' ' || i.prenom              intervenant_nom,
  i.date_naissance                            intervenant_date_naissance,
  si.libelle                                  intervenant_statut_libelle,

  ti.code                                     intervenant_type_code,
  ti.libelle                                  intervenant_type_libelle,
  CASE WHEN ti.code = 'P' THEN saff.libelle_court ELSE NULL END structure_aff_libelle,
  sens.libelle_court                          structure_ens_libelle,

  gtf.libelle_court                           groupe_type_formation_libelle,
  tf.libelle_court                            type_formation_libelle,
  etp.niveau                                  etape_niveau,
  etp.source_code                             etape_code,
  etp.libelle                                 etape_libelle,
  ep.source_code                              element_code,
  ep.libelle                                  element_libelle,
  ep.taux_fi                                  element_taux_fi,
  ep.taux_fc                                  element_taux_fc,
  ep.taux_fa                                  element_taux_fa,
  src.libelle                                 element_source_libelle,
  p.libelle_court                             periode,
  tiv.code                                    type_intervention_code,
  s.heures                                    heures_service,
  c.source                                    source_charges,
  c.heures                                    heures_charges,
  c.groupes                                   groupes_charges,
  s.heures - (COALESCE(c.heures * c.groupes,0) / COUNT(DISTINCT i.id) OVER (PARTITION BY tvh.id, ep.id, tiv.id)) heures_depassement
FROM
                                    s
       JOIN annee                   a ON a.id = s.annee_id
       JOIN type_volume_horaire   tvh ON tvh.id = s.type_volume_horaire_id
       JOIN intervenant             i ON i.id = s.intervenant_id
       JOIN statut                 si ON si.id = i.statut_id
       JOIN type_intervenant       ti ON ti.id = si.type_intervenant_id
       JOIN element_pedagogique    ep ON ep.id = s.element_pedagogique_id
       JOIN etape                 etp ON etp.id = ep.etape_id
       JOIN type_formation         tf ON tf.id = etp.type_formation_id
       JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id
       JOIN structure            sens ON sens.id = ep.structure_id
       JOIN source                src ON src.id = ep.source_id
       JOIN type_intervention     tiv ON tiv.id = s.type_intervention_id
  LEFT JOIN structure            saff ON saff.id = i.structure_id
  LEFT JOIN                         c ON c.element_pedagogique_id = s.element_pedagogique_id
                                     AND c.type_intervention_id = COALESCE(tiv.type_intervention_maquette_id,tiv.id)
  LEFT JOIN periode                 p ON p.id = ep.periode_id