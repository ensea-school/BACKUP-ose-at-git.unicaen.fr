
SELECT
  fi effectifs_fi, fc effectifs_fc, fa effectifs_fa, ti flag_fi, tc flag_fc, ta flag_fa, 
  ose_divers.CALCUL_TAUX_FI( fi,fc,fa,  ti, tc, ta,  2) taux_fi,
  ose_divers.CALCUL_TAUX_FC( fi,fc,fa,  ti, tc, ta,  2) taux_fc,
  ose_divers.CALCUL_TAUX_FA( fi,fc,fa,  ti, tc, ta,  2) taux_fa,
  ose_divers.CALCUL_TAUX_FI( fi,fc,fa,  ti, tc, ta,  2)
+ ose_divers.CALCUL_TAUX_FC( fi,fc,fa,  ti, tc, ta,  2)
+ ose_divers.CALCUL_TAUX_FA( fi,fc,fa,  ti, tc, ta,  2) taux_total
FROM (
      select  0 fi, 50 fc,  0 fa,   1 ordre from dual
union select 33 fi, 33 fc, 33 fa,   4 ordre from dual
union select  0 fi,  0 fc,  0 fa,   5 ordre from dual
union select 50 fi, 50 fc,  0 fa,   6 ordre from dual

union select  1 fi,  1 fc,  2 fa,  10 ordre from dual
union select  1 fi,  1 fc,  3 fa,  11 ordre from dual
union select  1 fi,  2 fc,  1 fa,  12 ordre from dual
union select  1 fi,  2 fc,  2 fa,  13 ordre from dual
union select  1 fi,  2 fc,  3 fa,  14 ordre from dual
union select  1 fi,  3 fc,  1 fa,  15 ordre from dual
union select  1 fi,  3 fc,  2 fa,  16 ordre from dual
union select  1 fi,  3 fc,  3 fa,  17 ordre from dual

union select  2 fi,  1 fc,  2 fa,  18 ordre from dual
union select  2 fi,  1 fc,  3 fa,  19 ordre from dual
union select  2 fi,  2 fc,  1 fa,  20 ordre from dual
union select  2 fi,  2 fc,  2 fa,  21 ordre from dual
union select  2 fi,  2 fc,  3 fa,  22 ordre from dual
union select  2 fi,  3 fc,  1 fa,  23 ordre from dual
union select  2 fi,  3 fc,  2 fa,  24 ordre from dual
union select  2 fi,  3 fc,  3 fa,  25 ordre from dual

union select  3 fi,  1 fc,  2 fa,  26 ordre from dual
union select  3 fi,  1 fc,  3 fa,  27 ordre from dual
union select  3 fi,  2 fc,  1 fa,  28 ordre from dual
union select  3 fi,  2 fc,  2 fa,  29 ordre from dual
union select  3 fi,  2 fc,  3 fa,  30 ordre from dual
union select  3 fi,  3 fc,  1 fa,  31 ordre from dual
union select  3 fi,  3 fc,  2 fa,  32 ordre from dual
union select  3 fi,  3 fc,  3 fa,  33 ordre from dual

union select  0 fi,  1 fc,  1 fa,  34 ordre from dual
union select  1 fi,  1 fc,  0 fa,  35 ordre from dual
union select  1 fi,  0 fc,  1 fa,  36 ordre from dual
union select  1 fi,  0 fc,  0 fa,  37 ordre from dual
) tmp
JOIN (
      SELECT 1 ti, 1 tc, 1 ta FROM DUAL
UNION SELECT 0 ti, 1 tc, 1 ta FROM DUAL
UNION SELECT 0 ti, 0 tc, 1 ta FROM DUAL
) tmp2 ON 1=1
order by ordre