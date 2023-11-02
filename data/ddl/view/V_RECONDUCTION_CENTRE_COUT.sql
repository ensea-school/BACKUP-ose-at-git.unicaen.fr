CREATE OR REPLACE FORCE VIEW V_RECONDUCTION_CENTRE_COUT AS
SELECT
  e.annee_id           annee_id,
  str.id               structure_id,
  str.ids              structure_ids,
  e.id                 etape_id,
  e.code               etape_code,
  e.libelle            etape_libelle,
  cc1.id               centre_cout_id,
  ccep1.type_heures_id type_heures_id,
  ep1.code             ep_code,
  ep2.id               new_ep_id
FROM
            etape                 e
       JOIN element_pedagogique ep1 ON ep1.etape_id = e.id AND ep1.HISTO_DESTRUCTION IS NULL
       JOIN centre_cout_ep    ccep1 ON ccep1.element_pedagogique_id = ep1.id AND ccep1.HISTO_DESTRUCTION IS NULL
       JOIN centre_cout         cc1 ON ccep1.centre_cout_id = cc1.id AND cc1.HISTO_DESTRUCTION IS NULL
       JOIN source                s ON s.id = ccep1.source_id AND s.importable = 0
       JOIN structure           str ON str.id = e.structure_id
       JOIN element_pedagogique ep2 ON ep2.annee_id = ep1.annee_id+1  AND ep1.code = ep2.code AND ep2.HISTO_DESTRUCTION IS NULL
  LEFT JOIN centre_cout_ep    ccep2 ON ccep2.element_pedagogique_id = ep2.id AND ccep2.histo_destruction IS NULL AND ccep2.type_heures_id = ccep1.TYPE_HEURES_ID
WHERE
  e.histo_destruction IS NULL
  AND ccep2.id IS NULL