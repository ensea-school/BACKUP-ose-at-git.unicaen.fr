SELECT
  'e' || '-' || frs.id || '-' || th.id        key,
  i.annee_id                                  annee_id,
  frs.service_id                              service_id,
  NULL                                        service_referentiel_id,
  NULL                                        mission_id,
  frs.id                                      formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  i.id                                        intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  th.id                                       type_heures_id,
  COALESCE(e.domaine_fonctionnel_id, ose_parametre.get_domaine_fonc_ens_ext) domaine_fonctionnel_id,
  ccep.centre_cout_id                         centre_cout_id,
  COALESCE(ep.taux_remu_id, si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  CASE th.code
    WHEN 'fi' THEN frs.heures_compl_fi
    WHEN 'fa' THEN frs.heures_compl_fa
    WHEN 'fc' THEN frs.heures_compl_fc
    WHEN 'fc_majorees' THEN frs.heures_compl_fc_majorees
  END                                         service_heures,
  CASE th.code
    WHEN 'fi' THEN frvh.heures_compl_fi
    WHEN 'fa' THEN frvh.heures_compl_fa
    WHEN 'fc' THEN frvh.heures_compl_fc
    WHEN 'fc_majorees' THEN frvh.heures_compl_fc_majorees
  END                                         heures,
  vh.horaire_debut                            horaire_debut,
  vh.horaire_fin                              horaire_fin,
  vh.periode_id                               periode_id
FROM
            formule_resultat_service        frs
       JOIN formule_resultat_vh            frvh ON frvh.formule_resultat_id = frs.formule_resultat_id
       JOIN volume_horaire                   vh ON vh.id = frvh.volume_horaire_id
                                               AND vh.service_id = frs.service_id

       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN type_heures                      th ON th.code IN ('fi', 'fa', 'fc', 'fc_majorees')
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id
       JOIN statut                           si ON si.id = i.statut_id
       JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             e ON e.id = ep.etape_id
  LEFT JOIN centre_cout_ep                 ccep ON ccep.histo_destruction IS NULL
                                               AND ccep.element_pedagogique_id = ep.id
                                               AND ccep.type_heures_id = th.id
WHERE
   CASE th.code
    WHEN 'fi' THEN frvh.heures_compl_fi
    WHEN 'fa' THEN frvh.heures_compl_fa
    WHEN 'fc' THEN frvh.heures_compl_fc
    WHEN 'fc_majorees' THEN frvh.heures_compl_fc_majorees
  END > 0
  /*@INTERVENANT_ID=fr.intervenant_id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@SERVICE_ID=frs.service_id*/
  /*@FORMULE_RES_SERVICE_ID=frs.id*/

UNION ALL

SELECT
  'r' || '-' || frsr.id || '-' || th.id       key,
  i.annee_id                                  annee_id,
  NULL                                        service_id,
  frsr.service_referentiel_id                 service_referentiel_id,
  NULL                                        mission_id,
  NULL                                        formule_res_service_id,
  frsr.id                                     formule_res_service_ref_id,
  i.id                                        intervenant_id,
  sr.structure_id                             structure_id,
  th.id                                       type_heures_id,
  fr.domaine_fonctionnel_id                   domaine_fonctionnel_id,
  NULL                                        centre_cout_id,
  COALESCE(si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  NULL                                        service_heures,
  frsr.heures_compl_referentiel               heures,
  NULL                                        horaire_debut,
  NULL                                        horaire_fin,
  NULL                                        periode_id
FROM
            formule_resultat_service_ref    frsr

       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN type_heures                      th ON th.code = 'referentiel'
       JOIN formule_resultat                 fr ON fr.id = frsr.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id
       JOIN statut                           si ON si.id = i.statut_id
       JOIN service_referentiel              sr ON sr.id = frsr.service_referentiel_id
       JOIN fonction_referentiel             fr ON fr.id = sr.fonction_id
WHERE
   frsr.heures_compl_referentiel > 0
   /*@INTERVENANT_ID=i.id*/
   /*@ANNEE_ID=i.annee_id*/
   /*@SERVICE_REFERENTIEL_ID=frsr.service_referentiel_id*/
   /*@FORMULE_RES_SERVICE_REF_ID=frsr.id*/

UNION ALL

SELECT
  'm' || '-' || m.id || '-' || th.id          key,
  tm.annee_id                                 annee_id,
  NULL                                        service_id,
  NULL                                        service_referentiel_id,
  m.id                                        mission_id,
  NULL                                        formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  tm.intervenant_id                           intervenant_id,
  m.structure_id                              structure_id,
  th.id                                       type_heures_id,
  NULL                                        domaine_fonctionnel_id,
  NULL                                        centre_cout_id,
  CASE WHEN
    TO_CHAR( vhm.horaire_debut, 'HH24:MI' ) >= ose_parametre.get_horaire_nocturne -- horaire nocturne
    OR jf.id IS NOT NULL                                                          -- jour ferie
    OR TO_CHAR(vhm.horaire_debut, 'DAY', 'NLS_DATE_LANGUAGE=FRENCH') = 'DIMANCHE' -- dimanche
  THEN
    COALESCE(m.taux_remu_majore_id, m.taux_remu_id)
  ELSE
    m.taux_remu_id
  END                                         taux_remu_id,
  ose_parametre.get_taux_conges_payes+1       taux_conges_payes,
  NULL                                        service_heures,
  vhm.heures                                  heures,
  vhm.horaire_debut                           horaire_debut,
  vhm.horaire_fin                             horaire_fin,
  NULL                                        periode_id
FROM
            tbl_mission                   tm
       JOIN mission                        m ON m.id = tm.mission_id
       JOIN volume_horaire_mission       vhm ON vhm.histo_destruction IS NULL AND vhm.mission_id = tm.mission_id
       JOIN type_volume_horaire          tvh ON tvh.id = vhm.type_volume_horaire_id AND tvh.code ='REALISE'
       JOIN type_heures                   th ON th.code = 'mission'
  LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
  LEFT JOIN validation                     v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
  LEFT JOIN jour_ferie jf                    ON TO_CHAR( jf.date_jour, 'dd/mm/YYYY' ) = TO_CHAR( vhm.horaire_debut, 'dd/mm/YYYY' )
WHERE
  tm.valide = 1
  /*@ANNEE_ID=tm.annee_id*/
  /*@INTERVENANT_ID=tm.intervenant_id*/
  /*@MISSION_ID=tm.mission_id*/
  AND (vhm.auto_validation = 1 OR v.id IS NOT NULL)