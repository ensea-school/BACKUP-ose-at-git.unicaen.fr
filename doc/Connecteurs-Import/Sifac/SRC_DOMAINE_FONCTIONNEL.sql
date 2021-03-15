CREATE OR REPLACE FORCE VIEW SRC_DOMAINE_FONCTIONNEL AS
WITH sifac_query AS (
  SELECT
    B.fkbtx libelle,
    'SIFAC' z_source_id,
    A.fkber source_code
  FROM
    sapsr3.tfkb@sifacp A,
    sapsr3.tfkbt@sifacp B
  WHERE
    A.mandt=B.mandt
    AND A.fkber=B.fkber
    AND B.SPRAS='F'
    AND A.mandt='500'
    AND SYSDATE BETWEEN to_date( NVL(A.datab,'10661231'), 'YYYYMMDD') AND to_date( NVL(A.datbis,'99991231'), 'YYYYMMDD')
    AND a.fkber IN ('D101', 'D102', 'D103', 'D1053', 'D106', 'D107', 'D108', 'D109', 'D110', 'D111', 'D112', 'D1132', 'D1153', 'D1011', 'D203')
)
SELECT
  sq.libelle     libelle,
  s.id           source_id,
  sq.source_code source_code
FROM
       sifac_query sq
  JOIN source       s ON s.code = sq.z_source_id;
