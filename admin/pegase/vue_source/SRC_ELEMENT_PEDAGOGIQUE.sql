CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_PEDAGOGIQUE AS
SELECT
  pep.code                  code,
  pep.libelle               libelle,
  a.id                     annee_id,
  e.id                     etape_id,
  str.id                   structure_id,
  p.id                     periode_id,
  pep.taux_foad             taux_foad,
  pep.fi                    fi,
  pep.fc                    fc,
  pep.fa                    fa,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, pep.fi, pep.fc, pep.fa )
    ELSE ose_divers.calcul_taux_fi( pep.fi, pep.fc, pep.fa, pep.fi, pep.fc, pep.fa )
  END                      taux_fi,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fc( etr.taux_fi, etr.taux_fc, etr.taux_fa, pep.fi, pep.fc, pep.fa )
    ELSE ose_divers.calcul_taux_fc( pep.fi, pep.fc, pep.fa, pep.fi, pep.fc, pep.fa )
  END                      taux_fc,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fa( etr.taux_fi, etr.taux_fc, etr.taux_fa, pep.fi, pep.fc, pep.fa )
    ELSE ose_divers.calcul_taux_fa( pep.fi, pep.fc, pep.fa, pep.fi, pep.fc, pep.fa )
  END                      taux_fa,
  COALESCE(d.id, d99.id) discipline_id,
  s.id                     source_id,
  pep.source_code           source_code
FROM
            peg_element_pedagogique                   pep
       JOIN annee a                        ON a.id BETWEEN pep.annee_debut AND pep.annee_fin
  LEFT JOIN etape                        e ON e.source_code = pep.z_etape_id
  LEFT JOIN structure                  str ON str.autre_1 = pep.z_structure_id
  LEFT JOIN periode                      p ON p.code = pep.z_periode_id
  LEFT JOIN element_pedagogique         ep ON ep.source_code             = pep.source_code
                                          AND ep.annee_id                = a.id
  LEFT JOIN element_taux_regimes       etr ON etr.element_pedagogique_id = ep.id
                                          AND etr.histo_destruction      IS NULL
  LEFT JOIN discipline                 d99 ON d99.source_code            = '99'
  LEFT JOIN discipline                   d ON ',' || d.CODES_CORRESP_1 || ',' LIKE '%,' || NVL(pep.z_discipline_id,'00') || ',%'
                                          AND d.histo_destruction        IS NULL
  LEFT JOIN source                       s ON s.code = 'Pegase';
