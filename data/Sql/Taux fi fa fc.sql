SELECT
  --t1.*,
  CASE
  WHEN etr_id IS NULL THEN
    '
    INSERT INTO ELEMENT_TAUX_REGIMES (
      ID,
      ELEMENT_PEDAGOGIQUE_ID,
      TAUX_FI,
      TAUX_FC,
      TAUX_FA,
      SOURCE_ID,
      SOURCE_CODE,
      HISTO_CREATION, HISTO_CREATEUR_ID,
      HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
    ) VALUES (
      ELEMENT_TAUX_REGIMES_ID_SEQ.NEXTVAL,
      ' || ep_id || ',
      ' || TRIM(TO_CHAR(n_taux_fi,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      ' || TRIM(TO_CHAR(n_taux_fc,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      ' || TRIM(TO_CHAR(n_taux_fa,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      (SELECT id FROM source WHERE code = ''OSE''),
      q''[' || annee || '-' || ep_code || ']'',
      SYSDATE, (SELECT id FROM utilisateur WHERE username=''lecluse''),
      SYSDATE, (SELECT id FROM utilisateur WHERE username=''lecluse'')
    );'
  WHEN etr_id IS NOT NULL AND 1 = etr_actif THEN
    '
    UPDATE ELEMENT_TAUX_REGIMES SET
      SOURCE_ID = (SELECT id FROM source WHERE code = ''OSE''),
      TAUX_FI   = ' || TRIM(TO_CHAR(n_taux_fi,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      TAUX_FA   = ' || TRIM(TO_CHAR(n_taux_fa,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      TAUX_FC   = ' || TRIM(TO_CHAR(n_taux_fc,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || '
    WHERE id = ' || etr_id || ';'
  WHEN etr_id IS NOT NULL AND 0 = etr_actif THEN
    '
    UPDATE ELEMENT_TAUX_REGIMES SET
      SOURCE_ID = (SELECT id FROM source WHERE code = ''OSE''),
      TAUX_FI   = ' || TRIM(TO_CHAR(n_taux_fi,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      TAUX_FA   = ' || TRIM(TO_CHAR(n_taux_fa,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      TAUX_FC   = ' || TRIM(TO_CHAR(n_taux_fc,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      HISTO_DESTRUCTEUR_ID = NULL,
      HISTO_DESTRUCTION = NULL
    WHERE id = ' || etr_id || ';'
  END rsql_etr,
  CASE WHEN annee <> ose_parametre.get_annee_import THEN
    '
    UPDATE element_pedagogique SET
      TAUX_FI   = ' || TRIM(TO_CHAR(n_taux_fi,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      TAUX_FA   = ' || TRIM(TO_CHAR(n_taux_fa,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || ',
      TAUX_FC   = ' || TRIM(TO_CHAR(n_taux_fc,'00000000000000.00000000', 'NLS_NUMERIC_CHARACTERS=''. ''')) || '
    WHERE id = ' || ep_id || ';'
  ELSE
    ''
  END rsql_ep
FROM (
SELECT
  ep.id ep_id,
  e.id e_id,
  etr.id etr_id,
  CASE WHEN 1 = ose_divers.comprise_entre( etr.histo_creation,etr.histo_destruction ) THEN 1 ELSE 0 END etr_actif,
  s.libelle s_libelle,
  ep.annee_id annee,
  ep.source_code ep_code,
  str.libelle_court structure,
  e.source_code e_code,
  e.libelle e_libelle,
  ep.fi,ep.taux_fi,
  ep.fa,ep.taux_fa,
  ep.fc,ep.taux_fc,
  -- NOUVEAUX TAUX FI FA FC
  90 /100 n_taux_fi,
  0 /100 n_taux_fa,
  10 /100 n_taux_fc
  -- FIN DES NOUVEAUX TAUX FI FA FC
FROM
  element_pedagogique ep
  JOIN source s ON s.id = ep.source_id
  JOIN etape e ON e.id = ep.etape_id
  JOIN structure str ON str.id = ep.structure_id
  LEFT JOIN element_taux_regimes etr ON etr.element_pedagogique_id = ep.id
WHERE
  -- FILTRES
  1 = ose_divers.comprise_entre( ep.histo_creation,ep.histo_destruction )
  --AND s.code <> 'OSE'
  AND e.source_code = 'MSST13_213'
  AND ep.annee_id = 2015
  -- FIN DES FILTRES
) t1
WHERE
  taux_fi <> n_taux_fi
  OR taux_fa <> n_taux_fa
  OR taux_fc <> n_taux_fc; -- Que s'il y a des différences

     
     
     select * from element_taux_regimes where id = 44174;
     
     
     
     
     
     
     
     
select
  dep.id,
  dep.source_code,
  dep.annee_id,
  CASE WHEN dep.fi = 1 THEN 'Oui' ELSE ' ' END fi,
  CASE WHEN dep.fa = 1 THEN 'Oui' ELSE ' ' END fa,
  CASE WHEN dep.fc = 1 THEN 'Oui' ELSE ' ' END fc,
  
  ROUND(ep.taux_fi*100,2) || '%' o_taux_fi,
  ROUND(ep.taux_fa*100,2) || '%' o_taux_fa,
  ROUND(ep.taux_fc*100,2) || '%' o_taux_fc,
  
  ROUND(dep.taux_fi*100,2) || '%' n_taux_fi,
  ROUND(dep.taux_fa*100,2) || '%' n_taux_fa,
  ROUND(dep.taux_fc*100,2) || '%' n_taux_fc,
  
  eff.fi eff_fi,
  eff.fa eff_fa,
  eff.fc eff_fc
from
  v_diff_element_pedagogique dep
  JOIN ELEMENT_PEDAGOGIQUE ep on ep.id = dep.id
  LEFT JOIN effectifs eff on eff.source_code = ep.annee_id-1 || '-' || ep.source_code
where
  1=1
  AND dep.source_code like '%DUUE%';
  
SELECT * FROM ELEMENT_PEDAGOGIQUE WHERE source_code like '%DUUE%';


select source_code from etape where source_code in (
'MSST01_201',
'MSST11_211',
'MSST13_213'
) order by source_code;
     