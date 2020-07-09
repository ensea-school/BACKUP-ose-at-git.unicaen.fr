CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_PEDAGOGIQUE AS
WITH apogee_query AS (
  SELECT
    ep.source_code code,
    ep.libelle,
    ep.z_etape_id,
    ep.z_structure_id,
    ep.z_periode_id,
    CASE WHEN ep.fi+ep.fa+ep.fc=0 THEN 1 ELSE ep.fi END fi,
    ep.fc,
    ep.fa,
    ep.taux_foad,
    'Apogee' z_source_id,
    ep.source_code,
    TO_NUMBER(ep.annee_id) annee_id,
    ep.z_discipline_id
  FROM
    ose_element_pedagogique@apoprod ep
)
SELECT
  aq.code,
  aq.libelle,
  etp.id etape_id,
  str.id structure_id,
  per.id periode_id,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fi( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fi,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fc( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fc( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fc,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fa( etr.taux_fi, etr.taux_fc, etr.taux_fa, aq.fi, aq.fc, aq.fa )
    ELSE ose_divers.calcul_taux_fa( aq.fi, aq.fc, aq.fa, aq.fi, aq.fc, aq.fa )
  END taux_fa,
  aq.taux_foad,
  aq.fc,
  aq.fi,
  aq.fa,
  s.id source_id,
  aq.source_code,
  aq.annee_id,
  NVL( d.id, d99.id ) discipline_id
FROM
            apogee_query aq
       JOIN source                       s ON s.code                     = aq.z_source_id
  LEFT JOIN etape                      etp ON etp.source_code            = aq.z_etape_id
                                          AND etp.annee_id               = aq.annee_id
  LEFT JOIN SRC_HARPEGE_STRUCTURE_CODES sc ON sc.c_structure             = aq.z_structure_id
  LEFT JOIN structure                  str ON str.source_code            = sc.c_structure_n2
  LEFT JOIN periode                    per ON per.libelle_court          = aq.z_periode_id
  LEFT JOIN element_pedagogique         ep ON ep.source_code             = aq.source_code
                                          AND ep.annee_id                = aq.annee_id
  LEFT JOIN element_taux_regimes       etr ON etr.element_pedagogique_id = ep.id
                                          AND etr.histo_destruction      IS NULL
  LEFT JOIN discipline                 d99 ON d99.source_code            = '99'
  LEFT JOIN discipline                   d ON ',' || d.CODES_CORRESP_1 || ',' LIKE '%,' || NVL(aq.z_discipline_id,'00') || ',%'
                                          AND d.histo_destruction        IS NULL