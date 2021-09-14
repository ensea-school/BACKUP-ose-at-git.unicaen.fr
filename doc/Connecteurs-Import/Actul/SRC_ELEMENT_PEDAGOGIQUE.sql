CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_PEDAGOGIQUE AS
SELECT
  an.code                  code,
  an.libelle               libelle,
  an.annee_id              annee_id,
  e.id                     etape_id,
  str.id                   structure_id,
  p.id                     periode_id,
  an.taux_foad             taux_foad,
  ae.fi                    fi,
  ae.fc                    fc,
  ae.fa                    fa,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fi( etr.taux_fi, etr.taux_fc, etr.taux_fa, ae.fi, ae.fc, ae.fa )
    ELSE ose_divers.calcul_taux_fi( ae.fi, ae.fc, ae.fa, ae.fi, ae.fc, ae.fa )
  END                      taux_fi,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fc( etr.taux_fi, etr.taux_fc, etr.taux_fa, ae.fi, ae.fc, ae.fa )
    ELSE ose_divers.calcul_taux_fc( ae.fi, ae.fc, ae.fa, ae.fi, ae.fc, ae.fa )
  END                      taux_fc,
  CASE
    WHEN etr.id IS NOT NULL
    THEN ose_divers.calcul_taux_fa( etr.taux_fi, etr.taux_fc, etr.taux_fa, ae.fi, ae.fc, ae.fa )
    ELSE ose_divers.calcul_taux_fa( ae.fi, ae.fc, ae.fa, ae.fi, ae.fc, ae.fa )
  END                      taux_fa,
  COALESCE( d.id, d99.id ) discipline_id,
  s.id                     source_id,
  an.source_code           source_code
FROM
            act_noeud                   an
       JOIN act_etape                   ae ON ae.source_code = an.z_etape_id
  LEFT JOIN act_lien               enfants ON enfants.z_noeud_sup_id = an.source_code
  LEFT JOIN etape                        e ON e.source_code = an.z_etape_id
  LEFT JOIN v_unicaen_structure_corresp sc ON sc.cod_cmp = an.z_structure_id
  LEFT JOIN structure                  str ON str.source_code = sc.c_structure_n2
  LEFT JOIN periode                      p ON p.code = OSE_ACTUL.CALC_SEMESTRE(an.source_code,an.z_periode_id_semestre, an.z_periode_id_ordre)
  LEFT JOIN element_pedagogique         ep ON ep.source_code             = ae.source_code
                                          AND ep.annee_id                = ae.annee_id
  LEFT JOIN element_taux_regimes       etr ON etr.element_pedagogique_id = ep.id
                                          AND etr.histo_destruction      IS NULL
  LEFT JOIN discipline                 d99 ON d99.source_code            = '99'
  LEFT JOIN discipline                   d ON ',' || d.CODES_CORRESP_1 || ',' LIKE '%,' || NVL(an.z_discipline_id,'00') || ',%'
                                          AND d.histo_destruction        IS NULL
  LEFT JOIN source                       s ON s.code = an.z_source_id
WHERE
  -- les éléments pédagogiques sont les feuilles de l'arbre
  enfants.z_noeud_sup_id IS NULL