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
       '0'                                                                            sens,
       CASE WHEN mode_calcul IS NOT NULL THEN mode_calcul ELSE 'B' END                 mc,
       nbu,
       montant,
       CASE WHEN type_paiement = 'conges_payes' THEN libelle ELSE libelle || ' ' || lpad(to_char(floor(nbu)), 2, '00') || ' H' ||
		       CASE to_char(round(nbu - floor(nbu), 2) * 100, '00')
		           WHEN ' 00' THEN ''
		           ELSE ' ' || lpad(round(nbu - floor(nbu), 2) * 100, 2, '00') END
           END 																								   libelle
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
             CASE WHEN t2.type_paiement = 'heures_payees' THEN							
	             	CASE WHEN ind <> ceil(t2.nbu / max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu * (ind - 1) END
	             ELSE 0 END																					   nbu,
			 --Si le type de paiement est conges payés on met systématiquement 0
	         CASE WHEN t2.type_paiement = 'heures_payees' THEN t2.nbu ELSE 0 END                               tnbu,
			 --Selon le type de paiement (conges ou heures) on affiche le montant en euros ou le taux horaire	
             CASE 
	             WHEN t2.type_paiement = 'heures_payees' 
	             THEN t2.taux_horaire ELSE ROUND((CASE WHEN ind <> ceil(t2.nbu / max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu * (ind - 1) END*t2.taux_horaire*(t2.taux_conges_payes-1)),3) END	   montant,	
             COALESCE(t2.unite_budgetaire, '') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id + 1) libelle,
             CASE WHEN t2.type_paiement = 'heures_payees' THEN si.code_indemnite ELSE '290' END				   code_indemnite,
             si.type_paie																					   type_paie,
             CASE WHEN t2.type_paiement = 'conges_payes' THEN 'A' ELSE si.mode_calcul END				       mode_calcul,
             si.code_indemnite_prime																		   code_indemnite_prime,
             si.type_paie_prime																				   type_paie_prime,
             si.mode_calcul_prime																			   mode_calcul_prime,
             t2.type_paiement																				   type_paiement
      FROM (SELECT structure_id,
                   structure_ids,
                   periode_paiement_id,
                   intervenant_id,
                   code_origine,
                   round(SUM(nbu), 2) nbu,
                   unite_budgetaire,
                   taux_horaire,
                   taux_conges_payes,
                   type_paiement
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
                         2             			  code_origine,
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
                  
                  UNION ALL
 
                  --Pour les congés payées pour les heures AA
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
                         'conges_payes'            type_paiement
                  FROM tbl_paiement tp
                  JOIN structure s ON tp.structure_id = s.id 
                  JOIN centre_cout cc ON cc.id = tp.centre_cout_id
                  WHERE tp.heures_payees_aa > 0
                  AND tp.periode_paiement_id IS NOT NULL
                  AND tp.taux_conges_payes > 1
                  
                  UNION ALL

				  --Pour les congés payées pour les heures AC
                  SELECT tp.mise_en_paiement_id   id,
                         tp.structure_id          structure_id,
                         s.ids                    structure_ids,
                         tp.periode_paiement_id,
                         tp.intervenant_id,
                         2             			  code_origine,
                         tp.heures_payees_ac      nbu,
                         cc.unite_budgetaire,
                         tp.taux_horaire,
                         tp.taux_conges_payes,
                         'conges_payes'           type_paiement
                  FROM tbl_paiement tp
                  JOIN structure s ON tp.structure_id = s.id 
                  JOIN centre_cout cc ON cc.id = tp.centre_cout_id
                  WHERE tp.heures_payees_ac > 0
                  AND tp.periode_paiement_id IS NOT NULL
			      AND tp.taux_conges_payes > 1
                 ) t1
            GROUP BY structure_id,
                     structure_ids,
                     periode_paiement_id,
                     intervenant_id,
                     code_origine,
                     unite_budgetaire,
                     taux_horaire,
                     taux_conges_payes,
                     type_paiement) t2
               JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu
                    ON ceil(t2.nbu / max_nbu) >= ind
               JOIN intervenant i ON i.id = t2.intervenant_id
               LEFT JOIN intervenant_dossier d ON i.id = d.intervenant_id AND d.histo_destruction IS NULL
               JOIN statut si ON si.id = i.statut_id
               JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
               JOIN structure s ON s.id = t2.structure_id) t3
ORDER BY annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC


