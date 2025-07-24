CREATE OR REPLACE FORCE VIEW SRC_TYPE_FORMATION AS
SELECT
  lib_tpd libelle_long,
  lic_tpd libelle_court,
  cod_tpd_etb source_code,
  s.id source_id
FROM
  typ_diplome@apoprod
    JOIN source s ON s.code = 'Apogee'
WHERE
  tem_en_sve_tpd = 'O'
  -- Exclusion des theses de recherche et HDR (formations sans enseignement)
  AND ( eta_ths_hdr_drt IS NULL OR ( eta_ths_hdr_drt || tem_sante = 'TO' ) )
  -- Exclusion des auditeurs libres
  AND cod_tpd_etb not in ('03')