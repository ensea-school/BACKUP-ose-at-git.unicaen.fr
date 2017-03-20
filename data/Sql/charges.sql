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
  
  
  noeud
      - structure
      - etape(s)
      - element
      - 