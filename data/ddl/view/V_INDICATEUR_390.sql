CREATE OR REPLACE FORCE VIEW V_INDICATEUR_390 AS
SELECT DISTINCT
  w.intervenant_id,
  CASE
    WHEN w.structure_id IS NOT NULL
      THEN w.structure_id
    ELSE i.structure_id
    END structure_id
FROM
  tbl_workflow w
  JOIN intervenant  i                   ON w.intervenant_id = i.id
  JOIN tbl_contrat tc ON tc.intervenant_id = w.intervenant_id AND tc.volume_horaire_index =0
  JOIN statut      si                   ON si.id = i.statut_id
WHERE
  w.atteignable = 1
  AND w.etape_code = 'CONTRAT'
  AND w.objectif > w.realisation
  AND i.histo_destruction IS NULL
  AND si.contrat = 1
  AND tc.contrat_parent_id IS NOT NULL
  AND tc.actif = 1