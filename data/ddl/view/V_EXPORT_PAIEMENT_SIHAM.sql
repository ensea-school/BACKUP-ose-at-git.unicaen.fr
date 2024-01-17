CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_SIHAM AS
SELECT annee_id,
       type_intervenant_id,
       structure_id,
       structure_ids,
       periode_id,
       CASE WHEN type_paie IS NOT NULL THEN type_paie ELSE 'P' END       type,
       code_rh                                                           matricule,
       CASE WHEN code_indemnite IS NOT NULL THEN code_indemnite ELSE
       		CASE WHEN type_intervenant_code = 'P' THEN '0204' ELSE '1578' END END code_indemnite_retenu,
       ose_paiement.get_format_mois_du()                                 du_mois,
       '20' || ose_paiement.get_annee_extraction_paie()                  annee_de_paye,
       ose_paiement.get_mois_extraction_paie()                           mois_de_paye,
       '01'                                                              numero_de_remise,
       'N'                                                               tg_specifique,
       'A definir'                                                       dossier_de_paye,
       '01/' || ose_paiement.get_mois_extraction_paie() || '/20' ||
       ose_paiement.get_annee_extraction_paie()                          date_pecuniaire,
       nbu                                                               nombre_d_unites,
       montant                                                           montant,
       'DN ' || type_intervenant_code || ' '
           || substr(UPPER(structure_libelle), 0, 10)
           || ' ' || annee_libelle                                       libelle,
        CASE WHEN mode_calcul IS NOT NULL THEN mode_calcul ELSE 'B' END  mode_de_calcul,
       code_origine                                                      code_origine
FROM (SELECT i.annee_id                                                                                        annee_id,
             a.libelle                                                                                         annee_libelle,
             ti.id                                                                                             type_intervenant_id,
             ti.code                                                                                           type_intervenant_code,
             i.code_rh                                                                                         code_rh,
             t2.structure_id                                                                                   structure_id,
             t2.structure_ids                                                                                  structure_ids,
             s.libelle_court                                                                                   structure_libelle,
             s.source_code                                                                                     structure_code,
             t2.periode_paiement_id                                                                            periode_id,
             i.id                                                                                              intervenant_id,
             CASE
                 WHEN i.numero_insee IS NULL THEN '''' || TRIM(d.numero_insee)
                 ELSE
                     '''' || TRIM(i.numero_insee)
                 END                                                                                           insee,
             i.nom_usuel || ',' || i.prenom                                                                    nom,
             t2.code_origine                                                                                   code_origine,
             CASE WHEN ind <> ceil(t2.nbu / max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu * (ind - 1) END        nbu,
             t2.nbu                                                                                            tnbu,
             t2.taux_horaire*t2.taux_conges_payes   														   montant,
             t2.taux_conges_payes																			   taux_conges_payes,
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
                   taux_horaire,
                   taux_conges_payes
            FROM (

                --Pour les heures payées pour les heures AA
                SELECT tp.mise_en_paiement_id    id,
                         tp.structure_id           structure_id,
                         s.ids                     structure_ids,
                         tp.periode_paiement_id,
                         tp.intervenant_id,
                         2             			   code_origine,
                         tp.heures_payees_aa       nbu,
                         cc.unite_budgetaire,
                         tp.taux_horaire,
                         tp.taux_conges_payes,
                         'heures_payees'           type_paiement
                         
                  FROM tbl_paiement tp
                  JOIN structure s ON tp.structure_id = s.id 
                  JOIN centre_cout cc ON cc.id = tp.centre_cout_id
                  WHERE tp.heures_payees_aa > 0
                  AND tp.periode_paiement_id IS NOT NULL

                  UNION ALL

                  --Pour les heures payées pour les heures AC
                  SELECT tp.mise_en_paiement_id   id,
                         tp.structure_id          structure_id,
                         s.ids                    structure_ids,
                         tp.periode_paiement_id,
                         tp.intervenant_id,
                         1             			  code_origine,
                         tp.heures_payees_ac      nbu,
                         cc.unite_budgetaire,
                         tp.taux_horaire,
                         tp.taux_conges_payes,
                         'heures_payees'          type_paiement
                  FROM tbl_paiement tp
                  JOIN structure s ON tp.structure_id = s.id 
                  JOIN centre_cout cc ON cc.id = tp.centre_cout_id
                  WHERE tp.heures_payees_ac > 0
                  AND tp.periode_paiement_id IS NOT NULL
                  ) t1
            GROUP BY structure_id,
                     structure_ids,
                     periode_paiement_id,
                     intervenant_id,
                     code_origine,
                     unite_budgetaire,
                     taux_horaire,
                     taux_conges_payes) t2
               JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu
                    ON ceil(t2.nbu / max_nbu) >= ind
               JOIN intervenant i ON i.id = t2.intervenant_id
               JOIN annee a ON a.id = i.annee_id
               LEFT JOIN intervenant_dossier d ON i.id = d.intervenant_id AND d.histo_destruction IS NULL
               JOIN statut si ON si.id = i.statut_id
               JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
               JOIN structure s ON s.id = i.structure_id) t3 WHERE code_rh = 'UCN000201041'
ORDER BY annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC



