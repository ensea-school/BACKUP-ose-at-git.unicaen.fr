SELECT

  CASE WHEN sc.type_intervention_id  IS NULL THEN 0 ELSE 2 END
  + CASE WHEN sc.groupe_type_formation_id  IS NULL THEN 0 ELSE 4 END
  + CASE WHEN sc.structure_id  IS NULL THEN 0 ELSE 8 END
  + CASE WHEN sc.scenario_id  IS NULL THEN 0 ELSE 16 END poids,

  sc.*
FROM
  seuil_charge sc
WHERE
  1 = ose_divers.comprise_entre( sc.histo_creation, sc.histo_destruction );
  
  
  
  
SELECT
  l.noeud_inf_id,
  l.noeud_sup_id etape_noeud_id,
  n.etape_id
FROM
  lien l
  JOIN noeud n ON n.id = l.noeud_sup_id 
WHERE
  1 = OSE_DIVERS.COMPRISE_ENTRE( l.histo_creation, l.histo_destruction )
  AND 1 = OSE_DIVERS.COMPRISE_ENTRE( n.histo_creation, n.histo_destruction )
  AND etape_id IS NOT NULL
CONNECT BY
  l.noeud_inf_id = PRIOR l.noeud_sup_id
;
START WITH
  l.noeud_inf_id = 58767;



select 
  count(*), noeud_inf_id
from 
  lien 
  group by
    noeud_inf_id
    having count(*) > 1;

    select * from noeud where id = 48203;


WITH noeud_etape AS (
  SELECT
    l.noeud_inf_id noeud_id,
    l.noeud_sup_id etape_noeud_id,
    n.etape_id
  FROM
    lien l
    JOIN noeud n ON n.id = l.noeud_sup_id 
  WHERE
    1 = OSE_DIVERS.COMPRISE_ENTRE( l.histo_creation, l.histo_destruction )
    AND 1 = OSE_DIVERS.COMPRISE_ENTRE( n.histo_creation, n.histo_destruction )
    AND etape_id IS NOT NULL
  CONNECT BY
    l.noeud_inf_id = PRIOR l.noeud_sup_id
)
SELECT
  sn.scenario_id,
  null structure_id,
  ne.*
FROM
  scenario_noeud_seuil sns
  JOIN scenario_noeud sn ON sn.id = sns.scenario_noeud_id
  JOIN noeud n ON n.id = sn.noeud_id
  JOIN noeud_etape ne ON ne.noeud_id = n.id
  
WHERE
  1 = OSE_DIVERS.COMPRISE_ENTRE( sn.histo_creation, sn.histo_destruction )
  ;
  












--CREATE OR REPLACE VIEW V_CHARGENS_CALC_EFFECTIFS AS
WITH v_tbl_lien2 AS (
  SELECT
    l.id             lien_id,
    s.id             scenario_id,
    sl.id            scenario_lien_id,
    l.noeud_sup_id   noeud_sup_id,
    l.noeud_inf_id   noeud_inf_id,
    l.structure_id   structure_id,
    NVL(sl.actif,1)  actif,
    NVL(sl.poids,1)  poids,
    MAX(NVL(sl.poids,1)) OVER (PARTITION BY l.noeud_sup_id, s.id) max_poids,
  
    COUNT(*) OVER (PARTITION BY l.noeud_sup_id, s.id) nb_choix,
    SUM(NVL(sl.poids,1)) OVER (PARTITION BY l.noeud_sup_id, s.id) total_poids
  
  FROM
    lien l
    JOIN scenario s ON s.histo_destruction IS NULL
    LEFT JOIN scenario_lien sl ON 
      sl.lien_id = l.id 
      AND sl.scenario_id = s.id
      AND s.histo_destruction IS NULL
      AND sl.actif = 1
  WHERE
    l.histo_destruction IS NULL
)
SELECT
  ns.id noeud_sup_id,
--  lsup.lien_id lien_sup_id,
--  nl.id noeud_liste_id,
--  linf.lien_id lien_inf_id,
  sni.id scenario_noeud_id,
  ni.id noeud_id,
  sn.scenario_id scenario_id,
  sne.type_heures_id,
  sne.etape_id,

--  slsup.choix_minimum,
--  slsup.choix_maximum,
--  linf.poids,
--  linf.max_poids,
--  linf.nb_choix,
--  linf.total_poids,
    NVL(sni.assiduite,1) * OSE_CHARGENS.CALC_COEF( 
      slsup.choix_minimum, slsup.choix_maximum,linf.poids, linf.max_poids, linf.total_poids, linf.nb_choix 
    ) * sne.effectif effectif

FROM
            scenario_noeud_effectif sne
       JOIN scenario_noeud          sn   ON sn.id = sne.scenario_noeud_id 
                                        AND sn.histo_destruction IS NULL
                                       
       JOIN noeud                   ns   ON ns.id = sn.noeud_id 
                                        AND ns.histo_destruction IS NULL 
                                        AND ns.liste = 0
                                       
       JOIN lien                  lsup   ON lsup.noeud_sup_id = ns.id 
       
       LEFT JOIN scenario_lien   slsup   ON slsup.lien_id = lsup.id
                                        AND slsup.actif = 1
                                        AND slsup.scenario_id = sn.scenario_id
                                       
       JOIN noeud                   nl   ON nl.liste = 1
                                        AND nl.histo_destruction IS NULL
                                        AND nl.id = lsup.noeud_inf_id
                                       
       JOIN v_tbl_lien2             linf ON linf.noeud_sup_id = nl.id 
                                        AND linf.actif = 1 
                                        AND linf.scenario_id = sn.scenario_id
                                   
       JOIN noeud                   ni   ON ni.id = linf.noeud_inf_id 
                                        AND ni.histo_destruction IS NULL 
                                        AND ni.liste = 0
                                        
  LEFT JOIN scenario_noeud         sni   ON sni.noeud_id = ni.id 
                                        AND sni.scenario_id = sn.scenario_id
                                        AND sni.histo_destruction IS NULL

WHERE
  ns.id = 54
  AND sn.scenario_id = 1
;

select * from V_CHARGENS_CALC_EFFECTIFS WHERE noeud_sup_id = 54;










SELECT
  MAX(CASE WHEN sl.poids IS NULL THEN 1 ELSE sl.poids END) max_poids,
  SUM(CASE WHEN sl.poids IS NULL THEN 1 ELSE sl.poids END) total_poids,
  COUNT(*) nb_choix
FROM
  lien l
  LEFT JOIN scenario_lien sl ON 
    sl.lien_id = l.id 
    AND sl.histo_destruction IS NULL
    AND sl.scenario_id = 1
WHERE
  l.histo_destruction IS NULL
  AND (sl.actif = 1 OR sl.actif IS NULL)
  AND l.noeud_sup_id = 2
GROUP BY
  l.noeud_sup_id
;








/
BEGIN
  PTBL_LIEN.CALCULER_TOUT;
END;
/
delete from tbl_lien;

select dbms_metadata.get_ddl('TABLE','TBL_LIEN') from dual ;

delete from tbl_lien where rownum < 50000;
select * from tbl_lien;



















CREATE OR REPLACE VIEW V_CHARGENS_GRANDS_LIENS AS
SELECT
  sn.id scenario_noeud_sup_id,

  sn.scenario_id scenario_id,
  sni.id scenario_noeud_id,
  nl.id noeud_liste_id,
  ni.id noeud_id,

  slsup.choix_minimum,
  slsup.choix_maximum,
  slinf.poids poids,
  COALESCE(sni.assiduite,1) assiduite
FROM
            scenario_noeud     sn

       JOIN noeud              ns   ON ns.id = sn.noeud_id 
                                   AND ns.histo_destruction IS NULL 
                                   AND ns.liste = 0
                                       
       JOIN lien             lsup   ON lsup.noeud_sup_id = ns.id 
                                   AND lsup.histo_destruction IS NULL
       
  LEFT JOIN scenario_lien   slsup   ON slsup.lien_id = lsup.id
                                   AND slsup.scenario_id = sn.scenario_id
                                       
       JOIN noeud              nl   ON nl.liste = 1
                                   AND nl.histo_destruction IS NULL
                                   AND nl.id = lsup.noeud_inf_id
                                       
       JOIN lien             linf   ON linf.noeud_sup_id = nl.id 
                                   AND linf.histo_destruction IS NULL
       
  LEFT JOIN scenario_lien   slinf   ON slinf.lien_id = linf.id
                                   AND slinf.scenario_id = sn.scenario_id
                                   
       JOIN noeud              ni   ON ni.id = linf.noeud_inf_id 
                                   AND ni.histo_destruction IS NULL 
                                   AND ni.liste = 0
                                        
  LEFT JOIN scenario_noeud    sni   ON sni.noeud_id = ni.id 
                                   AND sni.scenario_id = sn.scenario_id
                                   AND sni.histo_destruction IS NULL

WHERE
  (slsup.actif = 1 OR slsup.actif IS NULL)
  AND (slinf.actif = 1 OR slinf.actif IS NULL)
;


select * from v_chargens_grands_liens where SCENARIO_NOEUD_SUP_ID = 58;





select 
  * 
from 
  scenario_noeud_effectif sne
  join scenario_noeud sn on sn.id = SNE.SCENARIO_NOEUD_ID
WHERE
  scenario_noeud_id = 253;


select * from scenario_noeud where noeud_id = 10;

delete from scenario_noeud_effectif WHERE id <> 55;
update scenario_noeud_effectif set effectif = 18 where id = 55;

/
begin
  DBMS_OUTPUT.ENABLE;
  OSE_CHARGENS.CALC_SUB_EFFECTIF(253,1,3083,66.5);
  --OSE_CHARGENS.SET_SCENARIO_NOEUD_EFFECTIF( 253, 1, 3083, 0 );
--  ose_test.echo(ose_chargens.calc_coef(3,3,3,3,5,3);
end;

/
select
ose_chargens.calc_coef(3,3,3,3,5,3)
from dual;

select * from V_CHARGENS_GRANDS_LIENS where SCENARIO_NOEUD_SUP_ID = 58;