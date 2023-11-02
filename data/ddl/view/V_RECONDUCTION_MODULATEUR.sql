CREATE OR REPLACE FORCE VIEW V_RECONDUCTION_MODULATEUR AS
SELECT
  e.annee_id        annee_id,
  str.id            structure_id,
  str.ids           structure_ids,
  e.id              etape_id,
  e.code            etape_code,
  e.libelle         etape_libelle,
  em1.modulateur_id modulateur_id,
  ep1.code          ep_code,
  ep2.id            new_ep_id
FROM
            etape                 e
       JOIN structure           str ON str.id = e.structure_id
       JOIN element_pedagogique ep1 ON ep1.etape_id = e.id AND ep1.HISTO_DESTRUCTION IS NULL
       JOIN element_modulateur  em1 ON em1.element_id = ep1.id AND em1.histo_destruction IS NULL
       JOIN element_pedagogique ep2 ON ep2.annee_id = ep1.annee_id+1  AND ep1.code = ep2.code AND ep2.HISTO_DESTRUCTION IS NULL
  LEFT JOIN element_modulateur  em2 ON em2.element_id=ep2.id AND em2.histo_destruction IS NULL
WHERE
  e.histo_destruction IS NULL
  AND em2.id IS NULL