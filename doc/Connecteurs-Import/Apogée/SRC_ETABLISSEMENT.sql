CREATE OR REPLACE FORCE VIEW SRC_ETABLISSEMENT AS
WITH apogee_query AS (
  SELECT
    e.lib_off_etb libelle,
    e.lic_etb     localisation,
    e.cod_dep     departement,
    'Apogee'      z_source_id,
    e.cod_etb     source_code
  FROM
    etablissement@apoprod e
)
SELECT
  aq.libelle      libelle,
  aq.localisation localisation,
  aq.departement  departement,
  s.id            source_id,
  aq.source_code  source_code
FROM
       apogee_query aq
  JOIN source        s ON s.code = aq.z_source_id