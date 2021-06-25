CREATE OR REPLACE FORCE VIEW SRC_EFFECTIFS_ETAPE AS
SELECT
  e.id                                etape_id,
  to_number(etp.annee_id)             annee_id,
  etp.effectif_fi                     fi,
  etp.effectif_fc                     fc,
  etp.effectif_fa                     fa,
  s.id                                source_id,
  e.annee_id || '-' || etp.z_etape_id source_code
FROM
  OSE_ETAPE_EFFECTIFS@apoprod etp
  JOIN source                   s ON s.code = 'Apogee'
  LEFT JOIN etape               e ON e.source_code = etp.z_etape_id
                                 AND e.annee_id = to_number(etp.annee_id)