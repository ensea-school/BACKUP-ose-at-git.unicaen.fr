CREATE OR REPLACE FORCE VIEW V_TBL_PAIEMENT AS
SELECT
  'e' || '-' || frs.id || '-' || COALESCE(thens.id,th.id) key,
  CASE WHEN si.mode_enseignement_realise = 'semestriel' THEN 1 ELSE 0 END calcul_semestriel,
  vh.id                                       a_payer_id,
  i.annee_id                                  annee_id,
  frs.service_id                              service_id,
  NULL                                        service_referentiel_id,
  NULL                                        mission_id,
  vh.id                                       volume_horaire_id,
  frs.id                                      formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  si.type_intervenant_id                      type_intervenant_id,
  i.id                                        intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  COALESCE(thens.id,th.id)                    type_heures_id,
  COALESCE(e.domaine_fonctionnel_id, str.domaine_fonctionnel_id, ose_parametre.get_domaine_fonc_ens_ext) def_domaine_fonctionnel_id,
  COALESCE(ccep.centre_cout_id,str.centre_cout_id) def_centre_cout_id,
  COALESCE(ep.taux_remu_id, si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  CASE th.code
    WHEN 'fi' THEN frs.heures_compl_fi
    WHEN 'fa' THEN frs.heures_compl_fa
    WHEN 'fc' THEN frs.heures_compl_fc
    WHEN 'fc_majorees' THEN frs.heures_compl_fc_majorees
    WHEN 'enseignement' THEN frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees
  END                                         heures,
  CASE th.code
    WHEN 'fi' THEN frvh.heures_compl_fi
    WHEN 'fa' THEN frvh.heures_compl_fa
    WHEN 'fc' THEN frvh.heures_compl_fc
    WHEN 'fc_majorees' THEN frvh.heures_compl_fc_majorees
    WHEN 'enseignement' THEN frvh.heures_compl_fi + frvh.heures_compl_fa + frvh.heures_compl_fc + frvh.heures_compl_fc_majorees
  END                                         lap_heures,
  prd.id                                      periode_ens_id,
  prd.code                                    periode_ens_code,
  COALESCE(vh.horaire_debut, add_months(a.date_debut, prd.ecart_mois)) horaire_debut,
  COALESCE(vh.horaire_fin, add_months(a.date_debut, prd.ecart_mois + 5)) horaire_fin,

  mep.id                                      mise_en_paiement_id,
  mep.date_mise_en_paiement                   date_mise_en_paiement,
  mep.periode_paiement_id                     periode_paiement_id,
  mep.centre_cout_id                          mep_centre_cout_id,
  mep.heures                                  mep_heures,
  mep.domaine_fonctionnel_id                  mep_domaine_fonctionnel_id
FROM
            formule_resultat_service        frs
       JOIN parametre                         p ON p.nom = 'distinction_fi_fa_fc'
       JOIN parametre                       ccp ON ccp.nom = 'centres_couts_paye'
       JOIN formule_resultat_vh            frvh ON frvh.formule_resultat_id = frs.formule_resultat_id
       JOIN volume_horaire                   vh ON vh.id = frvh.volume_horaire_id
                                               AND vh.service_id = frs.service_id

       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN type_heures                      th ON (p.valeur = '1' AND th.code IN ('fi', 'fa', 'fc', 'fc_majorees')) OR (p.valeur = '0' AND th.code = 'enseignement')
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN periode                         prd ON prd.id = vh.periode_id
       JOIN intervenant                       i ON i.id = fr.intervenant_id
       JOIN annee                             a ON a.id = i.annee_id
       JOIN statut                           si ON si.id = i.statut_id
       JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN type_heures                   thens ON thens.code = 'enseignement' AND p.valeur = '0'
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             e ON e.id = ep.etape_id
  LEFT JOIN centre_cout_ep                 ccep ON ccep.histo_destruction IS NULL
                                               AND ccep.element_pedagogique_id = ep.id
                                               AND ccep.type_heures_id = th.id

  LEFT JOIN mise_en_paiement                mep ON mep.histo_destruction IS NULL
                                               AND mep.formule_res_service_id = frs.id
                                               AND COALESCE(thens.id,mep.type_heures_id) = COALESCE(thens.id,th.id)

  LEFT JOIN structure                       str ON str.id = CASE WHEN ccp.valeur = 'enseignement' THEN COALESCE( ep.structure_id, i.structure_id ) ELSE i.structure_id END
WHERE
   CASE th.code
    WHEN 'fi' THEN frvh.heures_compl_fi
    WHEN 'fa' THEN frvh.heures_compl_fa
    WHEN 'fc' THEN frvh.heures_compl_fc
    WHEN 'fc_majorees' THEN frvh.heures_compl_fc_majorees
    WHEN 'enseignement' THEN frvh.heures_compl_fi + frvh.heures_compl_fa + frvh.heures_compl_fc + frvh.heures_compl_fc_majorees
  END <> 0
  /*@INTERVENANT_ID=fr.intervenant_id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@SERVICE_ID=frs.service_id*/
  /*@FORMULE_RES_SERVICE_ID=frs.id*/

UNION ALL

SELECT
  'e' || '-' || frs.id || '-' || mep.type_heures_id key,
  CASE WHEN si.mode_enseignement_realise = 'semestriel' THEN 1 ELSE 0 END calcul_semestriel,
  mep.id                                      a_payer_id,
  i.annee_id                                  annee_id,
  frs.service_id                              service_id,
  NULL                                        service_referentiel_id,
  NULL                                        mission_id,
  NULL                                        volume_horaire_id,
  frs.id                                      formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  si.type_intervenant_id                      type_intervenant_id,
  i.id                                        intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  mep.type_heures_id                          type_heures_id,
  NULL                                        def_domaine_fonctionnel_id,
  str.centre_cout_id                          def_centre_cout_id,
  COALESCE(ep.taux_remu_id, si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  0                                           heures,
  0                                           lap_heures,
  NULL                                        periode_ens_id,
  NULL                                        periode_ens_code,
  a.date_debut                                horaire_debut,
  a.date_fin                                  horaire_fin,
  mep.id                                      mise_en_paiement_id,
  mep.date_mise_en_paiement                   date_mise_en_paiement,
  mep.periode_paiement_id                     periode_paiement_id,
  mep.centre_cout_id                          mep_centre_cout_id,
  mep.heures                                  mep_heures,
  mep.domaine_fonctionnel_id                  mep_domaine_fonctionnel_id
FROM
            mise_en_paiement                mep
       JOIN type_heures                      th ON th.id = mep.type_heures_id
       JOIN formule_resultat_service        frs ON frs.id = mep.formule_res_service_id
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
       JOIN intervenant                       i ON i.id = fr.intervenant_id
       JOIN annee                             a ON a.id = i.annee_id
       JOIN statut                           si ON si.id = i.statut_id
       JOIN service                           s ON s.id = frs.service_id
       JOIN parametre                         p ON p.nom = 'distinction_fi_fa_fc'
       JOIN parametre                       ccp ON ccp.nom = 'centres_couts_paye'
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN structure                       str ON str.id = CASE WHEN ccp.valeur = 'enseignement' THEN COALESCE( ep.structure_id, i.structure_id ) ELSE i.structure_id END
WHERE
  mep.histo_destruction IS NULL
  AND CASE
    WHEN th.code = 'fi' THEN frs.heures_compl_fi
    WHEN th.code = 'fa' THEN frs.heures_compl_fa
    WHEN th.code = 'fc' THEN frs.heures_compl_fc
    WHEN th.code = 'fc_majorees' THEN frs.heures_compl_fc_majorees
    WHEN p.valeur = '1' AND th.code = 'enseignement' THEN 0
    WHEN p.valeur = '0' AND th.code = 'enseignement' THEN frs.heures_compl_fi + frs.heures_compl_fa + frs.heures_compl_fc + frs.heures_compl_fc_majorees
  END = 0
  /*@INTERVENANT_ID=fr.intervenant_id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@SERVICE_ID=frs.service_id*/
  /*@FORMULE_RES_SERVICE_ID=frs.id*/

UNION ALL

SELECT
  'r' || '-' || frsr.id || '-' || th.id       key,
  1                                           calcul_semestriel,
  sr.id                                       a_payer_id,
  i.annee_id                                  annee_id,
  NULL                                        service_id,
  frsr.service_referentiel_id                 service_referentiel_id,
  NULL                                        mission_id,
  NULL                                        volume_horaire_id,
  NULL                                        formule_res_service_id,
  frsr.id                                     formule_res_service_ref_id,
  si.type_intervenant_id                      type_intervenant_id,
  i.id                                        intervenant_id,
  sr.structure_id                             structure_id,
  th.id                                       type_heures_id,
  fr.domaine_fonctionnel_id                   def_domaine_fonctionnel_id,
  str.centre_cout_id                          def_centre_cout_id,
  COALESCE(si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  NULL                                        heures,
  frsr.heures_compl_referentiel               lap_heures,
  NULL                                        periode_ens_id,
  NULL                                        periode_ens_code,
  a.date_debut                                horaire_debut,
  a.date_fin                                  horaire_fin,

  mep.id                                      mise_en_paiement_id,
  mep.date_mise_en_paiement                   date_mise_en_paiement,
  mep.periode_paiement_id                     periode_paiement_id,
  mep.centre_cout_id                          mep_centre_cout_id,
  mep.heures                                  mep_heures,
  mep.domaine_fonctionnel_id                  mep_domaine_fonctionnel_id
FROM
            formule_resultat_service_ref    frsr
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN type_heures                      th ON th.code = 'referentiel'
       JOIN formule_resultat                 fr ON fr.id = frsr.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN parametre                       ccp ON ccp.nom = 'centres_couts_paye'
       JOIN intervenant                       i ON i.id = fr.intervenant_id
       JOIN annee                             a ON a.id = i.annee_id
       JOIN statut                           si ON si.id = i.statut_id
       JOIN service_referentiel              sr ON sr.id = frsr.service_referentiel_id
       JOIN fonction_referentiel             fr ON fr.id = sr.fonction_id

  LEFT JOIN mise_en_paiement                mep ON mep.histo_destruction IS NULL
                                               AND mep.formule_res_service_ref_id = frsr.id
                                               AND mep.type_heures_id = th.id

  LEFT JOIN structure                       str ON str.id = CASE WHEN ccp.valeur = 'enseignement' THEN sr.structure_id ELSE i.structure_id END
WHERE
   frsr.heures_compl_referentiel <> 0
   /*@INTERVENANT_ID=i.id*/
   /*@ANNEE_ID=i.annee_id*/
   /*@SERVICE_REFERENTIEL_ID=frsr.service_referentiel_id*/
   /*@FORMULE_RES_SERVICE_REF_ID=frsr.id*/

UNION ALL

SELECT
  'm' || '-' || m.id || '-' || th.id          key,
  0                                           calcul_semestriel,
  vhm.id                                      a_payer_id,
  tm.annee_id                                 annee_id,
  NULL                                        service_id,
  NULL                                        service_referentiel_id,
  m.id                                        mission_id,
  vhm.id                                      volume_horaire_id,
  NULL                                        formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  si.type_intervenant_id                      type_intervenant_id,
  tm.intervenant_id                           intervenant_id,
  m.structure_id                              structure_id,
  th.id                                       type_heures_id,
  str.domaine_fonctionnel_id                  def_domaine_fonctionnel_id,
  str.centre_cout_id                          def_centre_cout_id,
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
  NULL                                        heures,
  vhm.heures                                  lap_heures,
  NULL                                        periode_ens_id,
  NULL                                        periode_ens_code,
  COALESCE(vhm.horaire_debut,m.date_debut)    horaire_debut,
  COALESCE(vhm.horaire_fin,m.date_fin)        horaire_fin,

  mep.id                                      mise_en_paiement_id,
  mep.date_mise_en_paiement                   date_mise_en_paiement,
  mep.periode_paiement_id                     periode_paiement_id,
  mep.centre_cout_id                          mep_centre_cout_id,
  mep.heures                                  mep_heures,
  mep.domaine_fonctionnel_id                  mep_domaine_fonctionnel_id
FROM
            tbl_mission                   tm
       JOIN mission                        m ON m.id = tm.mission_id
       JOIN intervenant                    i ON i.id = m.intervenant_id
       JOIN statut                        si ON si.id = i.statut_id
       JOIN volume_horaire_mission       vhm ON vhm.histo_destruction IS NULL AND vhm.mission_id = tm.mission_id
       JOIN type_volume_horaire          tvh ON tvh.id = vhm.type_volume_horaire_id AND tvh.code ='REALISE'
       JOIN type_heures                   th ON th.code = 'mission'
       JOIN parametre                    ccp ON ccp.nom = 'centres_couts_paye'
  LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
  LEFT JOIN validation                     v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
  LEFT JOIN jour_ferie jf                    ON TO_CHAR( jf.date_jour, 'dd/mm/YYYY' ) = TO_CHAR( vhm.horaire_debut, 'dd/mm/YYYY' )

  LEFT JOIN mise_en_paiement                mep ON mep.histo_destruction IS NULL
                                               AND mep.mission_id = m.id
                                               AND mep.type_heures_id = th.id

  LEFT JOIN structure                       str ON str.id = CASE WHEN ccp.valeur = 'enseignement' THEN m.structure_id ELSE i.structure_id END
WHERE
  tm.valide = 1
  /*@ANNEE_ID=tm.annee_id*/
  /*@INTERVENANT_ID=tm.intervenant_id*/
  /*@MISSION_ID=tm.mission_id*/
  AND (vhm.auto_validation = 1 OR v.id IS NOT NULL)

ORDER BY
  intervenant_id,
  horaire_debut,
  a_payer_id,
  mise_en_paiement_id