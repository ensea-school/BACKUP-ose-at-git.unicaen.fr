CREATE OR REPLACE FORCE VIEW SRC_PAYS AS
SELECT
  ll_pays                                                 libelle_long,
  coalesce(lc_pays,ll_pays)                               libelle_court,
  coalesce(d_deb_val, TO_DATE('1900/01/01','YYYY/MM/DD')) validite_debut,
  d_fin_val                                               validite_fin,
  decode(tem_ue, 'O', 1, 0)                               temoin_ue,
  s.id                                                    source_id,
  c_pays                                                  source_code
FROM
  pays@harpprod p
  JOIN source s ON s.code = 'Harpege';