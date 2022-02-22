CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
WITH services AS (
	SELECT
	  c.id                                             						contrat_id,
	  str.libelle_court                                						"serviceComposante",
	  ep.code                                          						"serviceCode",
	  ep.libelle                                       						"serviceLibelle",
	  CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END 				    heures_cm,
	  CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END 				    heures_td,
	  CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END 				    heures_tp,
	  CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END   heures_autres,
	  CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN ti.libelle ELSE '' END type_intervention_libelle,
	  vh.heures 															heures_totales
	FROM
	            contrat                  c
	       JOIN structure              str ON str.id = c.structure_id
	       JOIN volume_horaire          vh ON vh.contrat_id = c.id AND vh.histo_destruction IS NULL
	       JOIN service                  s ON s.id = vh.service_id
	       JOIN type_intervention       ti ON ti.id = vh.type_intervention_id
	  LEFT JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id


)  ,
servicesAutres AS (
	SELECT
		t.contrat_id                  																	   contrat_id,
		listagg(t.type_intervention_libelle, ', ') WITHIN GROUP (ORDER BY t.type_intervention_libelle)	   type_intervention_libelle
		FROM (
			SELECT DISTINCT
			  c.id                                             						contrat_id,
			  ti.libelle || ' (' || SUM(vh.heures) || ' h)'                           type_intervention_libelle
   			FROM
			            contrat                  c
			       JOIN volume_horaire          vh ON vh.contrat_id = c.id AND vh.histo_destruction IS NULL
			       JOIN type_intervention       ti ON ti.id = vh.type_intervention_id
			WHERE ti.code NOT IN ('CM','TD','TP')
			GROUP BY
			c.id, ti.libelle
		) t
		GROUP by
		t.contrat_id
		)
SELECT

  s.contrat_id,
  s."serviceComposante",
  s."serviceCode",
  s."serviceLibelle",
  CASE WHEN sum(s.heures_cm) = 0 THEN to_char(0) ELSE replace(ltrim(to_char(sum(s.heures_cm), '999999.00')),'.',',') END 		 "cm",
  CASE WHEN sum(s.heures_td) = 0 THEN to_char(0) ELSE replace(ltrim(to_char(sum(s.heures_td), '999999.00')),'.',',') END 		 "td",
  CASE WHEN sum(s.heures_tp) = 0 THEN to_char(0) ELSE replace(ltrim(to_char(sum(s.heures_tp), '999999.00')),'.',',') END 		 "tp",
  CASE WHEN sum(s.heures_autres) = 0 THEN to_char(0) ELSE replace(ltrim(to_char(sum(s.heures_autres), '999999.00')),'.',',') END "autres",
  SUM(heures_totales)																			                                 heures,
  SUM(heures_totales) 																									 		 "serviceHeures",
  MAX(sa.type_intervention_libelle)																								 "libelleAutres"
  FROM services s
  LEFT JOIN servicesAutres sa ON sa.contrat_id = s.contrat_id
  GROUP BY
  s.contrat_id,
  s."serviceComposante",
  s."serviceCode",
  s."serviceLibelle"