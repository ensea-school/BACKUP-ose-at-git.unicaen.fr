CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
SELECT
  c.id                                             contrat_id,
  str.libelle_court                                "serviceComposante",
  ep.code                                          "serviceCode",
  ep.libelle                                       "serviceLibelle",
  sum(vh.heures)                                   heures,
  replace(ltrim(to_char(sum(vh.heures), '999999.00')),'.',',') "serviceHeures"
FROM
            contrat                  c
       JOIN structure              str ON str.id = c.structure_id
       JOIN volume_horaire          vh ON vh.contrat_id = c.id AND vh.histo_destruction IS NULL
       JOIN service                  s ON s.id = vh.service_id
  LEFT JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id
GROUP BY
  c.id, str.libelle_court, ep.code, ep.libelle