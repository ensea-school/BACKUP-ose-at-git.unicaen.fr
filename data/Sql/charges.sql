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

SELECT
  sc.scenario_id,
  sc.type_intervention_id,
  sc.structure_id,
  sc.groupe_type_formation_id,
  sc.dedoublement
FROM
  seuil_charge sc
WHERE
  1 = OSE_DIVERS.COMPRISE_ENTRE( sc.histo_creation, sc.histo_destruction)
;

CREATE OR REPLACE VIEW V_CHARGENS_SEUILS_DED_DEF AS
WITH tisc AS (
  SELECT DISTINCT
    sc.type_intervention_id,
    sc.scenario_id
  FROM
    seuil_charge sc
  WHERE
    sc.histo_destruction IS NULL
)
SELECT
  n.id noeud_id,
--  n.structure_id structure_id,
--  tf.groupe_id groupe_type_formation_id,
--  netp.id noeud_etape_id,
  tisc.scenario_id,
  tisc.type_intervention_id,
  COALESCE(snsetp.dedoublement, sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement) dedoublement
FROM
            noeud n
       JOIN tisc ON 1=1
  LEFT JOIN element_pedagogique ep ON ep.id = n.element_pedagogique_id
       JOIN etape etp ON etp.id = COALESCE(n.etape_id, ep.etape_id)
       JOIN type_formation tf ON tf.id = etp.type_formation_id
  LEFT JOIN noeud netp ON 
    netp.etape_id = etp.id 
    AND netp.id <> n.id
    
  LEFT JOIN scenario_noeud snetp ON 
    snetp.noeud_id = netp.id 
    AND snetp.scenario_id = tisc.scenario_id 
    AND snetp.histo_destruction IS NULL
    
  LEFT JOIN scenario_noeud_seuil snsetp ON 
    snsetp.scenario_noeud_id = snetp.id 
    AND snsetp.type_intervention_id = tisc.type_intervention_id
    
  LEFT JOIN seuil_charge sc1 ON 
    sc1.histo_destruction            IS NULL
    AND sc1.scenario_id              = tisc.scenario_id
    AND sc1.type_intervention_id     = tisc.type_intervention_id
    AND sc1.structure_id             = n.structure_id
    AND sc1.groupe_type_formation_id = tf.groupe_id
    
  LEFT JOIN seuil_charge sc2 ON 
    sc2.histo_destruction            IS NULL
    AND sc2.scenario_id              = tisc.scenario_id
    AND sc2.type_intervention_id     = tisc.type_intervention_id
    AND sc2.structure_id             = n.structure_id
    AND sc2.groupe_type_formation_id IS NULL
    
  LEFT JOIN seuil_charge sc3 ON 
    sc3.histo_destruction            IS NULL
    AND sc3.scenario_id              = tisc.scenario_id
    AND sc3.type_intervention_id     = tisc.type_intervention_id
    AND sc3.structure_id             IS NULL
    AND sc3.groupe_type_formation_id = tf.groupe_id
    
  LEFT JOIN seuil_charge sc4 ON 
    sc4.histo_destruction            IS NULL
    AND sc4.scenario_id              = tisc.scenario_id
    AND sc4.type_intervention_id     = tisc.type_intervention_id
    AND sc4.structure_id             IS NULL
    AND sc4.groupe_type_formation_id IS NULL
WHERE
  n.histo_destruction IS NULL
  AND COALESCE(snsetp.dedoublement, sc1.dedoublement, sc2.dedoublement, sc3.dedoublement, sc4.dedoublement)  is not null
;


select * from seuil_charge where histo_destruction is null;

select * from v_chargens_seuils_ded_def;

select code, libelle, annee_id, source_code from noeud where structure_id is null;
delete from lien where source_id = 2;
delete from tbl_lien
;


CREATE OR REPLACE VIEW SRC_VOLUME_HORAIRE_ENS AS
SELECT
  null id,
  ep.id element_pedagogique_id,
  ti.id type_intervention_id,
  vhe.heures,
  s.id source_id,
  vhe.source_code source_code
FROM 
  ose_volume_horaire_ens@apoprod vhe
  JOIN source s ON s.code = 'Apogee'
  LEFT JOIN element_pedagogique ep ON ep.annee_id = vhe.annee_id AND ep.source_code = vhe.z_element_pedagogique_id
  LEFT JOIN type_intervention ti ON ti.code = vhe.z_type_intervention_id
;
select * from SRC_VOLUME_HORAIRE_ENS;
select * from src_volume_horaire_ens where annee_id = 2016;

SELECT
  ep.source_code code,
  ep.libelle,
  ep.z_etape_id,
  ep.z_structure_id,
  ep.z_periode_id,
  CASE WHEN ep.fi+ep.fa+ep.fc=0 THEN 1 ELSE ep.fi END fi,
  ep.fc,ep.fa,
  ep.taux_foad,
  s.id source_id,
  ep.source_code,
  --ep.annee_id,
  ep.z_discipline_id,ep.*
FROM
  ose_element_pedagogique@apoprod ep
  JOIN source s ON s.code = 'Apogee'

;

SELECT
  e.source_code code,
  e.libelle,
  e.z_type_formation_id,
  to_number(e.niveau) niveau,
  e.specifique_echanges,
  e.z_structure_id,
  e.domaine_fonctionnel z_domaine_fonctionnel_id,
  s.id source_id,
  e.source_code
FROM
  ose_etape@apoprod e
  JOIN source s ON s.code = 'Apogee'
;
select * from noeud where structure_id is null and histo_destruction is null;


SELECT
  null id,
  n.code code,
  n.libelle_court libelle,
  n.liste liste,
  to_number(n.annee_id) annee_id,
  e.id etape_id,
  ep.id element_pedagogique_id,
  COALESCE(str.structure_niv2_id,str.id) structure_id,
  s.id source_id,
  n.z_source_code source_code
FROM 
  ose_noeud@apoprod n
  JOIN source s ON s.code = 'Apogee'
  LEFT JOIN etape e ON e.source_code = n.z_etape_id AND e.annee_id = n.annee_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = n.z_element_pedagogique_id AND ep.annee_id = n.annee_id
  LEFT JOIN structure str ON str.source_code = n.z_structure_id
where
  n.code = '3LPHS1'
  --COALESCE(str.structure_niv2_id,str.id) is null
;















SELECT
  gl.noeud_inf_id             noeud_id,
  snsup.scenario_id           scenario_id,
  sninf.id                    scenario_noeud_id,
  sne.type_heures_id          type_heures_id,
  sne.etape_id                etape_id,
--  sne.effectif                effectif,
--  slsup.choix_minimum         choix_minimum,
--  slsup.choix_maximum         choix_maximum,
--  COALESCE(slinf.poids,1)     poids,
--  COALESCE(sninf.assiduite,1) assiduite,
--  MIN(COALESCE(sl.poids,1))   min_poids,
--  MAX(COALESCE(sl.poids,1))   max_poids,
--  SUM(COALESCE(sl.poids,1))   total_poids,
--  COUNT(*)                    nb_choix,
  OSE_CHARGENS.CALC_COEF(
    slsup.choix_minimum, 
    slsup.choix_maximum, 
    COALESCE(slinf.poids,1), 
    MAX(COALESCE(sl.poids,1)), 
    SUM(COALESCE(sl.poids,1)), 
    COUNT(*)
  ) * COALESCE(sninf.assiduite,1) * sne.effectif effectif
FROM
            v_chargens_grands_liens  gl
       JOIN scenario_noeud        snsup ON snsup.noeud_id = gl.noeud_sup_id 
                                       AND snsup.histo_destruction IS NULL

       JOIN scenario_noeud_effectif sne ON sne.scenario_noeud_id = snsup.id

  LEFT JOIN scenario_lien         slsup ON slsup.histo_destruction IS NULL 
                                       AND slsup.lien_id = gl.lien_sup_id
                                       AND slsup.scenario_id = snsup.scenario_id

  LEFT JOIN scenario_lien         slinf ON slinf.histo_destruction IS NULL 
                                       AND slinf.lien_id = gl.lien_inf_id
                                       AND slinf.scenario_id = snsup.scenario_id
                                       
  LEFT JOIN scenario_noeud        sninf ON sninf.noeud_id = gl.noeud_inf_id
                                       AND sninf.scenario_id = snsup.scenario_id
                                       AND sninf.histo_destruction IS NULL

       JOIN lien                      l ON l.noeud_sup_id = gl.noeud_liste_id 
                                       AND l.histo_destruction IS NULL

  LEFT JOIN scenario_lien            sl ON sl.lien_id = l.id 
                                       AND sl.scenario_id = snsup.scenario_id 
                                       AND sl.histo_destruction IS NULL

WHERE
  (slsup.actif = 1 OR slsup.actif IS NULL)
  AND (slinf.actif = 1 OR slinf.actif IS NULL)
  AND (sl.actif = 1 OR sl.actif IS NULL)
  AND (snsup.scenario_id = 0 OR NULL IS NULL)
  AND (sne.type_heures_id = 0 OR NULL IS NULL)
  AND (sne.etape_id = 0 OR NULL IS NULL)
  
  and gl.noeud_inf_id = 28672
GROUP BY
  gl.noeud_sup_id,
  gl.noeud_inf_id,
  snsup.scenario_id,
  sninf.id,
  sne.type_heures_id,
  sne.etape_id,
  sne.effectif,
  slsup.choix_minimum,
  slsup.choix_maximum,
  slinf.poids,
  sninf.assiduite
;



--create or replace view v_chargens_precalcul_heures AS
WITH sne AS (
  SELECT
    sne.scenario_noeud_id,
    sne.etape_id,
    SUM(sne.effectif) effectif
  FROM
    scenario_noeud_effectif sne
  GROUP BY
    sne.scenario_noeud_id,
    sne.etape_id
)
SELECT
  nep.id            noeud_ep_id,
  netp.id           noeud_etape_id,
  ep.id             element_pedagogique_id,
  ep.etape_id       etape_id,
  ep.structure_id   structure_id,
  ti.id             type_intervention_id,
  csdd.scenario_id  scenario_id,
  COALESCE(sns.ouverture, 1) ouverture,
  COALESCE(sns.dedoublement, csdd.dedoublement,1) dedoublement,
  COALESCE(sne.effectif,0) effectif,
  --CASE WHEN COALESCE(sne.effectif,0) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
  --  CEIL( COALESCE(sne.effectif,0) / COALESCE(sns.dedoublement, csdd.dedoublement,1) )
  --END groupes,
  CASE WHEN COALESCE(sne.effectif,0) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0) / COALESCE(sns.dedoublement, csdd.dedoublement,1) )
  END * vhe.heures                        heures,
  CASE WHEN COALESCE(sne.effectif,0) < COALESCE(sns.ouverture, 1) THEN 0 ELSE
    CEIL( COALESCE(sne.effectif,0) / COALESCE(sns.dedoublement, csdd.dedoublement,1) )
  END * vhe.heures * ti.taux_hetd_service hetd
FROM
            noeud                      nep
       JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = nep.element_pedagogique_id 
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0

       JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id
       JOIN element_pedagogique         ep ON ep.id = vhe.element_pedagogique_id
  LEFT JOIN noeud                     netp ON netp.etape_id = ep.etape_id
  LEFT JOIN v_chargens_seuils_ded_def csdd ON csdd.noeud_id = nep.id 
                                          AND csdd.type_intervention_id = ti.id

  LEFT JOIN scenario_noeud              sn ON sn.noeud_id = nep.id 
                                          AND sn.scenario_id = csdd.scenario_id

  LEFT JOIN scenario_noeud_seuil       sns ON sns.scenario_noeud_id = sn.id 
                                          AND sns.type_intervention_id = ti.id

  LEFT JOIN                            sne ON sne.scenario_noeud_id = sn.id 
                                          AND sne.etape_id = ep.etape_id
WHERE
  nep.histo_destruction IS NULL
  AND 40139 = nep.id
;







select * from volume_horaire_ens WHERE element_pedagogique_id = 51026;
delete from volume_horaire_ens where source_id = 2;






select * from V_CHARGENS_PRECALCUL_HEURES where noeud_ep_id = 40139;


select * from noeud where code = 'DRLE999';

select * from volume_horaire_ens where element_pedagogique_id = 52033;








