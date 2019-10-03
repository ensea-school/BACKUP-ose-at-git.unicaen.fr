# Changement d'année universitaire

Lors du changement d'année universitaire, il est parfois nécessaire de prolonger une offre de formation 
ou bien de transférer des paramétrages de l'année courante vers la future année.

Une interface de configuration sera proposée à terme dans OSE pour réaliser ces tâches.
Pour le moment, le transfert de données d'une année sur l'autre doit se faire par le biais des requêtes fournies ci-dessous.

```sql
-- Liste des éléments et étapes concernées
WITH v AS (SELECT 

        -- année à reverser (=2017 pour 2017/2018)
        2018 annee,

        -- Source à dupliquer
        'OSE' source,
        
        -- Eléments
        --NULL elements_codes -- à utiliser si pas de filtre par code
        '
        
CODE.1
CODE.2
AUTRE_CODE

' elements_codes -- à utiliser pour ne transférer que certains codes

FROM dual)
SELECT
  str.libelle_court composante,
  e.code etape_code,
  e.libelle etape_libelle,
  ep.code element_code,
  ep.libelle element_libelle
FROM
  v
  JOIN source s ON s.code = v.source
  JOIN element_pedagogique ep ON ep.source_id = s.id AND ep.annee_id = v.annee AND ep.histo_destruction IS NULL
  JOIN etape e ON e.id = ep.etape_id
  JOIN structure str ON str.id = ep.structure_id
WHERE
  v.elements_codes IS NULL OR '
' || v.elements_codes || '
' LIKE '%
' || ep.code || '
%'
ORDER BY
  composante, etape_code, element_code
;



-- activer l'année
-- MAJ des paramètres généraux


-- Opération à répéter plusieurs fois, tant que des requêtes sont générées, car des dépendances entre données
-- 
WITH v AS (SELECT p.*, p.annee annee_id, u.id utilisateur_id, s.id source_id FROM (SELECT 
        
        -- année à reverser (=2017 pour 2017/2018)
        2018 annee,
        
        -- Source à dupliquer
        'OSE' source,
        
        -- Utilisateur créateur
        'lecluse' utilisateur,

        -- filtres (1 ou 0)        
        1 etapes,
        1 elements,
        1 chemins,
        1 volumes_horaires_ens,
        0 modulateurs,
        0 centres_couts,
        
        -- Eléments
        -- NULL elements_codes -- à utiliser si pas de filtre par code
        '
CODE.1
CODE.2
AUTRE_CODE

        ' elements_codes -- à utiliser pour ne transférer que certains codes

FROM dual) p JOIN utilisateur u ON u.username = p.utilisateur JOIN source s ON s.code = p.source)
SELECT * FROM (
SELECT '-- Passage de ' || v.annee_id || ' à ' || (v.annee_id+1) || ' --' isql FROM v





UNION ALL SELECT '------------------------- étapes -------------------------' isql FROM dual UNION ALL
SELECT DISTINCT CASE WHEN e2.id IS NULL THEN '
INSERT INTO etape (
  id,
  libelle,
  type_formation_id,
  niveau,
  specifique_echanges,
  structure_id,
  source_id, source_code,
  histo_creation, histo_createur_id,
  histo_modification, histo_modificateur_id,
  domaine_fonctionnel_id,
  annee_id,
  code
) VALUES (
  etape_id_seq.nextval,
  q''[' || e.libelle || ']'',
  ' || COALESCE(to_char(e.type_formation_id),'NULL') || ',
  ' || COALESCE(to_char(e.niveau),'NULL') || ',
  ' || COALESCE(to_char(e.specifique_echanges),'NULL') || ',
  ' || COALESCE(to_char(e.structure_id),'NULL') || ',
  ' || e.source_id || ', q''[' || e.source_code || ']'',
  SYSDATE, ' || v.utilisateur_id || ',
  SYSDATE, ' || v.utilisateur_id || ',
  ' || COALESCE(to_char(e.domaine_fonctionnel_id),'NULL') || ',
  ' || (v.annee_id + 1) || ',
  q''[' || e.code || ']''
);' ELSE '-- CODE ' || e2.code || ' existant' END isql
FROM
  v
  JOIN etape e ON e.annee_id = v.annee_id AND e.source_id = v.source_id AND e.histo_destruction IS NULL
  JOIN element_pedagogique ep ON ep.etape_id = e.id AND ep.histo_destruction IS NULL AND (v.elements_codes IS NULL OR '
' || v.elements_codes || '
' LIKE '%
' || ep.code || '
%'
  )
  LEFT JOIN etape e2 ON e2.code = e.code AND e2.annee_id = e.annee_id+1 AND e2.histo_destruction IS NULL
WHERE
  v.etapes = 1





UNION ALL SELECT '------------------------- éléments pédagogiques -------------------------' isql FROM dual UNION ALL
SELECT CASE WHEN ep2.id IS NULL THEN '
  INSERT INTO element_pedagogique (
    ID,
    CODE,
    LIBELLE,
    ETAPE_ID,
    STRUCTURE_ID,
    PERIODE_ID,
    TAUX_FI, TAUX_FC, TAUX_FA,
    TAUX_FOAD,
    FI, FC, FA,
    SOURCE_ID, SOURCE_CODE,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID,
    ANNEE_ID,
    DISCIPLINE_ID
  ) VALUES (
    element_pedagogique_id_seq.nextval,
    q''[' || ep.code || ']'',
    q''[' || ep.libelle || ']'',
    ' || e2.id || ',
    ' || ep.structure_id || ',
    ' || NVL(to_char(ep.periode_id), 'NULL') || ',
    ' || TRIM(TO_CHAR(ep.taux_fi, '999.99')) || ', ' || TRIM(TO_CHAR(ep.taux_fc, '999.99')) || ', ' || TRIM(TO_CHAR(ep.taux_fa, '999.99')) || ',
    ' || TRIM(TO_CHAR(ep.taux_foad, '999.99')) || ',
    ' || TRIM(TO_CHAR(ep.fi, '999.99')) || ', ' || TRIM(TO_CHAR(ep.fc, '999.99')) || ', ' || TRIM(TO_CHAR(ep.fa, '999.99')) || ',
    ' || v.source_id || ', q''[' || ep.source_code || ']'',
    SYSDATE, ' || v.utilisateur_id || ',
    SYSDATE, ' || v.utilisateur_id || ',
    ' || (v.annee_id + 1) || ',
    ' || NVL(to_char(ep.discipline_id), 'NULL') || '
  );
  ' ELSE '-- CODE ' || ep2.code || ' existant' END isql
FROM
  v
  JOIN element_pedagogique ep ON ep.annee_id = v.annee_id AND ep.source_id = v.source_id AND ep.histo_destruction IS NULL
  JOIN etape e ON e.id = ep.etape_id
  JOIN etape e2 ON e2.code = e.code AND e2.annee_id = e.annee_id+1 AND e2.histo_destruction IS NULL
  LEFT JOIN element_pedagogique ep2 ON ep2.code = ep.code AND ep2.annee_id = ep.annee_id+1 AND ep2.histo_destruction IS NULL
WHERE
  v.elements = 1
  AND (v.elements_codes IS NULL OR '
' || v.elements_codes || '
' LIKE '%
' || ep.code || '
%'
  )





UNION ALL SELECT '------------------------- chemins pédagogiques -------------------------' isql FROM dual UNION ALL
SELECT CASE WHEN cp2.id IS NULL THEN '
  INSERT INTO chemin_pedagogique(
    ID,
    ELEMENT_PEDAGOGIQUE_ID,
    ETAPE_ID,
    ORDRE,
    SOURCE_ID, SOURCE_CODE,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
  ) VALUES (
    chemin_pedagogique_id_seq.nextval,
    ' || ep2.id || ',
    ' || e2.id || ',
    ' || rownum || ',
    ' || v.source_id || ', q''[' || e2.source_code || '_' || ep2.source_code || '_' || (v.annee_id + 1) || ']'',
    SYSDATE, ' || v.utilisateur_id || ',
    SYSDATE, ' || v.utilisateur_id || '
  );

  ' ELSE '-- chemin d''élément CODE ' || ep2.code || ', étape CODE ' || e2.code || ' existant' END isql
FROM
  v
  JOIN chemin_pedagogique cp ON cp.source_id = v.source_id AND cp.histo_destruction IS NULL
  JOIN element_pedagogique ep ON ep.id = cp.element_pedagogique_id AND ep.annee_id = v.annee_id
  JOIN etape e ON e.id = cp.etape_id
  JOIN element_pedagogique ep2 ON ep2.code = ep.code AND ep2.annee_id = ep.annee_id+1 AND ep2.histo_destruction IS NULL
  JOIN etape e2 ON e2.code = e.code AND e2.annee_id = e.annee_id+1 AND e2.histo_destruction IS NULL
  LEFT JOIN chemin_pedagogique cp2 ON cp2.etape_id = e2.id AND cp2.element_pedagogique_id = ep2.id AND cp2.histo_destruction IS NULL
WHERE
  v.chemins = 1
  AND (v.elements_codes IS NULL OR '
' || v.elements_codes || '
' LIKE '%
' || ep.code || '
%'
  )





UNION ALL SELECT '------------------------- volumes_horaire_ens -------------------------' isql FROM dual UNION ALL
SELECT CASE WHEN vhe2.id IS NULL THEN '
INSERT INTO volume_horaire_ens (
  id,
  type_intervention_id,
  heures,
  source_id, source_code,
  histo_creation, histo_createur_id,
  histo_modification, histo_modificateur_id,
  element_pedagogique_id,
  groupes
) VALUES (
  volume_horaire_ens_id_seq.nextval,
  ' || vhe.type_intervention_id || ',
  ' || vhe.heures || ',
  ' || v.source_id || ', q''[' || ep2.source_code || '_' || vhe.type_intervention_id || '_' || (v.annee_id + 1) || ']'',
  SYSDATE, ' || v.utilisateur_id || ',
  SYSDATE, ' || v.utilisateur_id || ',
  ' || ep2.id || ',
  ' || COALESCE(to_char(vhe.groupes),'NULL') || '
);
' ELSE '-- VHE d''élément CODE ' || ep2.code || ', TI ID ' || vhe.type_intervention_id || ' existant' END isql
FROM
  v
  JOIN volume_horaire_ens vhe ON vhe.source_id = v.source_id AND vhe.histo_destruction IS NULL
  JOIN element_pedagogique ep ON ep.id = vhe.element_pedagogique_id AND ep.annee_id = v.annee_id
  JOIN element_pedagogique ep2 ON ep2.code = ep.code AND ep2.annee_id = ep.annee_id+1 AND ep2.histo_destruction IS NULL
  LEFT JOIN volume_horaire_ens vhe2 ON vhe2.element_pedagogique_id = ep2.id AND vhe2.type_intervention_id = vhe.type_intervention_id AND vhe2.histo_destruction IS NULL
WHERE
  v.volumes_horaires_ens = 1
  AND (v.elements_codes IS NULL OR '
' || v.elements_codes || '
' LIKE '%
' || ep.code || '
%'
  )




UNION ALL SELECT '------------------------- modulateurs -------------------------' isql FROM dual UNION ALL
SELECT CASE WHEN em2.id IS NULL THEN '
INSERT INTO ELEMENT_MODULATEUR(
    ID,
    ELEMENT_ID,
    MODULATEUR_ID,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
    ELEMENT_MODULATEUR_ID_SEQ.NEXTVAL,
    ' || ep2.id || ',
    ' || em.modulateur_id || ',
    SYSDATE, ' || v.utilisateur_id || ',
    SYSDATE, ' || v.utilisateur_id || '
);' ELSE '-- EM d''élément CODE ' || ep2.code || ', Modulateur ID ' || em.modulateur_id || ' existant' END isql
FROM
  v
  JOIN element_modulateur    em  ON em.histo_destruction IS NULL
  JOIN element_pedagogique   ep  ON ep.id = em.element_id AND ep.annee_id = v.annee_id
  JOIN element_pedagogique  ep2  ON ep2.code = ep.code AND ep2.annee_id = ep.annee_id + 1 AND ep2.histo_destruction IS NULL
  LEFT JOIN element_modulateur em2 ON em2.element_id = ep2.id AND em2.modulateur_id = em.modulateur_id AND em2.histo_destruction IS NULL
WHERE
  v.modulateurs = 1
  AND (v.elements_codes IS NULL OR '
' || v.elements_codes || '
' LIKE '%
' || ep.code || '
%'
  )
  




UNION ALL SELECT '------------------------- centres de coûts -------------------------' isql FROM dual UNION ALL
SELECT CASE WHEN cce2.id IS NULL THEN '
INSERT INTO CENTRE_COUT_EP(
    ID,
    CENTRE_COUT_ID,
    ELEMENT_PEDAGOGIQUE_ID,
    TYPE_HEURES_ID,
    SOURCE_ID,
    SOURCE_CODE,
    HISTO_CREATION, HISTO_CREATEUR_ID,
    HISTO_MODIFICATION, HISTO_MODIFICATEUR_ID
)VALUES(
    CENTRE_COUT_EP_ID_SEQ.NEXTVAL,
    ' || cce.centre_cout_id || ',
    ' || ep2.id || ',
    ' || CCE.type_heures_id || ',
    ' || CCE.source_id || ',
    ''N-1toN_'' || CENTRE_COUT_EP_ID_SEQ.CURRVAL,
    SYSDATE, ' || v.utilisateur_id || ',
    SYSDATE, ' || v.utilisateur_id || '
);' ELSE '-- EM d''élément CODE ' || ep2.code || ', Centre coûts ID ' || cce.centre_cout_id || ' existant' END isql
FROM
  v
  JOIN centre_cout_ep       cce  ON cce.histo_destruction IS NULL
  JOIN element_pedagogique   ep  ON ep.id = cce.element_pedagogique_id AND ep.annee_id = v.annee_id
  JOIN element_pedagogique  ep2  ON ep2.code = ep.code AND ep2.annee_id = ep.annee_id+1 AND ep2.histo_destruction IS NULL
  LEFT JOIN centre_cout_ep cce2  ON cce2.centre_cout_id = cce.centre_cout_id 
                                AND cce2.element_pedagogique_id = cce.element_pedagogique_id 
                                AND cce2.type_heures_id = cce.type_heures_id 
                                AND cce2.histo_destruction IS NULL
WHERE
  v.centres_couts = 1
  AND (v.elements_codes IS NULL OR '
' || v.elements_codes || '
' LIKE '%
' || ep.code || '
%'
  )
  
) t
```