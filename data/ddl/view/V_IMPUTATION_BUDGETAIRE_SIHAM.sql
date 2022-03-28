CREATE
OR REPLACE FORCE VIEW V_IMPUTATION_BUDGETAIRE_SIHAM AS
SELECT 'P'                                                                                type,
       NULL                                                                               uo,
       intervenant_matricule                                                              matricule,
       date_debut                                                                         date_debut,
       date_fin                                                                           date_fin,
       CASE
           WHEN rem_fc_d714 > 0 THEN '1542'
           ELSE
               CASE WHEN type_intervenant_code = 'P' THEN '0204' ELSE '2251' END
           END                                                                            code_indemnite,
       eotp_code                                                                          operation,
       centre_cout_code                                                                   centre_cout,
       domaine_fonctionnel_code                                                           destination,
       NULL                                                                               fonds,
       NULL                                                                               poste_reservation_credit,
       to_char((CASE
                    WHEN pourc_ecart >= 0 THEN
                        CASE
                            WHEN rank() OVER (PARTITION BY periode_id, intervenant_id, etat ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END DESC) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / abs(pourc_ecart) * 0.001)
                            ELSE hetd_pourc END
                    ELSE
                        CASE
                            WHEN rank() OVER (PARTITION BY periode_id, intervenant_id, etat ORDER BY CASE WHEN (pourc_ecart >= 0 AND pourc_diff >= 0) OR (pourc_ecart < 0 AND pourc_diff < 0) THEN pourc_diff ELSE -1 END) <= (ABS(pourc_ecart) / 0.001) THEN hetd_pourc + (pourc_ecart / abs(pourc_ecart) * 0.001)
                            ELSE hetd_pourc END
           END)) * 100                                                                    pourcentage,
       --  pourc_ecart,
       --  pourc_diff,
       (lpad(floor(hetd), 2, '0')) || ':' || lpad(floor((hetd - floor(hetd)) * 60), 2, 0) nombres_heures,
       NULL                                                                               flmodi,
       NULL                                                                               numord,
       NULL                                                                               numgrp,
       annee_id,
       periode_id,
       intervenant_id,
       centre_cout_id,
       domaine_fonctionnel_id,
       etat,
       date_mise_en_paiement,
       domaine_fonctionnel_code,
       hetd,
       hetd_montant,
       rem_fc_d714,
       type_intervenant_id
FROM (
         SELECT dep3.*,
                1 - CASE WHEN hetd > 0 THEN SUM(hetd_pourc) OVER ( PARTITION BY periode_id, intervenant_id, etat) ELSE 0 END pourc_ecart


         FROM (
                  SELECT periode_id,
                         type_intervenant_id,
                         type_intervenant_code,
                         intervenant_id,
                         annee_id,
                         centre_cout_id,
                         domaine_fonctionnel_id,
                         etat,
                         date_mise_en_paiement,
                         date_debut,
                         date_fin,
                         statut,
                         intervenant_code,
                         intervenant_matricule,
                         intervenant_nom,
                         intervenant_numero_insee,
                         centre_cout_code,
                         centre_cout_libelle,
                         eotp_code,
                         eotp_libelle,
                         domaine_fonctionnel_code,
                         domaine_fonctionnel_libelle,
                         hetd,
                         round(CASE WHEN hetd > 0 THEN hetd / SUM(hetd) OVER( PARTITION BY periode_id, intervenant_id, etat) ELSE 0 END, 3) hetd_pourc,
                         round(hetd * taux_horaire, 2)                                                                                      hetd_montant,
                         round(fc_majorees * taux_horaire, 2)                                                                               rem_fc_d714,
                         exercice_aa,
                         round(exercice_aa * taux_horaire, 2)                                                                               exercice_aa_montant,
                         exercice_ac,
                         round(exercice_ac * taux_horaire, 2)                                                                               exercice_ac_montant,


                         (CASE WHEN hetd > 0 THEN hetd / SUM(hetd) OVER( PARTITION BY periode_id, intervenant_id, etat) ELSE 0 END)
                             -
                         round(CASE WHEN hetd > 0 THEN hetd / SUM(hetd) OVER( PARTITION BY periode_id, intervenant_id, etat) ELSE 0 END, 3) pourc_diff

                  FROM (
                           WITH dep AS ( -- détails par état de paiement
                               SELECT CASE WHEN th.code = 'fc_majorees' THEN 1 ELSE 0 END                        is_fc_majoree,
                                      p.id                                                                       periode_id,
                                      i.id                                                                       intervenant_id,
                                      i.annee_id                                                                 annee_id,
                                      cc.id                                                                      centre_cout_id,
                                      df.id                                                                      domaine_fonctionnel_id,
                                      ti.id                                                                      type_intervenant_id,
                                      ti.code                                                                    type_intervenant_code,
                                      CASE
                                          WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
                                          ELSE 'mis-en-paiement'
                                          END                                                                    etat,

                                      TRIM(to_char(add_months(a.date_debut, p.ecart_mois), 'dd/mm/yyyy'))        date_debut,
                                      TRIM(to_char(last_day(add_months(a.date_debut, p.ecart_mois)),
                                                   'dd/mm/yyyy'))                                                date_fin,
                                      mep.date_mise_en_paiement                                                  date_mise_en_paiement,
                                      ti.libelle                                                                 statut,
                                      i.source_code                                                              intervenant_code,
                                      i.code_rh                                                                  intervenant_matricule,
                                      i.nom_usuel || ' ' || i.prenom                                             intervenant_nom,
                                      i.numero_insee                                                             intervenant_numero_insee,
                                      CASE
                                          WHEN cc.parent_id IS NULL THEN cc.source_code
                                          ELSE cc2.source_code END                                               centre_cout_code,
                                      CASE WHEN cc.parent_id IS NULL THEN cc.libelle ELSE cc2.libelle END        centre_cout_libelle,
                                      CASE WHEN cc.parent_id IS NOT NULL THEN cc.source_code ELSE NULL END       eotp_code,
                                      CASE WHEN cc.parent_id IS NOT NULL THEN cc.libelle ELSE NULL END           eotp_libelle,
                                      df.source_code                                                             domaine_fonctionnel_code,
                                      df.libelle                                                                 domaine_fonctionnel_libelle,
                                      CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE mep.heures END      hetd,
                                      CASE WHEN th.code = 'fc_majorees' THEN mep.heures ELSE 0 END               fc_majorees,
                                      mis.heures_aa                                                              exercice_aa,
                                      mis.heures_ac                                                              exercice_ac,
                                      ose_formule.get_taux_horaire_hetd(nvl(mep.date_mise_en_paiement, sysdate)) taux_horaire
                               FROM tbl_paiement mis
                                        JOIN mise_en_paiement mep
                                             ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
                                        JOIN type_heures th ON th.id = mep.type_heures_id
                                        JOIN centre_cout cc
                                             ON cc.id = mep.centre_cout_id -- pas d'historique pour les centres de coût, qui devront tout de même apparaitre mais en erreur
                                        LEFT JOIN centre_cout cc2 ON cc.parent_id = cc2.id
                                        JOIN intervenant i ON i.id = mis.intervenant_id AND i.histo_destruction IS NULL
                                        JOIN annee a ON a.id = i.annee_id
                                        JOIN statut si ON si.id = i.statut_id
                                        JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
                                        LEFT JOIN validation v ON v.id = mep.validation_id AND v.histo_destruction IS NULL
                                        LEFT JOIN domaine_fonctionnel df ON df.id = mis.domaine_fonctionnel_id
                                        LEFT JOIN periode p ON p.id = mep.periode_paiement_id
                           )
                           SELECT periode_id,
                                  type_intervenant_id,
                                  MAX(type_intervenant_code) type_intervenant_code,
                                  intervenant_id,
                                  annee_id,
                                  centre_cout_id,
                                  domaine_fonctionnel_id,
                                  etat,
                                  date_debut,
                                  date_fin,
                                  date_mise_en_paiement,
                                  statut,
                                  intervenant_code,
                                  intervenant_matricule,
                                  intervenant_nom,
                                  intervenant_numero_insee,
                                  centre_cout_code,
                                  centre_cout_libelle,
                                  eotp_code,
                                  eotp_libelle,
                                  domaine_fonctionnel_code,
                                  domaine_fonctionnel_libelle,
                                  SUM(hetd)                  hetd,
                                  SUM(fc_majorees)           fc_majorees,
                                  SUM(exercice_aa)           exercice_aa,
                                  SUM(exercice_ac)           exercice_ac,
                                  taux_horaire
                           FROM dep
                           GROUP BY periode_id,
                                    type_intervenant_id,
                                    intervenant_id,
                                    annee_id,
                                    centre_cout_id,
                                    domaine_fonctionnel_id,
                                    etat,
                                    date_debut,
                                    date_fin,
                                    date_mise_en_paiement,
                                    statut,
                                    intervenant_code,
                                    intervenant_matricule,
                                    intervenant_nom,
                                    intervenant_numero_insee,
                                    centre_cout_code,
                                    centre_cout_libelle,
                                    eotp_code,
                                    eotp_libelle,
                                    domaine_fonctionnel_code,
                                    domaine_fonctionnel_libelle,
                                    taux_horaire,
                                    is_fc_majoree
                       ) dep2
              ) dep3
     ) dep4

ORDER BY annee_id,
         type_intervenant_id,
         periode_id,
         intervenant_nom