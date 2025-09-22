CREATE OR REPLACE FORCE VIEW V_EXPORT_SERVICE AS
WITH t AS ( SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  NULL                              service_referentiel_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etape_id                        etape_id,
  s.etablissement_id                etablissement_id,
  NULL                              structure_aff_id,
  NULL                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  NULL                              fonction_referentiel_id,
  NULL                              motif_non_paiement_id,
  t.id                              tag_id,
  s.description                     service_description,

  vh.heures                         heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
  NULL                              motif_non_paiement,
  t.libelle_court                   tag,
  frvh.heures_service_fi            service_fi,
  frvh.heures_service_fa            service_fa,
  frvh.heures_service_fc            service_fc,
  0                                 service_referentiel,
  frvh.heures_compl_fi              heures_compl_fi,
  frvh.heures_compl_fa              heures_compl_fa,
  frvh.heures_compl_fc              heures_compl_fc,
  frvh.heures_primes                heures_primes,
  0                                 heures_compl_referentiel,
  frvh.total                        total,
  fr.solde                          solde,
  NULL                              service_ref_formation,
  NULL                              commentaires
FROM
  formule_resultat_volume_horaire    frvh
  JOIN formule_resultat_intervenant    fr ON fr.id = frvh.formule_resultat_intervenant_id
  JOIN volume_horaire                  vh ON vh.id = frvh.volume_horaire_id AND vh.motif_non_paiement_id IS NULL AND vh.histo_destruction IS NULL
  JOIN service                         s  ON s.id = vh.service_id AND s.intervenant_id = fr.intervenant_id AND s.histo_destruction IS NULL
  LEFT JOIN tag t ON t.id = vh.tag_id

UNION ALL

SELECT
  'vh_' || vh.id                    id,
  s.id                              service_id,
  NULL                              service_referentiel_id,
  s.intervenant_id                  intervenant_id,
  vh.type_volume_horaire_id         type_volume_horaire_id,
  evh.id                            etat_volume_horaire_id,
  s.element_pedagogique_id          element_pedagogique_id,
  s.etape_id                        etape_id,
  s.etablissement_id                etablissement_id,
  NULL                              structure_aff_id,
  NULL                              structure_ens_id,
  vh.periode_id                     periode_id,
  vh.type_intervention_id           type_intervention_id,
  NULL                              fonction_referentiel_id,
  mnp.id                            motif_non_paiement_id,
  t.id                tag_id,
  s.description                     service_description,

  vh.heures                         heures,
  0                                 heures_ref,
  1                                 heures_non_payees,
  mnp.libelle_court                 motif_non_paiement,
  t.libelle_court                   tag,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  0                                 service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_primes,
  0                                 heures_compl_referentiel,
  0                                 total,
  COALESCE(fr.solde,0)              solde,
  NULL                              service_ref_formation,
  NULL                              commentaires
FROM
            volume_horaire               vh
       JOIN service                       s ON s.id = vh.service_id
       JOIN tbl_validation_enseignement tve ON tve.id = vh.id
       JOIN etat_volume_horaire         evh ON evh.ordre <= tve.etat_volume_horaire_ordre
       JOIN motif_non_paiement          mnp ON mnp.id = vh.motif_non_paiement_id
  LEFT JOIN tag                           t ON t.id = vh.tag_id
  LEFT JOIN formule_resultat_intervenant fr ON fr.intervenant_id = s.intervenant_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id AND fr.etat_volume_horaire_id = evh.id
WHERE
  vh.histo_destruction IS NULL
  AND s.histo_destruction IS NULL

UNION ALL


SELECT
  'vh_ref_' || vhr.id               id,
  NULL                              service_id,
  sr.id                             service_referentiel_id,
  sr.intervenant_id                 intervenant_id,
  fr.type_volume_horaire_id         type_volume_horaire_id,
  fr.etat_volume_horaire_id         etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  NULL                              etape_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  sr.structure_id                   structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  sr.fonction_id                    fonction_referentiel_id,
  mnp.id                            motif_non_paiement_id,
  t.id                              tag_id,

  NULL                              service_description,

  0                                 heures,
  vhr.heures                        heures_ref,
  CASE WHEN mnp.id IS NOT NULL
  THEN 1 ELSE 0 END                 heures_non_payees,
  mnp.libelle_court                 motif_non_paiement,
  t.libelle_court                   tag,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  frvr.heures_service_referentiel   service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_primes,
  frvr.heures_compl_referentiel     heures_compl_referentiel,
  frvr.total                        total,
  fr.solde                          solde,
  sr.formation                      service_ref_formation,
  sr.commentaires                   commentaires
FROM
  formule_resultat_volume_horaire frvr
  JOIN formule_resultat_intervenant fr ON fr.id = frvr.formule_resultat_intervenant_id
  JOIN volume_horaire_ref          vhr ON vhr.id =  frvr.volume_horaire_ref_id AND vhr.histo_destruction IS NULL
  JOIN service_referentiel          sr ON sr.id = vhr.service_referentiel_id AND sr.intervenant_id = fr.intervenant_id AND sr.histo_destruction IS NULL
  LEFT JOIN motif_non_paiement     mnp ON mnp.id = sr.motif_non_paiement_id
  LEFT JOIN tag                      t ON t.id = sr.tag_id

UNION ALL

SELECT
  'vh_0_' || i.id                   id,
  NULL                              service_id,
  NULL                              service_referentiel_id,
  i.id                              intervenant_id,
  tvh.id                            type_volume_horaire_id,
  evh.id                            etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  NULL                              etape_id,
  OSE_PARAMETRE.GET_ETABLISSEMENT   etablissement_id,
  NULL                              structure_aff_id,
  NULL                              structure_ens_id,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  NULL                              fonction_referentiel_id,
  NULL                              motif_non_paiement_id,
  NULL                              tag_id,
  NULL                              service_description,

  0                                 heures,
  0                                 heures_ref,
  0                                 heures_non_payees,
  NULL                              motif_non_paiement,
  NULL                              tag,
  0                                 service_fi,
  0                                 service_fa,
  0                                 service_fc,
  0                                 service_referentiel,
  0                                 heures_compl_fi,
  0                                 heures_compl_fa,
  0                                 heures_compl_fc,
  0                                 heures_primes,
  NULL                              heures_compl_referentiel,
  0                                 total,
  si.service_statutaire + SUM(msd.heures * mms.multiplicateur) solde,
  NULL                              service_ref_formation,
  NULL                              commentaires
FROM
  intervenant i
  JOIN statut               si ON si.id = i.statut_id
  JOIN etat_volume_horaire evh ON evh.code IN ('saisi','valide')
  JOIN type_volume_horaire tvh ON tvh.code IN ('PREVU','REALISE')
  JOIN modification_service_du msd ON msd.intervenant_id = i.id AND msd.histo_destruction IS NULL
  JOIN motif_modification_service mms ON mms.id = msd.motif_id
  LEFT JOIN formule_resultat_intervenant fr ON fr.intervenant_id = i.id AND fr.type_volume_horaire_id = tvh.id AND fr.etat_volume_horaire_id = evh.id
WHERE
  i.histo_destruction IS NULL
  AND COALESCE(fr.total,0) = 0
  AND msd.heures <> 0
GROUP BY
  i.id, si.service_statutaire, evh.id, tvh.id

), ponds AS (
SELECT
  ep.id                                          element_pedagogique_id,
  MAX(COALESCE( m.ponderation_service_du, 1))    ponderation_service_du,
  MAX(COALESCE( m.ponderation_service_compl, 1)) ponderation_service_compl
FROM
            element_pedagogique ep
  LEFT JOIN element_modulateur  em ON em.element_id = ep.id
                                  AND em.histo_destruction IS NULL
  LEFT JOIN modulateur          m ON m.id = em.modulateur_id
WHERE
  ep.histo_destruction IS NULL
GROUP BY
  ep.id
)
SELECT
  t.id                              id,
  t.service_id                      service_id,
  t.service_referentiel_id          service_referentiel_id,
  i.id                              intervenant_id,
  si.id                             statut_id,
  ti.id                             type_intervenant_id,
  i.annee_id                        annee_id,
  t.type_volume_horaire_id          type_volume_horaire_id,
  t.etat_volume_horaire_id          etat_volume_horaire_id,
  etab.id                           etablissement_id,
  saff.id                           structure_aff_id,
  saff.ids                          structure_aff_ids,
  sens.id                           structure_ens_id,
  sens.ids                          structure_ens_ids,
  gtf.id                            groupe_type_formation_id,
  tf.id                             type_formation_id,
  CASE
    WHEN 1 <> gtf.pertinence_niveau OR etp.niveau IS NULL OR etp.niveau < 1 OR gtf.id < 1 THEN NULL
    ELSE gtf.id * 256 + niveau END  niveau_formation_id,
  etp.id                            etape_id,
  ep.id                             element_pedagogique_id,
  t.periode_id                      periode_id,
  t.type_intervention_id            type_intervention_id,
  t.fonction_referentiel_id         fonction_referentiel_id,
  di.id                             intervenant_discipline_id,
  de.id                             element_discipline_id,
  t.motif_non_paiement_id           motif_non_paiement_id,
  t.tag_id                          tag_id,
  tvh.libelle || ' ' || evh.libelle type_etat,
  his.histo_modification            service_date_modification,

  i.code                            intervenant_code,
  i.code_rh                          intervenant_code_rh,
  i.nom_usuel || ' ' || i.prenom    intervenant_nom,
  i.date_naissance                  intervenant_date_naissance,
  si.libelle                        intervenant_statut_libelle,
  ti.code                           intervenant_type_code,
  ti.libelle                        intervenant_type_libelle,
  g.source_code                     intervenant_grade_code,
  g.libelle_court                   intervenant_grade_libelle,
  di.source_code                    intervenant_discipline_code,
  di.libelle_court                  intervenant_discipline_libelle,
  saff.libelle_court                service_structure_aff_libelle,

  sens.libelle_court                service_structure_ens_libelle,
  etab.libelle                      etablissement_libelle,
  gtf.libelle_court                 groupe_type_formation_libelle,
  tf.libelle_court                  type_formation_libelle,
  etp.niveau                        etape_niveau,
  etp.source_code                   etape_code,
  etp.libelle                       etape_libelle,
  ep.source_code                    element_code,
  COALESCE(ep.libelle,to_char(t.service_description)) element_libelle,
  de.source_code                    element_discipline_code,
  de.libelle_court                  element_discipline_libelle,
  fr.libelle_long                   fonction_referentiel_libelle,
  ep.taux_fi                        element_taux_fi,
  ep.taux_fc                        element_taux_fc,
  ep.taux_fa                        element_taux_fa,
  t.service_ref_formation           service_ref_formation,
  t.commentaires                    commentaires,
  p.libelle_court                   periode_libelle,
  CASE WHEN ponds.ponderation_service_compl = 1 THEN NULL ELSE ponds.ponderation_service_compl END element_ponderation_compl,
  src.libelle                       element_source_libelle,

  t.heures                          heures,
  t.heures_ref                      heures_ref,
  t.heures_non_payees               heures_non_payees,
  t.motif_non_paiement              motif_non_paiement,
  t.tag                             tag,
  si.service_statutaire             service_statutaire,
  tsd.service_modifie               service_du_modifie,
  t.service_fi                      service_fi,
  t.service_fa                      service_fa,
  t.service_fc                      service_fc,
  t.service_referentiel             service_referentiel,
  t.heures_compl_fi                 heures_compl_fi,
  t.heures_compl_fa                 heures_compl_fa,
  t.heures_compl_fc                 heures_compl_fc,
  t.heures_primes                   heures_primes,
  t.heures_compl_referentiel        heures_compl_referentiel,
  t.total                           total,
  t.solde                           solde,
  v.histo_modification              date_cloture_realise

FROM
  t
  JOIN intervenant                        i ON i.id     = t.intervenant_id AND i.histo_destruction IS NULL
  JOIN statut                            si ON si.id    = i.statut_id
  JOIN type_intervenant                  ti ON ti.id    = si.type_intervenant_id
  JOIN etablissement                   etab ON etab.id  = t.etablissement_id
  JOIN type_volume_horaire              tvh ON tvh.id   = t.type_volume_horaire_id
  JOIN etat_volume_horaire              evh ON evh.id   = t.etat_volume_horaire_id
  LEFT JOIN histo_intervenant_service   his ON his.intervenant_id = i.id AND his.type_volume_horaire_id = tvh.id AND his.referentiel = 0
  LEFT JOIN grade                         g ON g.id     = i.grade_id
  LEFT JOIN discipline                   di ON di.id    = i.discipline_id
  LEFT JOIN STRUCTURE                  saff ON saff.id  = i.structure_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique          ep ON ep.id    = t.element_pedagogique_id
  LEFT JOIN discipline                   de ON de.id    = ep.discipline_id
  LEFT JOIN STRUCTURE                  sens ON sens.id  = NVL(t.structure_ens_id, ep.structure_id)
  LEFT JOIN periode                       p ON p.id     = t.periode_id
  LEFT JOIN SOURCE                      src ON src.id   = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
  LEFT JOIN etape                       etp ON etp.id   = CASE WHEN t.etape_id IS NOT NULL THEN t.etape_id ELSE ep.etape_id END
  LEFT JOIN type_formation               tf ON tf.id    = etp.type_formation_id AND tf.histo_destruction IS NULL
  LEFT JOIN groupe_type_formation       gtf ON gtf.id   = tf.groupe_id AND gtf.histo_destruction IS NULL
  LEFT JOIN tbl_service_du              tsd ON tsd.intervenant_id = i.id
  LEFT JOIN ponds                     ponds ON ponds.element_pedagogique_id = ep.id
  LEFT JOIN fonction_referentiel         fr ON fr.id    = t.fonction_referentiel_id
  LEFT JOIN type_validation              tv ON tvh.code = 'REALISE' AND tv.code = 'CLOTURE_REALISE'
  LEFT JOIN validation                    v ON v.intervenant_id = i.id AND v.type_validation_id = tv.id AND v.histo_destruction IS NULL