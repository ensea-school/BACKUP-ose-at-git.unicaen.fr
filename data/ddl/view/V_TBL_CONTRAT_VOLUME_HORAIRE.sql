CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT_VOLUME_HORAIRE AS
SELECT
  i.annee_id                                                                      annee_id,
  ep.structure_id                                                                 structure_id,
  i.id                                                                            intervenant_id,
  vh.contrat_id                                                                   contrat_id,

  vh.service_id                                                                   service_id,
  NULL                                                                            service_referentiel_id,
  NULL                                                                            mission_id,

  vh.id                                                                           volume_horaire_id,
  NULL                                                                            volume_horaire_ref_id,
  NULL                                                                            volume_horaire_mission_id,

  ep.taux_remu_id                                                                 taux_remu_id,
  ep.taux_remu_id                                                                 taux_remu_majore_id,

  NULL                                                                            date_fin_mission,

  CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END                              cm,
  CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END                              td,
  CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END                              tp,
  CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END             autres,
  vh.heures                                                                       heures,
  frv.total                                                                       hetd,

  CASE WHEN ti.code NOT IN ('CM', 'TD', 'TP') THEN ti.code ELSE NULL END          autre_libelle,
  null                                                                            type_mission_libelle,
  null                                                                            mission_libelle
FROM
            volume_horaire                   vh
       JOIN service                           s ON s.id = vh.service_id
       JOIN intervenant                       i ON i.id = s.intervenant_id
       JOIN type_intervention                ti ON ti.id = vh.type_intervention_id
       JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id

       JOIN type_volume_horaire             tvh ON tvh.code = 'PREVU'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'

       JOIN formule_resultat_intervenant     fr ON  fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id AND fr.type_volume_horaire_id = tvh.id
       JOIN formule_resultat_volume_horaire frv ON  frv.formule_resultat_intervenant_id = fr.id AND frv.volume_horaire_id = vh.id

WHERE
  vh.histo_destruction IS NULL
  AND vh.motif_non_paiement_id IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=i.statut_id*/


UNION ALL


SELECT
  i.annee_id                                                                      annee_id,
  i.structure_id                                                                  structure_id,
  i.id                                                                            intervenant_id,
  vh.contrat_id                                                                   contrat_id,

  vh.service_id                                                                   service_id,
  NULL                                                                            service_referentiel_id,
  NULL                                                                            mission_id,

  vh.id                                                                           volume_horaire_id,
  NULL                                                                            volume_horaire_ref_id,
  NULL                                                                            volume_horaire_mission_id,

  NULL                                                                            taux_remu_id,
  NULL                                                                            taux_remu_majore_id,

  NULL                                                                            date_fin_mission,

  CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END                              cm,
  CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END                              td,
  CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END                              tp,
  CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END             autres,
  vh.heures                                                                       heures,
  frv.total                                                                       hetd,

  CASE WHEN ti.code NOT IN ('CM', 'TD', 'TP') THEN ti.code ELSE NULL END          autre_libelle,
  null                                                                            type_mission_libelle,
  null                                                                            mission_libelle
FROM
            volume_horaire                   vh
       JOIN service                           s ON s.id = vh.service_id AND s.element_pedagogique_id IS NULL
       JOIN intervenant                       i ON i.id = s.intervenant_id
       JOIN type_intervention                ti ON ti.id = vh.type_intervention_id

       JOIN type_volume_horaire             tvh ON tvh.code = 'PREVU'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'

       JOIN formule_resultat_intervenant     fr ON  fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id AND fr.type_volume_horaire_id = tvh.id
       JOIN formule_resultat_volume_horaire frv ON  frv.formule_resultat_intervenant_id = fr.id AND frv.volume_horaire_id = vh.id
WHERE
  vh.histo_destruction IS NULL
  AND vh.motif_non_paiement_id IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=i.statut_id*/


UNION ALL


SELECT
  i.annee_id                                                                      annee_id,
  sr.structure_id                                                                 structure_id,
  i.id                                                                            intervenant_id,
  vhr.contrat_id                                                                  contrat_id,

  NULL                                                                            service_id,
  vhr.service_referentiel_id                                                      service_referentiel_id,
  NULL                                                                            mission_id,

  NULL                                                                            volume_horaire_id,
  vhr.id                                                                          volume_horaire_ref_id,
  NULL                                                                            volume_horaire_mission_id,

  NULL                                                                            taux_remu_id,
  NULL                                                                            taux_remu_majore_id,

  NULL                                                                            date_fin_mission,

  0                                                                               cm,
  0                                                                               td,
  0                                                                               tp,
  vhr.heures                                                                      autres,
  vhr.heures                                                                      heures,
  frvr.total                                                                      hetd,

  fon_ref.libelle_long                                                            autre_libelle,
  null                                                                            type_mission_libelle,
  null                                                                            mission_libelle
FROM
            volume_horaire_ref          vhr
       JOIN service_referentiel         sr ON sr.id = vhr.service_referentiel_id
       JOIN intervenant                  i ON i.id = sr.intervenant_id
       JOIN fonction_referentiel   fon_ref ON fon_ref.id = sr.fonction_id

       JOIN type_volume_horaire        tvh ON tvh.code = 'PREVU'
       JOIN etat_volume_horaire        evh ON evh.code = 'valide'

       JOIN formule_resultat_intervenant     fr ON  fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id AND fr.type_volume_horaire_id = tvh.id
       JOIN formule_resultat_volume_horaire frvr ON  frvr.formule_resultat_intervenant_id = fr.id AND frvr.volume_horaire_ref_id = vhr.id

WHERE
  vhr.histo_destruction IS NULL
  AND sr.motif_non_paiement_id IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=i.statut_id*/


UNION ALL


SELECT
  i.annee_id                                                                      annee_id,
  m.structure_id                                                                  structure_id,
  i.id                                                                            intervenant_id,
  vhm.contrat_id                                                                  contrat_id,

  NULL                                                                            service_id,
  NULL                                                                            service_referentiel_id,
  m.id                                                                            mission_id,

  NULL                                                                            volume_horaire_id,
  NULL                                                                            volume_horaire_ref_id,
  vhm.id                                                                          volume_horaire_mission_id,

  tm.taux_remu_id                                                                 taux_remu_id,
  tm.taux_remu_majore_id                                                          taux_remu_majore_id,

  m.date_fin                                                                      date_fin_mission,
  m.date_debut                                                                    date_debut_mission,
  0                                                                               cm,
  0                                                                               td,
  0                                                                               tp,
  vhm.heures                                                                      autres,
  vhm.heures                                                                      heures,
  vhm.heures                                                                      hetd,

  CASE WHEN
    m.libelle_mission IS NOT NULL
    THEN m.libelle_mission || ' (' || tm.libelle || ')'
    ELSE tm.libelle
  END autre_libelle,
  tm.libelle                                                                      type_mission_libelle,
  COALESCE(m.libelle_mission, tm.libelle)                                         mission_libelle
FROM
       volume_horaire_mission            vhm
  JOIN mission                             m ON m.id = vhm.mission_id
  JOIN type_mission                       tm ON tm.id = m.type_mission_id

  JOIN type_volume_horaire               tvh ON tvh.id = vhm.type_volume_horaire_id AND tvh.code = 'PREVU'

  JOIN intervenant                         i ON i.id = m.intervenant_id
  JOIN statut                             si ON si.id = i.statut_id

  LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
  LEFT JOIN validation                     v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL

WHERE
  vhm.histo_destruction IS NULL
  AND (vhm.auto_validation = 1 OR v.id IS NOT NULL)
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=i.statut_id*/