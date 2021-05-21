CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_PEDAGOGIQUE AS
SELECT
  ao.code                  code,
  ao.libelle               libelle,
  ao.annee_id              annee_id,
  e.id                     etape_id,
  str.id                   structure_id,
  null                     periode_id, -- à revoir
  ao.taux_foad             taux_foad,
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
  ao.source_code           source_code
FROM
            act_odf                   ao
       JOIN act_etape                 ae ON ae.source_code = ao.z_etape_id
  LEFT JOIN act_odf              enfants ON enfants.element_parent_id = ao.id
  LEFT JOIN etape                      e ON e.source_code = ao.z_etape_id
  LEFT JOIN unicaen_structure_corresp sc ON sc.cod_cmp = ao.z_structure_id
  LEFT JOIN structure                str ON str.source_code = sc.c_structure_n2
  LEFT JOIN element_pedagogique       ep ON ep.source_code             = ae.source_code
                                        AND ep.annee_id                = ae.annee_id
  LEFT JOIN element_taux_regimes     etr ON etr.element_pedagogique_id = ep.id
                                        AND etr.histo_destruction      IS NULL
  LEFT JOIN discipline               d99 ON d99.source_code            = '99'
  LEFT JOIN discipline                 d ON ',' || d.CODES_CORRESP_1 || ',' LIKE '%,' || NVL(ao.z_discipline_id,'00') || ',%'
                                        AND d.histo_destruction        IS NULL
  LEFT JOIN source                     s ON s.code = ao.z_source_id
WHERE
  -- les éléments pédagogiques sont les feuilles de l'arbre
  enfants.id IS NULL