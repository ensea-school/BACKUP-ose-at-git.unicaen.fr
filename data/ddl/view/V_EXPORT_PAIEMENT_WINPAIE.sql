CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_WINPAIE AS
SELECT annee_id,
       type_intervenant_id,
       structure_id,
       structure_ids,
       periode_id,
       intervenant_id,
       insee,
       nom,
       CASE WHEN type_paie IS NOT NULL THEN type_paie ELSE '20' END    carte,
       code_origine,
       CASE WHEN code_indemnite IS NOT NULL THEN code_indemnite ELSE
              CASE WHEN type_intervenant_code = 'P' THEN '0204' ELSE '2251' END END   retenue,
       '0'                                                                 sens,
       CASE WHEN mode_calcul IS NOT NULL THEN mode_calcul ELSE 'B' END                 mc,
       nbu,
       montant,
       libelle || ' ' || lpad(to_char(floor(nbu)), 2, '00') || ' H' ||
       CASE to_char(round(nbu - floor(nbu), 2) * 100, '00')
           WHEN ' 00' THEN ''
           ELSE ' ' || lpad(round(nbu - floor(nbu), 2) * 100, 2, '00') END libelle
FROM (SELECT i.annee_id                                                                                        annee_id,
             ti.id                                                                                             type_intervenant_id,
             ti.code                                                                                           type_intervenant_code,
             t2.structure_id                                                                                   structure_id,
             t2.structure_ids                                                                                  structure_ids,
             t2.periode_paiement_id                                                                            periode_id,
             i.id                                                                                              intervenant_id,
             CASE
                 WHEN i.numero_insee IS NULL THEN '''' || TRIM(d.numero_insee) || TRIM(numero_pec)
                 ELSE
                     '''' || TRIM(i.numero_insee) || TRIM(numero_pec)
                 END                                                                                           insee,
             i.nom_usuel || ',' || i.prenom                                                                    nom,
             t2.code_origine                                                                                   code_origine,
             CASE WHEN ind <> ceil(t2.nbu / max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu * (ind - 1) END        nbu,
             t2.nbu                                                                                            tnbu,
             t2.taux_horaire*t2.taux_conges_payes									                           montant,
             COALESCE(t2.unite_budgetaire, '') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id + 1) libelle,
             si.code_indemnite																				   code_indemnite,
             si.type_paie																					   type_paie,
             si.mode_calcul																				       mode_calcul,
             si.code_indemnite_prime																			   code_indemnite_prime,
             si.type_paie_prime																				   type_paie_prime,
             si.mode_calcul_prime																			   mode_calcul_prime
      FROM (SELECT structure_id,
                   structure_ids,
                   periode_paiement_id,
                   intervenant_id,
                   code_origine,
                   round(SUM(nbu), 2) nbu,
                   unite_budgetaire,
                   date_mise_en_paiement,
                   taux_horaire,
                   taux_conges_payes
            FROM (WITH mep AS (SELECT
                                   -- pour les filtres
                                   mep.id,
                                   str.id structure_id,
                                   str.ids structure_ids,
                                   mep.periode_paiement_id,
                                   mis.intervenant_id,
                                   mep.heures,
                                   cc.unite_budgetaire,
                                   mep.date_mise_en_paiement,
                                   mis.heures_payees_aa heures_aa,
                                   mis.heures_payees_ac heures_ac,
                                   mis.taux_horaire,
                                   mis.taux_conges_payes
                               FROM tbl_paiement mis
                                        JOIN mise_en_paiement mep
                                             ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
                                        JOIN centre_cout cc ON cc.id = mep.centre_cout_id
                                        JOIN type_heures th ON th.id = mep.type_heures_id
                                        JOIN structure str ON str.id = mis.structure_id
                               WHERE mep.date_mise_en_paiement IS NOT NULL
                                 AND mep.periode_paiement_id IS NOT NULL
                                 AND th.eligible_extraction_paie = 1)
                  SELECT mep.id,
                         mep.structure_id,
                         mep.structure_ids,
                         mep.periode_paiement_id,
                         mep.intervenant_id,
                         2             code_origine,
                         mep.heures_aa nbu,
                         mep.unite_budgetaire,
                         mep.date_mise_en_paiement,
                         mep.taux_horaire,
                         mep.taux_conges_payes
                  FROM mep
                  WHERE mep.heures_aa > 0

                  UNION ALL

                  SELECT mep.id,
                         str.id structure_id,
                         str.ids structure_ids,
                         mep.periode_paiement_id,
                         mep.intervenant_id,
                         1             code_origine,
                         mep.heures_ac nbu,
                         mep.unite_budgetaire,
                         mep.date_mise_en_paiement,
                         mep.taux_horaire,
                         mep.taux_conges_payes
                  FROM mep
                    JOIN structure str ON str.id = mep.structure_id
                  WHERE mep.heures_ac > 0) t1
            GROUP BY structure_id,
                     structure_ids,
                     periode_paiement_id,
                     intervenant_id,
                     code_origine,
                     unite_budgetaire,
                     date_mise_en_paiement,
                     taux_horaire,
                     taux_conges_payes) t2
               JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu
                    ON ceil(t2.nbu / max_nbu) >= ind
               JOIN intervenant i ON i.id = t2.intervenant_id
               LEFT JOIN intervenant_dossier d ON i.id = d.intervenant_id AND d.histo_destruction IS NULL
               JOIN statut si ON si.id = i.statut_id
               JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
               JOIN structure s ON s.id = t2.structure_id) t3
ORDER BY annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC