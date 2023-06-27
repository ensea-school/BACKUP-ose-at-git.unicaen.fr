CREATE OR REPLACE FORCE VIEW V_TBL_PAIEMENT AS
SELECT
  annee_id,
  service_id,
  service_referentiel_id,
  formule_res_service_id,
  formule_res_service_ref_id,
  NULL mission_id,
  intervenant_id,
  structure_id,
  mise_en_paiement_id,
  periode_paiement_id,
  domaine_fonctionnel_id,
  heures_a_payer,
  heures_a_payer_pond,
  heures_demandees,
  heures_payees,
  ROUND(pourc_exercice_aa,2)            pourc_exercice_aa,
  1 - ROUND(pourc_exercice_aa,2)        pourc_exercice_ac,
  ROUND(heures_aa,2)                    heures_aa,
  heures_demandees - ROUND(heures_aa,2) heures_ac,
  taux_remu_id,
  taux_horaire,
  taux_conges_payes
FROM
(
SELECT
  i.annee_id                                  annee_id,
  frs.service_id                              service_id,
  NULL                                        service_referentiel_id,
  frs.id                                      formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  i.id                                        intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  COALESCE(mep.domaine_fonctionnel_id, e.domaine_fonctionnel_id, ose_parametre.get_domaine_fonc_ens_ext) domaine_fonctionnel_id,
  frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
  COUNT(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
  COALESCE(mep.heures,0)                      heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
  pea.pourc_exercice_aa                       pourc_exercice_aa,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id)  total_heures,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id) * pea.pourc_exercice_aa  total_heures_aa,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) cumul_heures,
  CASE WHEN ose_parametre.get_regle_repart_annee_civ = 'prorata' THEN COALESCE(mep.heures,0) * pea.pourc_exercice_aa ELSE ose_divers.CALC_HEURES_AA(
    COALESCE(mep.heures,0), -- heures
    pea.pourc_exercice_aa, -- pourc_exercice_aa
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id), -- total_heures
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) -- cumul_heures
  ) END heures_aa,
  COALESCE(ep.taux_remu_id, si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  OSE_PAIEMENT.GET_TAUX_HORAIRE(COALESCE(ep.taux_remu_id, si.taux_remu_id, ose_parametre.get_taux_remu),COALESCE(pea.horaire_debut, a.date_debut)) taux_horaire,
  1 taux_conges_payes
FROM
            formule_resultat_service        frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
       JOIN statut                           si ON si.id = i.statut_id
       JOIN annee                             a ON a.id = i.annee_id
       JOIN service                           s ON s.id = frs.service_id
       JOIN (
         SELECT
           frvh.formule_resultat_id,
           vh.service_id,
           MIN(vh.horaire_debut) horaire_debut,
           CASE WHEN SUM(vh.heures) > 0 THEN
             SUM(ose_divers.CALC_POURC_AA(vh.periode_id, vh.horaire_debut, vh.horaire_fin, i.annee_id) * vh.heures) / SUM(vh.heures)
           ELSE
             SUM(ose_divers.CALC_POURC_AA(vh.periode_id, vh.horaire_debut, vh.horaire_fin, i.annee_id))
           END pourc_exercice_aa
         FROM
           volume_horaire             vh
           JOIN service                s ON s.id = vh.service_id
           JOIN intervenant            i ON i.id = s.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
           JOIN formule_resultat_vh frvh ON frvh.volume_horaire_id = vh.id
         GROUP BY
           frvh.formule_resultat_id,
           vh.service_id
         )                                  pea ON pea.formule_resultat_id = fr.id AND pea.service_id = s.id
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             e ON e.id = ep.etape_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                               AND mep.histo_destruction IS NULL

UNION ALL

SELECT
  i.annee_id                                  annee_id,
  NULL                                        service_id,
  frs.service_referentiel_id                  service_referentiel_id,
  NULL                                        formule_res_service_id,
  frs.id                                      formule_res_service_ref_id,
  i.id                                        intervenant_id,
  sr.structure_id                             structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  COALESCE(mep.domaine_fonctionnel_id, fncr.domaine_fonctionnel_id) domaine_fonctionnel_id,
  frs.heures_compl_referentiel                heures_a_payer,
  COUNT(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
  COALESCE(mep.heures,0)                           heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
  pea.pourc_exercice_aa                       pourc_exercice_aa,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id)  total_heures,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id) * pea.pourc_exercice_aa  total_heures_aa,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) cumul_heures,
  CASE WHEN ose_parametre.get_regle_repart_annee_civ = 'prorata' THEN COALESCE(mep.heures,0) * pea.pourc_exercice_aa ELSE ose_divers.CALC_HEURES_AA(
    COALESCE(mep.heures,0), -- heures
    pea.pourc_exercice_aa, -- pourc_exercice_aa
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id), -- total_heures
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) -- cumul_heures
  ) END heures_aa,
  COALESCE(si.taux_remu_id, ose_parametre.get_taux_remu) taux_remu_id,
  OSE_PAIEMENT.GET_TAUX_HORAIRE(COALESCE(si.taux_remu_id, ose_parametre.get_taux_remu),COALESCE(pea.horaire_debut, a.date_debut)) taux_horaire,
  1 taux_conges_payes
FROM
            formule_resultat_service_ref    frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
       JOIN statut                           si ON si.id = i.statut_id
       JOIN annee                             a ON a.id = i.annee_id
       JOIN service_referentiel              sr ON sr.id = frs.service_referentiel_id
       JOIN (
         SELECT
           frvhr.formule_resultat_id,
           vhr.service_referentiel_id,
           MIN(vhr.horaire_debut) horaire_debut,
           CASE WHEN SUM(vhr.heures) > 0 THEN
             SUM(ose_divers.CALC_POURC_AA(NULL, vhr.horaire_debut, vhr.horaire_fin, i.annee_id) * vhr.heures) / SUM(vhr.heures)
           ELSE
             SUM(ose_divers.CALC_POURC_AA(NULL, vhr.horaire_debut, vhr.horaire_fin, i.annee_id))
           END pourc_exercice_aa
         FROM
           volume_horaire_ref vhr
           JOIN service_referentiel        sr ON sr.id = vhr.service_referentiel_id
           JOIN intervenant                 i ON i.id = sr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
           JOIN formule_resultat_vh_ref frvhr ON frvhr.volume_horaire_ref_id = vhr.id
         GROUP BY
           frvhr.formule_resultat_id,
           vhr.service_referentiel_id
         ) pea ON pea.formule_resultat_id = fr.id AND pea.service_referentiel_id = sr.id
       JOIN fonction_referentiel           fncr ON fncr.id = sr.fonction_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                               AND mep.histo_destruction IS NULL
) t

UNION ALL

SELECT
  t.annee_id,
  NULL                                        service_id,
  NULL                                        service_referentiel_id,
  NULL                                        formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  t.mission_id,
  t.intervenant_id,
  t.structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  mep.domaine_fonctionnel_id                  domaine_fonctionnel_id,
  t.heures_a_payer,
  COUNT(*) OVER(PARTITION BY t.mission_id, t.taux_remu_id, t.taux_horaire) heures_a_payer_pond,
  COALESCE(mep.heures,0)                      heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
  ROUND(t.heures_aa / t.heures_a_payer,2) pourc_exercice_aa,
  1 - ROUND(t.heures_aa / t.heures_a_payer,2) pourc_exercice_ac,
  t.heures_aa,
  t.heures_ac,
  t.taux_remu_id,
  t.taux_horaire,
  t.taux_conges_payes
FROM
  (
  SELECT
    t.annee_id,
    t.mission_id,
    t.intervenant_id,
    t.structure_id,
    SUM(t.heures_a_payer) heures_a_payer,
    --CASE WHEN t.aa = 1 THEN SUM(t.heures_a_payer) / SUM(t.heures_a_payer) ELSE 0 END pourc_exercice_aa,
   -- SUM(t.heures_a_payer) / SUM(CASE WHEN t.aa = 0 THEN t.heures_a_payer ELSE 0 END) pourc_exercice_ac,
    SUM(CASE WHEN t.aa = 1 THEN t.heures_a_payer ELSE 0 END) heures_aa,
    SUM(CASE WHEN t.aa = 0 THEN t.heures_a_payer ELSE 0 END) heures_ac,
    t.taux_remu_id,
    t.taux_horaire,
    t.taux_conges_payes
  FROM
    (
    SELECT
      tm.annee_id annee_id,
      tm.mission_id                               mission_id,
      tm.intervenant_id                           intervenant_id,
      tm.structure_id                             structure_id,
      vhm.heures                                  heures_a_payer,
      CASE WHEN to_number(TO_CHAR( vhm.horaire_debut, 'YYYY' )) = tm.annee_id THEN 1 ELSE 0 END aa,
      CASE WHEN
        TO_CHAR( vhm.horaire_debut, 'HH24:MI' ) >= ose_parametre.get_horaire_nocturne -- horaire nocturne
        OR jf.id IS NOT NULL                                                          -- jour ferie
        OR TO_CHAR(vhm.horaire_debut, 'DAY', 'NLS_DATE_LANGUAGE=FRENCH') = 'DIMANCHE' -- dimanche
      THEN
        COALESCE(m.taux_remu_majore_id, m.taux_remu_id)
      ELSE
        m.taux_remu_id
      END                                         taux_remu_id,
      ose_paiement.get_taux_horaire(CASE WHEN
        TO_CHAR( vhm.horaire_debut, 'HH24:MI' ) >= ose_parametre.get_horaire_nocturne -- horaire nocturne
        OR jf.id IS NOT NULL                                                          -- jour ferie
        OR TO_CHAR(vhm.horaire_debut, 'DAY', 'NLS_DATE_LANGUAGE=FRENCH') = 'DIMANCHE' -- dimanche
      THEN
        COALESCE(m.taux_remu_majore_id, m.taux_remu_id)
      ELSE
        m.taux_remu_id
      END, vhm.horaire_debut) taux_horaire,

      ose_parametre.get_taux_conges_payes+1       taux_conges_payes
    FROM
      tbl_mission tm
      JOIN mission m ON m.id = tm.mission_id
      JOIN volume_horaire_mission vhm ON vhm.histo_destruction IS NULL AND vhm.mission_id = tm.mission_id
      JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id AND tvh.code ='REALISE'
      LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
      LEFT JOIN validation v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
      LEFT JOIN jour_ferie jf ON TO_CHAR( jf.date_jour, 'dd/mm/YYYY' ) = TO_CHAR( vhm.horaire_debut, 'dd/mm/YYYY' )
    WHERE
      tm.valide = 1
      /*@INTERVENANT_ID=tm.intervenant_id*/ /*@ANNEE_ID=tm.annee_id*/
      AND (vhm.auto_validation = 1 OR v.id IS NOT NULL)
    ORDER BY
      vhm.horaire_debut
    ) t
  GROUP BY
    t.annee_id,
    t.mission_id,
    t.intervenant_id,
    t.structure_id,
    t.taux_remu_id,
    t.taux_horaire,
    t.taux_conges_payes
  ) t
  LEFT JOIN mise_en_paiement mep ON mep.mission_id = t.mission_id AND mep.histo_destruction IS NULL