CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_SIHAM AS
WITH paiement AS (
	--Année antérieur AA
    SELECT
    	tp.structure_id           							  				structure_id,
        s.ids                     							  				structure_ids,
        tp.periode_paiement_id    						      				periode_paiement_id,
        tp.intervenant_id           						  				intervenant_id,
        2             			     					      				code_origine,
        SUM(tp.heures_payees_aa)        									nbu,
        tp.taux_horaire*MAX(tp.heures_payees_aa)							montant,
        cc.unite_budgetaire       							  				unite_budgetaire,
        tp.taux_horaire                                       				taux_horaire,
        tp.taux_conges_payes                                  				taux_conges_payes,
        MAX(tp.mise_en_paiement_id)                           				mise_en_paiement_id,
        'heures_a_payer'									  				type_paiement
    FROM
    	 tbl_paiement tp
    JOIN structure s ON tp.structure_id = s.id
    JOIN centre_cout cc ON cc.id = tp.centre_cout_id
    JOIN type_heures th ON th.id = tp.type_heures_id
    WHERE
        tp.periode_paiement_id IS NOT NULL
        AND th.eligible_extraction_paie  = 1
        AND tp.heures_payees_aa > 0
    GROUP BY
    	tp.structure_id,
        s.ids,
        periode_paiement_id,
        intervenant_id,
        unite_budgetaire,
        taux_horaire,
        taux_conges_payes

    UNION ALL

    --Année courante AC
    SELECT
    	tp.structure_id           							  				structure_id,
        s.ids                     							  				structure_ids,
        tp.periode_paiement_id    						      				periode_paiement_id,
        tp.intervenant_id           						  				intervenant_id,
        1             			     					      				code_origine,
        SUM(tp.heures_payees_ac)        									nbu,
        tp.taux_horaire*MAX(tp.heures_payees_ac)							montant,
        cc.unite_budgetaire       							  				unite_budgetaire,
        tp.taux_horaire                                       				taux_horaire,
        tp.taux_conges_payes                                  				taux_conges_payes,
        MAX(tp.mise_en_paiement_id)                           				mise_en_paiement_id,
        'heures_a_payer'									  				type_paiement
    FROM
    	 tbl_paiement tp
    JOIN structure s ON tp.structure_id = s.id
    JOIN centre_cout cc ON cc.id = tp.centre_cout_id
    JOIN type_heures th ON th.id = tp.type_heures_id
    WHERE
        tp.periode_paiement_id IS NOT NULL
        AND th.eligible_extraction_paie  = 1
        AND tp.heures_payees_ac > 0
    GROUP BY
    	tp.structure_id,
        s.ids,
        periode_paiement_id,
        intervenant_id,
        unite_budgetaire,
        taux_horaire,
        taux_conges_payes

    UNION ALL
		--Congés payés année antérieur AA
        SELECT
    	tp.structure_id           							  										structure_id,
        s.ids                     							  										structure_ids,
        tp.periode_paiement_id    						      										periode_paiement_id,
        tp.intervenant_id           						  										intervenant_id,
        2             			     					      										code_origine,
        SUM(tp.heures_payees_aa)		  															nbu,
        SUM(tp.taux_horaire*(tp.heures_payees_aa)*(tp.taux_conges_payes-1))							montant,
	    cc.unite_budgetaire       							  										unite_budgetaire,
        MAX(tp.taux_horaire)                                       									taux_horaire,
        MAX(tp.taux_conges_payes)                                  									taux_conges_payes,
        MAX(tp.mise_en_paiement_id)                           										mise_en_paiement_id,
        'conges_payes'										  										type_paiement
    FROM
    	 tbl_paiement tp
    JOIN structure s ON tp.structure_id = s.id
    JOIN centre_cout cc ON cc.id = tp.centre_cout_id
    JOIN type_heures th ON th.id = tp.type_heures_id
    WHERE
    	tp.mission_id IS NOT NULL
        AND tp.periode_paiement_id IS NOT NULL
        AND th.eligible_extraction_paie  = 1
        AND tp.heures_payees_aa > 0
    GROUP BY
    	tp.structure_id,
        s.ids,
        periode_paiement_id,
        intervenant_id,
        unite_budgetaire

        UNION ALL

        --Congés payés année courante AC
        SELECT
    	tp.structure_id           							  										structure_id,
        s.ids                     							  										structure_ids,
        tp.periode_paiement_id    						      										periode_paiement_id,
        tp.intervenant_id           						  										intervenant_id,
        1             			     					      										code_origine,
        SUM(tp.heures_payees_ac)		  															nbu,
        SUM(tp.taux_horaire*(tp.heures_payees_ac)*(tp.taux_conges_payes-1))							montant,
	    cc.unite_budgetaire       							  										unite_budgetaire,
        MAX(tp.taux_horaire)                                       									taux_horaire,
        MAX(tp.taux_conges_payes)                                  									taux_conges_payes,
        MAX(tp.mise_en_paiement_id)                           										mise_en_paiement_id,
        'conges_payes'										  										type_paiement
    FROM
    	 tbl_paiement tp
    JOIN structure s ON tp.structure_id = s.id
    JOIN centre_cout cc ON cc.id = tp.centre_cout_id
    JOIN type_heures th ON th.id = tp.type_heures_id
    WHERE
    	tp.mission_id IS NOT NULL
        AND tp.periode_paiement_id IS NOT NULL
        AND th.eligible_extraction_paie  = 1
        AND tp.heures_payees_ac > 0
    GROUP BY
    	tp.structure_id,
        s.ids,
        periode_paiement_id,
        intervenant_id,
        unite_budgetaire

)
SELECT annee_id																			annee_id,
       type_intervenant_id																type_intervenant_id,
       structure_id																		structure_id,
       structure_ids																	structure_ids,
       periode_id																		periode_id,
       CASE WHEN type_paie IS NOT NULL THEN type_paie ELSE 'P' END       				type,
       matricule																        matricule,
       CASE WHEN code_indemnite IS NOT NULL
       			 AND type_paiement = 'heures_a_payer' THEN '="' || code_indemnite || '"'
       	    ELSE
	       		CASE WHEN type_intervenant_code = 'P'
	       				  AND type_paiement = 'heures_a_payer' THEN '="0204"'
	       			 WHEN type_intervenant_code = 'E'
	       			 	  AND type_paiement = 'heures_a_payer' THEN '="2251"'
	       			 WHEN type_intervenant_code = 'S'
	       			 	  AND type_paiement = 'heures_a_payer' THEN '="0125"'
	       			 WHEN type_paiement = 'conges_payes' THEN '="0290"'
	       			 ELSE NULL
	       		END
	       	END 								     									code_indemnite_retenu,
       TO_CHAR(TRUNC(date_mise_en_paiement , 'MONTH'), 'YYYY-MM')        				du_mois,
       TO_CHAR(date_mise_en_paiement, 'YY')				                 				annee_de_paye,
       '="' || TO_CHAR(date_mise_en_paiement, 'MM') || '"'                 				mois_de_paye,
       '="01"'                                                             				numero_de_remise,
       'G'                                                               				tg_specifique,
       'A definir'                                                       				dossier_de_paye,
        TO_CHAR(TRUNC(date_mise_en_paiement , 'MONTH'), 'DD/MM/YYYY')    				date_pecuniaire,
       CASE WHEN type_paiement = 'conges_payes' THEN 0 ELSE nbu END						nombre_d_unites,
       montant                                                           				montant,
	   CASE WHEN type_paiement = 'heures_a_payer'
	   		THEN libelle || ' ' || lpad(to_char(floor(nbu)), 2, '00') || ' H' ||
		       CASE to_char(round(nbu - floor(nbu), 2) * 100, '00')
		           WHEN ' 00' THEN ''
		           ELSE ' ' || lpad(round(nbu - floor(nbu), 2) * 100, 2, '00') END
		    ELSE libelle || ' CONGES PAYES' END 										libelle,
        CASE WHEN mode_calcul IS NOT NULL THEN mode_calcul ELSE 'B' END  				mode_de_calcul,
       code_origine                                                      				code_origine,
       nom
FROM (
	SELECT
			i.annee_id                                                                                        		annee_id,
            a.libelle                                                                                         		annee_libelle,
            ti.id                                                                                             		type_intervenant_id,
            ti.code                                                                                           		type_intervenant_code,
            i.code_rh                                                                                         		matricule,
            p.structure_id                                                                                   		structure_id,
            p.structure_ids                                                                                  		structure_ids,
            p.periode_paiement_id                                                                            		periode_id,
            mep.date_mise_en_paiement																				date_mise_en_paiement,
            i.id                                                                                              	    intervenant_id,
            i.nom_usuel || ',' || i.prenom                                                                    		nom,
            p.code_origine                                                                                   		code_origine,
            CASE WHEN ind <> ceil(p.nbu / max_nbu) THEN max_nbu ELSE p.nbu - max_nbu * (ind - 1) END        		nbu,
            p.nbu                                                                                            		tnbu,
			p.taux_horaire									   														montant,
            p.taux_conges_payes																			   			taux_conges_payes,
            COALESCE(p.unite_budgetaire, '') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id + 1) 		libelle,
            si.code_indemnite																				   		code_indemnite,
            si.type_paie																					   		type_paie,
            si.code_indemnite_prime																			   		code_indemnite_prime,
            si.type_paie_prime																				   		type_paie_prime,
            si.mode_calcul																			   				mode_calcul,
            p.type_paiement																							type_paiement
      FROM
	      	paiement p
	      JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu
	                    ON ceil(p.nbu / max_nbu) >= ind
	      JOIN intervenant i ON i.id = p.intervenant_id
	      JOIN annee a ON a.id = i.annee_id
	      JOIN statut si ON si.id = i.statut_id
	      JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
	      JOIN mise_en_paiement mep ON mep.id = p.mise_en_paiement_id
	      WHERE type_paiement = 'heures_a_payer'

	  UNION ALL

	  SELECT
			i.annee_id                                                                                        		annee_id,
            a.libelle                                                                                         		annee_libelle,
            ti.id                                                                                             		type_intervenant_id,
            ti.code                                                                                           		type_intervenant_code,
            i.code_rh                                                                                         		matricule,
            p.structure_id                                                                                   		structure_id,
            p.structure_ids                                                                                  		structure_ids,
            p.periode_paiement_id                                                                            		periode_id,
            mep.date_mise_en_paiement																				date_mise_en_paiement,
            i.id                                                                                              	    intervenant_id,
            i.nom_usuel || ',' || i.prenom                                                                    		nom,
            p.code_origine                                                                                   		code_origine,
			0																										nbu,
            0                                                                       	                     	    tnbu,
			ROUND(p.montant,2)  								  	 			  									montant,
            p.taux_conges_payes																			   			taux_conges_payes,
            COALESCE(p.unite_budgetaire, '') || ' ' ||
            SUBSTR(TO_CHAR(TO_NUMBER(i.annee_id)), -2) || '-' ||
            SUBSTR(TO_CHAR(TO_NUMBER(i.annee_id) + 1), -2)						 		libelle,
            si.code_indemnite																				   		code_indemnite,
            si.type_paie																					   		type_paie,
            si.code_indemnite_prime																			   		code_indemnite_prime,
            si.type_paie_prime																				   		type_paie_prime,
            si.mode_calcul_prime																			   		mode_calcul,
            p.type_paiement																							type_paiement
      FROM
	      	paiement p
	      JOIN intervenant i ON i.id = p.intervenant_id
	      JOIN annee a ON a.id = i.annee_id
	      JOIN statut si ON si.id = i.statut_id
	      JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
	      JOIN mise_en_paiement mep ON mep.id = p.mise_en_paiement_id
	      WHERE type_paiement = 'conges_payes'
	  ) t1
	  ORDER BY
		 annee_id,
		 type_intervenant_id,
	     structure_id,
		 periode_id,
	  	 intervenant_id,
	  	 code_origine,
	  	 type_paiement DESC,
	  	 nbu
	  DESC