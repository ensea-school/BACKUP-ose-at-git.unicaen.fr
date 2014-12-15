
SELECT
  fi, fc, fa, 
  ose_divers.CALCUL_TAUX_FI( fi,fc,fa,  ti, tc, ta,  2) taux_fi,
  ose_divers.CALCUL_TAUX_FC( fi,fc,fa,  ti, tc, ta,  2) taux_fc,
  ose_divers.CALCUL_TAUX_FA( fi,fc,fa,  ti, tc, ta,  2) taux_fa,
  ose_divers.CALCUL_TAUX_FI( fi,fc,fa,  ti, tc, ta,  2)
+ ose_divers.CALCUL_TAUX_FC( fi,fc,fa,  ti, tc, ta,  2)
+ ose_divers.CALCUL_TAUX_FA( fi,fc,fa,  ti, tc, ta,  2) taux_total
FROM (
      select  0 fi, 50 fc,  0 fa,  1 ti, 1 tc, 1 ta,   1 ordre from dual
union select  0 fi,  0 fc, 50 fa,  1 ti, 1 tc, 1 ta,   2 ordre from dual
union select 50 fi,  0 fc,  0 fa,  1 ti, 1 tc, 1 ta,   3 ordre from dual
union select 33 fi, 33 fc, 33 fa,  1 ti, 1 tc, 1 ta,   4 ordre from dual
union select  0 fi,  0 fc,  0 fa,  1 ti, 1 tc, 1 ta,   5 ordre from dual
union select 50 fi, 50 fc,  0 fa,  1 ti, 1 tc, 1 ta,   6 ordre from dual
union select 50 fi,  0 fc, 50 fa,  1 ti, 1 tc, 1 ta,   7 ordre from dual
union select  0 fi, 50 fc, 50 fa,  1 ti, 1 tc, 1 ta,   8 ordre from dual

union select  1 fi,  1 fc,  2 fa,  1 ti, 1 tc, 1 ta,   10 ordre from dual
union select  1 fi,  1 fc,  3 fa,  1 ti, 1 tc, 1 ta,   11 ordre from dual
union select  1 fi,  2 fc,  1 fa,  1 ti, 1 tc, 1 ta,   12 ordre from dual
union select  1 fi,  2 fc,  2 fa,  1 ti, 1 tc, 1 ta,   13 ordre from dual
union select  1 fi,  2 fc,  3 fa,  1 ti, 1 tc, 1 ta,   14 ordre from dual
union select  1 fi,  3 fc,  1 fa,  1 ti, 1 tc, 1 ta,   15 ordre from dual
union select  1 fi,  3 fc,  2 fa,  1 ti, 1 tc, 1 ta,   16 ordre from dual
union select  1 fi,  3 fc,  3 fa,  1 ti, 1 tc, 1 ta,   17 ordre from dual

union select  2 fi,  1 fc,  2 fa,  1 ti, 1 tc, 1 ta,   18 ordre from dual
union select  2 fi,  1 fc,  3 fa,  1 ti, 1 tc, 1 ta,   19 ordre from dual
union select  2 fi,  2 fc,  1 fa,  1 ti, 1 tc, 1 ta,   20 ordre from dual
union select  2 fi,  2 fc,  2 fa,  1 ti, 1 tc, 1 ta,   21 ordre from dual
union select  2 fi,  2 fc,  3 fa,  1 ti, 1 tc, 1 ta,   22 ordre from dual
union select  2 fi,  3 fc,  1 fa,  1 ti, 1 tc, 1 ta,   23 ordre from dual
union select  2 fi,  3 fc,  2 fa,  1 ti, 1 tc, 1 ta,   24 ordre from dual
union select  2 fi,  3 fc,  3 fa,  1 ti, 1 tc, 1 ta,   25 ordre from dual

union select  3 fi,  1 fc,  2 fa,  1 ti, 1 tc, 1 ta,   26 ordre from dual
union select  3 fi,  1 fc,  3 fa,  1 ti, 1 tc, 1 ta,   27 ordre from dual
union select  3 fi,  2 fc,  1 fa,  1 ti, 1 tc, 1 ta,   28 ordre from dual
union select  3 fi,  2 fc,  2 fa,  1 ti, 1 tc, 1 ta,   29 ordre from dual
union select  3 fi,  2 fc,  3 fa,  1 ti, 1 tc, 1 ta,   30 ordre from dual
union select  3 fi,  3 fc,  1 fa,  1 ti, 1 tc, 1 ta,   31 ordre from dual
union select  3 fi,  3 fc,  2 fa,  1 ti, 1 tc, 1 ta,   32 ordre from dual
union select  3 fi,  3 fc,  3 fa,  1 ti, 1 tc, 1 ta,   33 ordre from dual

union select  0 fi,  1 fc,  1 fa,  1 ti, 1 tc, 1 ta,   34 ordre from dual
union select  1 fi,  1 fc,  0 fa,  1 ti, 1 tc, 1 ta,   35 ordre from dual
union select  1 fi,  0 fc,  1 fa,  1 ti, 1 tc, 1 ta,   36 ordre from dual
union select  1 fi,  0 fc,  0 fa,  1 ti, 1 tc, 1 ta,   37 ordre from dual
union select  1 fi,  1 fc,  1 fa,  1 ti, 1 tc, 1 ta,   38 ordre from dual
) tmp
order by ordre