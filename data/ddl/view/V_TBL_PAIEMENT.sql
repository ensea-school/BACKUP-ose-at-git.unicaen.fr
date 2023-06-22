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
  COALESCE(mep.domaine_fonctionnel_id, e.domaine_fonctionnel_id, to_number(p.valeur)) domaine_fonctionnel_id,
  frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
  COUNT(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
  COALESCE(mep.heures,0)                      heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
  pea.pourc_exercice_aa                       pourc_exercice_aa,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id)  total_heures,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id) * pea.pourc_exercice_aa  total_heures_aa,
  SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) cumul_heures,
  CASE WHEN p2.valeur = 'prorata' THEN COALESCE(mep.heures,0) * pea.pourc_exercice_aa ELSE ose_divers.CALC_HEURES_AA(
    COALESCE(mep.heures,0), -- heures
    pea.pourc_exercice_aa, -- pourc_exercice_aa
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id), -- total_heures
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) -- cumul_heures
  ) END heures_aa,
  COALESCE(si.taux_remu_id, to_number(p3.valeur))                  taux_remu_id,
  OSE_PAIEMENT.GET_TAUX_HORAIRE(COALESCE(si.taux_remu_id, to_number(p3.valeur)),COALESCE(pea.horaire_debut, a.date_debut)) taux_horaire,
  1 taux_conges_payes
FROM
            formule_resultat_service        frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN parametre                         p ON p.nom = 'domaine_fonctionnel_ens_ext'
       JOIN parametre                        p2 ON p2.nom = 'regle_repartition_annee_civile'
       JOIN parametre                        p3 ON p3.nom = 'taux-remu'
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
  CASE WHEN p2.valeur = 'prorata' THEN COALESCE(mep.heures,0) * pea.pourc_exercice_aa ELSE ose_divers.CALC_HEURES_AA(
    COALESCE(mep.heures,0), -- heures
    pea.pourc_exercice_aa, -- pourc_exercice_aa
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id), -- total_heures
    SUM(COALESCE(mep.heures,0)) OVER (partition BY frs.id ORDER BY mep.id) -- cumul_heures
  ) END heures_aa,
  COALESCE(si.taux_remu_id, to_number(p3.valeur))                  taux_remu_id,
  OSE_PAIEMENT.GET_TAUX_HORAIRE(COALESCE(si.taux_remu_id, to_number(p3.valeur)),COALESCE(pea.horaire_debut, a.date_debut)) taux_horaire,
  1 taux_conges_payes
FROM
            formule_resultat_service_ref    frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN parametre                        p2 ON p2.nom = 'regle_repartition_annee_civile'
       JOIN parametre                        p3 ON p3.nom = 'taux-remu'
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
  tm.annee_id                                 annee_id,
  NULL                                        service_id,
  NULL                                        service_referentiel_id,
  NULL                                        formule_res_service_id,
  NULL                                        formule_res_service_ref_id,
  tm.mission_id                               mission_id,
  tm.intervenant_id                           intervenant_id,
  tm.structure_id                             structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  mep.domaine_fonctionnel_id                  domaine_fonctionnel_id,
  tm.heures_realisees_validees                heures_a_payer,
  COUNT(*) OVER(PARTITION BY tm.id)           heures_a_payer_pond,
  COALESCE(mep.heures,0)                      heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
  0.4                                         pourc_exercice_aa,
  0.6                                         pourc_exercice_ac,
  COALESCE(mep.heures,0) * 0.4                heures_aa,
  COALESCE(mep.heures,0) * 0.6                heures_ac,
  null taux_remu_id, null taux_horaire,COALESCE(to_number(p.valeur),1) taux_conges_payes
  --COALESCE(si.taux_remu_id, to_number(p3.valeur))                  taux_remu_id,
  --OSE_PAIEMENT.GET_TAUX_HORAIRE(COALESCE(si.taux_remu_id, to_number(p3.valeur)),COALESCE(vhr.horaire_debut, a.date_debut)) taux_horaire
FROM
  tbl_mission tm
  JOIN parametre p ON p.nom = 'taux_conges_payes'
  LEFT JOIN mise_en_paiement                mep ON mep.mission_id = tm.mission_id
                                               AND mep.histo_destruction IS NULL
WHERE
  tm.heures_realisees_validees > 0