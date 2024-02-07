CREATE OR REPLACE FORCE VIEW V_TBL_DMEP_LIQUIDATION AS
SELECT
  t1.annee_id,
  t1.type_ressource_id,
  t1.structure_id,
  str.ids structure_ids,
  SUM(t1.heures) heures
FROM
(
  SELECT
    i.annee_id,
    cc.type_ressource_id,
    COALESCE( ep.structure_id, i.structure_id ) structure_id,
    tp.heures_demandees_aa+tp.heures_demandees_ac   heures
  FROM
		 tbl_paiement tp
         JOIN centre_cout               cc ON cc.id = tp.centre_cout_id
         JOIN formule_resultat_service frs ON frs.id = tp.formule_res_service_id
         JOIN service                    s ON s.id = frs.service_id
         JOIN intervenant                i ON i.id = s.intervenant_id
    LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
  WHERE
    1=1
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/

  UNION ALL

  SELECT
    i.annee_id,
    cc.type_ressource_id,
    sr.structure_id structure_id,
    tp.heures_demandees_aa+tp.heures_demandees_ac   heures
  FROM
		 tbl_paiement tp
         JOIN centre_cout                    cc ON cc.id = tp.centre_cout_id
         JOIN formule_resultat_service_ref frsr ON frsr.id = tp.formule_res_service_ref_id
         JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
         JOIN intervenant                     i ON i.id = sr.intervenant_id

  WHERE
	1=1
	/*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/

) t1
JOIN structure str ON str.id = t1.structure_id
GROUP BY
  t1.annee_id, t1.type_ressource_id, t1.structure_id, str.ids