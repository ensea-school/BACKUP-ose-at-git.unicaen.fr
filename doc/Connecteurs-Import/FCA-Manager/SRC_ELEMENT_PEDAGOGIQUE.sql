CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_PEDAGOGIQUE AS
SELECT
  ep.code,
  ep.libelle,
  etp.id etape_id,
  str.id structure_id,
  per.id periode_id,
  ep.taux_fi taux_fi,
  ep.taux_fc taux_fc,
  ep.taux_fa taux_fa,
  ep.taux_foad,
  ep.fc,
  ep.fi,
  ep.fa,
  s.id,
  ep.source_code,
  TO_NUMBER(ep.annee_id) annee_id,
  d99.id discipline_id
FROM
            FCA.OSE_element_pedagogique@fcaprod  ep
       JOIN source                                s ON s.code            = 'FCAManager'
  LEFT JOIN etape                               etp ON etp.source_code   = ep.z_etape_id
                                                   AND etp.annee_id      = ep.annee_id
  LEFT JOIN SRC_HARPEGE_STRUCTURE_CODES          sc ON sc.c_structure    = ep.z_structure_id
  LEFT JOIN structure                           str ON str.source_code   = sc.c_structure_n2
  LEFT JOIN periode                             per ON per.libelle_court = ep.z_periode_id
  LEFT JOIN unicaen_element_discipline          ued ON ued.element_source_code = ep.source_code
  LEFT JOIN discipline                          d99 ON d99.source_code   = COALESCE( ued.discipline_source_code,'99')