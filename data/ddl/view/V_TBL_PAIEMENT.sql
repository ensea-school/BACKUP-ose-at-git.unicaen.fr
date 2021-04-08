CREATE OR REPLACE FORCE VIEW V_TBL_PAIEMENT AS
SELECT
  i.annee_id                                  annee_id,
  frs.service_id                              service_id,
  null                                        service_referentiel_id,
  frs.id                                      formule_res_service_id,
  null                                        formule_res_service_ref_id,
  i.id                                        intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  COALESCE(mep.domaine_fonctionnel_id, e.domaine_fonctionnel_id, to_number(p.valeur)) domaine_fonctionnel_id,
  frs.heures_compl_fi + frs.heures_compl_fc + frs.heures_compl_fa + frs.heures_compl_fc_majorees heures_a_payer,
  count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
  NVL(mep.heures,0)                           heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
  4 / 10                                      pourc_exercice_aa,
  6 / 10                                      pourc_exercice_ac
FROM
            formule_resultat_service        frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN parametre                         p ON p.nom = 'domaine_fonctionnel_ens_ext'
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
       JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique              ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                             e ON e.id = ep.etape_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
                                               AND mep.histo_destruction IS NULL

UNION ALL

SELECT
  i.annee_id                                  annee_id,
  null                                        service_id,
  frs.service_referentiel_id                  service_referentiel_id,
  null                                        formule_res_service_id,
  frs.id                                      formule_res_service_ref_id,
  i.id                                        intervenant_id,
  sr.structure_id                             structure_id,
  mep.id                                      mise_en_paiement_id,
  mep.periode_paiement_id                     periode_paiement_id,
  COALESCE(mep.domaine_fonctionnel_id, fncr.domaine_fonctionnel_id) domaine_fonctionnel_id,
  frs.heures_compl_referentiel                heures_a_payer,
  count(*) OVER(PARTITION BY frs.id)          heures_a_payer_pond,
  NVL(mep.heures,0)                           heures_demandees,
  CASE WHEN mep.periode_paiement_id IS NULL THEN 0 ELSE mep.heures END heures_payees,
  4 / 10                                      pourc_exercice_aa,
  6 / 10                                      pourc_exercice_ac
FROM
            formule_resultat_service_ref    frs
       JOIN type_volume_horaire             tvh ON tvh.code = 'REALISE'
       JOIN etat_volume_horaire             evh ON evh.code = 'valide'
       JOIN formule_resultat                 fr ON fr.id = frs.formule_resultat_id
                                               AND fr.type_volume_horaire_id = tvh.id
                                               AND fr.etat_volume_horaire_id = evh.id

       JOIN intervenant                       i ON i.id = fr.intervenant_id /*@INTERVENANT_ID=i.id*/ /*@ANNEE_ID=a.annee_id*/
       JOIN service_referentiel              sr ON sr.id = frs.service_referentiel_id
       JOIN fonction_referentiel           fncr ON fncr.id = sr.fonction_id
  LEFT JOIN mise_en_paiement                mep ON mep.formule_res_service_ref_id = frs.id
                                               AND mep.histo_destruction IS NULL