CREATE OR REPLACE FORCE VIEW V_EXPORT_PILOTAGE_ECARTS_ETATS AS
SELECT t3.annee_id                             annee_id,
       t3.annee_id || '-' || (t3.annee_id + 1) annee,
       t3.etat,
       t3.type_heures_id,
       t3.type_heures,
       s.id                                    structure_id,
       s.ids                                   structure_ids,
       s.libelle_court                         STRUCTURE,
       i.id                                    intervenant_id,
       ti.libelle                              intervenant_type,
       i.source_code                           intervenant_code,
       i.prenom                                prenom,
       i.nom_usuel                             nom_usuel,
       t3.hetd_payables
FROM (SELECT annee_id,
             etat,
             type_heures_id,
             type_heures,
             structure_id,
             intervenant_id,
             SUM(hetd) hetd_payables
      FROM (SELECT annee_id,
                   LOWER(tvh.code) || '-' || evh.code etat,
                   10 * tvh.ordre + evh.ordre         ordre,
                   type_heures_id,
                   type_heures,
                   structure_id,
                   intervenant_id,
                   SUM(hetd)                          hetd
            FROM (SELECT i.annee_id,
                         fr.type_volume_horaire_id,
                         fr.etat_volume_horaire_id,
                         th.id                                     type_heures_id,
                         th.code                                   type_heures,
                         COALESCE(ep.structure_id, i.structure_id) structure_id,
                         fr.intervenant_id,
                         SUM(frvh.heures_compl_fi)                  hetd
                  FROM formule_resultat_volume_horaire frvh
                           JOIN formule_resultat_intervenant fr ON fr.id = frvh.formule_resultat_intervenant_id
                           JOIN service s ON s.id = frvh.service_id
                           JOIN intervenant i ON i.id = fr.intervenant_id
                           JOIN type_heures th ON th.code = 'fi'
                           LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
                  GROUP BY i.annee_id,
                           fr.type_volume_horaire_id,
                           fr.etat_volume_horaire_id,
                           th.id, th.code,
                           fr.intervenant_id,
                           ep.structure_id,
                           i.structure_id

                  UNION ALL

                  SELECT i.annee_id,
                         fr.type_volume_horaire_id,
                         fr.etat_volume_horaire_id,
                         th.id                                     type_heures_id,
                         th.code                                   type_heures,
                         COALESCE(ep.structure_id, i.structure_id) structure_id,
                         fr.intervenant_id,
                         SUM(frvh.heures_compl_fa)                  hetd
                  FROM formule_resultat_volume_horaire frvh
                           JOIN formule_resultat_intervenant fr ON fr.id = frvh.formule_resultat_intervenant_id
                           JOIN service s ON s.id = frvh.service_id
                           JOIN intervenant i ON i.id = fr.intervenant_id
                           JOIN type_heures th ON th.code = 'fa'
                           LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
                  GROUP BY i.annee_id,
                           fr.type_volume_horaire_id,
                           fr.etat_volume_horaire_id,
                           th.id, th.code,
                           fr.intervenant_id,
                           ep.structure_id,
                           i.structure_id

                  UNION ALL

                  SELECT i.annee_id,
                         fr.type_volume_horaire_id,
                         fr.etat_volume_horaire_id,
                         th.id                                     type_heures_id,
                         th.code                                   type_heures,
                         COALESCE(ep.structure_id, i.structure_id) structure_id,
                         fr.intervenant_id,
                         SUM(frvh.heures_compl_fc)                  hetd
                  FROM formule_resultat_volume_horaire frvh
                           JOIN formule_resultat_intervenant fr ON fr.id = frvh.formule_resultat_intervenant_id
                           JOIN service s ON s.id = frvh.service_id
                           JOIN intervenant i ON i.id = fr.intervenant_id
                           JOIN type_heures th ON th.code = 'fc'
                           LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
                  GROUP BY i.annee_id,
                           fr.type_volume_horaire_id,
                           fr.etat_volume_horaire_id,
                           th.id, th.code,
                           fr.intervenant_id,
                           ep.structure_id,
                           i.structure_id

                  UNION ALL

                  SELECT i.annee_id,
                         fr.type_volume_horaire_id,
                         fr.etat_volume_horaire_id,
                         th.id                              type_heures_id,
                         th.code                            type_heures,
                         sr.structure_id,
                         fr.intervenant_id,
                         SUM(frvh.heures_compl_referentiel) hetd
                  FROM formule_resultat_volume_horaire frvh
                           JOIN formule_resultat_intervenant fr ON fr.id = frvh.formule_resultat_intervenant_id
                           JOIN service_referentiel sr ON sr.id = frvh.service_referentiel_id
                           JOIN intervenant i ON i.id = fr.intervenant_id
                           JOIN type_heures th ON th.code = 'referentiel'
                  GROUP BY i.annee_id,
                           fr.type_volume_horaire_id,
                           fr.etat_volume_horaire_id,
                           th.id, th.code,
                           fr.intervenant_id,
                           sr.structure_id) t1
                     JOIN type_volume_horaire tvh ON tvh.id = t1.type_volume_horaire_id
                     JOIN etat_volume_horaire evh ON evh.id = t1.etat_volume_horaire_id
            GROUP BY annee_id, tvh.code, evh.code, tvh.ordre, evh.ordre, type_heures_id, type_heures, structure_id,
                     intervenant_id

            UNION ALL

            SELECT annee_id,
                   etat,
                   ordre,
                   type_heures_id,
                   type_heures,
                   structure_id,
                   intervenant_id,
                   SUM(hetd) hetd
            FROM (SELECT mep.annee_id,
                         'demande-mise-en-paiement'                                 etat,
                         90                                                         ordre,
                         th.id                                                      type_heures_id,
                         th.code                                                    type_heures,
                         mep.structure_id                                           structure_id,
                         mep.intervenant_id                                         intervenant_id,
                         sum(mep.heures_demandees_aa + mep.heures_demandees_ac)     hetd
                  FROM tbl_paiement mep
                           JOIN type_heures th ON th.id = mep.type_heures_id
                           JOIN centre_cout cc ON cc.id = mep.centre_cout_id
                  WHERE
                    th.eligible_extraction_paie = 1
                  GROUP BY
                    mep.annee_id, th.id, th.code, mep.structure_id, mep.intervenant_id

                  UNION ALL

                  SELECT mep.annee_id,
                         'mise-en-paiement'                               etat,
                         91                                               ordre,
                         th.id                                            type_heures_id,
                         th.code                                          type_heures,
                         mep.structure_id                                 structure_id,
                         mep.intervenant_id                               intervenant_id,
                         sum(mep.heures_payees_aa + mep.heures_payees_ac) hetd
                  FROM tbl_paiement mep
                           JOIN type_heures th ON th.id = mep.type_heures_id
                  WHERE th.eligible_extraction_paie = 1
                    AND mep.periode_paiement_id IS NOT NULL
                  GROUP BY
                    mep.annee_id, th.id, th.code, mep.structure_id, mep.intervenant_id) t1
            GROUP BY annee_id, etat, ordre, type_heures_id, type_heures, structure_id, intervenant_id) t2
      GROUP BY annee_id, etat, ordre
             , type_heures_id, type_heures
             , structure_id
             , intervenant_id
      ORDER BY annee_id, ordre) t3
         JOIN intervenant i ON i.id = t3.intervenant_id
         JOIN statut si ON si.id = i.statut_id
         JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
         JOIN STRUCTURE s ON s.id = t3.structure_id