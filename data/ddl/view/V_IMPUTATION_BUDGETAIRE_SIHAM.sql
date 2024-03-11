CREATE OR REPLACE FORCE VIEW V_IMPUTATION_BUDGETAIRE_SIHAM AS
SELECT
	type,
	uo,
	matricule,
	date_debut,
	date_fin,
	code_indemnite,
	operation,
	centre_cout,
	destination,
	fonds,
	poste_reservation_credit,
	--On impact le potentiel écart de pourcentage détecté pour s'assurer que la somme des pourcentages fasse 100%
 	to_char((CASE
                WHEN pourc_ecart >= 0 THEN
                    CASE
                        WHEN rank() OVER (PARTITION BY periode_id, intervenant_id   ORDER BY operation,centre_cout, pourcentage) = 1
                        THEN pourcentage + pourc_ecart
                        ELSE pourcentage END
                ELSE
                    CASE
                        WHEN rank() OVER (PARTITION BY periode_id, intervenant_id   ORDER BY operation, centre_cout, pourcentage) = 1
                        THEN pourcentage - pourc_ecart
                        ELSE pourcentage END
       END))	pourcentage,
     nombres_heures,
     flmodi,
     numord,
     numgrp,
     intervenant_id																											intervenant_id,
     type_intervenant_id                                                                                                      type_intervenant_id,
     periode_id,
     annee_id,
     'mis-en-paiement'																								        etat
     FROM(
SELECT
	imputation2.*,
	SUM(pourcentage) OVER ( PARTITION BY periode_id, intervenant_id)   somme,
	--On regarde si la somme des pourcentages est égale à 100% sinon on calcule l'écart qu'il faudra redistribuer ou retirer
	100 - SUM(pourcentage) OVER ( PARTITION BY periode_id, intervenant_id)  pourc_ecart
FROM (
	SELECT 'P'                                                                                										type,
		   NULL																				  										uo,
		   intervenant_matricule                                                              										matricule,
		   date_debut																		  										date_debut,
		   date_fin																			  										date_fin,
		   code_indemnite																      										code_indemnite,
		   eotp_code                                                                          										operation,
	       centre_cout_code                                                                   										centre_cout,
	       domaine_fonctionnel_code                                                           										destination,
	       NULL                                                                               										fonds,
	       NULL                                                                               										poste_reservation_credit,
	       CASE WHEN montant_hetd > 0
	       	    THEN ROUND(montant_hetd / SUM(montant_hetd) OVER( PARTITION BY periode_id,intervenant_id),2)*100
	       	    ELSE 0  END																											pourcentage,
	       CASE WHEN hetd >= 100 THEN FLOOR(hetd) || ':' || lpad(FLOOR((hetd - FLOOR(hetd)) * 60), 2, 0)
	       ELSE (lpad(FLOOR(hetd), 2, '0')) || ':' || lpad(FLOOR((hetd - FLOOR(hetd)) * 60), 2, 0) END         						nombres_heures,
	       NULL                                                                               										flmodi,
	       NULL                                                                               										numord,
	       NULL                                                                               										numgrp,
	       intervenant_id																											intervenant_id,
	       type_intervenant_id                                                                                                      type_intervenant_id,
	       periode_id,
           annee_id,
           'mis-en-paiement'																								        etat
FROM(
    SELECT
		       p.id                                                                                          periode_id,
		       i.id                                                                                          intervenant_id,
		       MAX(i.annee_id)                                                                               annee_id,
		       MAX(ti.id)                                                                                    type_intervenant_id,
		       MAX(ti.code)                                                                                  type_intervenant_code,
		       MAX(i.code_rh)                                                                                intervenant_matricule,
		       MAX(TRIM(to_char(add_months(a.date_debut, p.ecart_mois), 'dd/mm/yyyy')))                      date_debut,
		       MAX(TRIM(to_char(last_day(add_months(a.date_debut, p.ecart_mois)),'dd/mm/yyyy')))             date_fin,
		       CASE
		          WHEN MAX(th.code) = 'fc_majorees'  THEN '1542'
		          WHEN MAX(mis.mission_id) IS NOT NULL THEN '0125'
		          ELSE
		             CASE WHEN MAX(ti.code) = 'P' THEN '="0204"' ELSE '="2251"' END
		          END                                                                                        code_indemnite,
		       MAX(CASE WHEN cc.parent_id IS NULL THEN cc.source_code ELSE cc2.source_code END)              centre_cout_code,
			   MAX(CASE WHEN cc.parent_id IS NULL THEN cc.id ELSE cc2.id END)                                centre_cout_id,
		       MAX(CASE WHEN cc.parent_id IS NOT NULL THEN cc.source_code ELSE NULL END)                     eotp_code,
		       CASE WHEN cc.parent_id IS NOT NULL THEN cc.id ELSE NULL END                                   eotp_id,
		       MAX(df.source_code)   																	     domaine_fonctionnel_code,
		       SUM(mis.heures_payees_aa + mis.heures_payees_ac)                                              hetd,
		       SUM(CASE WHEN th.code = 'fc_majorees'
		       		THEN mis.heures_payees_aa + mis.heures_payees_ac
		       		ELSE 0 END)               											                     fc_majorees,
		       CASE WHEN MAX(mis.mission_id) IS NULL
		       		--Si on n'est pas dans le cas d'une mission on multiplie juste par le taux horaire
		       	    THEN SUM(ROUND(mis.taux_horaire * (mis.heures_payees_aa + mis.heures_payees_ac),2))
		       	    --Si on est dans le cas d'une mission alors on rajoute 10% pour les congés payés systématiquement
		       	    ELSE SUM(ROUND(0.1*mis.taux_horaire * (mis.heures_payees_aa + mis.heures_payees_ac),2)) END  montant_hetd,
		       SUM(CASE WHEN th.code = 'fc_majorees'
		       		THEN ROUND(mis.taux_horaire * (mis.heures_payees_aa + mis.heures_payees_ac),2)
		       		ELSE 0 END)   	   				                        		                         montant_fc_majorees,
		       MAX(mis.taux_horaire)																	     taux_horaire
			FROM tbl_paiement mis
			JOIN mise_en_paiement mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
			JOIN type_heures th ON th.id = mis.type_heures_id
			JOIN centre_cout cc ON cc.id = mis.centre_cout_id
			LEFT JOIN centre_cout cc2 ON cc.parent_id = cc2.id
			JOIN intervenant i ON i.id = mis.intervenant_id AND i.histo_destruction IS NULL
			JOIN annee a ON a.id = i.annee_id
			JOIN statut si ON si.id = i.statut_id
			JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
			LEFT JOIN validation v ON v.id = mep.validation_id AND v.histo_destruction IS NULL
			LEFT JOIN domaine_fonctionnel df ON df.id = mis.domaine_fonctionnel_id
			LEFT JOIN periode p ON p.id = mis.periode_paiement_id
			WHERE mis.periode_paiement_id IS NOT NULL
			GROUP BY
				i.id,
			   	p.id,
				CASE WHEN cc.parent_id IS NULL THEN cc.id ELSE cc2.id END,
			   	CASE WHEN cc.parent_id IS NOT NULL THEN cc.id ELSE NULL END
			 ORDER BY i.id, p.id) imputation) imputation2) imputation3
ORDER BY matricule,date_debut DESC