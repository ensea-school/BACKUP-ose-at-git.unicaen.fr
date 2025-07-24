CREATE OR REPLACE FORCE VIEW V_TBL_PAIEMENT AS
SELECT
  -- heures d'enseignement par volume horaire payées ou non
  'e' || '-' || s.id || '-' || COALESCE(thens.id,th.id) KEY,
  CASE WHEN si.mode_enseignement_realise = 'semestriel' THEN 1 ELSE 0 END calcul_semestriel,
  vh.id                                       a_payer_id,
  i.annee_id                                  annee_id,
  frvh.service_id                             service_id,
  NULL                                        service_referentiel_id,
  NULL                                        mission_id,
  vh.id                                       volume_horaire_id,
  si.type_intervenant_id                      type_intervenant_id,
  i.id                                        intervenant_id,
  str.id                                      structure_id,
  COALESCE(thens.id,th.id)                    type_heures_id,
  COALESCE(e.domaine_fonctionnel_id, str.domaine_fonctionnel_id, ose_parametre.get_domaine_fonc_ens_ext) def_domaine_fonctionnel_id,
  COALESCE(ccep.centre_cout_id,str.centre_cout_id) def_centre_cout_id,
  COALESCE(ep.taux_remu_id, si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  CASE th.code
    WHEN 'fi' THEN frvh.heures_compl_fi
    WHEN 'fa' THEN frvh.heures_compl_fa
    WHEN 'fc' THEN frvh.heures_compl_fc
    WHEN 'primes' THEN frvh.heures_primes
    WHEN 'enseignement' THEN frvh.heures_compl_fi + frvh.heures_compl_fa + frvh.heures_compl_fc + frvh.heures_primes
  END                                         heures,
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
            formule_resultat_volume_horaire frvh
       JOIN parametre                         p ON p.nom = 'distinction_fi_fa_fc'
       JOIN parametre                       ccp ON ccp.nom = 'centres_couts_paye'
       JOIN volume_horaire                   vh ON vh.id = frvh.volume_horaire_id

       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN type_heures                      th ON (p.valeur = '1' AND th.code IN ('fi', 'fa', 'fc', 'primes')) OR (p.valeur = '0' AND th.code = 'enseignement')
       JOIN formule_resultat_intervenant     fr ON fr.id = frvh.formule_resultat_intervenant_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN periode                         prd ON prd.id = vh.periode_id
       JOIN intervenant                       i ON i.id = fr.intervenant_id
       JOIN annee                             a ON a.id = i.annee_id
       JOIN statut                           si ON si.id = i.statut_id
       JOIN type_intervenant                 ti ON ti.id = si.type_intervenant_id
       JOIN service                           s ON s.id = frvh.service_id
  LEFT JOIN type_heures                   thens ON thens.code = 'enseignement' AND p.valeur = '0'
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             e ON e.id = ep.etape_id
  LEFT JOIN centre_cout_ep                 ccep ON ccep.histo_destruction IS NULL
                                               AND ccep.element_pedagogique_id = ep.id
                                               AND ccep.type_heures_id = th.type_heures_element_id

  LEFT JOIN mise_en_paiement                mep ON mep.histo_destruction IS NULL
                                               AND mep.service_id = frvh.service_id
                                               AND COALESCE(thens.id,mep.type_heures_id) = COALESCE(thens.id,th.id)

  LEFT JOIN STRUCTURE                       str ON str.id = CASE WHEN ccp.valeur = 'enseignement' OR ti.code = 'E' THEN COALESCE( ep.structure_id, i.structure_id ) ELSE i.structure_id END
WHERE
  1=1
  /*@INTERVENANT_ID=fr.intervenant_id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@SERVICE_ID=frvh.service_id*/

UNION ALL

SELECT
  -- paiements sur d'anciens volumes horaires d'enseignement
  'e' || '-' || mep.service_id || '-' || mep.type_heures_id KEY,
  CASE WHEN si.mode_enseignement_realise = 'semestriel' THEN 1 ELSE 0 END calcul_semestriel,
  mep.id                                      a_payer_id,
  i.annee_id                                  annee_id,
  s.id                                        service_id,
  NULL                                        service_referentiel_id,
  NULL                                        mission_id,
  NULL                                        volume_horaire_id,
  si.type_intervenant_id                      type_intervenant_id,
  i.id                                        intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  mep.type_heures_id                          type_heures_id,
  NULL                                        def_domaine_fonctionnel_id,
  str.centre_cout_id                          def_centre_cout_id,
  COALESCE(ep.taux_remu_id, si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  0                                           heures,
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
            mise_en_paiement                 mep
       JOIN service                            s ON s.id = mep.service_id
       JOIN intervenant                        i ON i.id = s.intervenant_id
       JOIN annee                              a ON a.id = i.annee_id
       JOIN statut                            si ON si.id = i.statut_id
       JOIN type_intervenant                  ti ON ti.id = si.type_intervenant_id
       JOIN parametre                        ccp ON ccp.nom = 'centres_couts_paye'
       JOIN type_volume_horaire              tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire              evh ON tvh.code = 'valide'
  LEFT JOIN element_pedagogique               ep ON ep.id = s.element_pedagogique_id AND ccp.valeur = 'enseignement' OR ti.code = 'E'
  LEFT JOIN STRUCTURE                        str ON str.id = COALESCE( ep.structure_id, i.structure_id )
  LEFT JOIN formule_resultat_intervenant     fri ON fri.type_volume_horaire_id = tvh.id
                                                AND fri.etat_volume_horaire_id = evh.id
                                                AND fri.intervenant_id = i.id
  LEFT JOIN formule_resultat_volume_horaire frvh ON frvh.formule_resultat_intervenant_id = fri.id AND frvh.service_id = s.id

WHERE
  mep.histo_destruction IS NULL
  AND frvh.id IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@SERVICE_ID=s.id*/

UNION ALL

SELECT
  -- paiements sur des heures de référentiel
  'r' || '-' || sr.id || '-' || th.id         KEY,
  1                                           calcul_semestriel,
  frvh.volume_horaire_ref_id                  a_payer_id,
  i.annee_id                                  annee_id,
  NULL                                        service_id,
  frvh.service_referentiel_id                 service_referentiel_id,
  NULL                                        mission_id,
  frvh.volume_horaire_ref_id                  volume_horaire_id,
  si.type_intervenant_id                      type_intervenant_id,
  i.id                                        intervenant_id,
  sr.structure_id                             structure_id,
  th.id                                       type_heures_id,
  fr.domaine_fonctionnel_id                   def_domaine_fonctionnel_id,
  str.centre_cout_id                          def_centre_cout_id,
  COALESCE(si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  frvh.heures_compl_referentiel               heures,
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
  formule_resultat_volume_horaire frvh
  JOIN type_volume_horaire              tvh ON tvh.code = 'REALISE'
  JOIN etat_volume_horaire              evh ON evh.code = 'valide'
  JOIN type_heures                       th ON th.code = 'referentiel'
  JOIN formule_resultat_intervenant     fri ON fri.id = frvh.formule_resultat_intervenant_id
  AND fri.type_volume_horaire_id = tvh.id
  AND fri.etat_volume_horaire_id = evh.id

  JOIN parametre                        ccp ON ccp.nom = 'centres_couts_paye'
  JOIN intervenant                        i ON i.id = fri.intervenant_id
  JOIN annee                              a ON a.id = i.annee_id
  JOIN statut                            si ON si.id = i.statut_id
  JOIN type_intervenant                  ti ON ti.id = si.type_intervenant_id
  JOIN service_referentiel               sr ON sr.id = frvh.service_referentiel_id
  JOIN fonction_referentiel              fr ON fr.id = sr.fonction_id

  LEFT JOIN mise_en_paiement                 mep ON mep.histo_destruction IS NULL
  AND mep.service_referentiel_id = frvh.service_referentiel_id
  AND mep.type_heures_id = th.id

  LEFT JOIN STRUCTURE                        str ON str.id = CASE WHEN ccp.valeur = 'enseignement' OR ti.code = 'E' THEN sr.structure_id ELSE i.structure_id END
WHERE
   1=1
   /*@INTERVENANT_ID=i.id*/
   /*@ANNEE_ID=i.annee_id*/
   /*@SERVICE_REFERENTIEL_ID=frsr.service_referentiel_id*/

UNION ALL

SELECT
  -- paiements sur d'anciens volumes horaires référentiels
  'e' || '-' || sr.id || '-' || mep.type_heures_id KEY,
  CASE WHEN si.mode_enseignement_realise = 'semestriel' THEN 1 ELSE 0 END calcul_semestriel,
  mep.id                                      a_payer_id,
  i.annee_id                                  annee_id,
  NULL                                        service_id,
  sr.id                                       service_referentiel_id,
  NULL                                        mission_id,
  NULL                                        volume_horaire_id,
  si.type_intervenant_id                      type_intervenant_id,
  i.id                                        intervenant_id,
  str.id                                      structure_id,
  mep.type_heures_id                          type_heures_id,
  NULL                                        def_domaine_fonctionnel_id,
  str.centre_cout_id                          def_centre_cout_id,
  COALESCE(si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  1                                           taux_conges_payes,
  0                                           heures,
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
            mise_en_paiement                 mep
       JOIN service_referentiel               sr ON sr.id = mep.service_referentiel_id
       JOIN intervenant                        i ON i.id = sr.intervenant_id
       JOIN annee                              a ON a.id = i.annee_id
       JOIN statut                            si ON si.id = i.statut_id
       JOIN type_intervenant                  ti ON ti.id = si.type_intervenant_id
       JOIN parametre                        ccp ON ccp.nom = 'centres_couts_paye'
       JOIN type_volume_horaire              tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire              evh ON tvh.code = 'valide'
  LEFT JOIN STRUCTURE                        str ON str.id = CASE WHEN ccp.valeur = 'enseignement' OR ti.code = 'E' THEN COALESCE( sr.structure_id, i.structure_id ) ELSE i.structure_id END
  LEFT JOIN formule_resultat_intervenant     fri ON fri.type_volume_horaire_id = tvh.id
                                                AND fri.etat_volume_horaire_id = evh.id
                                                AND fri.intervenant_id = i.id
  LEFT JOIN formule_resultat_volume_horaire frvh ON frvh.formule_resultat_intervenant_id = fri.id AND frvh.service_referentiel_id = sr.id

WHERE
  mep.histo_destruction IS NULL
  AND frvh.id IS NULL
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@SERVICE_ID=s.id*/

UNION ALL

SELECT
  -- paiements sur des heures de mission
  'm' || '-' || m.id || '-' || th.id          KEY,
  0                                           calcul_semestriel,
  vhm.id                                      a_payer_id,
  tm.annee_id                                 annee_id,
  NULL                                        service_id,
  NULL                                        service_referentiel_id,
  m.id                                        mission_id,
  vhm.id                                      volume_horaire_id,
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
  vhm.heures                                  heures,
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
       JOIN type_intervenant              ti ON ti.id = si.type_intervenant_id
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

  LEFT JOIN STRUCTURE                       str ON str.id = CASE WHEN ccp.valeur = 'enseignement' OR ti.code = 'E' THEN COALESCE( m.structure_id, i.structure_id ) ELSE i.structure_id END
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