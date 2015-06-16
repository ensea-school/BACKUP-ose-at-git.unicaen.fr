select id from etape where source_code = 'DUNEU1_201';



--select * from element_taux_regimes where element_pedagogique_id IN (
  select * from element_pedagogique where etape_id IN (
    select id from etape where source_code = 'DUNEU1_201'
  --)
);

/*
UPDATE element_pedagogique
SET
  taux_fi = 0,
  taux_fc = 1,
  taux_fa = 0
WHERE
  id IN (
    select id from element_pedagogique where etape_id IN (
      select id from etape where source_code = 'DUNEU1_201'
    )
  )
  AND annee_id = 2014
*/;



