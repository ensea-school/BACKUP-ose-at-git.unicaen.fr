-- CALCUL récursif de TOUS les effectifs
SELECT 
  'BEGIN ose_chargens.CALC_SUB_EFFECTIF2( ' || sn.noeud_id || ', ' || sn.scenario_id || ', ' || sne.type_heures_id || ', ' || sne.etape_id || ' ); END;' 
  || '
/' plsql
FROM 
  scenario_noeud_effectif sne
  JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
  JOIN noeud n ON n.id = sn.noeud_id
WHERE
  n.etape_id IS NOT NULL
;



/
BEGIN
  
  FOR p IN (

      SELECT 
        sn.noeud_id,
        sn.scenario_id,
        sne.type_heures_id,
        sne.etape_id
      FROM 
        scenario_noeud_effectif sne
        JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
        JOIN noeud n ON n.id = sn.noeud_id
      WHERE
        n.etape_id IS NOT NULL

    ) LOOP
      
      ose_chargens.CALC_SUB_EFFECTIF2( p.noeud_id, p.scenario_id, p.type_heures_id, p.etape_id );
      
      --ose_test.echo( 'Calcul global des effectifs : ' || p.rownum || ' / ' || p.nbr );
    END LOOP;
  
END;

/

      SELECT 
        sn.noeud_id,
        sn.scenario_id,
        sne.type_heures_id,
        sne.etape_id,
        rownum, 
        count(*) over (partition by rownum) nbr
      FROM 
        scenario_noeud_effectif sne
        JOIN scenario_noeud sn ON sn.id = sne.scenario_noeud_id
        JOIN noeud n ON n.id = sn.noeud_id
      WHERE
        n.etape_id IS NOT NULL
        ;

select * from noeud where code = '2MSOC1_711';
select 
  n.id n_id, n.code, n.libelle, ti.code ti_code, vhe.heures
from 
  noeud n
  LEFT JOIN element_pedagogique ep on ep.id = n.element_pedagogique_id
  LEFT JOIN volume_horaire_ens         vhe ON vhe.element_pedagogique_id = ep.id
                                          AND vhe.histo_destruction IS NULL 
                                          AND vhe.heures > 0 
  LEFT JOIN type_intervention           ti ON ti.id = vhe.type_intervention_id 
where 
  n.id IN (257933,239198,244766,246574)
ORDER BY
  code, ti_code;



--CREATE OR REPLACE FORCE VIEW SRC_NOEUD AS 
SELECT
  null id,
  s.id source_id,
  n.z_source_code source_code
FROM 
  ose_noeud@apoprod n
  JOIN source s ON s.code = 'Apogee'
  LEFT JOIN etape e ON e.source_code = n.z_etape_id AND e.annee_id = n.annee_id
  LEFT JOIN element_pedagogique ep ON ep.source_code = n.z_element_pedagogique_id AND ep.annee_id = n.annee_id
  LEFT JOIN structure str ON str.source_code = n.z_structure_id;


CREATE OR REPLACE FORCE VIEW SRC_SCENARIO_LIEN AS 
SELECT
  null                            id,
  s.id                            scenario_id,
  li.id                           lien_id,
  1                               actif,
  1                               poids,
  l.choix_minimum                 choix_minimum,
  l.choix_maximum                 choix_maximum,
  src.id                          source_id,
  l.z_source_code || '_s' || s.id source_code
FROM
  ose_lien@apoprod l
  JOIN source src ON src.code = 'Apogee'
  JOIN scenario s ON 1 = OSE_DIVERS.COMPRISE_ENTRE(s.histo_creation, s.histo_destruction) AND s.type = 1 -- initial;
  JOIN lien li ON li.source_code = l.z_source_code
  LEFT JOIN scenario_lien sl ON sl.lien_id = li.id 
                            AND sl.scenario_id = s.id
                            AND 1 = OSE_DIVERS.COMPRISE_ENTRE( sl.histo_creation, sl.histo_destruction)
                            AND sl.source_id <> src.id
WHERE
  (l.choix_minimum IS NOT NULL OR l.choix_maximum IS NOT NULL)
  AND sl.id IS NULL
;
  select * from scenario_lien