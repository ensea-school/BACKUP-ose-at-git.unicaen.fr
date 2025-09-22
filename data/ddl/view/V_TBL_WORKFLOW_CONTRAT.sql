CREATE OR REPLACE FORCE VIEW V_TBL_WORKFLOW_CONTRAT AS
SELECT
  'contrat'          etape_code,
  c.intervenant_id   intervenant_id,
  c.structure_id     structure_id,
  sum(c.objectif)    objectif,
  sum(c.partiel)     partiel,
  sum(c.realisation) realisation
FROM (
  SELECT
    'contrat'                              etape_code,
    intervenant_id                         intervenant_id,
    structure_id                           structure_id,
    greatest(c.total_heures,1)             objectif,
    c.termine                              partiel,
    c.termine * greatest(c.total_heures,1) realisation
  FROM
    tbl_contrat c
  WHERE
    c.volume_horaire_index = 0
    /*@intervenant_id=c.intervenant_id*/
    /*@annee_id=c.annee_id*/
  ) c
GROUP BY
  c.intervenant_id,
  c.structure_id