CREATE OR REPLACE FORCE VIEW SRC_CENTRE_COUT AS
WITH sifac_query AS (
  SELECT DISTINCT
    TRIM(B.ktext) libelle,
    CASE
      WHEN a.kostl like '%A' THEN 'accueil' -- Activité (au sens compta analytique) ne devant pas permettre la saisie de référentiel
      WHEN a.kostl like '%B' THEN 'enseignement'
      WHEN a.kostl like '%M' THEN 'pilotage'
    END z_activite_id,
    CASE
      WHEN LENGTH(a.kostl) = 5 THEN 'paie-etat'
      WHEN LENGTH(a.kostl) > 5 THEN 'ressources-propres'
    END z_type_ressource_id,
    substr( A.kostl, 2, 3 ) unite_budgetaire,
    NULL z_parent_id,
    'SIFAC' z_source_id,
    A.kostl source_code

  FROM
    sapsr3.csks@sifacp A,
    sapsr3.cskt@sifacp B
  WHERE
      A.kostl=B.kostl(+)
      and A.kokrs=B.kokrs(+)
      and B.mandt(+)='500'
      and B.spras(+)='F'
      and A.kokrs='1010'
      and A.bkzkp !='X'
      and a.kostl LIKE 'P%' AND (a.kostl like '%A' OR a.kostl like '%B' OR a.kostl like '%M')
      AND SYSDATE BETWEEN to_date( NVL(A.datab,'10661231'), 'YYYYMMDD')-30 AND to_date( NVL(A.datbi,'99991231'), 'YYYYMMDD')

  UNION

  SELECT
    TRIM(A.post1) libelle,
    CASE
      WHEN a.fkstl like '%A' THEN 'accueil'
      WHEN a.fkstl like '%B' THEN 'enseignement'
      WHEN a.fkstl like '%M' THEN 'pilotage'
    END z_activite_id,
    CASE
      WHEN LENGTH(a.fkstl) = 5 THEN 'paie-etat'
      WHEN LENGTH(a.fkstl) > 5 THEN 'ressources-propres'
    END z_type_ressource_id,
    substr( A.fkstl, 2, 3 ) unite_budgetaire,
    A.fkstl z_parent_id,
    'SIFAC' z_source_id,
    A.posid source_code
  FROM
    sapsr3.prps@sifacp A,
    sapsr3.prte@sifacp B
  WHERE
    A.pspnr=B.posnr(+)
    AND A.pkokr='1010'
    AND B.mandt(+)='500'
    AND a.fkstl LIKE 'P%' AND (a.fkstl like '%A' OR a.fkstl like '%B' OR a.fkstl like '%M')
    AND SYSDATE BETWEEN to_date( NVL(B.pstrt,'10661231'), 'YYYYMMDD')-30 AND to_date( NVL(B.pende,'99991231'), 'YYYYMMDD')

  UNION

  SELECT
    TRIM(A.post1) libelle,
    'enseignement' z_activite_id,
    'ressources-propres' z_type_ressource_id,
    substr( A.fkstl, 2, 3 ) unite_budgetaire,
    null z_parent_id,
    'SIFAC' z_source_id,
    A.posid source_code
  FROM
    sapsr3.prps@sifacp A,
    sapsr3.prte@sifacp B
  WHERE
    A.pspnr=B.posnr(+)
    and A.pkokr='1010'
    and B.mandt(+)='500'
    AND (
      A.posid IN ('P950FCFCR', 'P950FCFFR')
    )
    AND SYSDATE BETWEEN to_date( NVL(B.pstrt,'10661231'), 'YYYYMMDD')-30 AND to_date( NVL(B.pende,'99991231'), 'YYYYMMDD')
)
SELECT
  code,
  libelle,
  activite_id,
  type_ressource_id,
  unite_budgetaire,
  poids,
  parent_id,
  source_id,
  source_code
FROM
  (
  SELECT
    sq.source_code                                                      code,
    sq.libelle                                                          libelle,
    a.id                                                                activite_id,
    tr.id                                                               type_ressource_id,
    sq.unite_budgetaire                                                 unite_budgetaire,
    ROW_NUMBER() OVER (PARTITION BY sq.source_code ORDER BY sq.libelle) poids,
    cc.id                                                               parent_id,
    src.id                                                              source_id,
    sq.source_code                                                      source_code
  FROM
              sifac_query    sq
         JOIN source        src ON src.code       = sq.z_source_id
    LEFT JOIN cc_activite     a ON a.code         = sq.z_activite_id
    LEFT JOIN type_ressource tr ON tr.code        = sq.z_type_ressource_id
    LEFT JOIN centre_cout    cc ON cc.source_code = sq.z_parent_id
  WHERE
    sq.z_activite_id IS NOT NULL
) cc
WHERE
  poids = 1;